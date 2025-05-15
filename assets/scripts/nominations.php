<?php 

// Database connection
include 'connect.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize POST data
    $nominator_name = mysqli_real_escape_string($conn, $_POST['nominator_name']);
    $nominator_email = mysqli_real_escape_string($conn, $_POST['nominator_email']);
    $nominator_phone = mysqli_real_escape_string($conn, $_POST['nominator_phone']);
    $nominator_relation = mysqli_real_escape_string($conn, $_POST['nominator_relation']);

    $nominee_name = mysqli_real_escape_string($conn, $_POST['nominee_name']);
    $nominee_age = (int)$_POST['nominee_age'];
    $nominee_gender = mysqli_real_escape_string($conn, $_POST['nominee_gender']);
    $nominee_email = mysqli_real_escape_string($conn, $_POST['nominee_email']);
    $nominee_phone = mysqli_real_escape_string($conn, $_POST['nominee_phone']);
    $nominee_address = mysqli_real_escape_string($conn, $_POST['nominee_address']);

    $award_category = mysqli_real_escape_string($conn, $_POST['award_category']);
    $nomination_reason = mysqli_real_escape_string($conn, $_POST['nomination_reason']);
    $before_contribution = mysqli_real_escape_string($conn, $_POST['before_contribution']);
    $after_contribution = mysqli_real_escape_string($conn, $_POST['after_contribution']);
    $unique_contribution = mysqli_real_escape_string($conn, $_POST['unique_contribution']);

    $consent_check = isset($_POST['consent_check']) ? 1 : 0;

    // File upload handling
    $upload_dir = '../../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    function handle_upload($input_name, $allowed_types, $max_size, $upload_dir) {
    if (!isset($_FILES[$input_name]) || $_FILES[$input_name]['error'] !== UPLOAD_ERR_OK) {
        return null;
      }
      $file = $_FILES[$input_name];
      $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
      if (!in_array($ext, $allowed_types)) return null;
      if ($file['size'] > $max_size) return null;
      $new_name = uniqid() . '_' . basename($file['name']);
      $target = $upload_dir . $new_name;
      if (move_uploaded_file($file['tmp_name'], $target)) {
          return $target;
      }
        return null;
    }

    //File Paths
    $supporting_doc_1_path = handle_upload('supporting_doc_1', ['pdf','doc','docx'], 1*1024*1024, $upload_dir);
    $supporting_doc_2_path = handle_upload('supporting_doc_2', ['pdf','doc','docx'], 1*1024*1024, $upload_dir);
    $additional_evidence_path = handle_upload('additional_evidence', ['pdf','doc','docx','jpg','jpeg','png'], 2*1024*1024, $upload_dir);

    // Insert into database
    $sql = "INSERT INTO nominations_tb (
        nominator_name, nominator_email, nominator_phone, nominator_relation,
        nominee_name, nominee_age, nominee_gender, nominee_email, nominee_phone, nominee_address,
        award_category, nomination_reason, before_contribution, after_contribution, unique_contribution, consent_check, supporting_doc_1, supporting_doc_2, additional_evidence
    ) VALUES (
        '$nominator_name', '$nominator_email', '$nominator_phone', '$nominator_relation',
        '$nominee_name', $nominee_age, '$nominee_gender', '$nominee_email', '$nominee_phone', '$nominee_address',
        '$award_category', '$nomination_reason', '$before_contribution', '$after_contribution', '$unique_contribution', $consent_check, 
        " . ($supporting_doc_1_path ? "'$supporting_doc_1_path'" : "NULL") . ",
        " . ($supporting_doc_2_path ? "'$supporting_doc_2_path'" : "NULL") . ",
        " . ($additional_evidence_path ? "'$additional_evidence_path'" : "NULL") . "
    )";

    if (mysqli_query($conn, $sql)) {
        header('Location: success.html');;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>