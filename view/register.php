<?php
require_once '../model/register.php';
require_once 'plain_top.php';
?>

<body>
  <main class="auth">
    <div class="main-title">
      Register
    </div>
    <div class="main-content">
      <div class="card">
        <div><?php echo $error ?? '';?></div>
        <form action="" method="POST" class="form">
          <div class="labels-inputs-vertical">
            <div class="label-input">
              <label for="email">Email</label>
              <input name="email" type="email" id="email" placeholder="Email">
            </div>
            <div class="label-input">
              <label for="username">Username</label>
              <input name="username" type="text" id="username" placeholder="Username">
            </div>
            <div class="label-input">
              <label for="passwd">Password</label>
              <input name="passwd" type="password" id="passwd" placeholder="Password">
            </div>
          </div>
          <div class="bottom">
            <button>Submit</button>
            <div>
              <a href="/view/login.php">Login</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>
  <?php
  require_once 'bottom.php';
  ?>