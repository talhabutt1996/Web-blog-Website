<?php

    // get a connection to database
    function getDbConnection() {
        $dbName = 'blogcms';
        $username = 'root';
        $password = 'yousaf';
        $serverName = 'localhost';

        $dbConnect = new mysqli($serverName, $username, $password, $dbName);

        /*if($dbConnect) {
            echo 'Database Connected';
        } else {
            echo $dbConnect->connect_error;
        }*/

        return $dbConnect;
    }

    //function to generate json response
    function generateJsonResponse($messageType, $message) {
        $response = [
            'messageType' => $messageType,
            'message' => $message
        ];
        header('Content-type: application/json');
        return json_encode($response);
    }

    // get category list from database
    function getCategoryList() {
        $dbConnect = getDbConnection();

        $query = 'SELECT * FROM categories';
        $statement = $dbConnect->prepare($query);

        if($statement->execute()) {
            $statement->execute();
            $resultSet = $statement->get_result();
            return $resultSet;
        }

        $statement->close();
        $dbConnect->close();

        return null;
    }

    // return current datetime in custom format
    function getCurrentDateTime() {
        date_default_timezone_set('Asia/Karachi');
        $currentDateTime = time();
        $formattedDate = date('F j, Y - g:ia', $currentDateTime);

        return $formattedDate;
    }

    // return full path of image on server
    function getPostImgFullPath($imagePath) {
        $fullPath = '../Admin New Post/'.$imagePath;
        return $fullPath;
    }

    // get lists of posts from database
    function getPosts() {
        $dbConnect = getDbConnection();
        $query = 'SELECT * FROM posts';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->execute();
            $resultSet = $statement->get_result();

            $statement->close();
            $dbConnect->close();

            return $resultSet;
        }
        return null;
    }

    // get two most recent posts
    function getRecentPosts() {
        $dbConnect = getDbConnection();
        $query = 'SELECT title, image, added_on FROM posts ORDER BY id DESC LIMIT 2';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->execute();
            $recentPosts = $statement->get_result();

            $statement->close();
            $dbConnect->close();

            return $recentPosts;
        }
        return null;
    }

    // get post id from post title that is passed as argument
    function getPostID($postTitle) {
        $dbConnect = getDbConnection();
        $query = 'SELECT id FROM posts WHERE title = ?';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->bind_param('s', $postTitle);
            $statement->execute();
            $resultSet = $statement->get_result();
            $row = $resultSet->fetch_assoc();

            $statement->close();
            $dbConnect->close();

            return $row['id'];
        }
        return null;
    }

    // get approved comment count of specific post
    function getApprovedCommentCount($postID) {
        $dbConnect = getDbConnection();
        $query = 'SELECT COUNT(*) AS "comment_count" FROM comments WHERE post_id = ? AND approved = "yes"';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->bind_param('s', $postID);
            $statement->execute();
            $resultSet = $statement->get_result();

            $statement->close();
            $dbConnect->close();

            return $resultSet;
        }
        return null;
    }

    // take the page to the location that is passed as pageName
    // load the desired page after the specified time in seconds
    function reloadPage($pageName, $reloadAfter) {
        echo '<script>
                setTimeout(function () {
                    window.location = "'.$pageName.'";
                }, '.$reloadAfter.');
              </script>';
    }

?>