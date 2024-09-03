<?php
session_start(); // Start the session at the very top

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<?php include('Headerforform137.php'); ?>
<?php include("../LoginRegisterAuthentication/connection.php"); ?>

<?php
$student_id = isset($_GET['id']) ? intval($_GET['id']) : '';

$query = "SELECT s.*, sg.final_grade, sub.name as subject_name 
          FROM students s 
          JOIN student_grades sg ON s.id = sg.student_id 
          JOIN subjects sub ON sg.subject_id = sub.id
          WHERE s.id = '$student_id'";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

$student = mysqli_fetch_assoc($result);
?>
<a href="r.php">Back</a>

<div class="container mt-5">
    <!-- <h2>Form 137 - < ?php echo htmlspecialchars($student['learners_name']); ?></h2> -->

    <!-- Display the student's transcript of records -->
    <!-- <p><strong>Section:</strong> < ? php echo htmlspecialchars($student['section']); ?></p>
    <p><strong>Grade:</strong> < ?php echo htmlspecialchars($student['grade']); ?></p>

    <h4>Subject Grades:</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Final Grade</th>
            </tr>
        </thead>
        <tbody>
            < ?php
            mysqli_data_seek($result, 0); // Reset result pointer
            while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td>< ?php echo htmlspecialchars($row['subject_name']); ?></td>
                <td>< ?php echo htmlspecialchars($row['final_grade']); ?></td>
            </tr>
            < ?php } ?>
        </tbody>
    </table>

    <a href="javascript:window.print()" class="btn btn-primary">Print Form</a>
    <a href="r.php" class="btn btn-secondary">Back to Report</a> -->
<div class="form137-container">
   <div class="left">
        <img src="./images/Logo.png.png" alt=""> 
   </div>
   <div class="center">
        <h3>Republic of the Philippines</h3>
        <h3>Department of Education</h3>
        <h1>Learners Permanent record</h1>
        <h5>(Formerly Form 137)</h5>
   </div>
   <div class="right">
        <img src="./images/depedlogo.png.png" alt=""> 
   </div>

</div>
    <h1 class="form137">Learner's Information</h1>
        <span>
        LAST NAME <p>:</p> 
        First name <p>:</p>
        Name extn (jr) <p>:</p>
        Middle name <p>:</p>
        Learner Reference Number (LRN)<p>:</p>
        Birthdate(mm/dd/yyyy)<p>:</p>
        Sex <p>:</p>
        </span>
        <h1 class="form137">ELIGIBILITY FOR JHS ENROLLMENT</h1>
        <div class="eligibility-box">
    <span>
    <input type="checkbox" id="elementaryCompleter" name="elementary_completer" value="yes">
    Elementary School Completer <p>:</p>
    General Average <p>:</p>
    Citation (If Any) <p>:</p>
    Name of Elementary School <p>:</p>
    School ID <P>:</P>
    Address of School <p>:</p>
    </span>
  
    <h5> Other Credentials Presented:</h5>
    <input type="checkbox" id="PEPT Passer" name="PEPT_Passer" value="yes">
    PEPT Passer <p></p>
    Rating <p>:</p>
    <input type="checkbox" id="ALS A & E Passer Rating" name="ALSA&Ep_PasserRating" value="yes">
    ALS A & E Passer Rating <p>:</p>
    <input type="checkbox" id="Others(Pls Specify)" name="Others_(PlsSpecify)" value="yes">
    Others(Pls Specify) <p>:</p>
    Date Of Examination/Assessment (mm/dd/yyyy) <p>:</p>
    Name and Address of Testing Center <p>:</p>
   
    </div>
    <div class="scholastic-record-container">
    <h1 class="form137">Scholastic Record</h1>
        <span>
            
            School<p>:</p>
            School id<p>:</p>
            District<p>:</p>
            Division<p>:</p>
            Region<p>:</p>
            Classified as Grade<p>:</p>
            Section <p>:</p>
            School year<p>:</p>
            Name of Adviser/Teacher <p>:</p>
            Signature<p>:</p>
        </span>
  
        <table class="form-container">
            <thead>
            <tr>
            <th rowspan="2">Learning areas</th>
            <th colspan="4">Quarterly Rating</th> <!-- This spans across 4 columns -->
            <th rowspan="2">Final Rating</th>
            <th rowspan="2"></th>
        </tr>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
        </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Filipino</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>Passed</td>
                </tr>
                <tr>
                    <td>Filipino</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>Passed</td>
                </tr>
                <tr>
                    <td>Filipino</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>Passed</td>
                </tr>
                <tr>
                    <td>Filipino</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>Passed</td>
                </tr>
                <tr>
                    <td>Filipino</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>Passed</td>
                </tr>

                
            </tbody>
        </table>
        
        <div class="final-grade">
            <div class="left">

            </div>
            <div class="center">
                <div class="average">General Average</div>
                <div class="total">83</div>
                
            </div>
            <div class="right">promoted</div>

        </div>


        <div class="Blank">
            <div class="left">

            </div>
            <div class="center">
                <div class="average"></div>
                <div class="total"></div>
                
            </div>
            <div class="right">promoted</div>

        </div>
        <table class="form-container">
            <tbody>
            <tr>
                    <td>Remedial Classes</td>
                   <td> Conducted from(mm/dd/yyyy)  <p>:</p>  </td>
                   <td> to(mm/dd/yyyy)<p>:</p></td>
                
                </tr>
                </tbody>
            </table>

            <table class="form-container">
            <tbody>
            <tr>
                    <td>Learning Areas</td>
                   <td> Final Rating </td>
                   <td> Remedial Class Remark</td>
                   <td>Recomputed Final Grade</td>
                    <td>Remarks</td>
                
                </tr>
          <!-- Visible blank rows -->
        <tr class="blank-row">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr class="blank-row">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
                </tbody>
            </table> 



            <div class="scholastic-record-container">
        <span>
            
            School<p>:</p>
            School id<p>:</p>
            District<p>:</p>
            Division<p>:</p>
            Region<p>:</p>
            Classified as Grade<p>:</p>
            Section <p>:</p>
            School year<p>:</p>
            Name of Adviser/Teacher <p>:</p>
            Signature<p>:</p>
        </span>
  
        <table class="form-container">
            <thead>
            <tr>
            <th rowspan="2">Learning areas</th>
            <th colspan="4">Quarterly Rating</th> <!-- This spans across 4 columns -->
            <th rowspan="2">Final Rating</th>
            <th rowspan="2"></th>
        </tr>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
        </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Filipino</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>Passed</td>
                </tr>
                <tr>
                    <td>Filipino</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>Passed</td>
                </tr>
                <tr>
                    <td>Filipino</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>Passed</td>
                </tr>
                <tr>
                    <td>Filipino</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>Passed</td>
                </tr>
                <tr>
                    <td>Filipino</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>75</td>
                    <td>Passed</td>
                </tr>

                
            </tbody>
        </table>
        
        <div class="final-grade">
            <div class="left">

            </div>
            <div class="center">
                <div class="average">General Average</div>
                <div class="total">83</div>
                
            </div>
            <div class="right">promoted</div>

        </div>


        <div class="Blank">
            <div class="left">

            </div>
            <div class="center">
                <div class="average"></div>
                <div class="total"></div>
                
            </div>
            <div class="right">promoted</div>

        </div>
        <table class="form-container">
            <tbody>
            <tr>
                    <td>Remedial Classes</td>
                   <td> Conducted from(mm/dd/yyyy)  <p>:</p>  </td>
                   <td> to(mm/dd/yyyy)<p>:</p></td>
                
                </tr>
                </tbody>
            </table>

            <table class="form-container">
            <tbody>
            <tr>
                    <td>Learning Areas</td>
                   <td> Final Rating </td>
                   <td> Remedial Class Remark</td>
                   <td>Recomputed Final Grade</td>
                    <td>Remarks</td>
                
                </tr>
          <!-- Visible blank rows -->
        <tr class="blank-row">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr class="blank-row">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
                </tbody>
            </table> 
            


    </div>
</div>
<div class="text-center">
        <button class="btn btn-secondary print-btn" onclick="window.print()">Print</button>
    </div>



    </div>
</div>


<?php include('../crud/footer.php'); ?>
