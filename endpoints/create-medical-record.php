<?php
include_once('../classes/user.php');
include_once('../classes/patient.php');
include_once('../classes/doctor.php');
include_once('../classes/medicalRecord.php');
include_once('../classes/treatment.php');
function checkUsers($doctor, $patient, $data)
{
  $doctor->setDoctorId($data->doctorId);
  $patient->setPatientId($data->patientId);
  $doctorResult = $doctor->checkExistingDoctor();
  $patientResult = $patient->checkExistingPatient($data->patientId);
  return !$doctorResult || !$patientResult;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'));
  if (!empty($data->doctorId) && !empty($data->patientId) && !empty($data->diagnosis) && !empty($data->treatmentId) && !empty($data->date)) {
    $patient = new Patient($db);
    $doctor = new Doctor($db);
    $treatment = new Treatment($db);
    $treatment->setTreatmentId($data->treatmentId);
    if (!$treatment->checkExistingTreatment()) {
      echo json_encode([
        'status' => 0,
        'message' => "This treatment doesn't exist"
      ]);
    } else {
      if (!checkUsers($doctor, $patient, $data)) {
        $record = new MedicalRecord($db);
        $record->setDoctorId($data->doctorId);
        $record->setPatientId($data->patientId);
        $record->setRecordDate($data->date);
        $record->setTreatmentId($data->treatmentId);
        $record->setDiagnosis($data->diagnosis);
        $record->createRecord();
        echo json_encode([
          'status' => 1,
          'message' => "Medical record creates successfully"
        ]);
      } else {
        echo json_encode([
          'status' => 0,
          'message' => "The patient or the doctor doesn't exist"
        ]);
      }
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
