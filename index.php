<?php
ob_start();
session_start();
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Login</title>
    </head>
    <body>
    <center>
        <h1>Hospital Staff Login</h1>
        <h3 style="color: grey">Welcome</h3>
        <form action="index.php" autocomplete="off" method="post">
            <table>
                <tr>
                    <td><label for="staffid">Staff ID</label></td>
                    <td><input id="staffid" type="text" name="staffid"></td>
                </tr>
                <tr>
                    <td><label for="password">Password</label></td>
                    <td><input id=password type="password" name="password"></td>
                </tr>
                <tr>
                    <td><input type="reset" value="Reset"></td>
                    <td><input type="submit" value="Login"></td>
                </tr>
            </table>
        </form>

        <span id="login-error" style="color: red; text-align: center; margin-top: 50px"></span>

    </center>
    </body>
    </html>

<?php
$loginStatus = "";
$host = "localhost";
$dbuser = "Auth";
$dbpassword = "authpass";
$db = "itda_project";

$mysqli = new mysqli($host, $dbuser, $dbpassword, $db);
$con = mysqli_connect($host, $dbuser, $dbpassword, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

if (isset($_POST['staffid'])) {
    $staff_id = $_POST['staffid'];
    $password = $_POST['password'];
    $sql = "select * from Authentication where StaffID = '" . $staff_id . "' and Password = '" . $password . "' limit 1";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) == 1) {
        $sql = "select `Type` from HospitalStaff where StaffID = '" . $staff_id . "'";
        $result = mysqli_query($con, $sql);
        $staffType = $result->fetch_object()->Type;
        $sql = "select `Name` from HospitalStaff where StaffID = '" . $staff_id . "'";
        $result = mysqli_query($con, $sql);
        $username = $result->fetch_object()->Name;
        if ($staffType == "CHW") {
            $_SESSION["staffid"] = $staff_id;
            $_SESSION["username"] = $username;
            $_SESSION["dbpass"] = "chwpass";
            $_SESSION["logged_in"] = true;
            header("location: community_healthcare_worker_home.php");
        } else {
            echo "<script>document.getElementById('login-error').innerText = 'Functionality for Staff Type: $staffType not yet supported'</script>";
        }
    } else {
        echo "<script>document.getElementById('login-error').innerText = 'Login failed'</script>";
    }
}
?>