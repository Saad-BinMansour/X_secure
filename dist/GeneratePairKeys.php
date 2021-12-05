<?php
      session_start();
      include "DB_connection.php";
      include 'functions.php';

      $userData= is_loggedIn($connection);
      $username=$userData['username'];
      
    // Becuase it's first time should Genearate Public and private keys
    if(isset($_POST['download_btn']))
    {
        $PK_userPath=$_POST['PK_userPath'];

        $config = array(
            "config" => "C:/xampp/apache/conf/openssl.cnf",
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
              
        // Create the private and public key
          $RSA_PairKeys = openssl_pkey_new($config);
            
        // Extract the private key from $RSA_PairKeys to $privateKey
          openssl_pkey_export($RSA_PairKeys, $privateKey,null,$config);
            
        // Extract the public key from $RSA_PairKeys to $publicKey
          $publicKey = openssl_pkey_get_details($RSA_PairKeys);
          $publicKey = $publicKey["key"];


        $sql_query = "UPDATE users SET PublicKey = '$publicKey' WHERE username = '$username'"; 
        $query_result= mysqli_query($connection,$sql_query);
        
        if($query_result)
        {
          echo('<script>alert("publickey is published !")</script>');
          $file=fopen($PK_userPath.'/'.$username."_PrivateKey.pem","a");
          fwrite($file,$privateKey);
          fclose($file); 
        header("Location: home.php");
        die;
        }else{
          echo("<script>alert('There is a problem with insert to the database!!')</script>");
        }
    }


?>


<!DOCTYPE>
<html>
    <header>
        <meta charset="UTF-8">
        <title>XSecure home</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
      <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
      <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
      <link rel="stylesheet" href="./style.css?version=2">
      
    </header>

    <body>
        <div class="pen-title">
          <h1> Before go to our applicatoin you should Enter your secret path to save it your in your computer<br/>Example to write Path:(<span> C:/xampp/htdocs/X_secure/keys/</span>) <br/> <br/></h1>

          <h1> Then press <span> "DWONLOAD"</span> button to save the private key and enter to our application </h1>
        </div>
        <div class="container">
            <div class="card"></div>
            <div class="card">

                <form method="POST">
                    <div class="input-container">
                        <input type="text" id="#{label}" name="PK_userPath" required="required"/>
                        <label for="#{label}">Enter Private key path</label>
                        <div class="bar"></div>
                    </div>
                    <div class="button-container">
                        <button name="download_btn"><span>Download</span></button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>