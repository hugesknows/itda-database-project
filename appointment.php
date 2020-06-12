<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: index.php");
}
if (isset($_POST['appointment'])) {
    $appointment_no = $_POST['appointment'];
}

$host = "localhost";
$dbuser = "CHW";
$dbpassword = "chwpass";
$db = "itda_project";
$username = $_SESSION["username"];
$staff_id = $_SESSION["staffid"];

#Declare a database object and database connection
$mysqli = new mysqli($host, $dbuser, $dbpassword, $db);
$con = mysqli_connect($host, $dbuser, $dbpassword, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
#Get all necessary appointment data from the 'chw_appointment_view' view
$query = "SELECT * FROM chw_appointment_view WHERE AppointmentID = $appointment_no limit 1";
$appointment_data = mysqli_query($con, $query);
#Check if the variable is not null. If null, display error
if (!$appointment_data) {
    printf("Error: %s\n", mysqli_error($con));
    exit();
} else { #Else extract data into appropriate variables (based on structure of the view.. could be done in a better way)
    while ($row = mysqli_fetch_array($appointment_data)) {
        $rowData = $row;
    }
    $appointmentDate = $rowData[1];
    $appointmentType = $rowData[2];
    $appointmentAttended = $rowData[3];
    $patientID = $rowData[4];
    $patientName = $rowData[5];
    $patientDOB = $rowData[6];
    $patientContactNo = $rowData[7];
    $patientIDNo = $rowData[8];
    $patientAddress = $rowData[9];
    $patientPregnant = $rowData[10];
    $patientHIV = $rowData[11];
    $patientDiabetes = $rowData[12];
    $patientDateRegistered = $rowData[13];
}
$mysqli->close();
#Get household member history
if ($appointment_data) {
    $mysqli = new mysqli($host, $dbuser, $dbpassword, $db);
    $con = mysqli_connect($host, $dbuser, $dbpassword, $db);
    $query = "CALL get_patient_householdmembers($patientID)";
    $household_member_history = mysqli_query($con, $query);
    $mysqli->close();
}

?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="appointment.css">
        <title>Appointment <?php echo " $appointment_no" ?></title>
    </head>
    <header>
        <header class="container">
            <h1 class="banner">Appointment No. <?php echo $appointment_no; ?></h1>
            <div class="nav-items">
                <a class="nav-item" href="educational_videos.php">View Educational Videos</a>
            </div>
            <a href="community_healthcare_worker_home.php" class="back">Back</a>
        </header>
    </header>
    <body>

    <div class="table-container">
        <div class="patient-info">
            <table>
                <tr>
                    <th class="table-header" colspan="2">Patient Info</th>
                </tr>
                <tr>
                    <th>Patient ID</th>
                    <td><?php echo $patientID ?></td>
                </tr>
                <tr>
                    <th>Patient Name</th>
                    <td><?php echo $patientName ?></td>
                </tr>
                <tr>
                    <th>Date of Birth</th>
                    <td><?php echo $patientDOB ?></td>
                </tr>
                <tr>
                    <th>Contact No.</th>
                    <td><?php echo $patientContactNo ?></td>
                </tr>
                <tr>
                    <th>ID No.</th>
                    <td><?php echo $patientIDNo ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo $patientAddress ?></td>
                </tr>
                <tr>
                    <th>Pregnant</th>
                    <td><?php echo $patientPregnant ?></td>
                </tr>
                <tr>
                    <th>HIV Status</th>
                    <td><?php echo $patientHIV ?></td>
                </tr>
                <tr>
                    <th>Diabetes Status</th>
                    <td><?php echo $patientDiabetes ?></td>
                </tr>
                <tr>
                    <th>Date Registered</th>
                    <td><?php echo $patientDateRegistered ?></td>
                </tr>

            </table>
        </div>
        <div class="table-spacer"></div>
        <div class="appointment-info">
            <table>
                <tr>
                    <th class="table-header" colspan="2">Appointment Info</th>
                </tr>
                <tr>
                    <th>Appointment ID</th>
                    <td><?php echo $appointment_no ?></td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td><?php echo $appointmentDate ?></td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td><?php echo $appointmentType ?></td>
                </tr>
                <tr>
                    <th>Attended</th>
                    <td><?php echo $appointmentAttended ?>
                        <button>Mark <?php if ($appointmentAttended == 1) {
                                echo "unattended";
                            } else {
                                echo "attended";
                            } ?></button>
                    </td>
                </tr>
            </table>

            <div class="log-test">
                <form action="" method="post">
                    <table style="margin-top: 20px">
                        <tr>
                            <th class="table-header" colspan="2">Log Test Data</th>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td><select name="test_type" id="testType">
                                    <option value="blood pressure">Blood Pressure</option>
                                    <option value="blood sugar">Blood Sugar</option>
                                </select></td>
                        </tr>
                        <tr>
                            <th colspan="2">Drugs Administered</th>
                        </tr>
                        <tr>
                            <td colspan="2"><textarea maxlength="255" required draggable="false"
                                                      name="drugs_administered"
                                                      id="" cols="30"
                                                      rows="5"></textarea></td>
                        </tr>
                        <tr>
                            <th colspan="2">Notes</th>
                        </tr>
                        <tr>
                            <td colspan="2"><textarea maxlength="255" required draggable="false" name="notes" id=""
                                                      cols="30"
                                                      rows="5"></textarea></td>
                        </tr>
                        <tr>
                            <td><input type="reset" value="Cancel"></td>
                            <td>
                                <button name="appointment" value="<?php echo $appointment_no ?>">Log Test Data</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="table-spacer"></div>
        <div class="household-member-info">
            <table>
                <tr>
                    <th class="table-header" colspan="5">Patient Household Members</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Sex</th>
                    <th>Birth Weight (g)</th>
                    <th>Date of Birth</th>
                    <th>Notes</th>
                </tr><?php
                while ($row = mysqli_fetch_array($household_member_history)) {

                    ?>
                    <tr>
                        <td><?php echo $row[2] ?></td>
                        <td><?php echo $row[3] ?></td>
                        <td><?php echo $row[4] ?></td>
                        <td><?php echo $row[6] ?></td>
                        <td><?php echo $row[5] ?></td>
                    </tr>

                    <?php
                }
                ?>
            </table>
            <div id="new-member" class="new-household-member">
                <form action="appointment.php" method="post" autocomplete="off">
                    <table>
                        <tr>
                            <th class="table-header" colspan="2">Register New Household Member</th>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td><input required type="text" name="name"></td>
                        </tr>
                        <tr>
                            <th>Sex</th>
                            <td><input required type="text" name="sex"></td>
                        </tr>
                        <tr>
                            <th>Birth Weight (g)</th>
                            <td><input required type="text" name="birthWeight"></td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td><input required name="date" type="date"></td>
                        </tr>
                        <tr>
                            <th colspan="2">Notes</th>
                        </tr>
                        <tr>
                            <td colspan="2"><textarea maxlength="255" draggable="false" name="notes" id=""
                                                      cols="30"
                                                      rows="5"></textarea></td>
                        </tr>
                        <tr>
                            <td><input type="reset" value="Cancel"></td>
                            <td>
                                <button name="appointment" type="submit" value="<?php echo $appointment_no ?>">Register New Household
                                    Member
                                </button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
    </body>
    </html>

<?php
#Adding new household member to the database
if (isset($_POST['date']) && isset($_POST['sex']) && isset($_POST['birthWeight']) && isset($_POST['notes'])) {
    $memberBirthDate = $_POST['date'];
    $memberSex = $_POST['sex'];
    $memberBirthWeight = $_POST['birthWeight'];
    $memberBirthNotes = $_POST['notes'];
    $memberName = $_POST['name'];

    $mysqli = new mysqli($host, $dbuser, $dbpassword, $db);
    $con = mysqli_connect($host, $dbuser, $dbpassword, $db);

    $query = "INSERT INTO `itda_project`.`HouseholdMember`(`RelatedPatient`,`Name`,`Sex`,`BirthWeight`,`Notes`,`DateOfBirth`)
VALUES ($patientID, '$memberName', $memberSex, $memberBirthWeight, '$memberBirthNotes', '$memberBirthDate')";

    if (mysqli_query($con, $query)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con) . "<br>" . $query;
    }
    $mysqli->close();
    $_POST['appointment'] = $appointment_no;
}

#Logging test data to database
if (isset($_POST['test_type']) && isset($_POST['drugs_administered']) && isset($_POST['notes']) && isset($_POST['test_type'])) {
    $fieldTestType = $_POST['test_type'];
    $fieldTestDrugsAdministered = $_POST['drugs_administered'];
    $fieldTestNotes = $_POST['notes'];

    $mysqli = new mysqli($host, $dbuser, $dbpassword, $db);
    $con = mysqli_connect($host, $dbuser, $dbpassword, $db);
    $query = "INSERT INTO `itda_project`.`FieldTest`(`Type`,`Notes`,`AppointmentID`,`DrugsAdministered`)
VALUES('$fieldTestType','$fieldTestNotes','$appointment_no','$fieldTestDrugsAdministered')";
    if (mysqli_query($con, $query)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con) . "<br>" . $query;
    }
    $mysqli->close();
}
?>