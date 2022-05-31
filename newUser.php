<style>
    

    #box {
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

    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    //EVERYTHING ABOVE, and the catch block below, is part of a template that we
    //will use every time we want to connect to the database…
    //BELOW THIS is where you will vary the response...put your application logic between
    //this comment and the catch block below

    $user = $_GET["username"];
    $password = $_GET["password"];
    $email = $_GET["email"];


    $sql = "INSERT INTO `users`(`username`, `password`, `email`) VALUES ('$user', '$password', '$email')";

    $conn -> exec( $sql );

    print "
    <div id='box'>
    New User Created
    </div>

    ";

}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

