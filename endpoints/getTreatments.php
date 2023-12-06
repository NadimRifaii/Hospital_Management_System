<?php
include_once('../database/database.php');
function getAllTreatments($db)
{
  $connection = $db->getConnection();
  $getAllTreatmentsQuery = $connection->prepare("SELECT * FROM treatments");
  $getAllTreatmentsQuery->execute();
  $result = $getAllTreatmentsQuery->get_result();
  $users = [];
  while ($row = $result->fetch_assoc()) {
    $users[] = $row;
  }
  return $users;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  echo json_encode([
    'treatments' => getAllTreatments($db)
  ]);
} else {
  echo json_encode([
    'status' => 0,
    'message' => "Invalid request method!"
  ]);
}
