<?php
include_once('../classes/token.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $data = json_decode(file_get_contents('php://input'));
  if (!empty($data->jwt)) {
    $secret = "secret";
    if ($decodedToken = Token::Verify($data->jwt, $secret)) {
      echo json_encode(
        [
          "role" => $decodedToken['role']
        ]
      );
    } else {
      echo json_encode([
        'status' => 0,
        "message" => "Invalid token"
      ]);
    }
  } else {
    echo json_encode([
      'status' => 0,
      "message" => "Missing JWT token"
    ]);
  }
} else {
  echo json_encode([
    'status' => 0,
    'message' => "Invalid request method"
  ]);
}
