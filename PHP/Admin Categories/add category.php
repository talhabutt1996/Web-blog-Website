<?php

    session_start();
    require '../common.php';

    $categoryName = $_POST['category-field'];

    // get full name of the currently logged-in admin who is adding the new category
    $added_by = $_SESSION['current_admin_fullname'];

    // post save date
    // getCurrentDateTime() method defined in common.php
    $formattedDate = getCurrentDateTime();

    // save admin info in to datbase
    saveCategoryInfo($categoryName, $added_by, $formattedDate);

    function saveCategoryInfo($name, $added_by, $date) {
        $dbConnect = getDbConnection();

        $query = 'INSERT INTO categories (category_name, created_by, added_on) VALUES (?,?,?)';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->bind_param('sss',$name,$added_by, $date);

            if($statement->execute()) {
                echo generateJsonResponse('success', 'New Category saved successfully');
            }
        }
        $statement->close();
        $dbConnect->close();
    }

?>