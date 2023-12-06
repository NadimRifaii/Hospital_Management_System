<?php
include_once('../database/database.php');
class User
{
  protected $userId;
  protected $userName;
  protected $lastName;
  protected $email;
  protected $role;
  protected $password;
  protected $connection;
  private $tableName;
  public function __construct($db)
  {
    $this->connection = $db->getConnection();
    $this->tableName = 'users';
  }
  public function createUser()
  {
    $insertQuery = $this->connection->prepare("INSERT INTO $this->tableName (user_name,last_name,role,email,password) VALUES (?,?,?,?,?)");
    $insertQuery->bind_param('sssss', $this->userName, $this->lastName, $this->role, $this->email, $this->password);
    $insertQuery->execute();
    $this->setUserId($this->email);
  }
  public function checkExistingUser($email)
  {
    $checkQuery = $this->connection->prepare("SELECT * FROM users WHERE email=?");
    $checkQuery->bind_param('s', $email);
    $checkQuery->execute();
    $result = $checkQuery->get_result();
    if ($result) {
      return $result->fetch_assoc();
    }
    return false;
  }
  private function setUserId($email)
  {
    $getIdQuery = $this->connection->prepare("SELECT user_id FROM users WHERE email=?");
    $getIdQuery->bind_param('s', $email);
    $getIdQuery->execute();
    $getIdQuery->bind_result($this->userId);
    $getIdQuery->fetch();
  }
  public function getUserId()
  {
    return $this->userId;
  }
  public function setUserName($name)
  {
    $this->userName = $name;
  }
  public function getUserName()
  {
    return $this->userName;
  }
  public function setLastName($lastName)
  {
    $this->lastName = $lastName;
  }
  public function getLastName()
  {
    return $this->lastName;
  }
  public function setRole($role)
  {
    $this->role = $role;
  }
  public function getRole()
  {
    return $this->role;
  }
  public function setEmail($email)
  {
    $this->email = $email;
  }
  public function getEmail()
  {
    return $this->email;
  }
  public function setPassword($password)
  {
    $this->password = $password;
  }
  public function getPassword()
  {
    return $this->password;
  }
}
