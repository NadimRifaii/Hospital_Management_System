<?php
include_once('../classes/token.php');
if (isset($_COOKIE['jwt_cookie'])) {
  $secret = "secret";
  $result = Token::Verify($_COOKIE['jwt_cookie'], $secret);
  if (!$result || $result['role'] != 'admin') {
    header('Location:./index.html');
  }
} else {
  header('Location:./index.html');
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <div>
    <label for="patientId">Enter patient id</label>
    <input type="text" id="patientId">
  </div>
  <div>
    <label for="roomType">Enter room type</label>
    <input type="text" id="roomType">
  </div>
  <div>
    <label for="date">Enter entrance date</label>
    <input type="date" id="date" min="<?php echo date('Y-m-d'); ?>">
  </div>
  <div>
    <button>Assign</button>
    <script>
      const patientId = document.querySelector('#patientId');
      const roomType = document.querySelector('#roomType');
      const date = document.querySelector('#date')
      const assignBtn = document.querySelector('button');
      assignBtn.addEventListener('click', function() {
        const data = {
          "patientId": patientId.value,
          "roomType": roomType.value,
          "date": date.value
        }
        console.log(data);
        fetch('http://localhost/HospitalManagementSystem/routes/assign-room.php', {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data),
        }).then(response => {
          return response.json();
        }).then(data => {
          console.log(data);
        }).catch(error => {
          console.log(error);
        })
      })
    </script>
</body>

</html>