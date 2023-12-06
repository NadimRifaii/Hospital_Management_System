<?php
include_once('../classes/user.php');
include_once('../classes/patient.php');
function getAllAppoitmentsOfPatient($db, $patientId)
{
  $connection = $db->getConnection();
  $getAllPatientAppoitmentQuery = $connection->prepare("SELECT a.* FROM appoitments a WHERE a.patient_id = ?");
  $getAllPatientAppoitmentQuery->bind_param('i', $patientId);
  $getAllPatientAppoitmentQuery->execute();
  $result = $getAllPatientAppoitmentQuery->get_result();
  $patientAppoitments = [];
  while ($row = $result->fetch_assoc()) {
    $patientAppoitments[] = $row;
  }
  return $patientAppoitments;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $data = json_decode(file_get_contents('php://input'));
  if (!empty($data->patientId)) {
    $patient = new Patient($db);
    $patient->setPatientId($data->patientId);
    if ($patient->checkExistingPatient()) {
      echo json_encode([
        'patient_id' => $data->patientId,
        'Appoitments' => getAllAppoitmentsOfPatient($db, $data->patientId)
      ]);
    } else {
      echo json_encode([
        'status' => 0,
        'message' => "This patient doesn't exist"
      ]);
    }
  } else {
    echo json_encode([
      'status' => 0,
      'message' => "patient id is required"
    ]);
  }
} else {
  echo json_encode([
    'status' => 0,
    'message' => "Invalid request method!"
  ]);
}
