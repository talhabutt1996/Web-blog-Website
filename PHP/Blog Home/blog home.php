<?php
    require '../common.php';

    // returns comment count of any particular post
    function getPostCommentCount($postID) {
        $resultSet = getApprovedCommentCount($postID);

        if($resultSet != null && $resultSet->num_rows > 0) {
            $row = $resultSet->fetch_assoc();
            $commentCount = $row['comment_count'];
            return $commentCount;
        }
    }


    // steps for creating PAGINATION to show 2 posts per page

    // define results wanted per page
    $resultsPerPage = 2;

    // get total number of posts in database
    $postResultSet = getPosts();
    $postCount = $postResultSet->num_rows;

    // no. of pages to create
    $numberOfPages = ceil($postCount / $resultsPerPage);

    // display links to the pages
    // done below in html code

    // determine the current page number the user is on
    if(!isset($_GET['pageNumber'])) {
        $currentPageNumber = 1;
    }
    else if(isset($_GET['pageNumber'])) {
        $currentPageNumber = $_GET['pageNumber'];
    }

    // determine starting limit of the results to display on current page
    // starting limit is the number from where results will be fetched from databse
    $startingLimit = ($currentPageNumber - 1) * $resultsPerPage;

    // get the limited number of posts per page
    // get the most recent ones first
    function getLimitedPostsPerPage() {
        global $resultsPerPage, $startingLimit;

        $dbConnect = getDbConnection();
        $query = 'SELECT * FROM posts ORDER BY added_on DESC LIMIT ? OFFSET ?';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->bind_param('ii', $resultsPerPage, $startingLimit);
            $statement->execute();
            $limitedPostResults = $statement->get_result();

            $statement->close();
            $dbConnect->close();

            return $limitedPostResults;
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
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../../Resources/Bootstrap v4.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../../CSS/blog home.css"/>
    <link rel="stylesheet" href="../../CSS/common.css"/>
    <title>Web Technologies Blog</title>
  </head>
  <body>
      <nav class="navbar navbar-expand-lg top-navbar" id="blog-navbar">
          <a class="navbar-brand" href="blog home.php">
              <img src="../../Resources/images/logo.png" alt="logo" id="logo"/>
              <p>Web Technologies Blog</p>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-content">
              <span class="navbar-toggler-icon">
                  <i class="ion-navicon-round"></i>
              </span>
          </button>

          <div class="collapse navbar-collapse" id="navbar-content">
              <form class="form-inline ml-auto">
                  <input type="text" name="search-field" placeholder="Search" class="form-control"/>
                  <button type="submit" class="btn btn-success">Search</button>
              </form>
          </div>
      </nav><!--end of navbar-->

    <div class="container blog-body">

      <?php

         $resultSet = getLimitedPostsPerPage();

         // keep track of which bootstrap row is being filled
         // if its 1st row, add categories side bar
         // if its seconds row, add recent posts side bar
         $currentRow = 1;

         if($resultSet != null && $resultSet->num_rows > 0) {

             while($row = $resultSet->fetch_assoc()) {
                 $postID = $row['id'];
                 $postTitle = $row['title'];
                 $postCategory = $row['category'];
                 $addedOn = $row['added_on'];
                 $postImage = getPostImgFullPath($row['image']);
                 // get the first 100 characters of post content
                 // to display as partial text on blog home page
                 $partialPostContent = substr($row['content'], 0, 150);

                 echo '<div class="row">
                       <div class="col-md-9">
                       <div class="post-container clearfix">
                       <img class="post-banner" src="'.$postImage.'"/>
                       <h1 class="post-title">'.$postTitle.'</h1>
                       <small class="post-category">Category: '.$postCategory.'</small>
                       <small class="post-publish-date">Published On: '.$addedOn.'</small>
                       <span class="post-comment-count badge">Comments: '.getPostCommentCount($postID).'</span>
                       <p>
                         '.$partialPostContent.'...
                       </p>
                       <a href="../Blog Full Post/blog full post.php?post_title='.$postTitle.'" class="read-more-btn">Read More</a>
                       </div><!--end of post container-->
                       </div><!--end of first column-->';

                 // if its first row, add categories side bar
                 if($currentRow === 1) {
                     echo '<div class="col-md-3">
                           <div class="col post-categories-container">
                           <h2>Categories</h2>';

                     $categoryList = getCategoryList();

                     if($categoryList->num_rows > 0) {
                         while($categoryRow = $categoryList->fetch_assoc()) {
                             echo '<a href="#">'.$categoryRow["category_name"].'</a>';
                         }
                     }

                     echo '</div><!--end of categories container-->
                           </div><!--end of second column-->';
                 }

                 // if its second row, add recent posts side bar
                 if($currentRow === 2) {
                     echo '<div class="col-md-3">
                           <div class="col recent-posts-container">
                           <h2>Recent Posts</h2>';

                     $recentPosts = getRecentPosts();

                     if($recentPosts != null && $recentPosts->num_rows > 0) {
                         while($recentRow = $recentPosts->fetch_assoc()) {
                             echo '<div class="recent-post">
                                   <img src="'.getPostImgFullPath($recentRow["image"]).'" alt="css"/>
                                   <p>
                                     <a href="../Blog Full Post/blog full post.php?post_title='.$recentRow["title"].'">
                                     '.$recentRow["title"].'
                                     </a>
                                   </p>
                                   <small>'.$recentRow["added_on"].'</small>
                                   </div>';
                         }
                     }

                     echo '</div><!--end of recent posts container-->
                           </div><!--end of second column-->';
                 }

                 echo '</div><!--end of row-->';

                 //increment currentRow count
                 $currentRow += 1;

             }
         }

      ?>

      <div class="row pagination-row">
        <div class="col-sm-12 page-numbering">
          <nav>
            <ul class="pagination">
              <?php
                  for($page = 1; $page <= $numberOfPages; $page++) {
                      echo '<li class="page-item">
                            <a class="page-link" href="blog home.php?pageNumber='.$page.'">'.$page.'</a>
                            </li>';
                  }
              ?>
            </ul>
          </nav>
        </div><!--end of first column-->
      </div><!--end of third row-->

    </div><!--end of second container-->

    <div class="blog-footer">
      <h1>Contact Us</h1>
      <ul>
        <li class="email-link">
          <i class="ion-at"></i>
          email@gmail.com
        </li>
        <li>
          <i class="ion-social-facebook"></i>
          Facebook
        </li>
      </ul>
    </div><!--end of footer-->

    <!--CDN versions of JQuery and Popper.js-->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="../../Resources/Bootstrap v4.1/js/bootstrap.min.js"></script>
  </body>
</html>