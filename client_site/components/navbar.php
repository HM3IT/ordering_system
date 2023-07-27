<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>
<header id="header">
  <nav class="nav-bar nav-bar-left">
    <ul>
      <li class="">
        <h4 id="store-name">SMART cafe & bakery shop</h4>
        <p id="slogan">Awesome Cafe and Beverages</p>
      </li>
    </ul>
  </nav>
  <?php
  require './components/search-bar.php';
  ?>
  <nav class="nav-bar nav-bar-right">
    <ul>

      <li class="nav-bar-btn view-cart-btn">
        <a href="view-cart-list.php" class="view-cart-icon">
          <i class="fa-solid fa-cart-arrow-down"></i>
        </a>
      </li>
      <li class="nav-bar-btn login-btn">
        <i class="fa-solid fa-user"></i>
        <a onclick="openAuthenticationCheckForm()"> Profile </a>
      </li>
      <li class="nav-bar-btn login-btn">
        <i class="fa-solid fa-right-from-bracket"></i>
        <a href="./controller/login_controller.php?logout">logout</a>
      </li>
    </ul>
  </nav>

</header>
<div id="popup-form-authentication-check" class="authentication-check-overlay">
  <div class="popup-form-authentication-check">
    <h2>Please confirm the authentication</h2>
    <form action="./controller/admin_controller.php" method="POST" id="popup-authentication-form">
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

  </script>