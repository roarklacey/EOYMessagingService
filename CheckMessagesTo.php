<style>
      #header {
        font-weight: 700;
        align-items: center;
        display: flex;
        justify-content: center; 
        padding: 5px;
        color: #535353;
        font-family: Verdana;
        font-size: 30px;
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
        overflow-x: auto;
        padding: 5px;
        color: #535353;
        font-family: Verdana;
      }
      #errorBox {
        font-weight: 700;
        align-items: center;
        display: flex;
        justify-content: center; 
        padding: 5px;
        color: red;
        font-family: Verdana;
        font-size: 30px;
      }
      #usernameSpan {
      color: skyblue;
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
        throw new Exception("Username_Field_Blank");
      }

      $userIdTemp = ($conn -> query("SELECT `userId` FROM `users` WHERE username='$username'"));
      if( ($userIdTemp -> rowCount() ) == 0) {
        throw new Exception("Username_Field_Invalid");
      }
      $userId = $userIdTemp -> fetch()['userId'];

      $messages = ($conn -> query("SELECT `body`, `fromUserId`, `dateTime` FROM `messages` JOIN `messagerecipients` WHERE messagerecipients.toUserId = '$userId' AND messages.messageId = messagerecipients.messageId ORDER BY `dateTime` DESC"));

      print " <div id='header'> To <span id='usernameSpan'>&nbsp;" . $username . "</span></div>";

      if( ($messages -> rowCount()) == 0 ) {
        print "<div id='box'><div id='obj'>No Messages to " . $username . "</div></div>";
      }

      foreach ( $messages as $message) {
        $fromUserIdTemp = $message['fromUserId'];
        $usernameTemp = ($conn -> query("SELECT `username` FROM `users` WHERE userId='$fromUserIdTemp' ")) -> fetch()['username'];
        print "<div id='box'>";
        print "<div id='obj'>";
        print "<strong>From <span id='usernameSpan'>" . $usernameTemp . "</span> at " . $message['dateTime'] . "</strong><br>";
        print $message['body'];
        print "</div>";
        print "</div>";
      }
    }
    catch (Exception $e) {
      if(str_contains($e, "Username_Field_Blank")) {
        print "<div id='errorBox'>Username Field Blank - Messages To Panel</div>";
      }
      if(str_contains($e, "Username_Field_Invalid")) {
        print "<div id='errorBox'>Username Field Invalid - Messages To Panel</div>";
      }
    }

}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>