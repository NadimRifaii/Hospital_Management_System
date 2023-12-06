<?php
include_once('../classes/user.php');
include_once('../classes/patient.php');
function getAllTreatmentsOfPatient($db, $patientId)
{
  $connection = $db->getConnection();
  $getAllTreatmentsQuery = $connection->prepare("SELECT DISTINCT t.*
FROM treatments t
JOIN medical_records mr ON t.treatment_id = mr.treatment_id
WHERE mr.patient_id = ?");
  $getAllTreatmentsQuery->bind_param('i', $patientId);
  $getAllTreatmentsQuery->execute();
  $result = $getAllTreatmentsQuery->get_result();
  $treatments = [];
  while ($row = $result->fetch_assoc()) {
    $treatments[] = $row;
  }
  return $treatments;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $data = json_decode(file_get_contents('php://input'));
  if (!empty($data->patientId)) {
    $patient = new Patient($db);
    $patient->setPatientId($data->patientId);
    if ($patient->checkExistingPatient()) {
      echo json_encode([
        'patient_id' => $data->patientId,
        'treatments' => getAllTreatmentsOfPatient($db, $data->patientId)
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
