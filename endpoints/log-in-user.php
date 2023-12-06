<?php

include_once('../classes/user.php');
include_once('../classes/admin.php');
include_once('../classes/patient.php');
include_once('../classes/doctor.php');
include_once('../classes/token.php');
/**
 * "jwt": {
        "user_id": 73,
        "patient_id": 22,
        "user_name": "Hayssam",
        "email": "nadimrifaii33@gmail.com"
    }
 */
function jwtCreation($row, $obj)
{
  $id = $row['role'] . '_id';
  $payload_info = [
    'user_id' => $row['user_id'],
    $id => $obj[$row['role'] . '_id'],
    'user_name' => $row['user_name'],
    'email' => $row['email'],
    'role' => $row['role']
  ];
  $secret = "secret";
  $jwt = Token::sign($payload_info, $secret);
  setcookie('jwt_cookie', "$jwt", time() + 3600, '/');
  return $jwt;
}
function createObject($role, $db, $userId)
{
  switch ($role) {
    case 'admin':
      $obj = new Admin($db);
      $obj->setUserId($userId);
      $objRow = $obj->getAdminRow();
      return $objRow;
    case 'patient':
      $obj = new Patient($db);
      $obj->setUserId($userId);
      $objRow = $obj->getPatientRow();
      return $objRow;
    case 'doctor':
      $obj = new Doctor($db);
      $obj->setUserId($userId);
      $objRow = $obj->getDoctorRow();
      return $objRow;
  }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'));
  $allHeaders = getallheaders();
  if (!empty($data->email) && !empty($data->password)) {
    $user = new User($db);
    $row = $user->checkExistingUser($data->email, $data->password);
    if ($row) {
      $obj = createObject($row['role'], $db, $row['user_id']);
      $jwt = jwtCreation($row, $obj);
      echo json_encode([
        'message' => "User logged in",
        "request body" => $data,
        "jwt" => $jwt
      ]);
    } else {
      echo json_encode([
        'message' => "No such user"
      ]);
    }
  } else {
    echo json_encode([
      'message' => 'Missing credentials'
    ]);
  }
} else {
  echo json_encode([
    'message' => "User logged in"
  ]);
}
