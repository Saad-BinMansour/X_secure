<?php
  session_start();
  include "DB_connection.php";
  include 'functions.php';

  $userData= is_loggedIn($connection);
?>

<!DOCTYPE>
<html>
    <header>
        <meta charset="UTF-8">
        <title>XSecure home</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
      <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
      <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
      <link rel="stylesheet" href="./style.css?version=3">
      
    </header>

    <body>
  
        <div class="NavContainer">
          <div class="NavCard"></div>
          <div class="NavCard">
            <h1 class="title">Home Page</h1>
              <div class="button-container">
                <button name="home_btn"><a href="home.php"><span>Home</span></a></button>
              </div>
              <div class="button-container">
                <button name="receivedmsg_btn"><a href="receivedMSGs.php"><span>Received Messages</span></a></button>
              </div>
              <div class="button-container">
                <button name="sendmsg_btn"><a href="sendMsgs.php"><span>Send Messages</span></a></button>
              </div>
              <div class="button-container">
                <button name="logout_btn"><a href="logout.php"><span>Logout</span></a></button>
              </div>
          </div>
        </div>

        <div class="pen-title">
          <h1>HEY...<span> <?php echo strtoupper($userData['username']); ?> </span><h1>
          <h1> Welcome to the <span>X SECURE company</span> application for secretly exchanging messages between users
             by generating public and private keys for each user and using them to encrypt AES key used to encrypt the original message</h1>
        </div>

    </body>
</html>