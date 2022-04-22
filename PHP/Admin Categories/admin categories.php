<?php

    // check if any admin is logged in, if not, redirect to login page
    session_start();
    if(!isset($_SESSION['any_admin_logged_in'])) {
        header('Location: ../Admin Login/admin login.php');
    }

    require '../common.php';

    // set to true when a category is added to database
    // When its true, javascript function is called from php code
    // set to true when category is deleted from database
    $displayMsg = null;

    $resultSet = getCategoryList();

    // delete admin when respective delete button is clicked
    if(isset($_GET['deleteCategory'])) {
        deleteAdmin($_GET['deleteCategory']);
    }

    // delete category
    function deleteAdmin($categoryName) {
        global $displayMsg;
        $dbConnect = getDbConnection();

        $query = 'DELETE FROM categories WHERE category_name=?';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->bind_param('s', $categoryName);

            if($statement->execute()) {
                $displayMsg = true;
            }
        }

        $statement->close();
        $dbConnect->close();
    }

?>

<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../Resources/Bootstrap v4.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../../CSS/admin categories.css"/>
    <link rel="stylesheet" href="../../CSS/common.css"/>
    <title>Admin - Manage Categories</title>
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
            <li>
              <a href="../Admin New Post/add new post.php">
                <img src="../../Resources/images/new post.png" alt="image">
                <span>Add New Post</span>
              </a>
            </li>
            <li class="active-tab">
              <a href="#">
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
            <p>Manage Categories</p>

            <p id="info-message-block">
              <span></span>
            </p>

            <form class="form-inline add-category-form" method="post" action="">
              <label class="sr-only" for="category-field">Category Name</label>
              <input type="text" id="category-field" name="category-field" class="form-control" placeholder="Category"/>
              <button class="btn btn-success">Add Category</button>
            </form>

            <?php

               if(isset($resultSet)) {
                 //to display category count
                 $counter = 1;

                 echo '<div class="table-responsive">
                      <table class="table table-bordered table-hover table-sm">
                      <thead>
                      <tr>
                      <th>No.</th>
                      <th>Category Name</th>
                      <th>Added By</th>
                      <th>Date &amp; Time</th>
                      <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>';

                 while($row = $resultSet->fetch_assoc()) {
                     $categoryName = $row['category_name'];
                     $createdBy = $row['created_by'];
                     $addedOn = $row['added_on'];

                     echo '<tr>
                          <td>'.$counter.'</td>
                          <td>'.$categoryName.'</td>
                          <td>'.$createdBy.'</td>
                          <td>'.$addedOn.'</td>
                          <td>
                            <a href="admin categories.php?deleteCategory='.$categoryName.'" class="btn btn-danger">Delete</a>
                          </td>';

                     //increment the counter
                     $counter += 1;
                 }

                 echo '</tbody>
                       </table>
                       </div><!--end of table-container-->';
               }

            ?>

          </div><!--end of content area-->
      </div><!--end of second row-->
    </div><!--end of container-->

    <!--CDN versions of JQuery and Popper.js-->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="../../Resources/Bootstrap v4.1/js/bootstrap.min.js"></script>
    <script src="../../Javascript/sidebar toggle.js"></script>
    <script src="../../Javascript/common.js"></script>
    <script src="../../Javascript/add category form validation.js"></script>

    <!--This is done to call displayInfoMessage function from php.
          common.js file has to be included after info-message-block
          div has been loaded and displayInfoMessage has to be called
          after common.js file has been included-->
    <?php
        if(isset($displayMsg)) {
            echo '<script>
                    displayInfoMessage("Selected category deleted successfully", "success")
                  </script>';

            // reload page after 4 seconds when category is deleted
            echo '<script>
                    setTimeout(function () {
                        window.location = "admin categories.php";
                    }, 4000);
                  </script>';
        }
    ?>
  </body>
</html>