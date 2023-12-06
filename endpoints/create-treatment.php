<?php
include_once('../classes/treatment.php');
function addTreatment($treatment, $data)
{
  $treatment->setTreatmentName($data->treatmentName);
  $treatment->setTreatmentDescription($data->treatmentDescription);
  $treatment->createTreatment();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'));
  if (!empty($data->treatmentName) && !empty($data->treatmentDescription)) {
    $treatment = new Treatment($db);
    addTreatment($treatment, $data);
    echo json_encode([
      'status' => "1",
      "message" => "Treatment was created successfully"
    ]);
  } else {
    echo json_encode([
      'status' => 0,
      'message' => 'missing cridentials'
    ]);
  }
} else {
  echo json_encode([
    'status' => "0",
    'message' => "endpoint is only for post methods"
  ]);
}
