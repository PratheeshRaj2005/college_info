<?php
include('dbconfig.php');

// Check if the form is submitted
if(isset($_POST['submit'])){
    $susername = $_POST['susername'];
    $semail = $_POST['semail'];
    $designation = $_POST['designation'];
    $spassword = $_POST['spassword'];
    
    // Echo the username to confirm the variable is set
    echo $susername;

    // Insert data into the 'register' table
    $insert = "INSERT INTO register (name, email, institute, designation, password) VALUES ('$susername', '$semail', '$designation', '$spassword')";

    // Execute the query and check if it was successful
    $result = mysqli_query($conn, $insert);
    if($result){
        echo "Successfully added";

        header("Location:index.php");
    } else {
        echo "Error occurred: " . mysqli_error($conn);
    }
} 
else {
    echo "Form not submitted";
}
?>
