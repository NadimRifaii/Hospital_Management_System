<?php
include('../classes/user.php');
include('../classes/patient.php');
include_once('../classes/room.php');
function addPatientToRoom($room, $patient, $data)
{
  $room->setRoomType($data->roomType);
  $patient->setPatientId($data->patientId);
  return $room->updateRoom($patient->getPatientId(), $data->date);
}
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
  $data = json_decode(file_get_contents('php://input'));
  if (!empty($data->patientId) && !empty($data->roomType) && !empty($data->date)) {
    $patient = new Patient($db);
    if ($patient->checkExistingPatient($data->patientId)) {
      $room = new Room($db);
      if (!$room->checkExistingPatient($data->patientId)) {
        if (addPatientToRoom($room, $patient, $data)) {
          echo json_encode([
            'status' => 1,
            "message" => "Patient was assigned successfully!"
          ]);
        } else {
          echo json_encode([
            'status' => 0,
            "message" => "No available rooms!"
          ]);
        }
      } else {
        echo json_encode([
          'status' => 0,
          "message" => "Patient already occupies a room , you need to remove him from it and assign him to a new one"
        ]);
      }
    } else {
      echo json_encode([
        'status' => 0,
        'message' => "This patient doesn't exist!"
      ]);
    }
  } else {
    echo json_encode([
      'status' => 0,
      "message" => "Missing credentials"
    ]);
  }
} else {
  echo json_encode([
    'status' => '0',
    "message" => "Invalid request method"
  ]);
}

/**
 * 1- get the patient id from the request body
 * 2- get the room type from the request body
 * 3- check for the first room that has that room type and availability yes , if there's no return no empty room create one
 * 4- if a room was found , update the patient id , update the availability to yes , put the current date ,
 */
