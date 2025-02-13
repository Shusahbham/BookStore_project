<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bookstore</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="index.php" >Bookstore</a>
    
    <form class="form-inline ml-3" method="GET" action="index.php">
      <input class="form-control mr-2" type="search" placeholder="Search Books" aria-label="Search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
      <button class="btn btn-outline-light" type="submit">Search</button>
    </form>
    
    
    <form class="form-inline ml-3" method="GET" action="index.php">
      <?php 
      if (isset($_GET['search']) && !empty($_GET['search'])): ?>
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
      <?php endif; ?>
      <select class="form-control" name="filter" onchange="this.form.submit()">
         <option value="">Sort By</option>
         <option value="price_desc" <?php if(isset($_GET['filter']) && $_GET['filter']=='price_desc') echo 'selected'; ?>>Price: Highest to Lowest</option>
         <option value="rating_desc" <?php if(isset($_GET['filter']) && $_GET['filter']=='rating_desc') echo 'selected'; ?>>Rating: Highest to Lowest</option>
         <option value="best_selling" <?php if(isset($_GET['filter']) && $_GET['filter']=='best_selling') echo 'selected'; ?>>Best Selling</option>
      </select>
    </form>
    
    <div class="dropdown ml-auto">
      <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bars"></i>
      </button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <?php if(isset($_SESSION['user_id'])): ?>
          <a class="dropdown-item" href="dashboard.php">Dashboard</a>
          <a class="dropdown-item" href="logout.php" onclick="return confirm('Do you really want to log out?');">Logout</a>
        <?php else: ?>
          <a class="dropdown-item" href="login.php">Login</a>
          <a class="dropdown-item" href="register.php">Register</a>
          <a class="dropdown-item" href="help.php">Help</a>
        <?php endif; ?>
      </div>
    </div>
    
  </div>
</nav>
<div class="container mt-4">
