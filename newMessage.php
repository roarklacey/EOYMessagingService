<style>
    #box {
        font-weight: 700;
        align-items: center;
        display: flex;
        justify-content: center; 
        padding: 5px;
        color: #535353;
        font-family: Verdana;
        font-size: 30px;
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
        
        $from = $_GET["from"];
        $to = $_GET["to"];
        $message = $_GET["message"];
        if ($from == "") {
            throw new Exception("From_Field_Blank");
        }
        if ($to == "") {
            throw new Exception("To_Field_Blank");
        }
        if ($message == "") {
            throw new Exception("Body_Field_Blank");
        }

        $fromUserIdTemp = ($conn -> query("SELECT `userId` FROM `users` WHERE username='$from'"));
        if( ($fromUserIdTemp -> rowCount() ) == 0) {
            throw new Exception("From_Field_Invalid");
        }
        $fromUserId = $fromUserIdTemp -> fetch()['userId'];

        $recipients = explode(", ", "$to");
        for($i = 0; $i < sizeOf($recipients); $i++) {
            $tempRecipientUN = $recipients[$i];
            $recipientUserIdTemp = ($conn -> query("SELECT `userId` FROM `users` WHERE username='$tempRecipientUN'"));
            if( ($recipientUserIdTemp -> rowCount() ) == 0) {
                throw new Exception("To_Field_Invalid");
            }
            $recipients[$i] =  $recipientUserIdTemp -> fetch()['userId'];
        }

        $conn -> exec( "INSERT INTO `messages`(`body`, `fromUserId`) VALUES ('$message', '$fromUserId')" );
        $messageId = $conn -> lastInsertId();

        foreach( $recipients as $recipient) {
            $conn -> exec ("INSERT INTO `messagerecipients`(`messageId`, `toUserId`) VALUES ('$messageId', '$recipient')");
        }
        
        print "<div id='box'>Your Message Has Been Sent</div>";
    }
    catch (Exception $e) {
        if(str_contains($e, "From_Field_Blank")) {
          print "<div id='errorBox'>From Field Blank - New Message Panel</div>";
        }
        if(str_contains($e, "To_Field_Blank")) {
            print "<div id='errorBox'>To Field Blank - New Message Panel</div>";
        }
        if(str_contains($e, "Body_Field_Blank")) {
          print "<div id='errorBox'>Body Field Blank - New Message Panel</div>";
        }
        if(str_contains($e, "From_Field_Invalid")) {
            print "<div id='errorBox'>From Field Invalid - New Message Panel</div>";
        }
        if(str_contains($e, "To_Field_Invalid")) {
            print "<div id='errorBox'>To Field Invalid - New Message Panel</div>";
        }
    }
    
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>