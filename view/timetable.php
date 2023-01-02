<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/index.php');
?>
<?php require_once 'top.php' ?>
<main>
  <div class="main-title">
    Timetable
  </div>
  <div class="main-content">
    <div class="card">
      <div class="title">Add Plan</div>
      <form class="form" method="POST" action="/model/Plan.php">
        <div class="labels-inputs-horizontal">
          <div class="label-input">
            <label for="add_activity_name">Activity name</label>
            <input type="text" name="add_activity_name" id="add_activity_name" placeholder="Activity name" class="add_plan">
          </div>
          <div class="label-input">
            <label for="add_datetime">Datetime</label>
            <input type="datetime-local" name="add_datetime" id="add_datetime" placeholder="Datetime" class="add_plan">
          </div>
        </div>
        <button onsubmit="addItem(event)">Submit</button>
      </form>
    </div>
    <div class="card">
      <div class="header">
        <div class="title">
          All Plan
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
          <th>Plan</th>
          <th>Time (YYYY-MM-DD)</th>
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
      <div class="title">Edit Plan</div>
      <div class="content">
        <table class="labels-inputs-table">
          <tr class="label-input">
            <td>
              <label for="edit_id">ID</label>
            </td>
            <td>
              <input type="text" name="edit_id" id="edit_id" class="edit_plan">
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_activity_name">Activity</label>
            </td>
            <td>
              <input type="text" name="edit_activity_name" id="edit_activity_name" class="edit_plan">
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_datetime">Time</label>
            </td>
            <td>
              <input type="datetime-local" name="edit_datetime" id="edit_datetime" class="edit_plan">
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
    item: 'plan',
    itemIdName: ['id'],
    listModelFile: 'Plan',
    itemKeysForEdit: ['id', 'activity_name', 'datetime']
  }
</script>
<script src="frontend.js"></script>
<script>
  function renderRow(data) {
    // Init or after insert item
    const id = data.hasOwnProperty('id') ? data['id'] : data['insert_id'];
    let {
      activity_name, datetime
    } = data;
    activity_name = capitalize(activity_name);
    const dateISOString = changeYYYYMMDDHHMMDateFormatToISOString(datetime);
    const dateTimeLocalString = changeDateStringtoDatetimeLocalString(dateISOString);
    const tbodyElement = document.getElementById("entries-tbody");
    const trElement = document.createElement("tr");

    trElement.id = id;
    trElement.innerHTML = `
      <td class='id' data-value='${id}'>${id}</td>
      <td class='activity_name' data-value='${activity_name}'>${activity_name}</td>
      <td class='datetime' data-value='${dateTimeLocalString}'>${dateTimeLocalString}</td>
      <td>
        <button data-modal-type='edit-item'  onclick='startEditItem(event)'>Edit</button>
        <button onclick='deleteItem(event)'>Delete</button>
      </td>
    `;
    tbodyElement.appendChild(trElement);
  };
</script>
