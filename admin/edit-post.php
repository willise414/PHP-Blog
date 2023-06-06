<?php

include 'partials/header.php';
// fetch categories from DB
$category_query = "SELECT * from categories";
$categories = mysqli_query($connection, $category_query);

// fetch form data from database so we can edit it
if (isset($_GET['id'])) {
  $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
  $query = "SELECT * from posts where id=$id";
  $result = mysqli_query($connection, $query);
  $post = mysqli_fetch_assoc($result);
} else {
  header('location: ' . ROOT_URL . 'admin/');
  die();
}
?>
<script src="../js/tinymce/tinymce.min.js"></script>

<body>
  <section class="form__section">
    <div class="container form__section-container">
      <h2>Edit Post</h2>

      <!-- <p>Debug: <?php echo ("{$_SESSION['debug']}" . "<br />"); ?></p> -->



      <!-- <p>
        <?= ("{$_SESSION['sql']}" . "<br />"); ?>
      </p> -->
      <!-- <?php var_dump($post) ?> -->



      <form action="<?= ROOT_URL ?>admin/edit-post-logic.php" enctype="multipart/form-data" method="POST">
        <input type="hidden" name="id" value="<?= $post['id'] ?>" />
        <input type="hidden" name="previous_thumbnail_name" value="<?= $post['thumbnail'] ?>" />
        <!-- <?= $previous_thumbnail_name ?>; -->
        <input type="text" name="title" value="<?= $post['title'] ?>" placeholder="Title" />
        <select name="category">
          <?php while ($category = mysqli_fetch_assoc($categories)) : ?>
            <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
          <?php endwhile ?>
        </select>

        <textarea rows="10" name="body" id='body' placeholder="Body"><?= $post['body'] ?></textarea>
        <script>
          tinymce.init({
            selector: '#body',
            menubar: false,
            plugins: 'link',
            toolbar: 'bold italic underline | alignleft aligncenter alignright | paste | link',
            branding: false,
            content_css: ['writer', '../css/style.css']


          })
        </script>
        <div class="form__control inline">
          <input type="checkbox" id="is_featured" name="is_featured" value='1' checked>
          <label for="is_featured">Featured</label>
        </div>
        <div class="form__control">
          <label for="thumbnail">Edit Thumbnail</label>
          <input type="file" id="thumbnail" name="thumbnail">

        </div>
        <button class="btn" name="submit" type="submit">Update Post</button>
      </form>
    </div>
  </section>
  <?php

  include '../partials/footer.php';

  ?>