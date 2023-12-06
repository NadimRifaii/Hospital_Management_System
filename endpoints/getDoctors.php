<?php
include_once('../database/database.php');
function getAllDoctors($db)
{
  $connection = $db->getConnection();
  $getAllDoctorsQuery = $connection->prepare("SELECT * FROM doctors");
  $getAllDoctorsQuery->execute();
  $result = $getAllDoctorsQuery->get_result();
  $users = [];
  while ($row = $result->fetch_assoc()) {
    $users[] = $row;
  }
  return $users;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  echo json_encode([
    'doctors' => getAlldoctors($db)
  ]);
} else {
  echo json_encode([
    'status' => 0,
    'message' => "Invalid request method!"
  ]);
}
