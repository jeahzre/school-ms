<header class="main-header">
  <button class="menu-button" onclick="toggleSidebar()">
    =
  </button>
  <nav class="nav-action">
      <ul class="action-list">
        <li class="action-item">notif</li>
        <li class="action-item">friend</li>
        <li class="action-item">message</li>
        <li class="action-item">profile</li>
        <li class="action-item">
          <button onclick="logout()">logout</button>
        </li>
      </ul>
  </nav>
</header>
<script>
  function toggleSidebar() {
    document.getElementById('left-sidebar').classList.toggle('show');
  }

  function logout() {
    window.location = '/model/logout.php';
  }
</script>