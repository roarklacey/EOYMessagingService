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
        $user = $_GET["username"];
        $password = $_GET["password"];
        $email = $_GET["email"];

        if ($user == "") {
            throw new Exception("User_Field_Blank");
        }
        if ($password == "") {
            throw new Exception("Password_Field_Blank");
        }
        if ($email == "") {
            throw new Exception("Email_Field_Blank");
        }
    
        $sql = "INSERT INTO `users`(`username`, `password`, `email`) VALUES ('$user', '$password', '$email')";
    
        $conn -> exec( $sql );
    
        print "
        <div id='box'>
        New User Created
        </div>
        ";
    }
    catch (Exception $e) {
        if(str_contains($e, "User_Field_Blank")) {
          print "<div id='errorBox'>Username Field Cannot Be Blank - New User Panel</div>";
        }
        if(str_contains($e, "Password_Field_Blank")) {
            print "<div id='errorBox'>Password Field Cannot Be Blank - New User Panel</div>";
        }
        if(str_contains($e, "Email_Field_Blank")) {
          print "<div id='errorBox'>Email Field Cannot Be Blank - New User Panel</div>";
        }
    }

}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>