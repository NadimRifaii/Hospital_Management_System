<?php
include_once('../classes/user.php');
include_once('../classes/doctor.php');
include_once('../classes/admin.php');
include_once('../classes/patient.php');
include_once('../classes/token.php');

function addUser($user, $data)
{
  $user->setUserName($data->userName);
  $user->setLastName($data->lastName);
  $user->setRole($data->role);
  $user->setEmail($data->email);
  $user->setPassword($data->password);
  $user->createUser();
}
function addDoctor($doctor, $user, $data)
{
  $doctor->setUserId($user->getUserId());
  $doctor->setSpeciality($data->speciality);
  $doctor->createDoctor();
}
function addAdmin($admin, $user)
{
  $admin->setUserId($user->getUserId());
  $admin->createAdmin();
}
function addPatient($patient, $user)
{
  $patient->setUserId($user->getUserId());
  $patient->createPatient();
}
function jwtCreation($data, $userId, $id)
{
  $payload_info = ['user_id' => $userId, $data->role . '_id' => $id, 'user_name' => $data->userName, 'email' => $data->email, 'role' => $data->role];
  $secret = "this is a secret";
  $jwt = Token::Sign($payload_info, $secret);
  setcookie('jwt_cookie', $jwt, time() + 3600, '/');
  return $jwt;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the raw POST data
  $postData = file_get_contents("php://input");

  // Attempt to decode the JSON data
  $data = json_decode($postData);
  if (!empty($data->userName) && !empty($data->lastName) && !empty($data->role) && !empty($data->email) && !empty($data->password)) {
    if ($data->role == 'doctor' && empty($data->speciality)) {
      echo json_encode([
        'status' => 0,
        "Doctor should have a speciality"
      ]);
      exit;
    }
    $user = new User($db);
    $row = $user->checkExistingUser($data->email);
    if (!$row) {
      addUser($user, $data);
      if ($data->role == 'doctor') {
        $doctor = new Doctor($db);
        addDoctor($doctor, $user, $data);
        $jwt = jwtCreation($data, $doctor->getUserId(), $doctor->getDoctorId());
      } else if ($data->role == 'admin') {
        $admin = new Admin($db);
        addAdmin($admin, $user);
        $jwt = jwtCreation($data, $admin->getUserId(), $admin->getAdminId());
      } else {
        $patient = new Patient($db);
        addPatient($patient, $user);
        $jwt = jwtCreation($data, $patient->getUserId(), $patient->getPatientId());
      }
      echo json_encode([
        'status' => 1,
        "message" => 'user was successfully created',
        "jwt" => $jwt
      ]);
    } else {
      echo json_encode([
        'status' => '0',
        "message" => "Email address already exists",
        "user" => $row
      ]);
    }
  } else {
    echo json_encode([
      'status' => '0',
      "message" => "Missing credentials"
    ]);
  }
}
