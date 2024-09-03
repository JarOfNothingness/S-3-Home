<?php
session_start(); // Start the session at the very top

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include('headerforreports.php');
include("../LoginRegisterAuthentication/connection.php");

// Get the student ID from the query string
$student_id = isset($_GET['id']) ? intval($_GET['id']) : '';

// SQL query to fetch student details and grades
$query = "SELECT s.*, sg.*
          FROM students s 
          JOIN student_grades sg ON s.id = sg.student_id
          WHERE s.id = '$student_id'";

$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

// Fetch the student details
$student = mysqli_fetch_assoc($result);

// Initialize variables for grades
$first_quarter = isset($student['first_quarter']) ? $student['first_quarter'] : 0;
$second_quarter = isset($student['second_quarter']) ? $student['second_quarter'] : 0;
$third_quarter = isset($student['third_quarter']) ? $student['third_quarter'] : 0;
$fourth_quarter = isset($student['fourth_quarter']) ? $student['fourth_quarter'] : 0;

// Calculate total and average
$total = $first_quarter + $second_quarter + $third_quarter + $fourth_quarter;
$average = $total > 0 ? $total / 4 : 0;

// Fetch final grades and metrics
$grades_query = "SELECT sg.*, s.learners_name FROM student_grades sg
                 JOIN students s ON sg.student_id = s.id
                 WHERE sg.student_id = '$student_id'";
$grades_result = mysqli_query($connection, $grades_query);

if (!$grades_result) {
    die("Query failed: " . mysqli_error($connection));
}

$grades = [];
while ($row = mysqli_fetch_assoc($grades_result)) {
    $grades[] = $row;
}

function calculateMetrics($grades) {
    $metrics = [
        'total_score' => 0,
        'no_of_cases' => 0,
        'highest_possible_score' => 0,
        'highest_score' => 0,
        'lowest_score' => null,
        'average_mean' => 0,
        'mps' => 0,
        'students_75_pl' => 0,
        'percentage_75_pl' => 0
    ];
    $total_scores = [];

    foreach ($grades as $grade) {
        $written_exam = isset($grade['written_exam']) ? $grade['written_exam'] : 0;
        $performance_task = isset($grade['performance_task']) ? $grade['performance_task'] : 0;
        $quarterly_exam = isset($grade['quarterly_exam']) ? $grade['quarterly_exam'] : 0;
        $final_grade = isset($grade['final_grade']) ? $grade['final_grade'] : 0;
        $highest_possible_score = isset($grade['highest_possible_score']) ? $grade['highest_possible_score'] : 0;

        $total_score = ($written_exam * 0.40) + ($performance_task * 0.40) + ($quarterly_exam * 0.20);
        $metrics['total_score'] += $total_score;
        $metrics['no_of_cases']++;
        $metrics['highest_possible_score'] = max($metrics['highest_possible_score'], $highest_possible_score);
        $metrics['highest_score'] = max($metrics['highest_score'], $final_grade);

        if ($metrics['lowest_score'] === null || $total_score < $metrics['lowest_score']) {
            $metrics['lowest_score'] = $total_score;
        }

        $total_scores[] = $total_score;
    }

    if ($metrics['no_of_cases'] > 0) {
        $metrics['average_mean'] = $metrics['total_score'] / $metrics['no_of_cases'];
        $metrics['mps'] = $metrics['average_mean'];
        $metrics['students_75_pl'] = count(array_filter($total_scores, fn($score) => $score >= 75));
        $metrics['percentage_75_pl'] = ($metrics['students_75_pl'] / $metrics['no_of_cases']) * 100;
    }

    return $metrics;
}

$metrics = calculateMetrics($grades);
?>
 <a href="r.php">Back</a>
<!-- Custom styles for this page -->
<style>

    body {
        font-family: Arial, sans-serif;
    }
    .container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .table {
        margin-bottom: 20px;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .print-btn {
        margin-right: 10px;
    }
    table{
        width: 100%;
        border: 1px solid black;
        text-align: center;
    }
    /* my css */
    td,th{
        border: 1px solid black;
    }
    .blue{
        background-color: #1dbfeb;
    }
    .gray{
        background-color: gray;
    }
    .form-header-container{
        display: flex;
    }
    .container p,.container h2{
        margin: unset;
        background-color: #38cb55;
    }
    .container h5{
        margin: unset;
        padding: 5px;
        font-weight: bold;
    }
    .head-container{
        display: flex;
        justify-content: center;
        background-color: beige;
    }
    .head-container p{
        border: 1px solid black;
        padding: 5px 10px;
    }
     /* table */
     table thead tr:first-child th:nth-child(2),
     table thead tr:first-child th:first-child,
     table tbody tr:nth-child(2) td{
       border: unset;
    }
    table tbody tr:first-child td,table tbody tr:nth-child(2) td{
        background-color: unset;
    }
    table tbody tr td{
        background-color: #38cb55;
    }
    .average{
        background-color: #38cb55;
        color: white;
    }
    table tbody tr td:nth-child(2),
    table tbody tr td:first-child{
        background-color: unset;
        text-align: left;
    }

    table tbody tr:nth-child(6) td,
    table tbody tr:nth-child(7) td{
        background-color: unset;
    }

    table tbody tr:nth-child(9) td{
        color:red;
    }
    .head-container p:nth-child(2){
        flex: 2;
    }
    .head-container p:nth-child(2){
        color: white;
        flex: 1;
    }
    .head-container p:nth-child(3){
        flex: 1;
    }
    .head-container p:last-child{
        flex: 2;
        color:white;
    }
    
    h6{
        border-bottom: 1px solid black;
        width: max-content;
        font-weight: bold;
        margin-bottom: unset;
        margin-left: 50px;
    }
    span{
        font-size: 14px;
        text-align: center;

    }
    .teacher{
        background-color: unset !important;
        margin-left: 70px !important;
    }
    /* */
    @media print {
        .print-btn,
        .btn-secondary {
            display: none;
        }
        .container {
            border: none;
            box-shadow: none;
        }
    }
</style>

<div class="container">
 
        <h2 class="text-center">LANAO NATIONAL HIGH SCHOOL</h2>
        <p class="text-center">Lanao,Pilar,Cebu</p>
        <p class="text-center">School Year: 2023-2024</p>
        <div class="head-container">
            <p class="text-center">Grade & Section: <?php echo htmlspecialchars($student['grade'] . ' - ' . $student['section']); ?></p>
            <!-- <p class="text-center">Subject: <?php echo htmlspecialchars($student['subject']); ?></p> -->
            <p class="text-center">Grade 9-Saphire</p>
            <p class="text-center">Subject:</p>
            <p class="text-center">EsP</p>
        </div>
        <h5 class="text-center">Form 14</h5>
   

<!-- 
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Learners Full Name</th>
                <th>Grade & Section</th>
                <th>Teacher</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>< ?php echo htmlspecialchars($student['id']); ?></td>
                <td>< ?php echo htmlspecialchars($student['learners_name']); ?></td>
                <td>< ?php echo htmlspecialchars($student['grade'] . ' - ' . $student['section']); ?></td>
                <td>< ?php // Display teacher name if applicable; otherwise leave blank or handle as needed ?></td>
            </tr>
        </tbody>
    </table> -->

    <!-- <h4>Student Scores:</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Subject</th>
                <th>First Quarter</th>
                <th>Second Quarter</th>
                <th>Third Quarter</th>
                <th>Fourth Quarter</th>
                <th>Total</th>
                <th>Average</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>< ?php echo htmlspecialchars($student['subject']); ?></td>
                <td>< ?php echo htmlspecialchars($first_quarter); ?></td>
                <td>< ?php echo htmlspecialchars($second_quarter); ?></td>
                <td>< ?php echo htmlspecialchars($third_quarter); ?></td>
                <td>< ?php echo htmlspecialchars($fourth_quarter); ?></td>
                <td>< ?php echo $total; ?></td>
                <td>< ?php echo number_format($average, 2); ?></td>
            </tr>
        </tbody>
    </table>

    <h4>Summary Statistics:</h4>
    <table class="table table-bordered">
        <tr>
            <td>Total Score:</td>
            <td>< ?php echo $metrics['total_score']; ?></td>
        </tr>
        <tr>
            <td>Average MPS:</td>
            <td>< ?php echo number_format($metrics['average_mean'], 2); ?></td>
        </tr> 
        <tr>
            <td>Highest Possible Score:</td>
            <td>< ?php echo $metrics['highest_possible_score']; ?></td>
        </tr>
        <tr>
            <td>Highest Score:</td>
            <td>< ?php echo $metrics['highest_score']; ?></td>
        </tr>
        <tr>
            <td>Lowest Score:</td>
            <td>< ?php echo $metrics['lowest_score']; ?></td>
        </tr>
        <tr>
            <td>No. of Students Getting 75% PL:</td>
            <td>< ?php echo $metrics['students_75_pl']; ?></td>
        </tr>
        <tr>
            <td>Percentage of Students Getting 75% PL:</td>
            <td>< ?php echo number_format($metrics['percentage_75_pl'], 2) . '%'; ?></td>
        </tr>
    </table>

    <div class="text-center">
        <button class="btn btn-secondary print-btn" onclick="window.print()">Print</button>
        <a href="r.php" class="btn btn-primary">Back</a>
    </div>
</div> -->
<table>
    <thead>
        <tr>
            <th></th>
            <th>Names</th>
            <th>First</th>
            <th>Second</th>
            <th>Third</th>
            <th>Fourth</th>
        </tr>
        <tr>
            <th> </th>
            <th class="gray">Male</th>
            <th class="blue">40</th>
            <th class="blue">40</th>
            <th class="blue">0</th>
            <th class="blue">0</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td><?php echo htmlspecialchars($student['learners_name']); ?></td>
            <td>27</td>
            <td>28</td>
            <td></td>
            <td></td>
        </tr>
        <tr><td><br></td></tr>
        <tr>
            <td> </td>
            <td>Total Score:</td>
            <td>551</td>
            <td>502</td>
            <td>0</td>
            <td>0</td>
        </tr>
        <tr>
            <td> </td>
            <td>No. of Cases:</td>
            <td>18</td>
            <td>18</td>
            <td>18</td>
            <td>18</td>
        </tr>
        <tr>
            <td> </td>
            <td>Highest Possible Score:</td>
            <td>40</td>
            <td>40</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>HIGHEST SCORE:</td>
            <td>39</td>
            <td>39</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>LOWEST SCORE:</td>
            <td>16</td>
            <td>17</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>AVERAGE MEAN</td>
            <td>31</td>
            <td>28</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>MPS</td>
            <td>77</td>
            <td>70</td>
            <td>#DIV</td>
            <td>#DIV</td>
        </tr>
        <tr>
            <td> </td>
            <td>No. of Students Getting 75% PL:</td>
            <td>12</td>
            <td>7</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>Percentage of Students Getting 75% PL:</td>
            <td>66.7%</td>
            <td>38.9%</td>
            <td>0.0%</td>
            <td>0.0%</td>
        </tr>
<!-- female -->
        <thead>
            <tr>
                <th> </th>
                <th class="gray">Female</th>

            </tr>
        </thead>
        <tr>
            <td>1</td>
            <td><?php echo htmlspecialchars($student['learners_name']); ?></td>
            <th class="blue">40</th>
            <th class="blue">40</th>
            <th class="blue">0</th>
            <th class="blue">0</th>
        </tr>
        <tr><td><br></td></tr>
        <tr>
            <td> </td>
            <td>Total Score:</td>
            <td>551</td>
            <td>502</td>
            <td>0</td>
            <td>0</td>
        </tr>
        <tr>
            <td> </td>
            <td>No. of Cases:</td>
            <td>18</td>
            <td>18</td>
            <td>18</td>
            <td>18</td>
        </tr>
        <tr>
            <td> </td>
            <td>Highest Possible Score:</td>
            <td>40</td>
            <td>40</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>HIGHEST SCORE:</td>
            <td>39</td>
            <td>39</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>LOWEST SCORE:</td>
            <td>16</td>
            <td>17</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>AVERAGE MEAN</td>
            <td>31</td>
            <td>28</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>MPS</td>
            <td>77</td>
            <td>70</td>
            <td>#DIV</td>
            <td>#DIV</td>
        </tr>
        <tr>
            <td> </td>
            <td>No. of Students Getting 75% PL:</td>
            <td>12</td>
            <td>7</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>Percentage of Students Getting 75% PL:</td>
            <td>66.7%</td>
            <td>38.9%</td>
            <td>0.0%</td>
            <td>0.0%</td>
        </tr>
        <tr class="average">
            <td></td>
            <td>AVERAGE MPS</td>
            <td>80.38</td>
            <td>70.57</td>
            <td>#DIV</td>
            <td>#DIV</td>
        </tr>
        <tr class="average">
            <td></td>
            <td>AVERAGE MRS</td>
            <td>32.15</td>
            <td>28.23</td>
            <td>0.00</td>
            <td>0.00</td>
        </tr>
    </tbody>
</table>
<!-- <table>
    <thead>
       
        <tr>
            <th> </th>
            <th class="gray">Female</th>
            <th class="blue">40</th>
            <th class="blue">40</th>
            <th class="blue">0</th>
            <th class="blue">0</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>boyonas</td>
            <td>27</td>
            <td>28</td>
            <td>blank</td>
            <td>blank</td>
        </tr>
        <tr>
            <td> </td>
            <td>Total Score:</td>
            <td>551</td>
            <td>502</td>
            <td>0</td>
            <td>0</td>
        </tr>
        <tr>
            <td> </td>
            <td>No. of Cases:</td>
            <td>18</td>
            <td>18</td>
            <td>18</td>
            <td>18</td>
        </tr>
        <tr>
            <td> </td>
            <td>Highest Possible Score:</td>
            <td>40</td>
            <td>40</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>HIGHEST SCORE:</td>
            <td>39</td>
            <td>39</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>LOWEST SCORE:</td>
            <td>16</td>
            <td>17</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>AVERAGE MEAN</td>
            <td>31</td>
            <td>28</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>MPS</td>
            <td>77</td>
            <td>70</td>
            <td>#DIV</td>
            <td>#DIV</td>
        </tr>
        <tr>
            <td> </td>
            <td>No. of Students Getting 75% PL:</td>
            <td>12</td>
            <td>7</td>
            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>Percentage of Students Getting 75% PL:</td>
            <td>66.7%</td>
            <td>38.9%</td>
            <td>0.0%</td>
            <td>0.0%</td>
        </tr>
        <tr>
            <td></td>
            <td>AVERAGE MPS</td>
            <td>80.38</td>
            <td>70.57</td>
            <td>#DIV</td>
            <td>#DIV</td>
        </tr>
        <tr>
            <td></td>
            <td>AVERAGE MRS</td>
            <td>32.15</td>
            <td>28.23</td>
            <td>0.00</td>
            <td>0.00</td>
        </tr>
    </tbody>
</table> -->
<br>
<span>Prepared by:</span>
<h6>PAMILA ANN BULIAS</h6>
<p class="teacher">Subject Teacher</p>
<br>
<br>
<span>Noted by:</span>
<h6>Constantino G. Lazonia</h6>
<p class="teacher">Teacher In-Charge</p>

<div class="text-center">
        <button class="btn btn-secondary print-btn" onclick="window.print()">Print</button>
    </div>


</div>
<?php include('../crud/footer.php'); ?>
