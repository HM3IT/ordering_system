<?php
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
    ?>
      <a href="./menu.php?category-id=<?php echo $data["id"]; ?>" class="sidebar-link">
        <?php echo $data["category_icon"]; ?>
        <h3><?php echo $data["category_name"]; ?></h3>
      </a>
    <?php
    }
    ?>

    <a href="./special-menu.php" class="sidebar-link">
      <i class="fa-solid fa-calendar-minus"></i>
      <h3>Special Menu</h3>
    </a>

    <a href="./order_manager.php" class="sidebar-link">
      <i class="fa-solid fa-list-check"></i>
      <h3>Orders</h3>
      <span class="report-count"><?php
                                  if (!isset($connection)) {
                                    require "../dao/connection.php";
                                  }
                                  $get_all_order_sql = "SELECT COUNT(*) AS row_count FROM orders";
                                  $result = $connection->query($get_all_order_sql);
                                  $row_count = $result->fetchColumn();

                                  echo $row_count; ?></span>
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