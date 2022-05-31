<style>
    #box {
        background-color: gainsboro;
        align-items: center;
        display: flex;
        justify-content: center;
        font-weight: 700;
    }
    #errorBox {
        background-color: gainsboro;
        align-items: center;
        display: flex;
        justify-content: center;
        font-weight: 700;
        color: red;
    }
</style>

<?php
//See original: https://www.w3schools.com/php/php_mysql_connect.asp
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "chatlog";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $username = $_GET["username"];
        $password = $_GET["password"];
        $email = $_GET["email"];

        if ($username == "") {
            throw new Exception("Username_Field_Blank");
        }
        if ($password == "") {
            throw new Exception("Password_Field_Blank");
        }
        if ($email == "") {
            throw new Exception("Email_Field_Blank");
        }

        $userIdTemp = ($conn -> query("SELECT `userId` FROM `users` WHERE username='$username'"));
         if( !(($userIdTemp -> rowCount() ) == 0)) {
            throw new Exception("Username_Field_Taken");
        }
        $emailTemp = ($conn -> query("SELECT `email` FROM `users` WHERE email='$email'"));
         if( !(($emailTemp -> rowCount() ) == 0)) {
            throw new Exception("Email_Field_Taken");
        }
        
        $conn -> exec( "INSERT INTO `users`(`username`, `password`, `email`) VALUES ('$username', '$password', '$email')" );
    
        print "<div id='box'>New User Created</div>";
    }
    catch (Exception $e) {
        if(str_contains($e, "Username_Field_Blank")) {
          print "<div id='errorBox'>Username Field Blank - New User Panel</div>";
        }
        if(str_contains($e, "Password_Field_Blank")) {
            print "<div id='errorBox'>Password Field Blank - New User Panel</div>";
        }
        if(str_contains($e, "Email_Field_Blank")) {
          print "<div id='errorBox'>Email Field Blank - New User Panel</div>";
        }
        if(str_contains($e, "Username_Field_Taken")) {
            print "<div id='errorBox'>Username Field Taken - New User Panel</div>";
        }
        if(str_contains($e, "Email_Field_Taken")) {
            print "<div id='errorBox'>Email Field Taken - New User Panel</div>";
        }
    }

}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>