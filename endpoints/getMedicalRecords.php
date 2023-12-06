<?php
include_once('../database/database.php');
function getAllMedicalRecords($db)
{
  $connection = $db->getConnection();
  $getAllMedicalRecordsQuery = $connection->prepare("SELECT * FROM medical_records");
  $getAllMedicalRecordsQuery->execute();
  $result = $getAllMedicalRecordsQuery->get_result();
  $users = [];
  while ($row = $result->fetch_assoc()) {
    $users[] = $row;
  }
  return $users;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  echo json_encode([
    'MedicalRecords' => getAllMedicalRecords($db)
  ]);
} else {
  echo json_encode([
    'status' => 0,
    'message' => "Invalid request method!"
  ]);
}
