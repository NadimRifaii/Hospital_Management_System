<?php
class Admin extends User
{
  private $adminId;
  private $tableName;
  public function __construct($db)
  {
    parent::__construct($db);
    $this->tableName = 'admins';
  }
  public function createAdmin()
  {
    $insertQuery = $this->connection->prepare("INSERT INTO $this->tableName (user_id) VALUES (?)");
    $insertQuery->bind_param('s', $this->userId);
    $insertQuery->execute();
    $this->setAdminId();
  }
  public function setAdminId()
  {
    $getIdQuery = $this->connection->prepare("SELECT admin_id FROM $this->tableName d,users u WHERE d.user_id=?");
    $getIdQuery->bind_param('s', $this->userId);
    $getIdQuery->execute();
    $getIdQuery->bind_result($this->adminId);
    $getIdQuery->fetch();
  }
  public function getAdminRow()
  {
    $getAdminQuery = $this->connection->prepare("SELECT * FROM $this->tableName where user_id=?");
    $getAdminQuery->bind_param('s', $this->userId);
    $getAdminQuery->execute();
    $result = $getAdminQuery->get_result();
    return $result->fetch_assoc();
  }
  public function setUserId($id)
  {
    $this->userId = $id;
  }
  public function getAdminId()
  {
    return $this->adminId;
  }
}
