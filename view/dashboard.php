<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/init/index.php';
class DashboardQuery
{
  public $totalStudent = "SELECT COUNT(`user_id`) AS total_student FROM `student`";
  public $totalTeacher = "SELECT COUNT(`user_id`) AS total_teacher FROM `teacher`";
  public $totalIncome = "SELECT SUM(`paid`) AS total_income FROM student_payment;";
}

class Dashboard
{
  function __construct()
  {
    $this->dashboardQuery = new DashboardQuery();
  }

  function renderTotalStudent()
  {
    $sql = $this->dashboardQuery->totalStudent;
    $result = $GLOBALS['conn']->query($sql);

    if ($result->num_rows > 0) {
      return ($result->fetch_assoc())['total_student'];
    } else {
      return "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
    }
  }

  function renderTotalTeacher()
  {
    $sql = $this->dashboardQuery->totalTeacher;
    $result = $GLOBALS['conn']->query($sql);

    if ($result->num_rows > 0) {
      return ($result->fetch_assoc())['total_teacher'];
    } else {
      return "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
    }
  }

  function renderTotalIncome()
  {
    $sql = $this->dashboardQuery->totalIncome;
    $result = $GLOBALS['conn']->query($sql);

    if ($result->num_rows > 0) {
      return ($result->fetch_assoc())['total_income'];
    } else {
      return "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
    }
  }
}

function renderDashboardBox($imageSrc, $title, $function)
{
  global $dashboard;
  $functionResult = $dashboard->$function();
  $imageAlt = strtolower($title);
  echo ("
  <div class='dashboard-box'>
    <img src='/asset/{$imageSrc}.png' alt='{$imageAlt}'>
    <div class='description'>
      <div class='title'>{$title}</div>
      <div class='detail'>
      {$functionResult}
      </div>
    </div>
  </div>
  ");
}

function renderDashboardBoxes($titlesAndFunctionsToRender)
{
  foreach ($titlesAndFunctionsToRender as $titleAndFunctionToRender) {
    ['imageSrc' => $imageSrc, 'title' => $title, 'function' => $function] = get_object_vars($titleAndFunctionToRender);
    renderDashboardBox($imageSrc, $title, $function);
  }
}

$dashboard = new Dashboard();
$titlesAndFunctionsToRender = array(
  (object)array('imageSrc' => 'write', 'title' => 'Total Student', 'function' => 'renderTotalStudent'),
  (object)array('imageSrc' => 'teacher', 'title' => 'Total Teacher', 'function' => 'renderTotalTeacher'),
  (object)array('imageSrc' => 'payment-method', 'title' => 'Total Income', 'function' => 'renderTotalIncome'),
);
?>

<!-- Render -->
<?php
require_once 'top.php';
?>
<main>
  Hi, <?= $username ?? '' ?> <br>
  <div class="main-title">
    Dashboard
  </div>

  <div class="dashboard-boxes-container">
    <?php
    renderDashboardBoxes($titlesAndFunctionsToRender);
    ?>
  </div>
</main>
<?php
require_once 'bottom.php';
?>