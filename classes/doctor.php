<?php
class Doctor extends User
{
  private $doctorId;
  private $speciality;
  private $tableName;
  protected $availability;
  public function __construct($db)
  {
    parent::__construct($db);
    $this->tableName = 'doctors';
  }
  public function createDoctor()
  {
    $insertQuery = $this->connection->prepare("INSERT INTO $this->tableName (speciality,user_id) VALUES (?,?)");
    $insertQuery->bind_param('ss', $this->speciality, $this->userId);
    $insertQuery->execute();
    $this->fetchDoctorId();
  }
  public function updateAvailability()
  {
    $updateQuery = $this->connection->prepare("UPDATE $this->tableName SET availability=?");
    $updateQuery->bind_param('s', $this->availability);
    $updateQuery->execute();
  }
  public function getDoctorRow()
  {
    $getDoctorQuery = $this->connection->prepare("SELECT * FROM $this->tableName where user_id=?");
    $getDoctorQuery->bind_param('s', $this->userId);
    $getDoctorQuery->execute();
    $result = $getDoctorQuery->get_result();
    return $result->fetch_assoc();
  }
  public function checkExistingDoctor()
  {
    $checkQuery = $this->connection->prepare("SELECT * FROM $this->tableName where doctor_id=?");
    $checkQuery->bind_param('s', $this->doctorId);
    $checkQuery->execute();
    $result = $checkQuery->get_result();
    if ($result)
      return $result->fetch_assoc();
    return false;
  }
  public function checkDoctorAvailability()
  {
    $checkQuery = $this->connection->prepare("SELECT availability FROM $this->tableName where doctor_id=?");
    $checkQuery->bind_param('s', $this->doctorId);
    $checkQuery->execute();
    $checkQuery->bind_result($this->availability);
    $checkQuery->fetch();
  }
  private function fetchDoctorId()
  {
    $getIdQuery = $this->connection->prepare("SELECT doctor_id FROM doctors d,users u WHERE d.user_id=?");
    $getIdQuery->bind_param('s', $this->userId);
    $getIdQuery->execute();
    $getIdQuery->bind_result($this->doctorId);
    $getIdQuery->fetch();
  }
  public function setUserId($id)
  {
    $this->userId = $id;
  }
  public function getDoctorId()
  {
    return $this->doctorId;
  }
  public function setSpeciality($speciality)
  {
    $this->speciality = $speciality;
  }
  public function getSpeciality()
  {
    return $this->speciality;
  }
  public function setDoctorId($id)
  {
    $this->doctorId = $id;
  }
  public function getAvailability()
  {
    return $this->availability;
  }
  public function setAvailability($availability)
  {
    $this->availability = $availability;
  }
}
