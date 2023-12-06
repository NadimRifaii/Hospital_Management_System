<?php
include_once('../database/database.php');
class MedicalRecord
{
  protected $recordId;
  protected $patientId;
  protected $doctorId;
  protected $recordDate;
  protected $diagnosis;
  protected $treatmentId;
  protected $connection;
  protected $tableName;
  public function __construct($db)
  {
    $this->connection = $db->getConnection();
    $this->tableName = 'medical_records';
  }
  public function createRecord()
  {
    $insertQuery = $this->connection->prepare("INSERT INTO $this->tableName 
    (patient_id,doctor_id,record_date,diagnosis,treatment_id)
    VALUES(?,?,?,?,?)");
    $insertQuery->bind_param('ssssi', $this->patientId, $this->doctorId, $this->recordDate, $this->diagnosis, $this->treatmentId);
    $insertQuery->execute();
  }
  // Setters
  public function setRecordId($recordId)
  {
    $this->recordId = $recordId;
  }

  public function setPatientId($patientId)
  {
    $this->patientId = $patientId;
  }

  public function setDoctorId($doctorId)
  {
    $this->doctorId = $doctorId;
  }

  public function setRecordDate($recordDate)
  {
    $this->recordDate = $recordDate;
  }

  public function setDiagnosis($diagnosis)
  {
    $this->diagnosis = $diagnosis;
  }

  public function setTreatmentId($treatmentId)
  {
    $this->treatmentId = $treatmentId;
  }


  // Getters
  public function getRecordId()
  {
    return $this->recordId;
  }

  public function getPatientId()
  {
    return $this->patientId;
  }

  public function getDoctorId()
  {
    return $this->doctorId;
  }

  public function getRecordDate()
  {
    return $this->recordDate;
  }

  public function getDiagnosis()
  {
    return $this->diagnosis;
  }

  public function getTreatments()
  {
    return $this->treatmentId;
  }
}
