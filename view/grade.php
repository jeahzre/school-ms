<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/index.php');
?>
<?php require_once 'top.php' ?>
<main>
  <div class="main-title">
    Grade
  </div>
  <div class="main-content">
    <div class="card">
      <div class="title">Add Grade</div>
      <form class="form">
        <div class="labels-inputs-vertical">
          <div class="label-input">
            <label for="add_grade">Grade</label>
            <input type="text" name="add_grade" id="add_grade" placeholder="Grade" class="add_grade">
          </div>
          <div class="label-input">
            <label for="add_admission_fee">Admission Fee</label>
            <input type="text" name="add_admission_fee" id="add_admission_fee" placeholder="Admission fee" class="add_grade">
          </div>
          <div class="label-input">
            <label for="add_hall_charge">Hall Charge</label>
            <input type="text" name="add_hall_charge" id="add_hall_charge" placeholder="Hall charge" class="add_grade">
          </div>
        </div>
        <button onsubmit="addItem(event)">Submit</button>
      </form>
    </div>
    <div class="card">
      <div class="header">
        <div class="title">
          All Grade
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
          <th>Grade</th>
          <th>Admission Fee</th>
          <th>Hall Charge (%)</th>
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
      <div class="title">Edit Grade</div>
      <div class="content">
        <table class="labels-inputs-table">
          <tr class="label-input">
            <td>
              <label for="edit_grade">Grade</label>
            </td>
            <td>
              <input type="text" name="edit_grade" id="edit_grade" class="edit_grade">
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_admission_fee">Admission Fee</label>
            </td>
            <td>
              <input type="text" name="edit_admission_fee" id="edit_admission_fee" class="edit_grade">
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_hall_charge">Hall Charge</label>
            </td>
            <td>
              <input type="text" name="edit_hall_charge" id="edit_hall_charge" class="edit_grade">
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
    item: 'grade',
    itemIdName: 'grade',
    listModelFile: 'grade',
    itemKeysForEdit: ['grade', 'admission_fee', 'hall_charge']
  }
</script>
<script src="frontend.js"></script>
<script>
  function renderRow(data) {
    const {
      grade,
      admission_fee,
      hall_charge
    } = data;
    const tbodyElement = document.getElementById("entries-tbody");
    const trElement = document.createElement("tr");
    trElement.id = grade;
    trElement.innerHTML = `
      <td class='grade' data-value='${grade}'>${grade}</td>
      <td class='admission_fee' data-value='${admission_fee}'>${admission_fee}</td>
      <td class='hall_charge' data-value='${hall_charge}'>${hall_charge}</td>
      <td>
        <button data-modal-type='edit-item'  onclick='startEditItem(event)'>Edit</button>
        <button onclick='deleteItem(event)'>Delete</button>
      </td>
    `;
    tbodyElement.appendChild(trElement);
  };
</script>
<?php require_once 'bottom.php' ?>