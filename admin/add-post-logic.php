<?php
require 'config/database.php';
//Justin
// $debug = "";
if (isset($_POST['submit'])) {

    $author_id = $_SESSION['user-id'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];

    // set is_featured to 0 if unchecked
    $is_featured = $is_featured == 1 ?: 0;


    // $debug = $debug + "1";
    // $_SESSION['debug'] = $debug;


    // validate form data 
    if (!$title) {
        $_SESSION['add-post'] = "Enter post title";
    } elseif (!$category_id) {
        $_SESSION['add-post'] = "Select post category";
    } elseif (!$body) {
        $_SESSION['add-post'] = "Enter post body";
    } elseif (!$thumbnail['name']) {
        $_SESSION['add-post'] = "Thumbnail image required";
    } else {
        // work on thumbnail
        // rename image to avoid duplicates
        $time = time();
        $thumbnail_name = $time . $thumbnail['name'];
        $thumbnail_tmp_name = $thumbnail['tmp_name'];
        $thumbnail_destination_path = '../images/' . $thumbnail_name;

        // verify thumnail image is valid
        $allowed_files = ['png', 'jpg', 'jpeg'];
        $extension = explode('.', $thumbnail_name);
        $extension = end($extension);

        if (in_array($extension, $allowed_files)) {
            // verify file size - max 2MB
            if ($thumbnail['size'] < 2000000) {
                // upload file
                move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);
            } else {
                $_SESSION['add-post'] = 'File size too large. Maximum file size is 2MB';
            }
        } else {
            $_SESSION['add-post'] = "File must be png, jpg, jpeg";
        }
    }
    // $debug = $debug . " 2";
    // $_SESSION['debug'] = $debug;
    // redirect back with form data to add-post page if error



    if (isset($_SESSION['add-post'])) {
        // $debug = $debug . " 3";
        // $_SESSION['debug'] = $debug;
        // $debug = $debug . " 3.1";
        $_SESSION['add-post-data'] = $_POST;
        // $debug = $debug . " 3.2";
        header('location: ' . ROOT_URL . 'admin/add-post.php');
        // $debug = $debug . " 3.5";
        die();
    } else {


        // $debug = $debug . " 4";
        // $_SESSION['debug'] = $debug;
        // if is_featured is checked, set all other posts to 0
        if ($is_featured == 1) {
            $zero_all_is_featured_query = "UPDATE posts SET is_featured = 0";
            $zero_all_is_featured_result = mysqli_query($connection, $zero_all_is_featured_query);
        }

        // insert post into DB


        $query = "INSERT INTO posts (title, body, thumbnail, category_id, author_id, is_featured)
        VALUES ('$title', '$body', '$thumbnail_name','$category_id', '$author_id', '$is_featured')";

        // $_SESSION['sql'] = $query;
        $result = mysqli_query($connection, $query);
        echo mysqli_errno($connection);


        if (!mysqli_errno($connection)) {
            $_SESSION['add-post-success'] = "New post added successfully";
            header('location: ' . ROOT_URL . 'admin/');
            die();
        }
    }
}
header('location: ' . ROOT_URL . 'admin/add-post.php');
