<?php
include 'partials/header.php';

// fetch post from DB if ID is set
if (isset($_GET['id'])) {
  $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
  $query = "SELECT * from posts where id=$id";
  $result = mysqli_query($connection, $query);
  $post = mysqli_fetch_assoc($result);
}


?>

<!-- ====================== POST=============================== -->

<section class="singlepost">
  <div class="container singlepost__container">
    <h2>
      <?= $post['title'] ?>
    </h2>
    <div class="post__author">
      <?php
      // fetch user from users table using author_id
      $author_id = $post['author_id'];
      $author_query = "SELECT * FROM users WHERE id=$author_id";
      $author_result = mysqli_query($connection, $author_query);
      $author = mysqli_fetch_assoc($author_result);
      // echo $author['firstname'] . $author['lastname'];
      ?>
      <div class="post__author-avatar">
        <img src="./images/<?= $author['avatar'] ?>" alt="" />
      </div>
      <div class="post_author-info">
        <h5>By: <?= "{$author['firstname']} {$author['lastname']}" ?></h5>
        <small><?= date("M d, Y", strtotime($post['date_time'])) ?></small>
      </div>
    </div>
    <div class="singlepost__thumbnail">
      <img src="./images/<?= $post['thumbnail'] ?>" alt="" />
    </div>
    <p>
      <?= htmlspecialchars_decode($post['body']) ?>
    </p>
  </div>
</section>

<!-- ====================== END OF POST =============================== -->
<?php

include 'partials/footer.php';

?>