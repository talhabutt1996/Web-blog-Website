<?php

    session_start();
    require '../common.php';

    $fullName = $_POST['name-field'];
    $username = $_POST['username-field'];
    $encryptedPassword = password_hash($_POST['password-field'], PASSWORD_DEFAULT);

    // get full name of the currently logged-in admin who is adding the new admin
    $added_by = $_SESSION['current_admin_fullname'];

    // post save date
    // getCurrentDateTime() method defined in common.php
    $formattedDate = getCurrentDateTime();

    // save admin info in to datbase
    saveAdminInfo($fullName, $username, $encryptedPassword, $added_by, $formattedDate);

    function saveAdminInfo($name, $username, $password, $added_by, $date) {
        $dbConnect = getDbConnection();

        $query = 'INSERT INTO admins (full_name, username, password, added_by, datetime) VALUES (?,?,?,?,?)';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->bind_param('sssss',$name,$username, $password, $added_by, $date);

            if($statement->execute()) {
                echo generateJsonResponse('success', 'New Admin saved successfully');
            }
        }
        $statement->close();
        $dbConnect->close();
    }

?>