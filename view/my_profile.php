<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/index.php');
?>
<?php
// $dbNameToFrontEndNameMap = array(
//   'id' => 'ID',
//   'email' => 'Email',
//   'passwd' => 'Password',
//   // 'user_id' => 'User ID', 
//   'name' => 'Name',
//   'initial_name' => 'Initial Name',
//   'gender' => 'Gender',
//   'phone_number' => 'Phone Number',
//   'registration_date' => 'Registration Date'
// );

// class MyProfileGet
// {
//   function __construct($user_type)
//   {
//     // teacher, student, or admin
//     $this->user_type = $user_type;
//     $this->data = $this->fetchData();
//   }

//   function getSql()
//   {
//     return ("SELECT `id`, `email`, `passwd`, `name`, `initial_name`, `gender`, `phone_number`, `registration_date` FROM `usr` 
//   INNER JOIN `{$this->user_type}`
//   ON `usr`.`id` = `teacher`.`user_id`
//   WHERE `usr`.`id` = 22");
//   }

// function fetchData()
// {
//   $sql = $this->getSql();
//   $result = $GLOBALS['conn']->query($sql);

//   if ($result->num_rows > 0) {
//     return ($result->fetch_assoc());
//   } else {
//     return "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
//   }
// }

// function renderUserName()
// {
//   echo ($this->data)['name'];
// }

// function renderTableRows()
// {
//   global $dbNameToFrontEndNameMap;

//   $data = $this->data;
//   foreach ($data as $key => $value) {
//     $FEKey = $dbNameToFrontEndNameMap[$key];
//     echo ("
//     <tr>
//       <td id='{$key}-key'>
//         {$FEKey}
//       </td>
//       <td id='{$key}-value'>
//         {$value}
//       </td>
//     </tr>
//     ");
//   }
// }

// function renderInputTableRows()
// {
//   global $dbNameToFrontEndNameMap;

//   $result = $this->fetchData();
//   foreach ($result as $key => $value) {
//     $FEKey = $dbNameToFrontEndNameMap[$key];
//     if ($key === 'id') {
//       echo ("
//       <tr>
//         <td>
//           <label for='{$key}-input'>
//             {$FEKey}
//           </label>
//         </td>
//         <td>
//           <input class='edit_my_profile' id='{$key}-input' name='edit_my_profile_{$key}' value='{$value}' placeholder='{$FEKey}' disabled>
//         </td>
//       </tr>
//       ");
//     } else {
//       echo ("
//       <tr>
//         <td>
//           <label for='{$key}-input'>
//             {$FEKey}
//           </label>
//         </td>
//         <td>
//           <input class='edit_my_profile' id='{$key}-input' name='edit_my_profile_{$key}' value='{$value}' placeholder='{$FEKey}'>
//         </td>
//       </tr>
//       ");
//     }
//   }
// }
// }

// $myProfileGet = new MyProfileGet('teacher');
?>

<!-- Render -->
<?php
require_once 'top.php';

session_start();

require_once('../model/Session.php');

use Model\Session;

$session = new Session();
$username = $session->getUsername();
?>
<main>
  <div class="username">Hi, <?= $username ?? '' ?></div><br>
  <div class="main-title">
    My Profile
  </div>
  <div class="my-profile-container">
    <div class="name" id="username">
      <!-- renderUserName() -->
    </div>
    <div class="img-container">
      <img src="/asset/write.png" alt="my profile image">
    </div>
    <button data-modal-type="edit-item" class="edit-my-profile-btn" onclick="startEditMyProfile(event)">Edit</button>
    <table class="detail-table" id="detail-table">
      <!-- renderTableRows(); -->
    </table>
  </div>
  <?php
  require_once('Renderer/Profile.php');

  use Renderer\Profile;

  require_once $_SERVER['DOCUMENT_ROOT'] . '/init/index.php';
  require_once 'top.php';

  $profile = new Profile();
  $profilePicturePath = $profile->getProfilePicturePath();
  $userPosition = $profile->getUserPosition();
  ?>
  <style>
    <?php require_once 'my_profile.scss'; ?>
  </style>
  <div class="main-content">
    <div class="card">
      <div class="title">
        Edit Profile
      </div>
      <form method="post" action="/model/Profile.php" class="edit-profile-form" enctype="multipart/form-data">
        <div class="labels-inputs-horizontal">
          <div class="label-input">
            <label for="set_profile_picture">Profile picture</label>
            <div class="image">
              <img src="<?= $profilePicturePath; ?>" alt="profile-picture" width="200">
            </div>
            <input type="file" name="set_profile_picture" id="set_profile_picture" placeholder="Profile picture" class="profile">
          </div>
          <div class="label-input user-position">
            <div>User Position</div>
            <div><?= $userPosition; ?></div>
          </div>
        </div>
        <button onsubmit="addItem(event)">Submit</button>
      </form>
    </div>
  </div>
</main>
<div id="overlay"></div>
<div id="edit-item-modal" class="modal">
  <button class="close-btn" id="close-edit-profile-modal-btn" onclick="closeModal(event);sessionStorage.removeItem('before_edit_id');">
    x
  </button>
  <div class="title">
    Edit Profile
  </div>
  <div class="content">
    <table class="labels-inputs-table" id="detail-edit-table">
      <tr>
        <td>
          <label for='id-input'>
            ID
          </label>
        </td>
        <td>
          <input class='edit_my_profile' id='id-input' name='edit_id' placeholder='ID'>
        </td>
      </tr>
      <tr>
        <td>
          <label for='email-input'>
            Email
          </label>
        </td>
        <td>
          <input class='edit_my_profile' id='email-input' name='edit_email' placeholder='Email'>
        </td>
      </tr>
      <tr>
        <td>
          <label for='password-input'>
            Password
          </label>
        </td>
        <td>
          <input class='edit_my_profile' id='password-input' name='edit_passwd' placeholder='Password'>
        </td>
      </tr>
      <tr>
        <td>
          <label for='name-input'>
            Name
          </label>
        </td>
        <td>
          <input class='edit_my_profile' id='name-input' name='edit_name' placeholder='Name'>
        </td>
      </tr>
      <tr>
        <td>
          <label for='initial-name-input'>
            Initial Name
          </label>
        </td>
        <td>
          <input class='edit_my_profile' id='initial-name-input' name='edit_initial_name' placeholder='Initial Name'>
        </td>
      </tr>
      <tr>
        <td>
          <label for='gender-input'>
            Gender
          </label>
        </td>
        <td>
          <input class='edit_my_profile' id='gender-input' name='edit_gender' placeholder='Gender'>
        </td>
      </tr>
      <tr>
        <td>
          <label for='phone-number-input'>
            Phone Number
          </label>
        </td>
        <td>
          <input class='edit_my_profile' id='phone-number-input' name='edit_phone_number' placeholder='Phone Number'>
        </td>
      </tr>
    </table>
    <button onclick="submitEditProfile(event)">Submit</button>
  </div>
</div>
<script>
  let _global_ = {
    itemIdName: 'id',
    item: 'my_profile',
    listModelFile: 'my_profile',
    // Table only has 2 column
    tableType: 'key-value',
    itemKeysForEdit: ['id', 'email', 'passwd', 'name', 'initial_name', 'gender', 'phone_number']
  };
</script>
<?php
$jsFileDependencies = array('ajax', 'modal', 'form', 'frontend', 'function');
foreach ($jsFileDependencies as $jsFileDependency) {
  echo "<script src='{$jsFileDependency}.js'></script>";
}
?>
<script>
  const dbNameToFrontEndNameMap = {
    id: 'ID',
    email: 'Email',
    passwd: 'Password',
    name: 'Name',
    initial_name: 'Initial Name',
    gender: 'Gender',
    phone_number: 'Phone Number',
    registration_date: 'Registration Date'
  };

  function getMyProfileData(handleXMLHttpResponse) {
    const formDataObject = {
      get_my_profile: 'get_my_profile',
      user_type: 'teacher',
      user_id: 1
    }
    const formData = getFormData(null, null, formDataObject);
    return requestXMLHttp(formData, 'my_profile', handleXMLHttpResponse, ['parsedData']);
  }

  function renderTableRows() {
    const detailTableElement = document.getElementById('detail-table');
    detailTableElement.innerHTML = '';
    Object.entries(_global_['data']).map(([key, value]) => {
      const FEKey = dbNameToFrontEndNameMap[key];
      const trElement = document.createElement('tr');
      if (key === _global_['itemIdName']) {
        trElement.id = 'table-id';
        trElement.dataset[key] = value;
      }
      trElement.innerHTML = `
      <td id='${key}_key'>${FEKey}</td>
      <td id='${key}_value' data-value='${value}'>${value}</td>
      `;
      detailTableElement.appendChild(trElement);
    });
  }

  function renderInputTableRows() {
    const detailEditTable = document.getElementById('detail-edit-table');
    detailEditTable.innerHTML = '';
    Object.entries(_global_['data']).map(([key, value]) => {
      const trElement = document.createElement('tr');
      const FEKey = dbNameToFrontEndNameMap[key];
      if (key === 'id') {
        trElement.innerHTML = `
        <td>
          <label for='${key}-input'>
            ${FEKey}
          </label>
        </td>
        <td>
          <input class='edit_my_profile' id='${key}-input' name='edit_${key}' value='${value}' placeholder='${FEKey}' disabled>
        </td>
        `;
      } else {
        trElement.innerHTML = `
        <td>
          <label for='${key}-input'>
            ${FEKey}
          </label>
        </td>
        <td>
          <input class='edit_my_profile' id='${key}-input' name='edit_${key}' value='${value}' placeholder='${FEKey}'>
        </td>
        `;
      }
      detailEditTable.appendChild(trElement);
    });
  }

  function renderUserName() {
    const usernameElement = document.getElementById('username');
    const username = _global_['data']['name'];
    usernameElement.innerHTML = `${username}`;
  }

  function startEditMyProfile(e) {
    openModal(e);
    renderInputTableRows();
    const idRow = document.getElementById('table-id');
    const idValue = idRow.dataset[_global_['itemIdName']];
    sessionStorage.setItem('before_edit_id', Number(idValue));
  }

  function submitEditProfile(e) {
    e.preventDefault();
    const formData = new FormData();
    const elements = document.getElementsByClassName("edit_my_profile");
    for (let i = 0; i < elements.length; i++) {
      formData.append(elements[i].name, elements[i].value);
      // sessionStorage.setItem(elements[i].name, elements[i].value);
    }
    const beforeEditUserIDValue = sessionStorage.getItem('before_edit_user_id');
    formData.append('before_edit_user_id', beforeEditUserIDValue)
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let parsedData = null;
        if (isJSON(this
            .responseText)) {
          parsedData = JSON.parse(this.responseText);
        }
        console.log(parsedData);
        let beforeEditIdNameValue; // [] or string

        if (Array.isArray(_global_["itemIdName"])) {
          const beforeEditIdNameValueObject = {};
          _global_["itemIdName"].map((itemId) => {
            beforeEditIdNameValueObject[itemId] = sessionStorage.getItem(
              `before_edit_${itemId}`
            );
          });
          beforeEditIdNameValue = beforeEditIdNameValueObject;
        } else {
          beforeEditIdNameValue = sessionStorage.getItem(
            `before_edit_${_global_["itemIdName"]}`
          );
        }
        if (parsedData) {
          // If server return true, change table value with input value from modal
          const editedMyProfileObject = {};
          for (let i = 0; i < elements.length; i++) {
            editedMyProfileObject[elements[i].name] = elements[i].value;
            const deformattedMyProfileDataName = deformatString('edit_', elements[i].name);
            _global_['data'][deformattedMyProfileDataName] = elements[i].value;
          }
          renderEditedItemRow(beforeEditIdNameValue, editedMyProfileObject);
          removeSessionStorageItems('before_edit_user_id');
        }
      }
    };
    xmlhttp.open("POST", "/model/my_profile.php");
    xmlhttp.send(formData);
  }

  window.addEventListener('DOMContentLoaded', () => {
    // Request data then render it
    function handleXMLHttpResponse(parsedData) {
      _global_['data'] = parsedData[0];
      renderUserName();
      renderTableRows();
    }
    getMyProfileData(handleXMLHttpResponse);
  });

  document.getElementById('close-edit-profile-modal-btn').addEventListener('click', (e) => {
    closeModal(e);
    removeSessionStorageItems();
    sessionStorage.removeItem('before_edit_id');
  });
</script>
<?php
require_once 'bottom.php'
?>