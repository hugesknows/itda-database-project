<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: index.php");
}

$dbuser = "CHW";
$dbpassword = "chwpass";
$db = "itda_project";
$host = "localhost";

$mysqli = new mysqli($host, $dbuser, $dbpassword, $db);
$con = mysqli_connect($host, $dbuser, $dbpassword, $db);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$sql = "SELECT * FROM EducationalVideo";
$videos = mysqli_query($con, $sql);
if (!$videos) {
    printf("Error: %s\n", mysqli_error($con));
    exit();
}
$mysqli -> close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Educational Videos</title>
</head>
<body>
<header class="container">
    <h1 class="banner">Educational Videos</h1>
</header>

<table>
    <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Source</th>
    </tr>

    <?php

    while ($row = mysqli_fetch_array($videos)) {
        ?>
        <tr>
            <td><?php echo $row[1] ?></td>
            <td><?php echo $row[3] ?></td>
            <td class="video-link"><a target="_blank" rel="noopener noreferrer" href="<?php echo $row[2] ?>">Go</a></td>

        </tr>

        <?php
    }
    ?>
</table>

</body>
</html>

<style>
    * {
        font-family: sans-serif;
    }
    .container {
        display: flex;
        justify-content: space-around;
    }
    .banner {
        margin-right: auto;
        margin-left: 15px;
    }



    header {
        width: 100%;
        height: 80px;
        align-items: center;
        background-color: grey;
        color: white;
    }
    td, th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #ddd;
    }

    th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }

    table {
        margin-top: 15px;
        width: 50%;
    }
</style>
