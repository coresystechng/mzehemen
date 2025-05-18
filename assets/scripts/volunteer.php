<?php 
// Database connection
include 'connect.php';

// Initialize variables
$first_name = "";
$last_name = "";
$email = "";
$phone_no = "";
$skills = "";
$availability = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  // Get form data
  $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
  $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $phone_no = mysqli_real_escape_string($conn, $_POST['phone_no']);
  $interests = $_POST['interests'];
  $interests = implode(", ", $interests);
  $skills = mysqli_real_escape_string($conn, $_POST['skills']);
  $availability = mysqli_real_escape_string($conn, $_POST['availability']);

  // Insert data into the database
  $sql = "INSERT INTO volunteers_tb (first_name, last_name, email, phone_no, interests, skills, availability) 
            VALUES ('$first_name', '$last_name', '$email', '$phone_no', '$interests', '$skills', '$availability')";

    if (mysqli_query($conn, $sql)) {
      header("Location: success.html");
      exit();
    } else {
      echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
  }

//close the database connection
mysqli_close($conn);

?>