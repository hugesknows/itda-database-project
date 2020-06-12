<?php

session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: index.php");
}


$host = "localhost";
$dbuser = "CHW";
$dbpassword = "chwpass";
$db = "itda_project";
$username = $_SESSION["username"];
$staff_id = $_SESSION["staffid"];
$appointments_missed_total = 0;
$appointments_tomorrow_total = 0;

$mysqli = new mysqli($host, $dbuser, $dbpassword, $db);
$con = mysqli_connect($host, $dbuser, $dbpassword, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$sql = "call get_todays_appointments($staff_id)";
$appointments = mysqli_query($con, $sql);
if (!$appointments) {
    printf("Error: %s\n", mysqli_error($con));
    exit();
}
$mysqli->close();

$mysqli = new mysqli($host, $dbuser, $dbpassword, $db);
$con = mysqli_connect($host, $dbuser, $dbpassword, $db);
$sql = "call get_appointment_reminders($staff_id)";
$reminders = mysqli_query($con, $sql);
if (!$reminders) {
    printf("Error: %s\n", mysqli_error($con));
    exit();
}
$mysqli->close();

$mysqli = new mysqli($host, $dbuser, $dbpassword, $db);
$con = mysqli_connect($host, $dbuser, $dbpassword, $db);
$sql = "call get_missed_appointments($staff_id)";
$missed_appointments = mysqli_query($con, $sql);
if (!$missed_appointments) {
    printf("Error: %s\n", mysqli_error($con));
    exit();
}
$mysqli->close();
$mysqli = new mysqli($host, $dbuser, $dbpassword, $db);

$query = "select chw_get_appointments_tomorrow($staff_id)";
if ($stmt = $mysqli->prepare($query)) {
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($appointments_tomorrow_total);
    if ($stmt->num_rows > 0) {
        while ($stmt->fetch()) {

        }
    }

    $stmt->free_result();
    $stmt->close();
}
$mysqli->close();
$mysqli = new mysqli($host, $dbuser, $dbpassword, $db);
$query = "select chw_get_appointments_missed($staff_id)";
if ($stmt = $mysqli->prepare($query)) {
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($appointments_missed_total);
    if ($stmt->num_rows > 0) {
        while ($stmt->fetch()) {
        }
    }

    $stmt->free_result();
    $stmt->close();
}
$mysqli->close();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="chw-home.css">
    <title>CHW Dashboard</title>
</head>
<header class="container">
    <h1 class="banner">Welcome, <?php echo $username; ?></h1>

    <a href="logout.php" class="logout">Logout</a>
</header>
<body>


<center>
    <div class="container">
        <div class="appointments-today">
            <h3>Appointments for Today</h3>
            <p>The following appointments are on today</p>
            <form action="appointment.php" method="post">
                <table>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Time</th>
                        <th>Appointment Type</th>
                        <th>Address</th>
                        <th></th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_array($appointments)) {

                        ?>
                        <tr>
                            <td><?php echo $row[0] ?></td>
                            <td><?php echo $row[1] ?></td>
                            <td><?php echo $row[2] ?></td>
                            <td><?php echo $row[3] ?></td>
                            <td><?php echo $row[4] ?></td>
                            <td><?php echo $row[5] ?></td>
                            <td><button type="submit" name="appointment" value="<?php echo $row[0] ?>">View</button></td>
                        </tr>

                        <?php
                    }
                    ?>
                </table>
            </form>
        </div>
        <div class="appointments-tomorrow">
            <h3>Appointment Reminders</h3>
            <p><?php echo $appointments_tomorrow_total ?> Appointments for Tomorrow</p>
            <table>
                <tr>
                    <th>Patient Name</th>
                    <th>Appointment Type</th>
                    <th>Date and Time</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_array($reminders)) {
                    ?>
                    <tr>
                        <td><?php echo $row[0] ?></td>
                        <td><?php echo $row[1] ?></td>
                        <td><?php echo $row[2] ?></td>
                    </tr>

                    <?php
                }
                ?>
            </table>
        </div>
        <div class="appointments-yesterday">
            <h3>Missed Appointments</h3>
            <p><?php echo $appointments_missed_total ?> appointments were missed yesterday</p>
            <table>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient Name</th>
                </tr>
                <?php
                while ($missed_appointment = mysqli_fetch_array($missed_appointments)) {
                    ?>
                    <tr>
                        <td><?php echo $missed_appointment[0] ?></td>
                        <td><?php echo $missed_appointment[1] ?></td>
                    </tr>

                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</center>
</body>
<footer>

</footer>
</html>

<?php

?>
