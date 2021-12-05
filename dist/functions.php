<?php
    
    function is_loggedIn($connection)
    {
        if(isset($_SESSION['username']))
        {
            $username = $_SESSION['username'];
            $sql_query = "SELECT * FROM users WHERE username='$username'";

            $query_result= mysqli_query($connection,$sql_query);

            if($query_result && mysqli_num_rows($query_result)>0)
            {
                $UserData = mysqli_fetch_assoc($query_result);
                return $UserData;
            }
        }
    
        header("Location: index.php");
            die; 
        

    }

    function INDEX_is_loggedIn($connection)
    {
        if(isset($_SESSION['username']))
        {
            $username = $_SESSION['username'];
            $sql_query = "SELECT * FROM users WHERE username='$username'";

            $query_result= mysqli_query($connection,$sql_query);

            if($query_result && mysqli_num_rows($query_result)>0)
            {
                header("Location: home.php");
                die; 
            }
        }
    }

    function test($connection)
    {
        if(isset($_SESSION['username']))
        {
            $username = $_SESSION['username'];
            $sql_query = "SELECT * FROM users WHERE username='$username'";

            $query_result= mysqli_query($connection,$sql_query);

            if($query_result && mysqli_num_rows($query_result)>0)
            {
                $UserData = mysqli_fetch_assoc($query_result);
                return $UserData;
            }
        }

        

    }
?>