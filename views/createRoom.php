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
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <div>
    <label for="room-type">Enter room type:</label>
    <input type="text" id="room-type">
  </div>
  <button>Create Room</button>
  <script>
    const roomType = document.querySelector('#room-type');
    const createBtn = document.querySelector('button');
    createBtn.addEventListener('click', function() {
      const data = {
        "roomType": roomType.value
      }
      fetch(`http://localhost/hospitalManagementSystem/routes/add-room.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        }).then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.log(error));
    })
  </script>
</body>

</html>