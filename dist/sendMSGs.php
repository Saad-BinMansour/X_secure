<?php
  session_start();
  include "DB_connection.php";
  include 'functions.php';

  $userData= is_loggedIn($connection);
  $username=$userData['username'];

  if(isset($_POST['Send_btn']))
    {
        $send_to_user=str_replace(' ', '', $_POST['users_list']);
        $User_message=$_POST['User_message'];

        //$User_message=file_get_contents($User_message);

        //define type of cipher we use here AES-265 with CBC block mode
        $TypeOfcipher = "aes-256-CBC";

        //generate Random key to encrypt 
        $Encryption_random_key=openssl_random_pseudo_bytes(80);

        // Generate an initialization vector 
        $iv_size = openssl_cipher_iv_length($TypeOfcipher); 
        $iv = openssl_random_pseudo_bytes($iv_size); 

        //Data to encrypt 
         
        $encrypted_message = openssl_encrypt($User_message, $TypeOfcipher, $Encryption_random_key, 0, $iv); 

        $sql_query = "SELECT * FROM users WHERE username='$send_to_user'";
        $query_result= mysqli_query($connection,$sql_query);

        if($query_result&& mysqli_num_rows($query_result)>0)
        {
          
          $query_result=mysqli_fetch_assoc($query_result);
          $user_publickey= $query_result['PublicKey'];
          
          openssl_public_encrypt ($Encryption_random_key, $Encrypted_Key , $user_publickey, OPENSSL_PKCS1_PADDING);	
          openssl_public_encrypt ($iv, $Encrypted_Iv , $user_publickey, OPENSSL_PKCS1_PADDING);	

          $Encrypted_Key=base64_encode($Encrypted_Key);
          $Encrypted_Iv=base64_encode($Encrypted_Iv);


            $sql_query= "INSERT INTO `user_messages` (`id`, `username`, `user_message`, `msg_from`, `msg_key`, `msg_iv`) VALUES (NULL, '$send_to_user', '$encrypted_message', '$username', '$Encrypted_Key', '$Encrypted_Iv' )";

          $query_result= mysqli_query($connection,$sql_query);
          if($query_result)
          {
            echo('<script>alert("Message is sent succsesfuly !")</script>');
          }
          else{
            echo('<script>alert("Message is not sent !!'.mysqli_error($connection).' !")</script>');
            echo mysqli_error($connection);
          }

        }
        else{
          echo('<script>alert("Can not find user'.mysqli_error($connection).' !")</script>');
        }


        

    }

?>

<!DOCTYPE>
<html>
    <header>
        <meta charset="UTF-8">
        <title>XSecure Send Messages</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
      <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
      <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
      <link rel="stylesheet" href="./style.css?version=3">
      
    </header>

    <body>
  
        <div class="NavContainer">
          <div class="NavCard"></div>
          <div class="NavCard">
            <h1 class="title">Send messages</h1>
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
          <h1> Here you can send an encrypted message to any regeisted user:</h1>
          <h2> In bottom form you will find a text area with <span>"Enter Your message"</span> label, here put your message and then you should choose the user to whom you want to send the message securely from 
              <span>"message receiver user"</span> options then click  <span>"Send the message"</span> button to send the message</h2>
        </div>
        
        <div class="container">
            <div class="cardd"></div>
            <div class="cardd">

                <form method="POST">
                  <div style="  position: relative; margin: 0 60px 50px;">
                    <label style="Font-size:20px;">Enter Your message</label> <br/>
                    <textarea style="    margin: 10px 0px 50px; width: 580px;" cols="45" rows="15" name="User_message" required="required"></textarea>
                    
                    <br/><label style="Font-size:20px;">Choose the  message receiver user</label> <br/>
                    <div style="margin: 0 60px; text-align: center;">
                    <select style="margin: 20px 0px 50px; font-size: 1.5em; width: 200px; text-align-last: center" name="users_list" required="required">
                      <?php
                        $sql_query = "SELECT username FROM users ORDER BY username";
                        $query_result= mysqli_query($connection,$sql_query);
                    
                        if($query_result && mysqli_num_rows($query_result)>0)
                        {
                          while($rows= mysqli_fetch_assoc($query_result))
                          {
                            echo "<option value=\" ".$rows['username']. " \">".$rows['username']."</option>";
                          }
                        }
                      ?>
                    </select>
                    </div>
                    <div class="button-container">
                        <button name="Send_btn"><span>Send the Message</span></button>
                    </div>
                  </div>
                </form>
            </div>
        </div>
    </body>
</html>
<!-- echo ('<script>alert("selected user ='.$send_to_user .'\n The message='. $User_message .'!!")</script>') ; -->