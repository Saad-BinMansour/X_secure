<?php
  session_start();
  include "DB_connection.php";
  include 'functions.php';

  $userData= is_loggedIn($connection);
  $username=$userData['username'];
  $decrypted_message="";

  if(isset($_POST['decrypt']))
    {
        $PK_userPath=$_POST['PK_userPath'];
        $EncryptedMessage=$_POST['EncryptedMSG'];
        $EMessaeg_Key;
        $EMessaeg_IV;
        $TypeOfcipher = "aes-256-CBC";
        
        $userPrivateKey=file_get_contents($PK_userPath);

        $sql_query = "SELECT * FROM user_messages WHERE user_message='$EncryptedMessage'";
        $query_result= mysqli_query($connection,$sql_query);
    
        if($query_result && mysqli_num_rows($query_result)>0)
        {
            $query_result= mysqli_fetch_assoc($query_result);
            $EMessaeg_Key= base64_decode($query_result['msg_key']);
            $EMessaeg_IV= base64_decode($query_result['msg_iv']);

            $userPrivateKey = openssl_pkey_get_private($userPrivateKey);
            openssl_private_decrypt($EMessaeg_Key,$Messaeg_Key, $userPrivateKey,OPENSSL_PKCS1_PADDING);
            openssl_private_decrypt($EMessaeg_IV,$Messaeg_IV, $userPrivateKey,OPENSSL_PKCS1_PADDING);
            
            
            $decrypted_message = openssl_decrypt($EncryptedMessage, $TypeOfcipher, $Messaeg_Key, 0, $Messaeg_IV);
            
            //echo('<script>alert("DONE'.mysqli_error($connection).' !")</script>');
        }else{
            echo('<script>alert("Can not find user'.mysqli_error($connection).' !")</script>');
        }

            /*echo $EMessaeg_Key;
            echo "AFTER ENCRYPT".$Messaeg_Key;
            echo $EMessaeg_IV;
            echo "AFTER ENCRYPT".$Messaeg_IV;*/
    }

    //if(isset($_POST['deletebutton']))
    //{}


?>

<!DOCTYPE>
<html>
    <header>
        <meta charset="UTF-8">
        <title>XSecure Received messages</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
      <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
      <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
      <link rel="stylesheet" href="./style.css?version=5">
      
    </header>

    <body>
  
        <div class="NavContainer">
          <div class="NavCard"></div>
          <div class="NavCard">
            <h1 class="title">Received messages</h1>
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
          <h1> You can accsess to your encrypted received Messages:</h1>
          <h2> In bottom form you will find tabel with your encrypted received messages with Sender name to decrept the message you should copy the encrypted message and paste to 
              <span>"Message to decrypted"</span> feild and then should enter your private key file path in <span>"Private Key file path"</span> feild and should write it correctly for example 
              (<span>C:/X_secure/keys/<?php echo $userData['username']; ?>_PrivateKey.pem</span> )</h2>
        </div>
        
        <div class="container">
            <div class="cardd"></div>
            <div class="cardd">

                <form method="POST">
                  <div style="  position: relative; margin: 0 60px 50px;"> 
                    <label style="Font-size:20px; ">Tabel of your Messages:</label> <br/>
                  <table>
                        <tr>
                            <th>Sender name</th>
                            <th>Message content</th>
                           <!-- <th>Message key</th> -->
                            <!-- <th>Message IV key</th>-->
                        </tr>
                      <?php
                        $sql_query = "SELECT * FROM user_messages WHERE username='$username'";
                        $query_result= mysqli_query($connection,$sql_query);
                    
                        if($query_result && mysqli_num_rows($query_result)>0)
                        {
                          while($rows= mysqli_fetch_assoc($query_result))
                          {
                            echo "<tr>";
                            echo "<td>".$rows['msg_from']."</td>";
                            echo "<td>".$rows['user_message']."</td>";
                          //  echo "<td><button id='deletebutton'name=\"".$rows['id']."\"><span>Delete</span></button></td>";
                            //echo "<td>".$rows['msg_key']."</td>";
                            //echo "<td>".$rows['msg_iv']."</td>";
                            echo "</tr>";
                          }
                        }
                      ?>
                    </table>
                    <div class="input-container">
                        <input type="text" id="#{label}" name="PK_userPath" required="required"/>
                        <label for="#{label}">Private key file path</label>
                        <div class="bar"></div>
                    </div>
                    <div class="input-container">
                        <input type="text" id="#{label}" name="EncryptedMSG" required="required"/>
                        <label for="#{label}">Message to decrypted</label>
                        <div class="bar"></div>
                    </div>
                    <label style="Font-size:20px; ">Here is the message after decrypt</label></br>
                    <textarea cols="45" rows="15" name="DecryptedMSG"><?php if($decrypted_message!=null) {echo   $decrypted_message;}else{echo "";} ?></textarea>
                  </div>
                  <div class="button-container">
                        <button name="decrypt"><span>Decrypt the message</span></button>
                    </div>
                </form>
            </div>
        </div>

    </body>
</html>
<!-- echo ('<script>alert("selected user ='.$send_to_user .'\n The message='. $User_message .'!!")</script>') ; -->