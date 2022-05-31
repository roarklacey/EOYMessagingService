<style>
      
      #header {

        font-weight: 700;
        align-items: center;
        display: flex;
        justify-content: center; 

      }
      
      #box {

        background-color: gainsboro;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;    


      }

      #obj {
        background-color: white;
        position: relative;
        width: 50%;
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
      $username = $_GET["username"];
      if( $username == ""){
        throw new Exception("Username_Blank");
      }

      $userId = ($conn -> query("SELECT `userId` FROM `users` WHERE username='$username'")) -> fetch()['userId'];

      $messages = ($conn -> query("SELECT `body`, `fromUserId` FROM `messages` JOIN `messagerecipients` WHERE messagerecipients.toUserId = '$userId' AND messages.messageId = messagerecipients.messageId"));

      print " <div id='header'> To Username: " . $username . "</div>";

      if( ($messages -> rowCount()) == 0 ) {
        print "<div id='box'><div id='obj'>No Messages to " . $username . "</div></div>";
      }

      foreach ( $messages as $message) {
        $fromUserIdTemp = $message['fromUserId'];
        $usernameTemp = ($conn -> query("SELECT `username` FROM `users` WHERE userId='$fromUserIdTemp' ")) -> fetch()['username'];
        print "<div id='box'>";
        print "<div id='obj'>";
        print "<strong>" . $usernameTemp . ":</strong> ";
        print $message['body'];
        print "</div>";
        print "</div>";
      }
    }
    catch (Exception $e) {
      if(str_contains($e, "Username_Blank")) {
        print "<div id='errorBox'>Username Cannot Be Blank - Messages To Panel</div>";
      }
    }

    

}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<a href="#"></a>
