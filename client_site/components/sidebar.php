<?php
if (!isset($connection)) {
  require "../dao/connection.php";
}
$get_category = "SELECT * FROM category";
$dataset = $connection->query($get_category); // Fix the variable name here
?>

<aside id="sidebar">
  <div class="top">
    <div class="logo">
      <img src="../../images/logo.png" alt="log.png" />
    </div>
  </div>
  <div class="sidebar-function-container">
    <?php
    while ($data = $dataset->fetch()) {
      /*
      * Althoughht this condition checking ($data["id"] == 8) is hardcoding, 
      *the ID will not be changed, as it is not allowed to remove the Special menu in the admin site.
       */
      if ($data["id"] == 8) continue;
    ?>
      <a href="./menu.php?category-id=<?php echo $data["id"]; ?>" class="sidebar-link">
        <?php echo $data["category_icon"]; ?>
        <h3><?php echo $data["category_name"]; ?></h3>
      </a>
    <?php
    }
    $get_pending_order_sql = "SELECT COUNT(*) FROM orders WHERE order_status='Pending'";
    $stmt1 = $connection->query($get_pending_order_sql);
    $total_pending_orders = $stmt1->fetchColumn();

    $get_completed_order_sql = "SELECT COUNT(*)  FROM orders WHERE order_status='Completed'";
    $stmt2 = $connection->query($get_completed_order_sql);
    $total_completed_orders = $stmt2->fetchColumn();
    ?>
    <a href="./special-menu.php" class="sidebar-link">
      <i class="fa-solid fa-calendar-minus"></i>
      <h3>Special Menu</h3>
    </a>
    <a href="./pending_order_panel.php" class="sidebar-link">
      <i class="fa-solid fa-list-check"></i>
      <h3>Pending Orders</h3>
      <span class="report-count">
        <?php echo $total_pending_orders; ?>
      </span>
    </a>

    <a href="./completed_order_panel.php" class="sidebar-link">
      <i class="fa-solid fa-utensils"></i>
      <h3>Completed Orders</h3>
      <span class="report-count">
        <?php echo $total_completed_orders; ?>
      </span>
    </a>
  </div>
</aside>

<script>
  let currentPage = window.location.href;
  let sidebarLinks = document.getElementsByClassName("sidebar-link");

  // Loop through the sidebar links and check if the href matches the current page
  for (let i = 0; i < sidebarLinks.length; i++) {
    var link = sidebarLinks[i];

    // If the href matches the current page, add the active class
    if (link.href === currentPage) {
      link.classList.add("active");
    } else {
      link.classList.remove("active");
    }
  }
</script>