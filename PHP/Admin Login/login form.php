<?php

    require '../common.php';

    $username = $_POST['username-field'];
    $password = $_POST['password-field'];

    verifyLoginCredentials($username, $password);

    // verify admin login credentials
    function verifyLoginCredentials($username, $password) {
        $dbConnect = getDbConnection();

        $query = 'SELECT full_name, username, password FROM admins WHERE username = ?';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->bind_param('s', $username);
            $statement->execute();
            $resultSet = $statement->get_result();

            // since there will be only one row returned at max, no need of a loop
            $row = $resultSet->fetch_assoc();

            if($row != null) {
                $adminFullName = $row['full_name'];
                $adminUsername = $row['username'];
                $adminPassword = $row['password'];

                // if username/password is correct start session and store
                // username, password, full name in the session
                if($username === $adminUsername && password_verify($password, $adminPassword)) {
                    session_start();
                    $_SESSION['current_admin_fullname'] = $adminFullName;
                    $_SESSION['current_admin_username'] = $adminUsername;
                    $_SESSION['current_admin_password'] = $adminPassword;
                    // to verify that any user is logged in
                    $_SESSION['any_admin_logged_in'] = 'Logged In';

                    echo generateJsonResponse('success', 'grant access');
                }
                else { // if username/password combination is incorrect
                    echo generateJsonResponse('error', 'Incorrect Username/Password Combination');
                }
            } else { // if username doesn't exists in the database
                echo generateJsonResponse('error', 'Entered username isn\'t registered');
            }
        }

        $statement->close();
        $dbConnect->close();
    }

?>