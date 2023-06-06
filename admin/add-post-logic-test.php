<?php

require 'config/database.php';

if (isset($_POST['submit'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$title) {
        $_SESSION['add-post'] = "Enter Title";
    } elseif (!$description) {
        $_SESSION['add-post'] = "Enter body";
    }

    if (isset($_SESSION['add-post'])) {
        $_SESSION['add-post-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-post.php');
        die();
    } else {
        $query = "INSERT INTO posts (title, body) VALUES ('$title', '$body')";
        $result = mysqli_query($connection, $query);
        if (mysqli_errno($connection)) {
            $_SESSION['add-post'] = "Unable to add category";
            header('location: ' . ROOT_URL . 'admin/add-category.php');
            die();
        } else {
            $_SESSION['add-post-success'] = "Category $title added successfully";
            header('location: ' . ROOT_URL . 'admin/manage-categories.php');
            die();
        }
    }
}
