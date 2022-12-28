<?php
session_start();
require_once 'plain_top.php';
?>
<body>
  <main class="auth">
    <div class="main-title">
      Login
    </div>
    <div class="main-content">
      <div class="card">
        <div class="notification">
          <?php 
          if (isset($_SESSION['message'])) {
            echo($_SESSION['message']);
            unset($_SESSION['message']);
          }
          ?>
        </div>
        <form action="/model/login.php" method="POST" class="form">
          <div class="labels-inputs-vertical">
            <div class="label-input">
              <label for="email">Email</label>
              <input name="email" type="email" id="email" placeholder="Email">
            </div>
            <div class="label-input">
              <label for="passwd">Password</label>
              <input name="passwd" type="password" id="passwd" placeholder="Password">
            </div>
          </div>
          <div class="bottom">
            <button>Submit</button>
            <div>
              <a href="/view/register.php">Register</a>
              <a href="/view/forgot_password.php">Forgot Password</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>
  <?php
  require_once 'bottom.php';
  ?>