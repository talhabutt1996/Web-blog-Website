<?php

    require '../common.php';

    // get the title of the post that is clicked on from GET variable
    // It is used to get the entire post from the database and display the
    // whole post
    $postClicked = $_GET['post_title'];

    // get the specific post
    function getFullPost($postTitle) {
        $dbConnect = getDbConnection();
        $query = 'SELECT * FROM posts WHERE title = ?';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->bind_param('s', $postTitle);
            $statement->execute();
            $resultSet = $statement->get_result();

            $statement->close();
            $dbConnect->close();

            return $resultSet;
        }
        return null;
    }

    // get approved comments related to any post
    function getApprovedPostComments($postTitle) {
        $dbConnect = getDbConnection();
        $query = 'SELECT * FROM comments WHERE approved = "yes" AND post_id = (SELECT id FROM posts WHERE title = ?)';
        $statement = $dbConnect->prepare($query);

        if($statement) {
            $statement->bind_param('s', $postTitle);
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
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../../Resources/Bootstrap v4.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../../CSS/blog full post.css"/>
    <link rel="stylesheet" href="../../CSS/common.css"/>
    <title>Introduction To CSS Flexbox</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg top-navbar" id="blog-navbar">
      <a class="navbar-brand" href="../Blog Home/blog home.php">
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
      <div class="row">
        <div class="col-md-9">

           <?php

               $resultSet = getFullPost($postClicked);

               if($resultSet != null && $resultSet->num_rows > 0) {
                   $row = $resultSet->fetch_assoc();

                   echo '<div class="post-container clearfix">
                         <h1 class="post-title">'.$row["title"].'</h1>
                         <small class="post-category">Category: '.$row["category"].'</small>
                         <small class="post-publish-date">Published On: '.$row["added_on"].'</small>
                         <img class="post-banner" src="'.getPostImgFullPath($row["image"]).'"/>
                         <p>'.$row["content"].'.</p>
                         </div>';
               } else {
                   echo $resultSet->num_rows;
               }
           ?>

        </div><!--end of first column-->

        <div class="col-md-3">
          <div class="row blog-row">
            <div class="col post-categories-container">
              <h2>Categories</h2>
                <?php
                    $categoryList = getCategoryList();

                    if($categoryList != null && $categoryList->num_rows > 0) {
                        while($categoryRow = $categoryList->fetch_assoc()) {
                            echo '<a href="#">'.$categoryRow["category_name"].'</a>';
                        }
                    }
                ?>
            </div>
          </div><!--end of first nested row-->

          <div class="row second-row">
            <div class="col recent-posts-container">
              <h2>Recent Posts</h2>
                <?php
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
                ?>
            </div>
          </div><!--end of second nested row-->
        </div><!--end of second column-->
      </div><!--end of first row-->

      <div class="row comment-row">
        <div class="col-md-9">
          <div class="comments-container">
            <h1>Comments</h1>
            <?php
                $resultSet = getApprovedPostComments($postClicked);

                if($resultSet != null && $resultSet->num_rows > 0) {
                    while($row = $resultSet->fetch_assoc()) {
                        echo '<div class="user-comment-container">
                              <img src="../../Resources/images/user.png" alt="user"/>
                              <div class="user-comment">
                              <h5>'.$row["author"].'</h5>
                              <small class="comment-post-date">'.$row["added_on"].'</small>
                              <div>'.$row["comment_text"].'</div>
                              </div>
                              </div>';
                    }
                }
                else { // if there are no comments to display
                    echo 'No comments to display';
                }
            ?>
          </div><!--end of comments container-->
        </div><!--end of first column-->
      </div><!--end of second row-->

      <div class="row comment-form-row">
        <div class="col-md-9">
          <div class="comment-form-container">
            <form class="comment-form" method="post" action="">
              <h1>Leave a comment</h1>

              <!--used for displaying info messages when a comment form is submitted-->
              <p id="info-message-block">
                <span></span>
              </p>

              <div class="form-group">
                <input type="text" id="name-field" name="name-field" class="form-control" placeholder="Name"/>
              </div>
              <div class="form-group">
                <input type="text" id="email-field" name="email-field" class="form-control" placeholder="Email"/>
              </div>
              <div class="form-group">
                <textarea class="form-control comment-field" id="comment-field" name="comment-field" placeholder="Comment"></textarea>
              </div>
              <!--this hidden field is used to identify th post on which comment is being made
                  this field value is equal to the post title whihc is currently open
                  value of this field is used when saving a user's comment-->
              <input type="hidden" name="post-identifier-field" value="<?php echo $postClicked?>"/>
              <button class="btn btn-success" type="submit">Post Comment</button>
            </form>
          </div><!--end of form container-->
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
    <script src="../../Javascript/common.js"></script>
    <script src="../../Javascript/comment form validation.js"></script>
  </body>
</html>