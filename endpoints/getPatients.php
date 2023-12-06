<?php
include_once('../database/database.php');
function getAllPatients($db)
{
  $connection = $db->getConnection();
  $getAllPatientsQuery = $connection->prepare("SELECT * FROM patients");
  $getAllPatientsQuery->execute();
  $result = $getAllPatientsQuery->get_result();
  $users = [];
  while ($row = $result->fetch_assoc()) {
    $users[] = $row;
  }
  return $users;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  echo json_encode([
    'patients' => getAllPatients($db)
  ]);
} else {
  echo json_encode([
    'status' => 0,
    'message' => "Invalid request method!"
  ]);
}
