<style>
      body {
        background-color: gainsboro;
      }

      #header {

        background-color: gainsboro;

        outline: 1px solid black;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;        

      }
      
      #box {

        background-color: gainsboro;
        /* outline: 1px solid black; */
        padding: 5px;
        display: flex;
        text-align: center;
        align-items: center;
        justify-content: center;    


      }

      #obj {
        background-color: white;
        position: relative;
        width: 50%;
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

    $username = $_GET["username"];
    // $to = $_POST["to"];

    $userId = ($conn -> query("SELECT `userId` FROM `users` WHERE username='$username'")) -> fetch()['userId'];

    $messages = ($conn -> query("SELECT `body`, `fromUserId` FROM `messages` JOIN `messagerecipients` WHERE messagerecipients.toUserId = '$userId' AND messages.messageId = messagerecipients.messageId"));

    print " <h1 id='header'> To Username: " . $username . "</h1>";
    foreach ( $messages as $message) {
      $fromUserIdTemp = $message['fromUserId'];
      $usernameTemp = ($conn -> query("SELECT `username` FROM `users` WHERE userId='$fromUserIdTemp' ")) -> fetch()['username'];
      print "<div id='box'>";
      print "<div id='obj'>";
      print $usernameTemp . ": ";
      print $message['body'];
      print "</div>";
      print "</div>";
    }

}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<a href="#"></a>
