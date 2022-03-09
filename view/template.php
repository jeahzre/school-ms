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
      <div class="title">Title</div>
    </div>
    <div class="" id="overlay">
    </div>
    <div class="" id="modal">
      <button class="close-btn" onclick="closeModal(event);removeSessionStorageItems('before_edit_class_name')">
        x
      </button>
      <div class="title">Edit Class</div>
      <div class="content">
        <table class="labels-inputs-table">
          <tr class="label-input">
          </tr>
        </table>
        <button onclick="submitEditItem()">Submit</button>
      </div>
      
    </div>
  </div>
</main>
<?php require_once 'bottom.php' ?>