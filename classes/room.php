<?php
include_once('../database/database.php');
class Room
{
  protected $roomId;
  protected $patientId;
  protected $isOccupied;
  protected $roomType;
  protected $entranceDate;
  protected $exitDate;
  private $tableName;
  private $connection;
  public function __construct($db)
  {
    $this->connection = $db->getConnection();
    $this->tableName = 'rooms';
  }
  public function createRoom()
  {
    $insertQuery = $this->connection->prepare("INSERT INTO $this->tableName (room_type)
    VALUES (?)");
    $insertQuery->bind_param('s',  $this->roomType);
    $insertQuery->execute();
    $insertQuery->store_result();
  }
  public function getFirstAvailable()
  {
    $firstAvailableRoom = $this->connection->prepare("SELECT room_id FROM $this->tableName where is_occupied='no' and room_type=? LIMIT 1");
    $firstAvailableRoom->bind_param('s', $this->roomType);
    $firstAvailableRoom->execute();
    $result = $firstAvailableRoom->get_result();
    if ($result) {
      return $result->fetch_assoc();
    }
    return false;
  }
  //   UPDATE table_name
  // SET column1 = value1, column2 = value2, ...
  // WHERE condition;
  public function updateRoom($patientId, $date)
  {
    if ($row = $this->getFirstAvailable()) {
      $updateQuery = $this->connection->prepare("UPDATE $this->tableName SET is_occupied='yes',patient_id=?,entrance_date=? 
      WHERE room_id=?");
      $updateQuery->bind_param('sss', $patientId, $date, $row['room_id']);
      $updateQuery->execute();
      return true;
    } else {
      return false;
    }
  }
  public function checkExistingPatient($patientId)
  {
    $checkQuery = $this->connection->prepare("SELECT * FROM $this->tableName WHERE patient_id=?");
    $checkQuery->bind_param('s', $patientId);
    $checkQuery->execute();
    $result = $checkQuery->get_result();
    if ($result->num_rows > 0) {
      return true;
    }
    return false;
  }
  public function setRoomId($roomId)
  {
    $this->roomId = $roomId;
  }
  public function getRoomId()
  {
    return $this->roomId;
  }
  public function setPatientId($patientId)
  {
    $this->patientId = $patientId;
  }
  public function getPatientId()
  {
    return $this->patientId;
  }
  public function setIsOccupied($status)
  {
    $this->isOccupied = $status;
  }
  public function getIsOccupied()
  {
    return $this->isOccupied;
  }
  public function setRoomType($roomType)
  {
    $this->roomType = $roomType;
  }
  public function getRoomType()
  {
    return $this->roomType;
  }
  public function setEntranceDate($date)
  {
    $this->entranceDate = $date;
  }
  public function getEntranceDate()
  {
    return $this->entranceDate;
  }
  public function setExitDate($date)
  {
    $this->exitDate = $date;
  }
  public function getExitDate()
  {
    return $this->exitDate;
  }
}
