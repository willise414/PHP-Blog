<?php

include 'partials/header.php';

// get list of categories in DB
$query = "SELECT * FROM categories";
$categories = mysqli_query($connection, $query);

// get back form data if error occurred
$title = $_SESSION['add-post-data']['title'] ?? null;
$body = $_SESSION['add-post-data']['body'] ?? null;

// delete form data session
unset($_SESSION['add-post-data']);
?>
<script src="../js/tinymce/tinymce.min.js"></script>

<body>

  <section class="form__section">
    <div class="container form__section-container">
      <h2>Add Post</h2>
      <?php if (isset($_SESSION['add-post'])) : ?>
        <div class="alert__message error">
          <p>
            <?= $_SESSION['add-post'];
            unset($_SESSION['add-post']);
            ?>
          </p>
        </div>
      <?php endif ?>
      <!-- <p>Debug: <?php echo ("{$_SESSION['debug']}" . "<br />"); ?></p> -->
      <!-- <p>
         <?php echo ("{$_SESSION['sql']}" . "<br />"); ?> 
      </p> -->

      <form action="<?= ROOT_URL ?>admin/add-post-logic.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" value="<?= $title  ?>" placeholder="Title" />
        <select name="category">
          <?php while ($category = mysqli_fetch_assoc($categories)) : ?>
            <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
            <?php endwhile ?>;
        </select>

        <textarea rows="10" name="body" id='body' placeholder="Body"><?= $body ?></textarea>
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


        <!-- Only show Featured to admins -->
        <?php if (isset($_SESSION['user_is_admin'])) : ?>
          <div class="form__control inline">
            <input type="checkbox" name="is_featured" value="1" id="is_featured" checked>
            <label for="is_featured">Featured</label>
          </div>
        <?php endif ?>
        <div class="form__control">
          <label for="thumbnail">Add Thumbnail</label>
          <input type="file" id="thumbnail" name="thumbnail">
        </div>
        <button class="btn" name="submit" type="submit">Add Post</button>
      </form>
    </div>
  </section>
  <?php

  include '../partials/footer.php';

  ?>