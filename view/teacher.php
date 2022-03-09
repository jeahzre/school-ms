<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/index.php');
?>
<?php require_once 'top.php' ?>
<main>
  <div class="main-title">
    Teacher
  </div>
  <div class="main-content">
    <div class="card">
      <div class="title">Add Teacher</div>
      <form class="form">
        <div class="labels-inputs-vertical">
          <div class="label-input">
            <label for="add_name">Name</label>
            <input type="text" name="add_name" id="add_name" placeholder="Name" class="add_teacher" autocomplete="username">
          </div>
          <div class="label-input">
            <label for="add_name">Initial Name</label>
            <input type="text" name="add_initial_name" id="add_initial_name" placeholder="Initial Name" class="add_teacher">
          </div>
          <div class="label-input">
            <div>Gender</div>
            <div class="labels-inputs-horizontal inner-labels-inputs">
              <div class="label-input">
                <input type="radio" name="add_gender" value="male" id="add_male" class="add_teacher">
                <label for="add_male">Male</label>
              </div>
              <div class="label-input">
                <input type="radio" name="add_gender" value="female" id="add_female" class="add_teacher">
                <label for="add_female">Female</label>
              </div>
            </div>
          </div>
          <div class="label-input">
            <label for="add_phone_number">Phone Number</label>
            <input type="text" name="add_phone_number" id="add_phone_number" placeholder="Phone Number" class="add_teacher">
          </div>
          <div class="label-input">
            <label for="add_email">Email</label>
            <input type="text" name="add_email" id="add_email" placeholder="Email" class="add_teacher">
          </div>
          <div class="label-input">
            <label for="add_passwd">Password</label>
            <input type="text" name="add_passwd" id="add_passwd" placeholder="Password" class="add_teacher">
          </div>
        </div>
        <button onclick="addItem(event)">Submit</button>
      </form>
    </div>
    <div class="card">
      <div class="header">
        <div class="title">
          All Teacher
        </div>
        <div class="action">
          <div class="filter-list-count">
            Show
            <select onchange="setAndRenderListRows(event);" id="number-of-entries-select">
              <option selected>All</option>
              <!-- renderNumberOfEntriesOptions(); -->
            </select>
            entries
          </div>
          <div class="filter-list-by-id">
            <label for="search_item_by_id">
              Search:
            </label>
            <input type="text" name="search_item_by_id" id="search_item_by_id" oninput="searchRowsByIdName(event)">
          </div>
        </div>
      </div>
      <table>
        <thead>
          <th>ID</th>
          <th>Name</th>
          <th>Initial Name</th>
          <th>Gender</th>
          <th>Phone Number</th>
          <th>Registration Date</th>
          <th>Email</th>
          <th>Password</th>
          <th>Action</th>
        </thead>
        <tbody id="entries-tbody">
          <!-- renderListRows() -->
        </tbody>
      </table>
      <div class="bottom">
        <div class="description">
          Showing
          <!-- Class 'show' to show/hide element -->
          <div id="all-number-entries" class="">
            All
          </div>
          <div id="entries-count-detail">
            <div id="from-index"></div> to
            <div id="to-index"></div> of
            <div id="global-number-of-entries"></div>
          </div>
          entries
        </div>
        <div class="nav-action">
          <button data-navigate-page="previous-page" onclick="navigatePage(event)">Prev</button>
          <div id="nav-page-numbers">
            <!-- renderNavPageNumbers() -->
          </div>
          <button data-navigate-page="next-page" onclick="navigatePage(event)">Next</button>
        </div>
      </div>
    </div>
    <div class="" id="overlay">
    </div>
    <div class="modal" id="edit-item-modal">
      <button class="close-btn" onclick="handleCloseModal(event)">
        x
      </button>
      <div class="title">Edit Teacher</div>
      <div class="content">
        <table class="labels-inputs-table">
          <tr class="label-input">
            <td>
              <label for="edit_id">ID</label>
            </td>
            <td>
              <input type="text" name="edit_id" id="edit_id" class="edit_teacher">
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_name">Name</label>
            </td>
            <td>
              <input type="text" name="edit_name" id="edit_name" class="edit_teacher">
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_initial_name">Initial Name</label>
            </td>
            <td>
              <input type="text" name="edit_initial_name" id="edit_initial_name" class="edit_teacher">
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="gender">Gender</label>
            </td>
            <td>
              <div class="labels-inputs-horizontal">
                <div class="label-input">
                  <input type="radio" name="edit_gender" value="male" id="edit_male" class="edit_teacher">
                  <label for="edit_male">Male</label>
                  <input type="radio" name="edit_gender" value="female" id="edit_female" class="edit_teacher">
                  <label for="edit_female">Female</label>
                </div>
              </div>
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_phone_number">Phone Number</label>
            </td>
            <td>
              <input type="number" name="edit_phone_number" id="edit_phone_number" class="edit_teacher">
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_email">Email</label>
            </td>
            <td>
              <input type="email" name="edit_email" id="edit_email" placeholder="Email" class="edit_teacher">
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_passwd">Password</label>
            </td>
            <td>
              <input type="password" name="edit_passwd" id="edit_passwd" placeholder="Password" class="edit_teacher">
            </td>
          </tr>
        </table>
        <button onclick="submitEditItem()">Submit</button>
      </div>
    </div>
  </div>
</main>
<?php
$jsFileDependencies = array('ajax', 'modal', 'form');
foreach ($jsFileDependencies as $jsFileDependency) {
  echo "<script src='{$jsFileDependency}.js'></script>";
}
?>
<script>
  let _global_ = {
    item: 'teacher',
    itemIdName: 'id',
    listModelFile: 'teacher',
    itemKeysForEdit: ['id', 'name', 'initial_name', 'gender', 'phone_number', 'email', 'passwd'],
    capitalizeValueKeys: ['gender']
  }
</script>
<script src="frontend.js"></script>
<script>
  function renderRow(data) {
    const formattedData = formatData(data);

    // Init or after insert item
    const id = data.hasOwnProperty(_global_['itemIdName']) ? data[_global_['itemIdName']] : data['insert_id'];
    const {
      name,
      initial_name,
      gender,
      phone_number,
      registration_date,
      email,
      passwd
    } = data;
    const {
      gender: formatted_gender,
    } = formattedData;

    const tbodyElement = document.getElementById("entries-tbody");
    const trElement = document.createElement("tr");
    trElement.id = id;
    trElement.innerHTML = `
      <td class='id' data-value='${id}'>${id}</td>
      <td class='name' data-value='${name}'>${name}</td>
      <td class='initial_name' data-value='${initial_name}'>${initial_name}</td>
      <td class='gender' data-value='${gender}'>${formatted_gender}</td>
      <td class='phone_number' data-value='${phone_number}'>${phone_number}</td>
      <td class='registration_date' data-value='${registration_date}'>${registration_date}</td>
      <td class='email' data-value='${email}'>${email}</td>
      <td class='passwd' data-value='${passwd}'>${passwd}</td>
      <td>
        <button data-modal-type='edit-item'  onclick='startEditItem(event)'>Edit</button>
        <button onclick='deleteItem(event)'>Delete</button>
      </td>
    `;
    tbodyElement.appendChild(trElement);
  };
</script>