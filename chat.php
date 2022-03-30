<?php

include "connect/function_&_config.php";
session_start();

if(isset($_GET['id']) && isset($_SESSION['id']) ){

  $my_id = $_SESSION['id'];
  $user_id = $_GET['id'];

}elseif(isset($_SESSION['id'])){
  header("location: user.php");
  exit();

}else{
  header("location: index.php");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat</title>
  <link rel="stylesheet" href="css/all.css">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">

</head>
<body>
  <div class="wrapper">
    <section class="chat-area">
      <header class="user-row">
        <a href="user.php"><i class="fas fa-arrow-left back"></i></a>
        <a href="" data-id="<?php echo $user_id; ?>" class="user-rec">
          <img src="" alt="">
          <div class="details">
            <span class="name"></span>
            <p class="status">This is test</p>
          </div>
        </a>
        <span class="status-dot"></span>
      </header>
      
      <div class="chat-box">

        <div class="chat copy">
          <div class="details">
            <p class="message">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            <span class="date"></span>
          </div>
        </div>

        

      </div>
      <div action="" class="typing">
        <input type="text" data-id_rec="<?php echo $user_id; ?>" data-id_send="<?php echo $my_id; ?>" class="msg" placeholder="Type message">
        <button class="send-btn"><i class="fab fa-telegram-plane"></i></button>
      </div>
      
    </section>
  </div>

  <script src="js/chat.js"></script>
</body>
</html>