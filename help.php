<?php

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = $conn->real_escape_string($_POST['name']);
    $email   = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);
    
    $sql = "INSERT INTO help_tickets (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
    if ($conn->query($sql)) {
        $success = "Your message has been submitted. We will contact you soon.";
    } else {
        $error = "Error: " . $conn->error;
    }
}
include 'header.php';
?>

<div class="row">
  <div class="col-md-8 offset-md-2">
    <h2>Help & Support</h2>
    <?php if (isset($success)): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php else: ?>
      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>
      <form method="POST" action="help.php">
        <div class="form-group">
          <label>Your Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Your Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Subject</label>
          <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Your Message</label>
          <textarea name="message" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Help Request</button>
      </form>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>
