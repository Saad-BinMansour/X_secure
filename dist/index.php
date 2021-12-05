<?php
  session_start();

  include "DB_connection.php";
  include 'functions.php';

  INDEX_is_loggedIn($connection);


  $errorMSG="";

  //If POST method is for Register Form 
  if(isset($_POST['Register_btn']))
  {
    //Get user inforamtion form the form.
    $username=$_POST['Register_Uname'];
    $Password=$_POST['Register_Password'];
    $ConfirmPassword=$_POST['Register_ConfirmPassword'];

    //check if user Password and user confirm password is match 
    if($Password== $ConfirmPassword)
    {
      $sql_query = "SELECT * FROM users WHERE username='$username'";
      $query_result= mysqli_query($connection,$sql_query);

      //check if the user already exists 
      if($query_result && mysqli_num_rows($query_result)>0)
      {
        $errorMSG="USER ALREADY EXISTS!!";   
      }else{

        //if 
        $Password= password_hash($Password, PASSWORD_DEFAULT);
        $sql_query = "INSERT INTO users (username, password) VALUES ('$username', '$Password')";
        $query_result= mysqli_query($connection,$sql_query);
        
        if($query_result)
        {
          echo('<script>alert("Regstration done!")</script>');
         
          $_SESSION['username']= $username;
          header("Location: GeneratePairKeys.php"); 
          die; 
        }else{
          echo("<script>alert('Regstration is falied Try again because database!!')</script>");
        }
      }
    } else{
      $errorMSG="THE PASSWORD CONFIRMATION DOES NOT MATCH!!";
    }
  }

  //If POST method is for login Form 
  if(isset($_POST['login_btn']))
  {
    $username=$_POST['Login_Uname'];
    $Password=$_POST['Login_Password'];

    $sql_query = "SELECT * FROM users WHERE username='$username'";
    $query_result= mysqli_query($connection,$sql_query);

    if($query_result && mysqli_num_rows($query_result)>0)
    {
      $UserData= mysqli_fetch_assoc($query_result);

      if(password_verify($Password,$UserData['password']))
      {
        $_SESSION['username']= $UserData['username'];
        header("Location: home.php");
        die;
      }else
      {
        $errorMSG="PASSWORD IS NOT CORRECT!!";
      }
      
    }else{
      $errorMSG="USER IS NOT REGISTERD!!";
    }
  }

?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>XSecure</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'><link rel="stylesheet" href="./style.css">

</head>
<body>
<!-- partial:index.partial.html -->
<!-- Mixins-->
<!-- Pen Title-->
<div class="pen-title">
   <h1>XSecure Messages sharing</h1><!--<span>Pen <i class='fa fa-code'></i> by <a href='http://andytran.me'>Andy Tran</a></span> -->
</div>
<!-- <div class="rerun"><a href="">Rerun Pen</a></div> -->
<div class="container">
  <div class="card"></div>
  <div class="card">
    <h1 class="title">Login</h1>
    <form method="POST">
      <div class="input-container">
        <input type="#{type}" id="#{label}" name="Login_Uname" required="required"/>
        <label for="#{label}">Username</label>
        <div class="bar"></div>
      </div>
      <div class="input-container">
        <input type="password" id="#{label}" name="Login_Password" required="required"/>
        <label for="#{label}">Password</label>
        <div class="bar"></div>
      </div>
      <div class="button-container">
        <button name="login_btn"><span>Go</span></button>
      </div>
    </form>
    <?php //if($passwordNOTmatch==true)
          //{
            echo("<div style= \"  margin: 40px 0 0;
              color: #ed2553;
              font-size: 24px;
              font-weight: 300;
              text-align: center;\"><p>".$errorMSG."</p></div>");
              $errorMSG="";
          // $passwordNOTmatch=false; 
          //}

          //if($UserExistErr==true)
          //{
            echo("<div style= \"  margin: 40px 0 0;
              color: #ed2553;
              font-size: 24px;
              font-weight: 300;
              text-align: center;\"><p>".$errorMSG."</p></div>");
              $errorMSG="";
          //  $UserExistErr=false; 
          //}
            
    ?> 
  </div>
  <div class="card alt">
    <div class="toggle"></div>
    <h1 class="title">Register
      <div class="close"></div>
    </h1>
    <form method="POST">
      <div class="input-container">
        <input type="#{type}" id="#{label}" name="Register_Uname" required="required"/>
        <label for="#{label}">Username</label>
        <div class="bar"></div>
      </div>
      <div class="input-container">
        <input type="password" id="#{label}" name="Register_Password" required="required"/>
        <label for="#{label}">Password</label>
        <div class="bar"></div>
      </div>
      <div class="input-container">
        <input type="password" id="#{label}" name="Register_ConfirmPassword" required="required"/>
        <label for="#{label}">Repeat Password</label>
        <div class="bar"></div>
      </div>
      <div class="button-container">
        <button name="Register_btn"><span>Next</span></button>
      </div>
    </form>
    <?php //if($passwordNOTmatch==true)
          //{
            echo("<div style= \"  margin: 40px 0 0;
              color: #ed2553;
              font-size: 24px;
              font-weight: 300;
              text-align: center;\"><p>".$errorMSG."</p></div>");
              $errorMSG="";
          // $passwordNOTmatch=false; 
          //}

          //if($UserExistErr==true)
          //{
            echo("<div style= \"  margin: 40px 0 0;
              color: #ed2553;
              font-size: 24px;
              font-weight: 300;
              text-align: center;\"><p>".$errorMSG."</p></div>");
              $errorMSG="";
          //  $UserExistErr=false; 
          //}
            
    ?> 
  </div>
</div>
<!-- Portfolio<a id="portfolio" href="http://andytran.me/" title="View my portfolio!"><i class="fa fa-link"></i></a>-->
<!-- CodePen<a id="codepen" href="https://codepen.io/andytran/" title="Follow me!"><i class="fa fa-codepen"></i></a>-->
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script><script  src="./script.js"></script>

</body>
</html>
