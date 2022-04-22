<?php

    require '../common.php';

    $userName = $_POST['name-field'];
    $userEmail = $_POST['email-field'];
    $userComment = $_POST['comment-field'];
    $currentTime = getCurrentDateTime();
    $postId = (int) getPostID($_POST['post-identifier-field']);

    saveComment($userComment, $userName, $userEmail, $currentTime, $postId);

    // save comment in the database
    function saveComment($comment, $name, $email, $time, $postID) {
        $dbConnect = getDbConnection();
        $query = 'INSERT INTO comments (comment_text, author, author_email, added_on, 
                                        approved, approved_by, post_id) VALUES (?,?,?,?,?,?,?)';
        $statement = $dbConnect->prepare($query);

        if($statement) {

            // default values for this two columns
            $approved = 'no';
            $approvedBy = 'not approved';

            $statement->bind_param('ssssssi', $comment, $name, $email, $time, $approved, $approvedBy, $postID);

            if($statement->execute()) {
                echo generateJsonResponse('success', 'Comment saved, pending for approval by admin');
            }
        }

        $statement->close();
        $dbConnect->close();
    }

?>