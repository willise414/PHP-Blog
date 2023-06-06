<?php

require 'config/database.php';


if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // get user from DB
    $query = "SELECT * FROM users WHERE id=$id";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($result);

    // make sure we get back only one user
    if (mysqli_num_rows($result) == 1) {
        $avatar_name = $user['avatar'];
        $avatar_path = '../images/' . $avatar_name;

        // delete avatar if one exists
        if ($avatar_path) {
            unlink($avatar_path);
        }
    }
    // Delete all posts by user and remove images from folder
    $thumbnails_query = "SELECT thumbnail FROM posts WHERE author_id=$id";
    $thumbnails_result = mysqli_query($connection, $thumbnails_query);

    if (mysqli_num_rows($thumbnails_result) > 0) {
        while ($thumbnail = mysqli_fetch_assoc($thumbnails_result)) {

            $thumbnail_path = '../images/' . $thumbnail['thumbnail'];

            //delete thumbnail from images folder if one exists
            if ($thumbnail_path) {
                unlink($thumbnail_path);
            }
        }
    }

    // delete user from DB
    // $delete_user_query = "DELETE FROM users where id=$id";
    $delete_post_query = "DELETE users, posts from users INNER JOIN posts on posts.author_id = users.id WHERE users.id=$id ";
    $delete_user_result = mysqli_query($connection, $delete_post_query);

    if (mysqli_errno($connection)) {
        $_SESSION['delete-user'] = "Unable to delete '$user[firstname]' '$user[lastname]' ";
        $_SESSION['delete-user'] = mysqli_errno($connection);
    } else {
        $_SESSION['delete-user-success'] = " '$user[firstname]' '$user[lastname]' successfully deleted ";
    }
}
header('location: ' . ROOT_URL . 'admin/manage-users.php');
die();
