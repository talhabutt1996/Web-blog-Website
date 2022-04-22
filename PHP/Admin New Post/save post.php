<?php

    session_start();
    require '../common.php';

    // get full name of the currently logged-in admin who is adding the new post
    $post_author = $_SESSION['current_admin_fullname'];

    $postTitle = $_POST['post-title-field'];
    $postCategory = $_POST['post-category-field'];
    $imageFile = $_FILES['post-banner-field'];
    $postContent = $_POST['post-content-field'];

    $postImagePath = uploadImageToServer($imageFile);

    // post save date
    // getCurrentDateTime() method defined in common.php
    $formattedDate = getCurrentDateTime();

    if($postImagePath != null) {
        savePost($postTitle, $postCategory, $post_author, $formattedDate, $postImagePath, $postContent);
    }

    function uploadImageToServer($imageFile) {
        global $postTitle;

        $fileExtension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $filePath = 'uploaded banners/'.$postTitle.'.'.$fileExtension;

        //if file is selected
        if($imageFile['name']) {

            // if no errors
            if(!$imageFile['error']) {

                // validate file size
                // can't be larger than 1Mb
                if($imageFile['size'] < 1024000) {
                    move_uploaded_file($imageFile['tmp_name'], $filePath);
                    return $filePath;
                } else {
                    echo generateJsonResponse('error', 'Image size should be less then 1Mb');
                }
            } else {
                echo generateJsonResponse('error', $imageFile['error']);
            }
        }

        return null;
    }

    function savePost($title, $category, $author, $addedOn, $imagePath, $content) {
        $dbConnect = getDbConnection();
        $query = 'INSERT INTO posts (title, category, author, added_on, image, content) VALUES (?,?,?,?,?,?)';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->bind_param('ssssss', $title, $category, $author, $addedOn, $imagePath, $content);

            if($statement->execute()) {
                echo generateJsonResponse('success', 'Post added successfully');
            }
        }

        $statement->close();
        $dbConnect->close();
    }
?>