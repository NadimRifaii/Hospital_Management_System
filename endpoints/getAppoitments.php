<?php
include_once('../database/database.php');
function getAllAppoitments($db)
{
  $connection = $db->getConnection();
  $getAllAppoitmentsQuery = $connection->prepare("SELECT * from appoitments");
  $getAllAppoitmentsQuery->execute();
  $result = $getAllAppoitmentsQuery->get_result();
  $appoitments = [];
  while ($row = $result->fetch_assoc()) {
    $appoitments[] = $row;
  }
  return $appoitments;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  echo json_encode([
    'appoitments' => getAllAppoitments($db)
  ]);
} else {
  echo json_encode([
    'status' => 0,
    'message' => "Invalid request method!"
  ]);
}
