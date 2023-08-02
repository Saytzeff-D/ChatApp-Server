<?php
  header("Access-Control-Allow-Origin:http://localhost:4200");
  header("Access-Control-Allow-Headers: Content-Type");
    class Connection{
        public  $server = 'localhost';
        public $username = 'root';
        public $password = '';
        public $dbName = 'chatapp';
        public $connect;
        public function __construct()
        {
            $this->connect = new mysqli($this->server, $this->username, $this->password, $this->dbName);
        }
        
    }

    class SignUp extends Connection{
        public function insertNewUser($fullname, $email, $phoneNum, $address, $username, $pword, $userImg)
        {
            $verifyEmail = "SELECT * FROM users WHERE email = '$email'";
            $queryDB = $this->connect->query($verifyEmail);
            if ($queryDB->num_rows>0) {
                echo json_encode('Email Already exists');
            }
            else{
                $querySql = "INSERT INTO users (fullname, email, phone_num, address, username, pword, user_img) VALUES ('$fullname', '$email', '$phoneNum', '$address', '$username', '$pword', '$userImg')";
                $insertIntoDb = $this->connect->query($querySql);
                if($insertIntoDb){
                    echo json_encode('True');
                }
                else{
                    echo json_encode('False');
                }
            }
        }
    }
    
    class Login extends Connection{
        public function checkMyUser($username, $pword)
        {
            $sql = "SELECT user_id, pword FROM users where username = '$username' or email = '$username'";
            $fetchFromDb = $this->connect->query($sql);
            $arrDb = $fetchFromDb->fetch_assoc();
            $verify = password_verify($pword, $arrDb['pword']);
            if($verify){
                echo $arrDb['user_id'];
            }
            else{
                echo json_encode('False');
            }
        }
    }
    
    class FetchAllData extends Connection{
        public function getAllUsers()
        {
            $myQuery = "SELECT * FROM users";
            $fetchMyQuery = $this->connect->query($myQuery);
            echo json_encode($fetchMyQuery->fetch_all(MYSQLI_ASSOC));
        }
        public function getAllMessages()
        {
            $sql = "SELECT * FROM messages join users USING(user_id) ORDER BY msg_id ASC";
            $fetchMsg = $this->connect->query($sql);
            echo json_encode($fetchMsg->fetch_all(MYSQLI_ASSOC));
        }
    }

    class Messages extends FetchAllData{
        public function sendMessage($userId, $myReceiver, $messageType, $myMessage, $sendBool)        
        {
            $mySqlQuery = "INSERT INTO messages (user_id, receiver_id, msgType, msgContent, senderBoolean) VALUES ('$userId', '$myReceiver', '$messageType', '$myMessage', '$sendBool')";
            $queryDb = $this->connect->query($mySqlQuery);
            if($queryDb){
                $this->getAllMessages();
            }
            else{
                echo json_encode('Error');
            }
        }
        public function updateSenderBoolean($myMsgId, $sendMyBool)
        {
            $updateQuery = "UPDATE messages SET senderBoolean = '$sendMyBool' WHERE msg_id = '$myMsgId'";
            $queryMyDb = $this->connect->query($updateQuery);
            if($queryMyDb){
                echo json_encode('Fine');
            }
            else{
                echo json_encode('Bad');
            }
        }
    }
?>