<?php
    // Once login pasge is loaded, unset session variable so that user cannot go in
    // in to any web page without login
    session_start();

    if(isset($_SESSION['any_admin_logged_in']) && $_SESSION['any_admin_logged_in'] === 'Logged In') {
        header('Location: ../Admin Home/admin dashboard.php');
    }

    if(isset($_GET['loggedout'])) {
        session_unset();
    }

?>

<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../Resources/Bootstrap v4.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../../CSS/admin login.css"/>
    <link rel="stylesheet" href="../../CSS/common.css"/>
    <title>Admin Login</title>
  </head>
  <body>
    <div class="container-fluid">

      <div class="row">
        <div class="col-sm-12 navbar-container">
          <nav class="top-navbar">
            <img src="../../Resources/images/logo.png" alt="logo"/>
            <p>Admin Panel</p>
          </nav><!--end of navbar-->
        </div><!--end of first column-->
      </div><!--end of first row-->

      <div class="row">
        <div class="col-sm-4 login-form-container">

          <!--used for displaying error or success message-->
          <p id="info-message-block">
            <span></span>
          </p>

          <form class="login-form" name="login-form" method="post" action="">
            <p>Welcome Back!</p>

            <div class="form-group username-group">
              <label for="username-field">Username</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <img src="../../Resources/images/envelope.png" alt="envelope image"/>
                  </div>
                </div>
                <input class="form-control" id="username-field" type="text" name="username-field" id="username-field" placeholder="Username"/>
              </div>
            </div><!--end of first form group-->

            <div class="form-group password-group">
              <label for="password-field">Password</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <img src="../../Resources/images/lock.png" alt="lock image"/>
                  </div>
                </div>
                <input class="form-control" id="password-field" type="password" name="password-field" id="password-field" placeholder="Password"/>
              </div>
            </div><!--end of second form-group-->

            <input type="submit" class="btn" id="login-btn" name="login-btn" value="Login"/>
          </form><!--end of login form-->

        </div><!--end of first column-->
      </div><!--end of second row-->

    </div><!--end of container-->

    <!--CDN versions of JQuery and Popper.js-->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="../../Resources/Bootstrap v4.1/js/bootstrap.min.js"></script>
    <script src="../../Javascript/common.js"></script>
    <script src="../../Javascript/admin login form validation.js"></script>
  </body>
</html>