<?php

include 'partials/header.php';

// require a user id in order to edit the correct profile
if (isset($_GET['id'])) {
  $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
  // get user details (firstname, lastname, role) 
  $query = "SELECT * FROM users WHERE id = $id";
  $result = mysqli_query($connection, $query);
  $user = mysqli_fetch_assoc($result);
  var_dump($user['id']);
  // if no ID, send back to manage users page
} else {
  header('location: ' . ROOT_URL . 'admin/manage-users.php');
  die();
}

?>

<body>
  <section class="form__section">

    <div class="container form__section-container">
      <h2>Edit User</h2>

      <form action="<?= ROOT_URL ?>admin/edit-user-logic.php" method="POST">
        <!-- input type hidden in not the best way. Security risk. MUST FIX! -->
        <input type="hidden" value="<?= $user['id']  ?>" placeholder="ID" name="id" />
        <!-- FIX ABOVE!  -->
        <input type="text" value="<?= $user['firstname']  ?>" placeholder="First Name" name="firstname" />
        <input type="text" value="<?= $user['lastname']  ?>" placeholder="Last Name" name="lastname" />
        <input type="text" value="<?= $user['username']  ?>" placeholder="Username" name="username" />
        <input type="text" value="<?= $user['email']  ?>" placeholder="Email" name="email" />

        <select name="userrole">
          <option value="0">Author</option>
          <option value="1">Admin</option>
        </select>

        <button class="btn" type="submit" name="submit">Edit User</button>

      </form>
    </div>
  </section>
  <?php

  include '../partials/footer.php';

  ?>