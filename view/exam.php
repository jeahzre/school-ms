<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/index.php');
?>
<?php require_once 'top.php' ?>
<main>
  <div class="main-title">
    Exam
  </div>
  <div class="main-content">
    <div class="card">
      <div class="title">Add Exam</div>
      <form class="form" method="POST" action="/model/Exam.php">
        <div class="labels-inputs-horizontal">
          <div class="label-input">
            <label for="add_name">Name</label>
            <input type="text" name="add_name" id="add_name" placeholder="Name" class="add_exam">
          </div>
          <div class="label-input">
            <label for="add_subject_id">Subject ID</label>
            <input type="number" name="add_subject_id" id="add_subject_id" placeholder="Subject ID" class="add_exam">
          </div>
          <div class="label-input">
            <label for="add_datetime">Datetime</label>
            <input type="datetime-local" name="add_datetime" id="add_datetime" placeholder="Datetime" class="add_exam">
          </div>
        </div>
        <button onsubmit="addItem(event)">Submit</button>
      </form>
    </div>
    <div class="card">
      <div class="header">
        <div class="title">
          All Exam
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
          <th>Subject ID</th>
          <th>Datetime (YYYY-MM-DD)</th>
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
      <div class="title">Edit Exam</div>
      <div class="content">
        <table class="labels-inputs-table">
          <tr class="label-input">
            <td>
              <label for="edit_id">ID</label>
            </td>
            <td>
              <input type="number" name="edit_id" id="edit_id" class="edit_exam" disabled>
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_name">Name</label>
            </td>
            <td>
              <input type="text" name="edit_name" id="edit_name" class="edit_exam">
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_subject_id">Subject ID</label>
            </td>
            <td>
              <input type="text" name="edit_subject_id" id="edit_subject_id" class="edit_exam">
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_datetime">Datetime (YYYY-MM-DD)</label>
            </td>
            <td>
              <input type="datetime-local" name="edit_datetime" id="edit_datetime" class="edit_exam">
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
    item: 'exam',
    itemIdName: ['id'],
    listModelFile: 'Exam',
    itemKeysForEdit: ['id', 'name', 'subject_id', 'datetime']
  }
</script>
<script src="frontend.js"></script>
<script>
  function renderRow(data) {
    // Init or after insert item
    const id = data.hasOwnProperty('id') ? data['id'] : data['insert_id'];
    let {
      name, subject_id, datetime
    } = data;
    const tbodyElement = document.getElementById("entries-tbody");
    const trElement = document.createElement("tr");
    const dateISOString = changeYYYYMMDDHHMMDateFormatToISOString(datetime);
    const dateTimeLocalString = changeDateStringtoDatetimeLocalString(dateISOString);

    trElement.id = id;
    trElement.innerHTML = `
      <td class='id' data-value='${id}'>${id}</td>
      <td class='name' data-value='${name}'>${name}</td>
      <td class='subject_id' data-value='${subject_id}'>${subject_id}</td>
      <td class='datetime' data-value='${dateTimeLocalString}'>${dateTimeLocalString}</td>
      <td>
        <button data-modal-type='edit-item' onclick='startEditItem(event)'>Edit</button>
        <button onclick='deleteItem(event)'>Delete</button>
      </td>
    `;
    tbodyElement.appendChild(trElement);
  };
</script>
