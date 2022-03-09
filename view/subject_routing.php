<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/index.php');
?>
<?php require_once 'top.php' ?>
<main>
  <div class="main-title">
    Subject Routing
  </div>
  <div class="main-content">
    <!-- <div class="card">
      <div class="title">Subject Routing</div>
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
    </div> -->
    <div class="card">
      <div class="header">
        <div class="first-row">
          <div class="title">
            All Teacher
          </div>
          <button class="add-item-button" data-modal-type='add-item' onclick="startAddItem(event)">
            Add
          </button>
        </div>
        <div class="second-row">
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
      </div>
      <table>
        <thead>
          <th>Grade</th>
          <th>Subject</th>
          <th>Teacher</th>
          <th>Fee ($)</th>
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
    <div class="modal" id="add-item-modal">
      <button class="close-btn" onclick="handleCloseModal(event)">
        x
      </button>
      <div class="title">Add Subject Routing</div>
      <div class="content">
        <table class="labels-inputs-table">
          <tr class="label-input">
            <td>
              <label for="add_grade">Grade</label>
            </td>
            <td>
              <select name="add_grade" id="add_grade" class="add_subject_routing" onchange="handleSelectChange(event)">
              </select>
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="add_subject_id">Subject</label>
            </td>
            <td>
              <select name="add_subject_id" id="add_subject_id" class="add_subject_routing" onchange="handleSelectChange(event)">
              </select>
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="add_teacher_id">Teacher</label>
            </td>
            <td>
              <select name="add_teacher_id" id="add_teacher_id" class="add_subject_routing" onchange="handleSelectChange(event)">
              </select>
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="add_fee">Fee</label>
            </td>
            <td>
              <input type="number" name="add_fee" id="add_fee" class="add_subject_routing">
            </td>
          </tr>
          <tr class="label-input hidden">
            <td>
              <input type="hidden" name="add_subject_name" id="add_subject_name" class="add_subject_routing">
            </td>
          </tr>
          <tr class="label-input hidden">
            <td>
              <input type="hidden" name="add_teacher_name" id="add_teacher_name" class="add_subject_routing">
            </td>
          </tr>
        </table>
        <button onclick="addItem()">Submit</button>
      </div>
    </div>
    <div class="modal" id="edit-item-modal">
      <button class="close-btn" onclick="handleCloseModal(event)">
        x
      </button>
      <div class="title">Edit Subject Routing</div>
      <div class="content">
        <table class="labels-inputs-table">
          <tr class="label-input">
            <td>
              <label for="edit_grade">Grade</label>
            </td>
            <td>
              <select name="edit_grade" id="edit_grade" class="edit_subject_routing" onchange="handleSelectChange(event)">
                <!-- renderOptions() -->
              </select>
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_subject_id">Subject</label>
            </td>
            <td>
              <select name="edit_subject_id" id="edit_subject_id" class="edit_subject_routing" onchange="handleSelectChange(event)">
              </select>
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_teacher_id">Teacher</label>
            </td>
            <td>
              <select name="edit_teacher_id" id="edit_teacher_id" class="edit_subject_routing" onchange="handleSelectChange(event)">
              </select>
            </td>
          </tr>
          <tr class="label-input">
            <td>
              <label for="edit_fee">Fee</label>
            </td>
            <td>
              <input type="number" name="edit_fee" id="edit_fee" class="edit_subject_routing">
            </td>
          </tr>
          <tr class="hidden">
            <td>
              <input type="hidden" name="edit_subject_name" id="edit_subject_name" class="edit_subject_routing">
            </td>
          </tr>
          <tr class="hidden">
            <td>
              <input type="hidden" name="edit_teacher_name" id="edit_teacher_name" class="edit_subject_routing">
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
    item: 'subject_routing',
    itemIdName: ['grade', 'subject_id'],
    listModelFile: 'subject_routing',
    itemKeysForEdit: [
      'grade',
      'subject_id',
      'subject_name',
      'teacher_id',
      'teacher_name',
      'fee'
    ],
    capitalizeValueKeys: [],
    optionKeys: ['grade', 'subject_id', 'teacher_id'],
    hiddenInputs: ['subject_id', 'teacher_id'],
    hiddenIdNameKeyValue: {
      subject_id: "subject_name",
      teacher_id: "teacher_name",
    }
  }
</script>
<script src="frontend.js"></script>
<script>
  function renderRow(data) {
    function checkHasProperties() {
      const hasAllIDProperties = true
      _global_['itemIdName'].map(itemId => {
        if (!data.hasOwnProperty(itemId)) {
          hasAllIDProperties = false;
        }
      });
      return hasAllIDProperties;
    }

    let id = '';
    const hasAllIDProperties = checkHasProperties();
    // Init or after insert item    
    if (hasAllIDProperties) {
      const idArr = [];
      _global_['itemIdName'].map(itemId => {
        idArr.push(data[itemId]);
      });
      id = idArr.join('_');
    } else {
      id = data['insert_id'];
    }

    const {
      grade,
      subject_id,
      subject_name,
      teacher_id,
      teacher_name,
      fee
    } = data;

    const tbodyElement = document.getElementById("entries-tbody");
    const trElement = document.createElement("tr");
    trElement.id = id;
    trElement.innerHTML = `
      <td class='grade' data-value='${grade}'>${grade}</td>
      <td class='subject_id hidden' data-value='${subject_id}'>${subject_id}</td>
      <td class='subject_name' data-value='${subject_name}'>${subject_name}</td>
      <td class='teacher_id hidden' data-value='${teacher_id}'>${teacher_id}</td>
      <td class='teacher_name' data-value='${teacher_name}'>${teacher_name}</td>
      <td class='fee' data-value='${fee}'>${fee}</td>
      <td>
        <button data-modal-type='edit-item' onclick='startEditItem(event)'>Edit</button>
        <button onclick='deleteItem(event)'>Delete</button>
      </td>
    `;
    tbodyElement.appendChild(trElement);
  };

  function getOptionElement(option, optionKey) {
    const destructuringElement = {
      grade: ["grade"],
      teacher_id: ["teacher_id", "teacher_name"],
      subject_id: ["subject_id", "subject_name"],
    };
    const [value, innerHTML] = destructuringElement[optionKey];
    const {
      [value]: elementValue, [innerHTML]: elementInnerHTML
    } = option;
    let anotherElementInnerHTML = "";
    if (!elementInnerHTML) {
      anotherElementInnerHTML = elementValue;
    }
    const optionElement = document.createElement("option");
    optionElement.value = elementValue;
    optionElement.innerHTML = elementInnerHTML || anotherElementInnerHTML;
    return optionElement;
  }
</script>