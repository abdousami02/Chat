<?php

include "function_&_config.php";

session_start();

// form SignUp Page
if(isset($_GET['signup']) ){

  if($_SERVER['REQUEST_METHOD'] != "POST"){
    echo "you don't have permition" ;

  }else{
    $fname =  $_POST['fname'];
    $lname =  $_POST['lname'];
    $email =  $_POST['email'];
    $pass =   $_POST['password'];
    $pass =   hash('sha256', $pass);

    if($fname == '' || $lname == '' || $email == '' || $pass == ''){
      $stat = 0;
      $msg =  "all input required!";

    }else{

      if(filter_var($email, FILTER_VALIDATE_EMAIL) != true){
        $stat = 0;
        $msg =  "email not valid";

      }else{
        $sql = $con->prepare("SELECT Email FROM chatUsers WHERE Email = ?");
        $sql->execute(array($email));
        if($sql->rowCount() > 0){
          $stat = 0;
          $msg = "This email already exist";
        
        }else{
          $image = $_FILES['image'];

          if($image['size'] == 0){
            $stat = 0;
            $msg = "Please upload image";

          }else{
            
            $img_name = $image['name'];
            $img_type = explode('/',$image['type'])[1];
            $img_size = $image['size'];
            $tmp_name = $image['tmp_name'];
            
            $ext = ['png', 'jpeg', 'jpg', 'jfif'];

            if(in_array($img_type, $ext) != true){
              $stat = 0;
              $msg =  "Please Slecte image file";

            }else{
              
              $new_img_name = time().$img_name;

              if(move_uploaded_file($tmp_name, "../images/".$new_img_name) != true){
                $stat = 0;
                $msg =  "Falid to file";

              }else{
                $rand_id = rand(time(), 10000000);
                
                $sql2 = $con->prepare("INSERT INTO `chatUsers` (UniqueID, Fname, Lname, Email, Password, img, status)
                            VALUES (:id , :fname , :lname , :email , :pass , :img , 1)");
                $sql2->execute(array(
                  "id" => $rand_id,
                  "fname" => $fname,
                  "lname" => $lname,
                  "email" => $email,
                  "pass" => $pass,
                  "img"  => $new_img_name
                ));
                
                if($sql2 != true){
                  $stat = 0;
                  $msg = "same thing wrong";

                }else{
                  $sql3 = $con->prepare("SELECT UniqueID, Email, Fname, Lname, img FROM chatUsers WHERE Email = ?");
                  $sql3->execute(array($email));
                  if($sql3->rowCount() > 0){

                    $user = $sql3->fetch();
                    $_SESSION['id'] = $user['UniqueID'];
                    $_SESSION['email'] = $user['Email'];
                    $_SESSION['fname'] = $user['Fname'];
                    $_SESSION['lname'] = $user['Lname'];
                    $_SESSION['img']   = $user['img'];

                    $stat = 1;
                    $msg = "sacces";

                  }
                }


              }
            }
          }
        }
      }
    }
    
    $out = [ 'status' => $stat, 'message' => $msg ];
    echo json_encode($out);
  }

//for LogIn Page
}elseif(isset($_GET['login'])){

  if($_SERVER['REQUEST_METHOD'] != "POST"){
    echo "you not have permition";

  }else{
    $email = $_POST['email'];
    $pass = hash('sha256',$_POST['password']);

    if(filter_var($email, FILTER_VALIDATE_EMAIL) != true){
      $stat = 0;
      $msg = "email not valid";

    }else{
      $sql1 = $con->prepare("SELECT Email FROM chatUsers WHERE Email = ?");
      $sql1->execute(array($email));
      if($sql1->rowCount() == 0){
        $stat = 0;
        $msg = "email Wrong";
        //echo "dd";

      }else{
        $sql2 = $con->prepare("SELECT UniqueID, Fname, Lname, img FROM chatUsers WHERE Email = ? AND Password = ? ");
        $sql2->execute(array($email, $pass));
        if($sql2->rowCount() == 0){
          $stat = 0;
          $msg = "password wrong";

        }else{
          $user = $sql2->fetch();
          $sql4 = $con->prepare("UPDATE chatUsers SET status = 1 WHERE Email = ?");
          $sql4->execute(array($email));
          
          $_SESSION['id'] = $user['UniqueID'];
          $_SESSION['fname'] = $user['Fname'];
          $_SESSION['lname'] = $user['Lname'];
          $_SESSION['img']   = $user['img'];
          
          $stat = 1;
          $msg = 'sacces';
        }
      }
      
    }
  }

  $out = ["status"=>$stat, "message"=>$msg];
  echo json_encode($out);

}elseif( isset($_GET['logout']) ){

  $id = $_SESSION['id'];

  $sql = $con->prepare("UPDATE chatUsers SET status = 0 WHERE UniqueID = ?");
  $sql->execute(array($id));
  
  session_unset();
  session_destroy();
  header("location: ../index.php");
  exit();
}


