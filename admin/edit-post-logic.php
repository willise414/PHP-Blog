<?php

require 'config/database.php';

// $debug = "";

if (isset($_POST['submit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $previous_thumbnail_name = filter_var($_POST['previous_thumbnail_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];

    // $debug = $debug + "1";
    // $_SESSION['debug'] = $debug;


    // set is_featured to 0 if unchecked
    $is_featured = $is_featured == 1 ?: 0;


    // validate fields are completed
    if (!$title) {
        $_SESSION['edit-post'] = "Unable to update post. Form data missing";
    } elseif (!$category_id) {
        $_SESSION['edit-post'] = "Unable to update post. Form data missing";
    } elseif (!$body) {

        $_SESSION['edit-post'] = "Unable to update post. Form data missing";
    } else {
        // delete curent thumbnail if new one is added
        if ($thumbnail['name']) {

            $previous_thumbnail_path = '../images/' . $previous_thumbnail_name;

            if ($previous_thumbnail_path) {
                echo $previous_thumbnail_path;
                unlink($previous_thumbnail_path);
            }
            // add new thumbnail
            $time = time(); // make each image unique again
            $thumbnail_name = $time . $thumbnail['name'];
            $thumbnail_tmp_name = $thumbnail['tmp_name'];
            $thumbnail_destination_path = '../images/' . $thumbnail_name;

            $allowed_files = ['png', 'jpg', 'jpeg'];
            $extension = explode('.', $thumbnail_name);
            $extension = end($extension);

            if (in_array($extension, $allowed_files)) {
                // check file size
                if ($thumbnail['size'] < 2000000) {
                    move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);
                } else {
                    $_SESSION['edit-post'] = "Unable to update post. File size too large. 2MB maximum";
                }
            } else {
                $_SESSION['edit-post'] = "Unable to update post. File must be png, jpg, or jpeg";
            }
        }
    }


    if (isset($_SESSION['edit-post'])) {
        // echo 'hello';
        header('location: ' . ROOT_URL . 'admin/');
        die();
    } else {
        if ($is_featured == 1) {
            $zero_all_is_featured_query = "UPDATE posts set is_featured = 0 ";
            $zero_all_is_featured_result = mysqli_query($connection, $zero_all_is_featured_query);
        }

        // upload new thumbnail if provided, otherwise kepp old one
        $thumbnail_to_insert = $thumbnail_name ?? $previous_thumbnail_name;

        $query = "UPDATE posts SET title = '$title', body='$body', thumbnail = '$thumbnail_to_insert',
        category_id = $category_id, is_featured = $is_featured WHERE id=$id LIMIT 1 ";


        // $_SESSION['sql'] = $query;

        $result = mysqli_query($connection, $query);
    }

    if (!mysqli_errno($connection)) {
        $_SESSION['edit-post-success'] = "Post update successfully";
    }
}
header('location: ' . ROOT_URL . 'admin/');

die();
