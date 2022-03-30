<?php
$dsn = "mysql:host=db5006712685.hosting-data.io;dbname=dbs5554618";
$opt = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
$con = new PDO($dsn, "dbu2658258", "Abdou123.", $opt);

// function for transfer user to fronend page
function strUser($data){
  foreach($data as $elem){
    
    if($elem['UniqueID'] != $_SESSION['id']){
      $i = $elem['UniqueID'];
  
      $info[$i]['name'] = $elem['Fname']." ".$elem['Lname'];
      $info[$i]['img']  = $elem['img'];
      $info[$i]['stat'] = $elem['status'];
    }
  }
  return $info;
}


//function for get special user or all user
function getUser($id=''){
  global $con;
  if($id == ''){
    $sql = $con->prepare("SELECT UniqueID, Fname, Lname, img, status FROM chatUsers");
    $sql->execute();

    $out = $sql->fetchAll();
    return $out;
  
  }else{
    $sql = $con->prepare("SELECT UniqueID, Fname, Lname, img, status FROM chatUsers WHERE UniqueID = ?");
    $sql->execute(array($id));

    $out = $sql->fetch();
    return $out;
  
  }
}