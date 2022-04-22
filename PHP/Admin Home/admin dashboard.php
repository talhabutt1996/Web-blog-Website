<?php

    session_start();
    require '../common.php';

    // check if any admin is logged in, if not, redirect lo login page
    if(!isset($_SESSION['any_admin_logged_in'])) {
        header('Location: ../Admin Login/admin login.php');
    }

    // currently logged in admin name, will be used to
    // display in welcome message on admin home page
    $currentAdminName = $_SESSION['current_admin_fullname'];

    // get unapproved comment count of specific post
    function getUnApprovedCommentCount($postID) {
        $dbConnect = getDbConnection();
        $query = 'SELECT COUNT(*) AS "comment_count" FROM comments WHERE post_id = ? AND approved = "no"';
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

?>

<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../Resources/Bootstrap v4.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../../CSS/admin dashboard.css"/>
    <link rel="stylesheet" href="../../CSS/common.css"/>
    <title>Admin Home</title>
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
            <li class="active-tab">
              <a href="#">
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
          <p>Dashboard</p>
          <p class="welcome-msg">Welcome <?php echo $currentAdminName?>!</p>

          <?php

             $postsResultSet = getPosts();

             if($postsResultSet != null && $postsResultSet->num_rows > 0) {

                 // post counter for No. column
                 $counter = 1;

                 echo '<div class="table-responsive">
                       <table class="table table-bordered table-hover table-sm">
                       <thead>
                       <tr>
                       <th>No.</th>
                       <th>Post Title</th>
                       <th>Category</th>
                       <th>Author</th>
                       <th>Date &amp; Time</th>
                       <th>Banner</th>
                       <th>Comments</th>
                       <th>Action</th>
                       <th>Details</th>
                       </tr>
                       </thead>
                       <tbody>';

                 while($row = $postsResultSet->fetch_assoc()) {

                     // get approved comment count of current post
                     $result = getApprovedCommentCount($row['id']);
                     $countSet = $result->fetch_assoc();
                     $approvedCommentCount = $countSet['comment_count'];

                     // get unapproved comment count of current post
                     $result = getUnApprovedCommentCount($row['id']);
                     $countSet = $result->fetch_assoc();
                     $unapprovedCommentCount = $countSet['comment_count'];

                     echo '<tr>
                           <td>'.$counter.'</td>
                           <td>
                           <a href="#">'.$row["title"].'</a>
                           </td>
                           <td>'.$row["category"].'</td>
                           <td>'.$row["author"].'</td>
                           <td>'.$row["added_on"].'</td>
                           <td>
                           <img src="'.getPostImgFullPath($row["image"]).'" alt="CSS3 Flexbox">
                           </td>
                           <td>
                           <span class="badge badge-danger">'.$unapprovedCommentCount.'</span>
                           <span class="badge badge-success">'.$approvedCommentCount.'</span>
                           </td>
                           <td>
                           <button class="btn btn-warning">Edit</button>
                           <button class="btn btn-danger">Delete</button>
                           </td>
                           <td>
                           <button class="btn btn-success">Preview</button>
                           </td>
                           </tr>';

                     // increment post counter
                     $counter += 1;
                 }

                 echo '</tbody>
                       </table>
                       </div><!--end of table-container-->';
             }

          ?>

        </div>
      </div><!--end of second row-->
    </div><!--end of container-->

    <!--CDN versions of JQuery and Popper.js-->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="../../Resources/Bootstrap v4.1/js/bootstrap.min.js"></script>
    <script src="../../Javascript/sidebar toggle.js"></script>
  </body>
</html>