<?php

session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email    = $conn->real_escape_string($_POST['email']);
    $contact  = $conn->real_escape_string($_POST['contact']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (username, email, password, contact) VALUES ('$username', '$email', '$password', '$contact')";
    if ($conn->query($sql)) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
include 'header.php';
?>

<div class="row">
  <div class="col-md-6 offset-md-3">
    <h2>Register</h2>
    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="register.php">
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Contact</label>
        <input type="text" name="contact" class="form-control">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Register</button>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>
