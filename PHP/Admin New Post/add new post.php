<?php

    session_start();
    require '../common.php';

    // check if any admin is logged in, if not, redirect lo login page
    if(!isset($_SESSION['any_admin_logged_in'])) {
        header('Location: ../Admin Login/admin login.php');
    }

?>

<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../Resources/Bootstrap v4.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../../CSS/add new post.css"/>
    <link rel="stylesheet" href="../../CSS/common.css"/>
    <title>Admin - Add New Post</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12 navbar-container">
          <nav class="top-navbar">
            <button class="sidebar-toggler">
              <img src="../../Resources/images/hamburger.png" alt="image" id="menu-icon"/>
            </button>
            <img src="../../Resources/images/logo.png" alt="logo" id="logo"/>
            <p>Admin Panel</p>
          </nav><!--end of navbar-->
        </div><!--end of first column-->
      </div><!--end of first row-->

      <div class="row sidebar-row">
        <div class="col-lg-2 sidebar-container">
          <ul>
            <li>
              <a href="../Admin Home/admin dashboard.php">
                <img src="../../Resources/images/dashboard.png" alt="image" class="large-icon">
                <span>Dashboard</span>
              </a>
            </li>
            <li class="active-tab">
              <a href="#">
                <img src="../../Resources/images/new post.png" alt="image">
                <span>Add New Post</span>
              </a>
            </li>
            <li>
              <a href="../Admin Categories/admin categories.php">
                <img src="../../Resources/images/categories.png" alt="image">
                <span>Categories</span>
              </a>
            </li>
            <li>
              <a href="../Admin Access Management/admin access management.php">
                <img src="../../Resources/images/manage admins.png" alt="image" class="small-icon">
                <span>Manage Admins</span>
              </a>
            </li>
            <li>
              <a href="../Admin Comments/admin comments.php">
                <img src="../../Resources/images/comments.png" alt="image">
                <span>Comments</span>
              </a>
            </li>
            <li>
              <a href="../Blog Home/blog home.php">
                <img src="../../Resources/images/live blog.png" alt="image">
                <span>Live blog</span>
              </a>
            </li>
            <li>
              <a href="../Admin Login/admin login.php?loggedout=true">
                <img src="../../Resources/images/logout.png" alt="image">
                <span>Logout</span>
              </a>
            </li>
          </ul>
        </div><!--end of first column-->

        <div class="col-lg-10 content-area">
          <p>Add New Post</p>
          <!-- <p class="welcome-msg">Welcome Jane Doe!</p> -->

          <p id="info-message-block">
            <span></span>
          </p>

          <div class="new-post-form-container">
            <form id="new-post-form" method="post" action="" enctype="multipart/form-data">
              <div class="form-group">
                <label for="post-title-field">Post Title</label>
                <input type="text" name="post-title-field" id="post-title-field" class="form-control">
              </div>
              <div class="form-group">
                <label for="post-category-field">Post Category</label>
                <select class="custom-select form-control" id="post-category-field" name="post-category-field">
                    <?php
                        $categoryResultSet = getCategoryList();

                        if($categoryResultSet->num_rows > 0) {
                            echo '<option selected value="none selected">--Select Category--</option>';

                            while($row = $categoryResultSet->fetch_assoc()) {
                                $categoryName = $row['category_name'];
                                echo '<option value="'.$categoryName.'">'.$categoryName.'</option>';
                            }
                        } else {
                            echo '<option selected value="none selected">--No Category Available--</option>';
                        }
                    ?>
                </select>
              </div>
              <div class="form-group">
                <label for="post-banner-field">Post Banner</label>
                <input type="file" accept="image/jpeg, image/png" name="post-banner-field" id="post-banner-field" class="form-control">
              </div>
              <div class="form-group">
                <label for="post-content-field">Post Content</label>
                <textarea name="post-content-field" id="post-content-field" class="form-control"></textarea>
              </div>
              <button class="btn">Add New Post</button>
            </form>
          </div><!--end of new post form container-->
        </div>
      </div><!--end of second row-->
    </div><!--end of container-->

    <!--CDN versions of JQuery and Popper.js-->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="../../Resources/Bootstrap v4.1/js/bootstrap.min.js"></script>
    <script src="../../Javascript/sidebar toggle.js"></script>
    <script src="../../Javascript/common.js"></script>
    <script src="../../Javascript/add post form validation.js"></script>
  </body>
</html>