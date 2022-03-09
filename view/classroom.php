<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/session.php');
?>
<?php
// class ClassroomGet
// {
// function __construct()
// {
//   $this->data = $this->fetchData();
//   $this->multiplicationOf = 2;
//   $this->totalPage = ceil(count($this->data) / $this->multiplicationOf);
// }

// public $classListSql = "SELECT * FROM `class`";

// function fetchData()
// {
//   $result = $GLOBALS['conn']->query($this->classListSql);

//   if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//       $rows[] = $row;
//     }
//     return $rows;
//   } else {
//     return "Error: " . $this->classListSql . "<br>" . $GLOBALS['conn']->error;
//   }
// }

// function renderClassRows()
// {
//   $classList = $this->data;
//   if (is_array($classList)) {
//     foreach ($classList as $class) {
//       echo ("
//     <tr id='{$class['class_name']}'>
//       <td class='class_name'>{$class['class_name']}</td>
//       <td class='student_count'>{$class['student_count']}</td>
//       <td>
//         <button onclick='startEditItem(event)'>Edit</button>
//         <button onclick='deleteClass(event)'>Delete</button>
//       </td>
//     </tr>
//     ");
//     }
//   }
// }

// function renderNumberOfEntriesOptions()
// {
//   // $multiplicationOf 2 -> 2, 4, 6, 8, 10, ...
//   $multiplicationOf = $this->multiplicationOf;
//   $classList = $this->data;
//   $numberOfEntries = count($classList);
//   for ($i = $multiplicationOf; $i < $numberOfEntries; $i += $multiplicationOf) {
//     echo "<option value='{$i}'>{$i}</option>";
//   }
// }

// function renderNavPageNumbers()
// {
//   for ($i = 1; $i <= $this->totalPage; $i++) {
//     echo "<button data-navigate-page='{$i}' class='nav-number-page' onclick='navigatePage(event)'>
//     {$i}
//     </button>";
//   }
// }
// }
// $classroom = new ClassroomGet();
?>

<!-- Render -->
<?php
require_once 'top.php';
?>
<main>
  <div class="main-title">
    Classroom
  </div>
  <div class="main-content">
    <div class="card">
      <div class="title">
        Add Classroom
      </div>
      <form method="post" action="/model/classroom.php" class="add-class-form">
        <div class="labels-inputs-horizontal">
          <div class="label-input">
            <label for="add_class_name">Classroom name </label>
            <input type="text" name="add_class_name" id="add_class_name" placeholder="Classroom name" class="add_class">
          </div>
          <div class="label-input">
            <label for="add_student_count">Student count</label>
            <input type="number" name="add_student_count" id="add_student_count" placeholder="Student count" class="add_class">
          </div>
        </div>
        <button onsubmit="addItem(event)">Submit</button>
      </form>
    </div>
    <div class="card">
      <div class="header">
        <div class="title">
          Class List
        </div>
        <div class="action">
          <div class="filter-list-count">
            Show
            <select onchange="setAndRenderListRows(event);" id="number-of-entries-select">
              <option selected>All</option>
              <!-- renderNumberOfEntriesOptions()             -->
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
          <th>Name</th>
          <th>Student Count</th>
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
  </div>
  <!-- Class 'show' in overlay and modal to show/hide them -->
  <div class="" id="overlay">
  </div>
  <div class="modal" id="edit-item-modal">
    <button class="close-btn" onclick="handleCloseModal(event);">
      x
    </button>
    <div class="title">Edit Class</div>
    <div class="content">
      <table class="labels-inputs-table">
        <tr class="label-input">
          <td>
            <label for="edit_class_name">Classroom name</label>
          </td>
          <td>
            <input type="text" name="edit_class_name" id="edit_class_name" class="edit_class">
          </td>
        </tr>
        <tr class="label-input">
          <td>
            <label for="edit_student_count">Student count</label>
          </td>
          <td>
            <input type="text" name="edit_student_count" id="edit_student_count" class="edit_class">
          </td>
        </tr>
      </table>
      <button onclick="submitEditItem()">Submit</button>
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
  // const globalItem = 'class';
  // const globalItemIdName = 'class_name';
  // let globalListModelFile = 'classroom';
  // const globalItemKeys = ['class_name', 'student_count'];
  let _global_ = {
    item: 'class',
    itemIdName: 'class_name',
    listModelFile: 'classroom',
    itemKeysForEdit: ['class_name', 'student_count']
  }
</script>
<script src="frontend.js"></script>
<script>
  function renderRow(data) {
    const {
      class_name,
      student_count
    } = data;
    const tbodyElement = document.getElementById("entries-tbody");
    const trElement = document.createElement("tr");
    trElement.id = class_name;
    trElement.innerHTML = `
      <td class='class_name' data-value='${class_name}'>${class_name}</td>
      <td class='student_count' data-value='${student_count}' >${student_count}</td>
      <td>
        <button data-modal-type="edit-item" onclick='startEditItem(event)'>Edit</button>
        <button onclick='deleteItem(event)'>Delete</button>
      </td>
    `;
    tbodyElement.appendChild(trElement);
  };
</script>
<?php
require_once 'bottom.php';
?>