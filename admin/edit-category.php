<?php

include 'partials/header.php';
// require 'config/database.php';

if (isset($_GET['id'])) {
  $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

  // get categories from DB
  $query = "SELECT * FROM categories WHERE id=$id";
  $result = mysqli_query($connection, $query);

  if (mysqli_num_rows($result) == 1) {
    $category = mysqli_fetch_assoc($result);
  }
} else {
  header('location:' . ROOT_URL . 'admin/manage-categories.php');
}

?>

<body>
  <section class="form__section">
    <div class="container form__section-container">
      <h2>Edit Category</h2>

      <form action="<?= ROOT_URL ?>admin/edit-category-logic.php" method="POST">
        <!-- FIND ANOTHER WAY RATHER THAN USING HIDDEN. SECURITY RISK!! -->
        <input type="hidden" name="id" value="<?= $category['id'] ?>" />
        <input type="text" name="title" value="<?= $category['title'] ?>" placeholder="Title" />
        <textarea rows="4" name="description" placeholder="Description"><?= $category['description'] ?></textarea>

        <button class="btn" name="submit" type="submit">Update Category</button>
      </form>
    </div>
  </section>
  <?php

  include '../partials/footer.php';

  ?>