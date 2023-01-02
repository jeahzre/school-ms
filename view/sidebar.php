<?php
require_once('Renderer/Profile.php');
use Renderer\Profile;

$profile = new Profile();
$profilePicturePath = $profile->getProfilePicturePath();
?>
<!-- Left sidebar -->
<aside id="left-sidebar" class="left-sidebar">
  <header class="my-profile">
    <img src="/asset/profile-user.png" alt="my-profile" class="profile-img">
    <span>
      <div class="profile-info">
        <span>
          <div class="name">Hi</div>
        </span>
        <span>
          <div class="status">Online</div>
        </span>
      </div>
    </span>
  </header>
  <nav class="nav-sidebar">
    <div class="nav-title">
      <span>
        <div>MAIN NAVIGATION</div>
      </span>
    </div>
    <ul class="nav-sidebar-list" id="nav-sidebar-list">
      <!-- new SidebarListItemElement().render() -->
    </ul>
  </nav>
</aside>
<script>
  function renderSidebarListItemElement(imageSrc, listItemTitle) {
    const imageAlt = (listItemTitle).toLowerCase();
    const imageID = (listItemTitle.replace(/\s/, '-')).toLowerCase();
    const linkHref = (listItemTitle.replace(/\s/, '_')).toLowerCase();
    const sidebarListElement = document.getElementById('nav-sidebar-list');
    const itemElement = document.createElement('li');
    
    itemElement.classList.add('list-item');
    itemElement.innerHTML = `
        <a href='/view/${linkHref}.php' class='list-item-link' title='${listItemTitle}'>
          <img src='/asset/${imageSrc}.png' alt='${imageAlt}' class='sidebar-img' id='${imageID}'>
          <span>
            <div>${listItemTitle}</div>
          </span>
        </a>
      `;
    sidebarListElement.appendChild(itemElement);
  }

  const listItems = [{
      imageSrc: 'speedometer',
      listItemTitle: 'Dashboard'
    },
    {
      imageSrc: 'profile-user',
      listItemTitle: 'My Profile'
    },
    {
      imageSrc: 'classroom',
      listItemTitle: 'Classroom'
    },
    {
      imageSrc: 'bar-chart',
      listItemTitle: 'Grade'
    },
    {
      imageSrc: 'books-stack-of-three',
      listItemTitle: 'Subject'
    },
    {
      imageSrc: 'teacher',
      listItemTitle: 'Teacher'
    },
    {
      imageSrc: 'distance',
      listItemTitle: 'Subject Routing'
    },
    {
      imageSrc: 'timetable',
      listItemTitle: 'Timetable'
    },
    {
      imageSrc: 'write',
      listItemTitle: 'Student'
    },
    {
      imageSrc: 'payment-method',
      listItemTitle: 'Student Payment'
    },
    {
      imageSrc: 'check',
      listItemTitle: 'Student Attendance'
    },
    {
      imageSrc: 'exam',
      listItemTitle: 'Exam'
    },
    {
      imageSrc: 'cash-flow',
      listItemTitle: 'Cash'
    }
  ];

  // Render sidebar list
  for (const listItem of listItems) {
    const {
      imageSrc: imageSrc,
      listItemTitle: listItemTitle
    } = listItem;
    renderSidebarListItemElement(imageSrc, listItemTitle);
  }
</script>
