<?php
include_once('../database/database.php');
function getAllRooms($db)
{
  $connection = $db->getConnection();
  $getRoomsQuery = $connection->prepare("SELECT * FROM rooms");
  $getRoomsQuery->execute();
  $result = $getRoomsQuery->get_result();
  $rooms = [];
  while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
  }
  return $rooms;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  echo json_encode([
    'rooms' => getAllRooms($db)
  ]);
} else {
  echo json_encode([
    'status' => 0,
    'message' => "Invalid request method!"
  ]);
}
