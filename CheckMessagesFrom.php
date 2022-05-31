<style>

      #header {

        font-weight: 700;
        align-items: center;
        display: flex;
        justify-content: center; 

      }
      
      #box {

        background-color: gainsboro;
        /* outline: 1px solid black; */
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

    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    //EVERYTHING ABOVE, and the catch block below, is part of a template that we
    //will use every time we want to connect to the database…
    //BELOW THIS is where you will vary the response...put your application logic between
    //this comment and the catch block below

    try {
      $username = $_GET["username"];
      if( $username == ""){
        throw new Exception("Username_Blank");
      }

      $userId = ($conn -> query("SELECT `userId` FROM `users` WHERE username='$username'")) -> fetch()['userId'];
      // $userId = $userIdTemp -> fetch()['userId'];

      $messages = ($conn -> query("SELECT `body`, `messageId` FROM `messages` WHERE messages.fromUserId = '$userId'"));

      print "<div id='header'> From Username: " . $username . "</div>";
        
      if( $messages == "" ) {
        print "<div id='box'><div id='obj'>No Messages</div></div>";
      }
      foreach ( $messages as $message) {
        $messageIdTemp = $message['messageId'];
        $recipientUserIds = ($conn -> query("SELECT `toUserId` FROM `messageRecipients` WHERE `messageId` = '$messageIdTemp' "));
        print "<div id='box'>";
        print "<div id='obj'>";
        print "<strong>To ";
        $firstTimeRecipientLoop = True;
        foreach( $recipientUserIds as $recipientUserId) {
          if ($firstTimeRecipientLoop) {
            $firstTimeRecipientLoop = False;
          }
          else {
            print ", ";
          }
          $idTemp = $recipientUserId['toUserId'];
          print ($conn -> query("SELECT `username` FROM `users` WHERE `userId` = '$idTemp'"))->fetch()['username'];
        }
        print "</strong>: ";
        print $message['body'];
        print "</div>";
        print "</div>";
      }
    }
    catch (Exception $e) {
      if(str_contains($e, "Username_Blank")) {
        print "<div id='errorBox'>Username Cannot Be Blank - Messages From Panel</div>";
      }
    }

    

}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<a href="#"></a>
