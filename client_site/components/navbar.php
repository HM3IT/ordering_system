<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>
<header id="header">
  <nav class="nav-bar nav-bar-left">
    <ul>
      <li class="">
        <span id="store-name">SMART Café & Bakery Bar</span>
        <p id="slogan">Awesome Cafe and Beverages</p>
      </li>
    </ul>
  </nav>
  <?php
  require './components/search-bar.php';
  ?>
  <nav class="nav-bar nav-bar-right">
    <ul>
      <a href="view-cart-list.php" class="view-cart-icon">
        <li class="nav-bar-btn view-cart-btn">
          <i class="fa-solid fa-cart-arrow-down"></i>
        </li>
      </a>
      <a onclick="openAuthenticationCheckForm()">
        <li class="nav-bar-btn login-btn">
          <i class="fa-solid fa-user"></i>
          Profile
        </li>
      </a>
      <a href="./controller/login_controller.php?logout">
        <li class="nav-bar-btn login-btn">
          <i class="fa-solid fa-right-from-bracket"></i>
          logout
        </li>
      </a>
    </ul>
  </nav>

</header>
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
</script>