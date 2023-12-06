<?php
include_once('../classes/room.php');
function addRoom($room, $data)
{
  $room->setRoomType($data->roomType);
  $room->createRoom();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'));
  if (!empty($data->roomType)) {
    $room = new Room($db);
    addRoom($room, $data);
  } else {
    echo json_encode([
      'status' => 0,
      'message' => 'missing cridentials'
    ]);
  }
} else {
  echo json_encode([
    'status' => "0",
    'message' => "endpoint is only for post methods"
  ]);
}
