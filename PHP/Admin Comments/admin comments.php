<?php
    session_start();
    require '../common.php';

    // check if any admin is logged in, if not, redirect lo login page
    if(!isset($_SESSION['any_admin_logged_in'])) {
        header('Location: ../Admin Login/admin login.php');
    }

    // used to display info message block
    $approvedMsgDisplay = null;
    $deleteMsgDisplay = null;

    // $_GET['approveComment'] is set when a admin clicks on approve button
    // on any comment
    if(isset($_GET['approveComment'])) {
        $approvedMsgDisplay = true;
        approveComment($_GET['approveComment']);
        // reload the original page to avoid form resubmit on page refresh
        //header('Location: admin comments.php');
    }

    // $_GET['deleteComment'] is set when a admin clicks on delete button
    // on any comment
    if(isset($_GET['deleteComment'])) {
        $deleteMsgDisplay = true;
        deleteComment($_GET['deleteComment']);
        // reload the original page to avoid form resubmit on page refresh
        //header('Location: admin comments.php');
    }

    // get currently logged in admin
    $currentAdmin = $_SESSION['current_admin_fullname'];

    function approveComment($commentID) {
        $dbConnect = getDbConnection();
        $query = 'UPDATE comments SET approved = "yes" WHERE id = ?';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->bind_param('i', $commentID);
            $statement->execute();

            $statement->close();
            $dbConnect->close();
        }
    }

    function deleteComment($commentID) {
        $dbConnect = getDbConnection();
        $query = 'DELETE FROM comments WHERE id = ?';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->bind_param('i', $commentID);
            $statement->execute();

            $statement->close();
            $dbConnect->close();
        }
    }

    // get comments from the database based on the
    // value of the argument passed
    // commentType can only have two values,
    //  1. approved
    // 2. unapproved
    function getComments($commentType) {
        $dbConnect = getDbConnection();
        $query = 'SELECT id, comment_text, author, added_on, approved_by FROM comments WHERE approved = ?';
        $statement = $dbConnect->prepare($query);

        if($statement) {

            if($commentType === 'approved') {
                $approved = 'yes';
            } else if($commentType === 'unapproved') {
                $approved = 'no';
            }
            $statement->bind_param('s', $approved);
            $statement->execute();
            $resultSet = $statement->get_result();

            $statement->close();
            $dbConnect->close();

            return $resultSet;
        }
        return null;
    }
?>

<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../Resources/Bootstrap v4.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../../CSS/admin comments.css"/>
    <link rel="stylesheet" href="../../CSS/common.css"/>
    <title>Admin - Comments Section</title>
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
            <li class="active-tab">
              <a href="#">
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
          <p>Unapproved Comments</p>

          <p id="info-message-block">
            <span></span>
          </p>

          <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Username</th>
                  <th>Date &amp; Time</th>
                  <th>Comment</th>
                  <th>Approve</th>
                  <th>Delete</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <?php
                     $commentsSet = getComments('unapproved');

                     if($commentsSet != null && $commentsSet->num_rows > 0) {

                         $commentCounter = 1;

                         while($commentRow = $commentsSet->fetch_assoc()) {
                             echo '<td>'.$commentCounter.'</td>
                                   <td>'.$commentRow["author"].'</td>
                                   <td>'.$commentRow["added_on"].'</td>
                                   <td class="comment-container">
                                    '.$commentRow["comment_text"].'
                                   </td>
                                   <td>
                                   <a href="admin comments.php?approveComment='.$commentRow["id"].'" class="btn btn-primary">Approve</a>
                                   </td>
                                   <td>
                                   <a href="admin comments.php?deleteComment='.$commentRow["id"].'" class="btn btn-danger">Delete</a>
                                   </td>
                                   <td>
                                   <button class="btn btn-success">Preview</button>
                                   </td>
                                   </tr>';

                             $commentCounter += 1;
                         }
                     }
                  ?>
              </tbody>
            </table>
          </div><!--end of table-container-->

          <p class="approved-text">Approved Comments</p>

          <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Username</th>
                  <th>Date &amp; Time</th>
                  <th>Comment</th>
                  <th>Approved By</th>
                  <th>Delete</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
              <?php
                  $commentsSet = getComments('approved');

                  if($commentsSet != null && $commentsSet->num_rows > 0) {

                      $commentCounter = 1;

                      while($commentRow = $commentsSet->fetch_assoc()) {
                          echo '<td>'.$commentCounter.'</td>
                                       <td>'.$commentRow["author"].'</td>
                                       <td>'.$commentRow["added_on"].'</td>
                                       <td class="comment-container">
                                        '.$commentRow["comment_text"].'
                                       </td>
                                       <td>'.$currentAdmin.'</td>
                                       <td>
                                       <a href="admin comments.php?deleteComment='.$commentRow["id"].'" class="btn btn-danger">Delete</a>
                                       </td>
                                       <td>
                                       <button class="btn btn-success">Preview</button>
                                       </td>
                                       </tr>';

                          $commentCounter += 1;
                      }
                  }
              ?>
              </tbody>
            </table>
          </div><!--end of second table-container-->
        </div><!--end of content area-->
      </div><!--end of second row-->
    </div><!--end of container-->

    <!--CDN versions of JQuery and Popper.js-->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="../../Resources/Bootstrap v4.1/js/bootstrap.min.js"></script>
    <script src="../../Javascript/sidebar toggle.js"></script>
    <script src="../../Javascript/common.js"></script>

    <!--This is done to call displayInfoMessage function from php.
        common.js file has to be included after info-message-block
        div has been loaded and displayInfoMessage has to be called
        after common.js file has been included-->
    <?php

        if($approvedMsgDisplay === true) {
            $infoMessage = 'Comment approved successfully';
            echo '<script>
                    displayInfoMessage("Comment approved successfully", "success")
                  </script>';

        } else if($deleteMsgDisplay === true) {
            echo '<script>
                    displayInfoMessage("Comment deleted successfully", "success")
                  </script>';
        }

        // reload page after 5 seconds when admin is deleted or comment is approved
        if($approvedMsgDisplay === true || $deleteMsgDisplay === true) {
            reloadPage('admin comments.php', 5000);
        }
    ?>
  </body>
</html>