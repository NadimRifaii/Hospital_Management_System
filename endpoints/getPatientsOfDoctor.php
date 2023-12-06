<?php
include_once('../database/database.php');
function getAllPatientsOfDoctor($db, $doctorId)
{
  $connection = $db->getConnection();
  $getAllDoctorsQuery = $connection->prepare("SELECT DISTINCT p.*
FROM patients p
JOIN medical_records mr ON p.patient_id = mr.patient_id
JOIN doctors d ON mr.doctor_id = d.doctor_id
WHERE d.doctor_id = ?
");
  $getAllDoctorsQuery->bind_param('i', $doctorId);
  $getAllDoctorsQuery->execute();
  $result = $getAllDoctorsQuery->get_result();
  $users = [];
  while ($row = $result->fetch_assoc()) {
    $users[] = $row;
  }
  return $users;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $data = json_decode(file_get_contents('php://input'));
  if (!empty($data->doctorId)) {
    echo json_encode([
      'doctor_id' => $data->doctorId,
      'patients' => getAllPatientsOfDoctor($db, $data->doctorId)
    ]);
  } else {
    echo json_encode([
      'status' => 0,
      'message' => "Doctor id is required"
    ]);
  }
} else {
  echo json_encode([
    'status' => 0,
    'message' => "Invalid request method!"
  ]);
}
