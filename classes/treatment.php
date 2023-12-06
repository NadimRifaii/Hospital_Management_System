<?php
include_once('../database/database.php');
class Treatment
{
  protected $treatmentId;
  protected $treatmentName;
  protected $treatmentDescription;
  protected $connection;
  protected $tableName;
  public function __construct($db)
  {
    $this->connection = $db->getConnection();
    $this->tableName = 'treatments';
  }
  public function createTreatment()
  {
    $insertQuery = $this->connection->prepare("INSERT INTO $this->tableName (treatment_id,treatment_name,treatment_description)
    VALUES (?,?,?)");
    $insertQuery->bind_param('sss', $this->treatmentId, $this->treatmentName, $this->treatmentDescription);
    $insertQuery->execute();
  }
  public function checkExistingTreatment()
  {
    $checkQuery = $this->connection->prepare("SELECT treatment_id FROM $this->tableName where treatment_id=?");
    $checkQuery->bind_param('s', $this->treatmentId);
    $checkQuery->execute();
    $result = $checkQuery->get_result();
    if ($result->num_rows > 0) {
      return true;
    }
    return false;
  }
  public function setTreatmentId($id)
  {
    $this->treatmentId = $id;
  }
  public function getTreatmentId()
  {
    return $this->treatmentId;
  }
  public function setTreatmentName($name)
  {
    $this->treatmentName = $name;
  }

  public function getTreatmentName()
  {
    return $this->treatmentName;
  }

  public function setTreatmentDescription($description)
  {
    $this->treatmentDescription = $description;
  }

  public function getTreatmentDescription()
  {
    return $this->treatmentDescription;
  }
}
