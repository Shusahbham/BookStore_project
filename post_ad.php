<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id           = $_SESSION['user_id'];
    $book_name         = $conn->real_escape_string($_POST['book_name']);
    $title             = $conn->real_escape_string($_POST['title']);
    $genre             = $conn->real_escape_string($_POST['genre']);
    $price             = $conn->real_escape_string($_POST['price']);
    $publication_house = $conn->real_escape_string($_POST['publication_house']);
    $contact           = $conn->real_escape_string($_POST['contact']);
    $description       = $conn->real_escape_string($_POST['description']);

    $imagePath = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $imagePath = $target_file;
        }
    }

    $sql = "INSERT INTO ads (user_id, book_name, title, genre, price, publication_house, contact, image, description)
            VALUES ($user_id, '$book_name', '$title', '$genre', '$price', '$publication_house', '$contact', '$imagePath', '$description')";
    if ($conn->query($sql)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
include 'header.php';
?>

<div class="row">
  <div class="col-md-8 offset-md-2">
    <h2>Post a New Advertisement</h2>
    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="post_ad.php" enctype="multipart/form-data">
      <div class="form-group">
        <label>Book Name</label>
        <input type="text" name="book_name" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" class="form-control">
      </div>
      <div class="form-group">
        <label>Genre</label>
        <input type="text" name="genre" class="form-control">
      </div>
      <div class="form-group">
        <label>Price ($)</label>
        <input type="number" step="0.01" name="price" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Publication House</label>
        <input type="text" name="publication_house" class="form-control">
      </div>
      <div class="form-group">
        <label>Contact Information</label>
        <input type="text" name="contact" class="form-control">
      </div>
      <div class="form-group">
        <label>Book Image</label>
        <input type="file" name="image" class="form-control-file">
      </div>
      <div class="form-group">
        <label>Book Description</label>
        <textarea name="description" class="form-control" rows="4"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Post Advertisement</button>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>
