<aside id="sidebar">
  <div class="top">
    <div class="logo">
      <img src="../../images/logo.png" alt="log.png" />
    </div>
    <div class="close" id="close-btn">
      <i class="fa-solid fa-xmark"></i>
    </div>
  </div>
  <div class="sidebar-function-container">
    <a href="./dashboard.php" class="sidebar-link">
      <i class="fa-solid fa-table-columns"></i>
      <h3>Dashboard</h3>
    </a>

    <a href="./user_manager.php" class="sidebar-link">
      <i class="fa-solid fa-user"></i>
      <h3>Staff</h3>
    </a>

    <a href="./menu_item_manager.php" class="sidebar-link">
      <i class="fa-solid fa-utensils"></i>
      <h3>Menu items</h3>
    </a>

    <a href="./category_manager.php" class="sidebar-link">
      <i class="fa-solid fa-tags"></i>
      <h3>Category</h3>
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

    <a onclick="openAuthenticationCheckForm()" class="sidebar-link">
      <i class="fa-solid fa-gear"></i>
      <h3>Settings</h3>
    </a>

    <a href="./controller/login_controller.php?logout" class="logout">
      <i class="fa-solid fa-arrow-right-from-bracket"></i>
      <h3>Logout</h3>
    </a>
  </div>
</aside>

<div id="popup-form-authentication-check" class="authentication-check-overlay">
  <div class="popup-form-authentication-check">
    <h2>Please confirm the authentication</h2>
    <form action="./controller/login_controller.php" method="POST" id="popup-authentication-form">
      <i class="fa-solid fa-circle-xmark" onclick="closeAuthenticationCheckForm()"></i>
      <input type="password" placeholder="Password" name="password" id="password">
      <input type="submit" name="authentication-check-submit" class="information-bg" value="check">
    </form>
  </div>
</div>

<script>
  function openAuthenticationCheckForm() {
    document.getElementById("popup-form-authentication-check").style.display = "flex";
  }

  function closeAuthenticationCheckForm() {
    document.getElementById("popup-form-authentication-check").style.display = "none";
  }

  // Get the current page URL
  let currentPage = window.location.href;

  // Get all the sidebar links
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