<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "form";
$tableName = "register_table";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";//checking if database exists in localserver
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' checked/created successfully.<br>";
} else {
    echo "Error checking/creating database: " . $conn->error . "<br>";
}

$conn->select_db($dbname);

$sql = "CREATE TABLE IF NOT EXISTS $tableName (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    gender VARCHAR(10) NOT NULL,
    phno VARCHAR(15) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table '$tableName' checked/created successfully.<br>";
} else {
    echo "Error checking/creating table: " . $conn->error . "<br>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $phno = $_POST['phno'];


    if (!empty($firstname) && !empty($lastname) && !empty($dob) && !empty($email) && !empty($gender) && !empty($phno)) {
        
        $SELECT = "SELECT email FROM $tableName WHERE email = ? LIMIT 1";
        $INSERT = "INSERT INTO $tableName (firstname, lastname, dob, email, gender, phno) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->store_result();
        $rnum = $stmt->num_rows;

        if ($rnum == 0) {
            $stmt->close();
            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("sssssi", $firstname, $lastname, $dob, $email, $gender, $phno);
            $stmt->execute();
            echo "New record inserted successfully.";
        } else {
            echo "Someone already registered using this email.";
        }
        
        $stmt->close();
        $conn->close();
    } else {
        echo "All fields are required.";
        die();
    }
}
?>
