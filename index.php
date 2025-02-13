<?php

session_start();
include 'config.php';
include 'header.php';


$bookCountResult = $conn->query("SELECT COUNT(*) as count FROM ads");
$bookCountRow = $bookCountResult->fetch_assoc();
$bookCount = $bookCountRow['count'];

$orderCountResult = $conn->query("SELECT COUNT(*) as count FROM orders");
$orderCountRow = $orderCountResult->fetch_assoc();
$orderCount = $orderCountRow['count'];


$whereClause = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $whereClause = " WHERE book_name LIKE '%$search%' OR title LIKE '%$search%'";
}


if (isset($_GET['filter']) && !empty($_GET['filter'])) {
    $filter = $_GET['filter'];
    if ($filter == 'price_desc') {
        $query = "SELECT * FROM ads $whereClause ORDER BY price DESC";
    } elseif ($filter == 'rating_desc') {
        $query = "SELECT ads.*, IFNULL((SELECT AVG(rating) FROM orders WHERE orders.ad_id = ads.id), 0) as avg_rating 
                  FROM ads $whereClause ORDER BY avg_rating DESC";
    } elseif ($filter == 'best_selling') {
        $query = "SELECT ads.*, (SELECT COUNT(*) FROM orders WHERE orders.ad_id = ads.id) as order_count 
                  FROM ads $whereClause ORDER BY order_count DESC";
    } else {
        $query = "SELECT * FROM ads $whereClause ORDER BY created_at DESC";
    }
} else {
    $query = "SELECT * FROM ads $whereClause ORDER BY created_at DESC";
}

$result = $conn->query($query);
?>


<div id="carouselExampleIndicators" class="carousel slide mb-4" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    
    <div class="carousel-item active">
      <img src="img1.jpg" class="d-block w-100" alt="Carousel 1">
    </div>
    <div class="carousel-item">
      <img src="img3.jpg" class="d-block w-100" alt="Carousel 2">
    </div>
    <div class="carousel-item">
      <img src="img4.jpg" class="d-block w-100" alt="Carousel 3">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>


 <h3>Statistics:</h3>
<div class="row mb-4 text-center">
  <div class="col-md-6">
    <h4>Total Books Listed:</h4>
    <p><?php echo $bookCount; ?></p>
  </div>
  <div class="col-md-6">
    <h4>Total Books Purchased:</h4>
    <p><?php echo $orderCount; ?></p>
  </div>
</div>


<div class="mb-3">
    <h3>Recently Added:</h3>
</div>


<div class="row">
    <?php if($result && $result->num_rows > 0): ?>
        <?php while($ad = $result->fetch_assoc()): ?>
            <div class="col-md-3 col-sm-4 mb-4">
                <div class="card h-100">
                    <?php if(!empty($ad['image'])): ?>
                        <img src="<?php echo $ad['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($ad['book_name']); ?>">
                    <?php else: ?>
                        <img src="default_book.jpg" class="card-img-top" alt="Default Book">
                    <?php endif; ?>
                    <div class="card-body p-2">
                        <h6 class="card-title"><?php echo htmlspecialchars($ad['book_name']); ?></h6>
                        <p class="card-text"><?php echo htmlspecialchars(substr($ad['description'], 0, 50)); ?>...</p>
                        <p><strong>$<?php echo htmlspecialchars($ad['price']); ?></strong></p>
                        <a href="order.php?ad_id=<?php echo $ad['id']; ?>" class="btn btn-sm btn-primary">Order</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No books found.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
