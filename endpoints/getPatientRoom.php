<?php
include_once('../classes/user.php');
include_once('../classes/patient.php');
function getPatientRoom($db, $patientId)
{
  $connection = $db->getConnection();
  $getPatientRoomQuery = $connection->prepare("SELECT * FROM rooms WHERE patient_id=?");
  $getPatientRoomQuery->bind_param('i', $patientId);
  $getPatientRoomQuery->execute();
  $result = $getPatientRoomQuery->get_result();
  $rooms = [];
  while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
  }
  return $rooms;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $data = json_decode(file_get_contents('php://input'));
  if (!empty($data->patientId)) {
    $patient = new Patient($db);
    $patient->setPatientId($data->patientId);
    if ($patient->checkExistingPatient()) {
      echo json_encode([
        'patient_id' => $data->patientId,
        'rooms' => getPatientRoom($db, $data->patientId)
      ]);
    } else {
      echo json_encode([
        'status' => 0,
        'message' => "Patient doesn't exist"
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
    'status' => 0,
    'message' => "Invalid request method!"
  ]);
}
