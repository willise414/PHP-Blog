<?php
include 'partials/header.php';

// fetch featured post from DB
$featured_query = "SELECT * from posts where is_featured=1";
$featured_result = mysqli_query($connection, $featured_query);
$featured = mysqli_fetch_assoc($featured_result);


// fetch 9 posts from DB
$query = "SELECT * FROM posts where is_featured = 0 ORDER by date_time DESC LIMIT 9";
$posts = mysqli_query($connection, $query);
?>

<!-- ====================== FEATURED =============================== -->

<!-- show featured post from DB if one exists -->
<?php if (mysqli_num_rows($featured_result) == 1) : ?>
  <section class="featured">
    <div class="container featured__container">
      <div class="post__thumbnail">
        <img src="./images/<?= $featured['thumbnail'] ?>" alt="" />
      </div>
      <div class="post__info">
        <?php
        // fetch category

        $category_id = $featured['category_id'];;

        $category_query = "SELECT * FROM categories where id=$category_id";
        // $category_query = "SELECT posts, categories FROM posts INNER JOIN categories on categories.id = posts.category_id WHERE category_id = $category_id ";
        $category_result = mysqli_query($connection, $category_query);
        $category = mysqli_fetch_assoc($category_result);
        ?>
        <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $category['id'] ?>" class="category__button"><?= $category['title'] ?></a>
        <h2 class="post__title">
          <a href="<?= ROOT_URL ?>post.php?id=<?= $featured['id'] ?>"><?= $featured['title'] ?></a>
        </h2>
        <p class="post__body">
          <?= substr(htmlspecialchars_decode($featured['body']), 0, 400)  ?>
          ...<a href="<?= ROOT_URL ?>post.php?id=<?= $featured['id'] ?>"> <span id="read_more"> CONTINUE READING</span></a>

        </p>
        <div class="post__author">
          <?php
          // fetch user from users table using author_id
          $author_id = $featured['author_id'];
          $author_query = "SELECT * FROM users WHERE id=$author_id";
          $author_result = mysqli_query($connection, $author_query);
          $author = mysqli_fetch_assoc($author_result);
          // echo $author['firstname'] . $author['lastname'];

          ?>
          <div class="post__author-avatar">
            <img src="./images/<?= $author['avatar'] ?>" alt="" />
          </div>
          <div class="post_author-info">
            <h5>By: <?= "{$author['firstname']}  {$author['lastname']}" ?></h5>
            <small>
              <!-- date calculation if time is required -->
              <!-- <?= date("M d, Y - H:i", strtotime($featured['date_time'])) ?> -->
              <!-- Just date -->
              <?= date("M d, Y", strtotime($featured['date_time'])) ?>
            </small>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php endif ?>
<!-- ====================== END OF FEATURED=============================== -->

<section class="posts  <?= $featured ? '' : 'section_extra_margin' ?> ">
  <div class="container posts__container">
    <?php while ($post = mysqli_fetch_assoc($posts)) : ?>
      <article class="post">
        <div class="post__thumbnail">
          <img src="./images/<?= $post['thumbnail'] ?>" alt="" />
        </div>
        <div class="post__info">
          <?php
          // fetch category

          $category_id = $post['category_id'];;

          $category_query = "SELECT * FROM categories where id=$category_id";
          // $category_query = "SELECT posts, categories FROM posts INNER JOIN categories on categories.id = posts.category_id WHERE category_id = $category_id ";
          $category_result = mysqli_query($connection, $category_query);
          $category = mysqli_fetch_assoc($category_result);
          ?>
          <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $post['category_id'] ?>" class="category__button"><?= $category['title'] ?></a>
          <h3 class="post__title">
            <a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a>
          </h3>
          <p class="post__body">
            <?= substr(htmlspecialchars_decode($post['body']), 0, 200) ?>...<a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>"> <span id="read_more"> CONTINUE READING</span></a>
          </p>
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
            <div class="post__author-info">
              <h5>By: <?= "{$author['firstname']}  {$author['lastname']}" ?></h5>
              <small><?= date("M d, Y", strtotime($post['date_time'])) ?></small>
            </div>
          </div>
        </div>
      </article>
    <?php endwhile ?>


  </div>
</section>

<!-- ====================== END OF POSTS =============================== -->

<section class="category__buttons">
  <div class="container category__buttons-container">
    <?php
    $all_categories_query = "SELECT * FROM categories";
    $all_categories_result = mysqli_query($connection, $all_categories_query);
    ?>
    <?php while ($category = mysqli_fetch_assoc($all_categories_result)) : ?>
      <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $category['id'] ?>" class="category__button"><?= $category['title'] ?></a>
    <?php endwhile ?>
  </div>
</section>

<!-- ====================== END OF CATEGORY BUTTONS =============================== -->

<?php
include 'partials/footer.php';
?>