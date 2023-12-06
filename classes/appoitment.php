<?php
class Appointment
{
  protected $appointmentId;
  protected $doctorId;
  protected $patientId;
  protected $appointmentDate;
  protected $status;
  protected $connection;
  protected $tableName;

  public function __construct($db)
  {
    $this->connection = $db->getConnection();
    $this->tableName = 'appoitments';
  }
  public function createAppoitment($doctorId, $patientId, $date)
  {
    if (!$this->checkAvailability($doctorId, $date)) {
      return false;
    }
    $insertQuery = $this->connection->prepare("INSERT INTO $this->tableName (doctor_id,patient_id,appoitment_date)
    VALUES (?,?,?)");
    $insertQuery->bind_param('sss', $doctorId, $patientId, $date);
    $insertQuery->execute();
    return true;
  }
  public function checkAvailability($doctorId, $date)
  {
    $dateQuery = $this->connection->prepare("SELECT * FROM $this->tableName where appoitment_date=? and doctor_id=? and status='not done'");
    $dateQuery->bind_param('ss', $date, $doctorId);
    $dateQuery->execute();
    $result = $dateQuery->get_result();
    if ($result->num_rows > 0) {
      return false;
    }
    return true;
  }
  public function setAppointmentId($appointmentId)
  {
    $this->appointmentId = $appointmentId;
  }

  public function setDoctorId($doctorId)
  {
    $this->doctorId = $doctorId;
  }

  public function setPatientId($patientId)
  {
    $this->patientId = $patientId;
  }

  public function setAppointmentDate($appointmentDate)
  {
    $this->appointmentDate = $appointmentDate;
  }

  public function setStatus($status)
  {
    $this->status = $status;
  }

  public function getAppointmentId()
  {
    return $this->appointmentId;
  }

  public function getDoctorId()
  {
    return $this->doctorId;
  }

  public function getPatientId()
  {
    return $this->patientId;
  }

  public function getAppointmentDate()
  {
    return $this->appointmentDate;
  }

  public function getStatus()
  {
    return $this->status;
  }
}
