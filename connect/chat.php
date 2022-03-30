<?php 
include "function_&_config.php";

session_start();

if(count($_SESSION) == 0 && $_SESSION['id'] == ''){
  echo "error permition";

}else{
  $id = $_SESSION['id'];

  // get user to page
  if(isset($_GET['getUser'])){
    $out = getUser();
    echo json_encode(strUser($out));
  

  // resulte of search
  }elseif(isset($_GET['searchUser'])){
    if($_SERVER['REQUEST_METHOD'] != "POST"){
      echo "you don't have permition";

    }else{
      $srch =  $_POST['search'];
      $sql = $con->prepare("SELECT UniqueID, Fname, Lname, img FROM chatUsers 
              WHERE Fname LIKE :srch OR Lname LIKE :srch ");
      $sql->execute(array('srch' => "%{$srch}%"));

      $out = $sql->fetchAll();
      echo json_encode(strUser($out));
    }

  // get user info
  }elseif(isset($_GET['getInfo'])){
    $id = $_GET['getInfo'];

    $user = getUser($id);

    $out = [
      'name' => $user['Fname']." ".$user["Lname"],
      'img' => $user['img'],
      'status' => $user['status'] == 1 ? "online" : "offline"
    ];
    echo json_encode($out);

  // show message to user
  }elseif(isset($_GET['showMessage'])){
    if($_SERVER['REQUEST_METHOD'] != "POST"){
      echo "Error Permition";
    
    }elseif($_POST['send'] != $id){
      $stat = 0;
      $msg_stat =  "error ID";
    
    }else{
      $send = $_POST['send'];
      $rec = $_POST['rec'];

      $sql = $con->prepare("SELECT * FROM chatMessage WHERE 
            ( send_msg_id = :send_id AND rec_msg_id = :rec_id ) 
            OR ( send_msg_id = :rec_id AND rec_msg_id = :send_id ) 
             ORDER BY msg_id DESC LIMIT 20");
      $sql->execute(array(
        "tim"     => "2022-03-30 08:56:52",
        "send_id" => $send,
        "rec_id"  => $rec
      ));
      $msg = $sql->fetchAll(PDO::FETCH_ASSOC);
      $msg = array_reverse($msg);
      $stat = 1;
      $out = [ "status" => $stat, "msgStatus" => $msg_stat, "message" => $msg];
      echo json_encode($out);
    }



  // send Message
  }elseif(isset($_GET['sendMessage'])){
    $msg_data = json_decode($_POST['send']);

    $send_id = trim($msg_data->send_id);
    $rec_id = trim($msg_data->rec_id);
    $msg = trim($msg_data->message);
    
    //print_r($msg_data->send_id);
    if($send_id == '' || $rec_id == '' || $msg == ''){
      $status = 0;
      $msg_stat = "Message field emty";
    
    }elseif($send_id != $id){
      session_unset();
      session_destroy();
      header("location: ../index.php");
    
    }else{

      $sql = $con->prepare("INSERT INTO chatMessage (send_msg_id, rec_msg_id, msg) VALUES (:send_id, :rec_id, :msg)");
      $sql->execute(array(
        "send_id" => $send_id, 
        "rec_id"  => $rec_id, 
        "msg"     => $msg
      ));

      if($sql->rowCount() > 0){
        $status = 1;
        $msg_stat = "sacces";

      }else{
        $status = 0;
        $msg_stat = "same thing wrong";
      }
    } 
    
    $out = ['status' => $status, "message" => $msg_stat];
    echo json_encode($out);

  
  // get last message
  }elseif(isset($_GET['getLastMessage'])){
    if($_SERVER['REQUEST_METHOD'] != "POST"){
      echo "Error Permition";
    
    }elseif($_POST['send'] != $id){
      $stat = 0;
      $msg_stat =  "error ID";
    
    }else{
      $send = $_POST['send'];
      $rec = $_POST['rec'];
      $time = $_POST['time'];

      $sql = $con->prepare("SELECT * FROM chatMessage WHERE (date BETWEEN :tim AND NOW()) AND
            ( ( send_msg_id = :send_id AND rec_msg_id = :rec_id ) 
            OR ( send_msg_id = :rec_id AND rec_msg_id = :send_id ) )
             ORDER BY msg_id DESC LIMIT 4");
      $sql->execute(array(
        "tim"     => $time,
        "send_id" => $send,
        "rec_id"  => $rec
      ));
      $msg = $sql->fetchAll(PDO::FETCH_ASSOC);
      $msg = array_reverse($msg);
      $stat = 1;
      $out = [ "status" => $stat, "msgStatus" => $msg_stat, "message" => $msg];
      echo json_encode($out);
    }

  
  
  
  
  }
}

