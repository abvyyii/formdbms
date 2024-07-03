<?php
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$dob = $_POST['dob'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$phno = $_POST['phno'];

if(!empty($firstname) || !empty($lastname) || !empty($dob) || !empty($email) ||!empty($gender) || !empty($phno)) {
    $host="localhost";
    $dbUsername="root";
    $dbPassword="";
    $database_name="form";

    $conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbname);

    if(mysqli_connect_error()){
        die("Connection error: ". mysqli_coonect_error());
    } else{
        $SELECT = "SELECT email From table1 where email= ? Limit 1";
        $INSERT = "INSERT into table1 (firstname, lastname, dob, email, gender, phno) values(?,?,?,?,?,?)";

        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->store_result();
        $rnum= $stmt->num_rows;

        if($rnum==0){
            $stmt->close();

            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("sssssi", $firstname, $lastname, $dob, $gender, $phno);
            $stmt->execute();
            echo "New record inserted sucessfully";

        }
        else{
            echo "Someone already registered using this email";
        }
       $stmt->close();
       $conn->close(); 
    }

}
else{
echo "All Field are required";
die();

}
?>
