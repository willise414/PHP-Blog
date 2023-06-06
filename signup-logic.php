<?php
session_start();
// echo "form works!";
require 'config/database.php';

//get signup form data when button is clicked
if (isset($_POST['submit'])){
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $createpassword = filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmpassword = filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $avatar = $_FILES['avatar'];
    // echo $firstname, $lastname, $email, $username, $createpassword;
    // var_dump($avatar);

    //validate input values
    if(!$firstname){
        $_SESSION['signup'] = "Please enter your first name";
    } elseif (!$lastname){
        $_SESSION['signup'] = "Please enter your last name";
    } elseif (!$username) {
        $_SESSION['signup'] = "Please enter a user name";
    } elseif (!$email) {
        $_SESSION['signup'] = "Please enter a valid email address";
    } elseif (strlen($createpassword) < 8 || strlen($confirmpassword) < 8 ) {
        $_SESSION['signup'] = "Password must be at least 8 characters";
    } elseif (!$avatar['name']) {
        $_SESSION['signup'] = "Please add avatar";
    } else {
        //check if passwords match
        if ($createpassword !== $confirmpassword){
            $_SESSION['signup'] = "Passwords do not match";
        } else {
            //hash passwords for database entry
            $hashed_password = password_hash($createpassword, PASSWORD_DEFAULT);
            // echo $createpassword . '<br>';
            // echo $hashed_password;

            //check if username or email already exists
            $user_check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
            $user_check_result = mysqli_query($connection, $user_check_query);
            if(mysqli_num_rows($user_check_result) > 0){
                $_SESSION['signup']= "Username or email already exists";
            } else {
                //work on avatar
                //rename avatar to make it unique using current time
                $time = time();
                $avatar_name = $time . $avatar['name'];
                $avatar_tmp_name = $avatar['tmp_name'];
                $avatar_destination_path = 'images/' . $avatar_name;

                //make sure file is an image
                $allowed_files = ['png', 'jpg', 'jpeg'];
                $extension = explode('.', $avatar_name);
                $extension = end($extension);

                //check if extension is in allowed files
                if (in_array($extension, $allowed_files)) {
                    //make sure the file size is not too large (1mb)
                    if ($avatar['size'] < 1000000) {
                        //upload avatar
                        move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                    } else {
                        $_SESSION['signup'] = 'File size is too big. Must be less than 1MB';
                    }
                } else {
                    $_SESSION['signup'] = "File should be png, jpg, or jpeg";
                }
            }
        }
    }
    
    // redirect back to signup page is there was an error
    if(isset($_SESSION['signup'])){
        //pass form data back to signup page
        $_SESSION['signup-data'] = $_POST;
        header('location: ' . ROOT_URL . 'signup.php');
        die();
    } else {
        //insert new user into database
        $insert_user_query = "INSERT INTO users  SET firstname = '$firstname', lastname = '$lastname', username = '$username', email = '$email', 
        password = '$hashed_password', avatar = '$avatar_name', is_admin = 0";

        $insert_user_result = mysqli_query($connection, $insert_user_query);

        if(!mysqli_errno($connection)){
            // redirect to login page with success message
            $_SESSION['signup-success'] = 'Registration Successful. Please log in';
            header('location: ' . ROOT_URL . 'signin.php' );
            die();
        }
    }

} else {
    // if button not clicked, return to signup
    header('location: ' . ROOT_URL . 'signup.php');
    die();
}