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

        $sqlFrom = "SELECT `userId` FROM `users` WHERE username='$from'";
        $fromUserIdRC = $conn -> query( $sqlFrom);
        $fromUserIdR = $fromUserIdRC -> fetch();
        $fromUserId = $fromUserIdR['userId'];

        $recipients = explode(", ", "$to");
        for($i = 0; $i < sizeOf($recipients); $i++) {
            $tempRecipientUN = $recipients[$i];
            $recipients[$i] = ($conn -> query("SELECT `userId` FROM `users` WHERE username='$tempRecipientUN'")) -> fetch()['userId'];
        }

        $conn -> exec( "INSERT INTO `messages`(`body`, `fromUserId`) VALUES ('$message', '$fromUserId')" );
        $messageId = $conn -> lastInsertId();

        foreach( $recipients as $recipient) {
            $conn -> exec ("INSERT INTO `messagerecipients`(`messageId`, `toUserId`) VALUES ('$messageId', '$recipient')");
        }
        
        print "
        <div id='box'>
        Your Message Has Been Sent
        </div>
        ";
    }
    catch (Exception $e) {
        if(str_contains($e, "From_Field_Blank")) {
          print "<div id='errorBox'>From Field Cannot Be Blank - New Message Panel</div>";
        }
        if(str_contains($e, "To_Field_Blank")) {
            print "<div id='errorBox'>To Field Cannot Be Blank - New Message Panel</div>";
        }
        if(str_contains($e, "Body_Field_Blank")) {
          print "<div id='errorBox'>Body Field Cannot Be Blank - New Message Panel</div>";
        }
    }
    
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>