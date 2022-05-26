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

    $from = $_GET["from"];
    $to = $_GET["to"];
    $message = $_GET["message"];

    $sqlFrom = "SELECT `userId` FROM `users` WHERE username='$from'";
    $fromUserIdRC = $conn -> query( $sqlFrom);
    $fromUserIdR = $fromUserIdRC -> fetch();
    $fromUserId = $fromUserIdR['userId'];

    $toUserId = $conn -> query("SELECT `userId` FROM `users` WHERE username='$to'") -> fetch()['userId'];

    $conn -> exec( "INSERT INTO `messages`(`body`, `fromUserId`) VALUES ('$message', '$fromUserId')" );

    $messageId = ($conn -> query("SELECT `messageId` FROM `messages` WHERE body='$message'")) -> fetch()['messageId'];

    $sqlMessageRecipients = "INSERT INTO `messagerecipients`(`messageId`, `toUserId`) VALUES ('$messageId', '$toUserId')";
    $conn -> exec ($sqlMessageRecipients);


    print "
    <div id='box'>
    Success
    </div>

    ";
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<a href="#"></a>
