<?php
include_once('../classes/user.php');
include_once('../classes/appoitment.php');
include_once('../classes/doctor.php');
include_once('../classes/patient.php');
function checkUsers($doctor, $patient, $data)
{
  $doctor->setDoctorId($data->doctorId);
  $patient->setPatientId($data->patientId);
  $doctorResult = $doctor->checkExistingDoctor();
  $patientResult = $patient->checkExistingPatient($data->patientId);
  return !$doctorResult || !$patientResult;
}
function changeDoctorAvailability($doctor)
{
  $doctor->setAvailability('no');
  $doctor->updateAvailability($doctor->getAvailability());
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'));
  if (!empty($data->doctorId) && !empty($data->patientId) && !empty($data->date)) {
    $doctor = new Doctor($db);
    $patient = new Patient($db);
    if (checkUsers($doctor, $patient, $data)) {
      echo json_encode([
        'status' => 0,
        'message' => "The doctor or the patient doesn't exist"
      ]);
    } else {
      $doctor->checkDoctorAvailability();
      if ($doctor->getAvailability() == 'yes') {
        changeDoctorAvailability($doctor);
        $appoitment = new Appointment($db);
        if ($appoitment->createAppoitment($data->doctorId, $data->patientId, $data->date)) {
          echo json_encode([
            'status' => 1,
            'message' => "Appoitment was successfully created"
          ]);
        } else {
          echo json_encode([
            'status' => 0,
            'message' => "The appoitment date is taken"
          ]);
        }
      } else {
        echo json_encode([
          'status' => 0,
          "message" => "This doctor is not available"
        ]);
      }
    }
  } else {
    echo json_encode([
      'status' => 0,
      'message' => 'Missing credentials'
    ]);
  }
} else {
  echo json_encode([
    'status' => '0',
    "message" => "Invalid request method"
  ]);
}
