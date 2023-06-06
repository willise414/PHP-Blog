<?php

require 'partials/header.php';

if (isset($_GET['search']) && isset($GET['submit'])) {
    $search = filter_var($GET['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $query = "SELECT * FROM posts WHERE title LIKE '%$search%'";
    $posts = mysqli_query($connection, $query);
    // } else {
    //     header('location: ' . ROOT_URL . 'blog.php');
    // }

}


?>
<section class="posts  <?= $posts ? '' : 'section_extra_margin' ?> ">

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

<?php include 'partials/footer.php' ?>