<?php
class Patient extends User
{
  private $patientId;
  private $tableName;
  public function __construct($db)
  {
    parent::__construct($db);
    $this->tableName = 'patients';
  }
  public function createPatient()
  {
    $insertQuery = $this->connection->prepare("INSERT INTO $this->tableName (user_id) VALUES (?)");
    $insertQuery->bind_param('s', $this->userId);
    $insertQuery->execute();
    $this->fetchPatientId();
  }
  public function fetchPatientId()
  {
    $getIdQuery = $this->connection->prepare("SELECT patient_id FROM $this->tableName d,users u WHERE d.user_id=?");
    $getIdQuery->bind_param('s', $this->userId);
    $getIdQuery->execute();
    $getIdQuery->bind_result($this->patientId);
    $getIdQuery->fetch();
  }
  public function getPatientRow()
  {
    $getPatientQuery = $this->connection->prepare("SELECT * FROM $this->tableName where user_id=?");
    $getPatientQuery->bind_param('s', $this->userId);
    $getPatientQuery->execute();
    $result = $getPatientQuery->get_result();
    $json = $result->fetch_assoc();
    return $json;
  }
  public function checkExistingPatient()
  {
    $checkQuery = $this->connection->prepare("SELECT * FROM $this->tableName where patient_id=?");
    $checkQuery->bind_param('s', $this->patientId);
    $checkQuery->execute();
    $result = $checkQuery->get_result();
    if ($result)
      return $result->fetch_assoc();
    return false;
  }
  public function setUserId($id)
  {
    $this->userId = $id;
  }
  public function setPatientId($id)
  {
    $this->patientId = $id;
  }
  public function getPatientId()
  {
    return $this->patientId;
  }
}
