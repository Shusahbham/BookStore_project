<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';
include 'header.php';

$user_id = $_SESSION['user_id'];

$adsResult = $conn->query("SELECT * FROM ads WHERE user_id = $user_id ORDER BY created_at DESC");

$ordersResult = $conn->query("SELECT orders.*, ads.book_name FROM orders INNER JOIN ads ON orders.ad_id = ads.id WHERE ads.user_id = $user_id ORDER BY orders.created_at DESC");
?>

<h2 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
<a href="post_ad.php" class="btn btn-success mb-3">Post New Advertisement</a>

<ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="ads-tab" data-toggle="tab" href="#ads" role="tab" aria-controls="ads" aria-selected="true">
      Your Advertisements
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="orders-tab" data-toggle="tab" href="#orders" role="tab" aria-controls="orders" aria-selected="false">
      Orders Received
    </a>
  </li>
</ul>
<div class="tab-content mt-3" id="dashboardTabsContent">
  <div class="tab-pane fade show active" id="ads" role="tabpanel" aria-labelledby="ads-tab">
    <div class="row">
        <?php if ($adsResult && $adsResult->num_rows > 0): ?>
          <?php while ($ad = $adsResult->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
              <div class="card h-100">
                <?php if (!empty($ad['image'])): ?>
                  <img src="<?php echo $ad['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($ad['book_name']); ?>">
                <?php else: ?>
                  <img src="default_book.jpg" class="card-img-top" alt="Default Book">
                <?php endif; ?>
                <div class="card-body">
                  <h5 class="card-title"><?php echo htmlspecialchars($ad['book_name']); ?></h5>
                  <p class="card-text"><?php echo htmlspecialchars($ad['description']); ?></p>
                  <p><strong>$<?php echo htmlspecialchars($ad['price']); ?></strong></p>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="ml-3">You haven't posted any advertisements yet.</p>
        <?php endif; ?>
    </div>
  </div>
  <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
    <?php if ($ordersResult && $ordersResult->num_rows > 0): ?>
      <table class="table">
        <thead>
          <tr>
            <th>Book</th>
            <th>Buyer Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Rating</th>
            <th>Ordered At</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($order = $ordersResult->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($order['book_name']); ?></td>
              <td><?php echo htmlspecialchars($order['buyer_name']); ?></td>
              <td><?php echo htmlspecialchars($order['buyer_address']); ?></td>
              <td><?php echo htmlspecialchars($order['buyer_phone']); ?></td>
              <td>
                <?php 
                $rating = $order['rating'];
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating) {
                        echo '<span class="fa fa-star checked"></span>';
                    } else {
                        echo '<span class="fa fa-star"></span>';
                    }
                }
                ?>
              </td>
              <td><?php echo htmlspecialchars($order['created_at']); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No orders received yet.</p>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>
