<?php
    require_once 'index.php';
    $_POST = json_decode(file_get_contents('php://input'));
    if($_POST->type == 'signUp'){
        $fullname = $_POST->fullname;
        $email = $_POST->email;
        $phoneNum = $_POST->phone;
        $address = $_POST->address;
        $username = $_POST->username;
        $pword = password_hash($_POST->confirm_pword, PASSWORD_DEFAULT);
        $userImg = $_POST->userImg;
        $signUser = new SignUp;
        $signUser->insertNewUser($fullname, $email, $phoneNum, $address, $username, $pword, $userImg);
    }
    elseif($_POST->type == 'login'){
        $username = $_POST->username;
        $pword = $_POST->pword;
        $myLogin = new Login;
        $myLogin->checkMyUser($username, $pword);
    }
    elseif($_POST->type == 'allUsers'){
        $goAhead = new FetchAllData;
        $goAhead->getAllUsers();
    }
    elseif($_POST->type == 'messages'){
        $userId = $_POST->senderId;
        $myReceiver = $_POST->receiverId;
        $messageType = $_POST->msgType;
        $myMessage = trim(htmlspecialchars($_POST->message));
        $sendBool = $_POST->senderBoolean;
        $sendMyMsg = new Messages;
        $sendMyMsg->sendMessage($userId, $myReceiver, $messageType, $myMessage, $sendBool);
    }
    elseif($_POST->type == 'retrieveAllMsg'){
        $retrieve = new FetchAllData;
        $retrieve->getAllMessages();
    }
    elseif($_POST->type == 'changeBoolean'){
        $myMsgId = $_POST->msgId;
        $sendMyBool = $_POST->senderBoolean;
        $changeBool = new Messages;
        $changeBool->updateSenderBoolean($myMsgId, $sendMyBool);
    }

?>