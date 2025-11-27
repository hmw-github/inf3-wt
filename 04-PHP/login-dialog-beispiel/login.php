<?php

//var_dump($_POST);

$username = $_POST['username'] ?? "";
$passwort = $_POST['passwort'] ?? "";
$usernameError = "";
$passwordError = "";
$checkError = "";

if (isset($_POST['login'])) {
  if (empty($username)) {
    $usernameError = "Please supply a username!";
  }
  if (empty($passwort)) {
    $passwordError = "Please supply a password!";
  }
  if (!empty($username) && !empty($passwort)) {
    if ($username != 'Hugo' || $passwort != '123') {
      $checkError = 'Invalid combination!';
    } else {
      header('location: app.php?username=' . $username);
      exit();
    }
  }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Login</title>
    <style>
      .error {
        color: red;
      }
    </style>
  </head>
  <body>
    <form action="" method="POST">
      <p>
        Username: <input type="text" name="username" value="<?= $username ?>">
        <span class="error"><?= $usernameError ?></span>
      </p>
      <p>
        Passwort: <input type="password" name="passwort" value="<?= $passwort ?>">
        <span class="error"><?= $passwordError ?></span>
      </p>
      <p class="error">
        <?= $checkError ?>
      </p>
      <button type="submit" name="login">Login</button>
    </form>
  </body>
</html>