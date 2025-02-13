<?php
include 'config.php';
if (!isset($_GET['ad_id'])) {
    header("Location: index.php");
    exit;
}
$ad_id = intval($_GET['ad_id']);

$adResult = $conn->query("SELECT * FROM ads WHERE id = $ad_id");
if ($adResult->num_rows == 0) {
    echo "Advertisement not found.";
    exit;
}
$ad = $adResult->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $buyer_name    = $conn->real_escape_string($_POST['buyer_name']);
    $buyer_address = $conn->real_escape_string($_POST['buyer_address']);
    $buyer_phone   = $conn->real_escape_string($_POST['buyer_phone']);
    $buyer_info    = $conn->real_escape_string($_POST['buyer_info']);
    $rating        = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    
    $sql = "INSERT INTO orders (ad_id, buyer_name, buyer_address, buyer_phone, buyer_info, rating)
            VALUES ($ad_id, '$buyer_name', '$buyer_address', '$buyer_phone', '$buyer_info', $rating)";
    if ($conn->query($sql)) {
        $success = "Congratulations, your order has been successfully submitted. The seller will contact you as soon as possible.";
    } else {
        $error = "Error: " . $conn->error;
    }
}
include 'header.php';
?>

<div class="row">
  <div class="col-md-8 offset-md-2">
    <h2>Order Book:<?php echo htmlspecialchars($ad['book_name']); ?></h2>
    <?php if (isset($success)): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php else: ?>
      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>
      <form method="POST" action="order.php?ad_id=<?php echo $ad_id; ?>">
        <div class="form-group">
          <label>Your Name</label>
          <input type="text" name="buyer_name" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Your Address</label>
          <input type="text" name="buyer_address" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Your Phone Number</label>
          <input type="text" name="buyer_phone" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Additional Information</label>
          <textarea name="buyer_info" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
          <label>Rate this Book (out of 5)</label>
          <div class="star-rating">
            <?php for ($i = 5; $i >= 1; $i--): ?>
              <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>">
              <label for="star<?php echo $i; ?>" title="<?php echo $i; ?> stars">&#9733;</label>
            <?php endfor; ?>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit Order</button>
      </form>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>
