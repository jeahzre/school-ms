<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/index.php');
?>
<?php require_once 'top.php' ?>
<main>
  <div class="main-title">
    Student Payment
  </div>
  <div class="main-content">
    <div class="card">
      <div class="title">Add Student Payment</div>
      <form class="form" method="POST" action="/model/StudentPayment.php">
        <div class="labels-inputs-horizontal">
          <div class="label-input">
            <label for="add_user_id">User ID</label>
            <input type="number" name="add_user_id" id="add_user_id" placeholder="User ID" class="add_student_payment">
          </div>
          <div class="label-input">
            <label for="add_paid">Paid</label>
            <input type="number" name="add_paid" id="add_paid" placeholder="Paid" class="add_student_payment">
          </div>
          <div class="label-input">
            <label for="add_paid_date">Paid date</label>
            <input type="date" name="add_paid_date" id="add_paid_date" placeholder="Paid date" class="add_student_payment">
          </div>
        </div>
        <button onsubmit="addItem(event)">Submit</button>
      </form>
    </div>
    <div class="card">
      <div class="header">
        <div class="title">
          All Student Payment
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
          <th>User ID</th>
          <th>Paid</th>
          <th>Paid Date (YYYY-MM-DD)</th>
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
      <div class="title">Edit Student Payment</div>
      <div class="content">
        <table class="labels-inputs-table">
          <tr class="label-input">
            <td>
              <label for="edit_user_id">User ID</label>
            </td>
            <td>
              <input type="number" name="edit_user_id" id="edit_user_id" class="edit_student_payment">
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_paid">Paid</label>
            </td>
            <td>
              <input type="number" name="edit_paid" id="edit_paid" class="edit_student_payment">
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_paid_date">Paid Date (YYYY-MM-DD)</label>
            </td>
            <td>
              <input type="date" name="edit_paid_date" id="edit_paid_date" class="edit_student_payment">
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
    item: 'student_payment',
    itemIdName: ['user_id'],
    listModelFile: 'StudentPayment',
    itemKeysForEdit: ['paid', 'paid_date']
  }
</script>
<script src="frontend.js"></script>
<script>
  function renderRow(data) {
    // Init or after insert item
    const id = data.hasOwnProperty('id') ? data['id'] : data['insert_id'];
    let {
      user_id, paid, paid_date
    } = data;
    const tbodyElement = document.getElementById("entries-tbody");
    const trElement = document.createElement("tr");

    trElement.id = id;
    trElement.innerHTML = `
      <td class='user_id' data-value='${user_id}'>${user_id}</td>
      <td class='paid' data-value='${paid}'>${paid}</td>
      <td class='paid_date' data-value='${paid_date}'>${paid_date}</td>
      <td>
        <button data-modal-type='edit-item'  onclick='startEditItem(event)'>Edit</button>
        <button onclick='deleteItem(event)'>Delete</button>
      </td>
    `;
    tbodyElement.appendChild(trElement);
  };
</script>
