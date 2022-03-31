<?php
  session_start();

  if(count($_SESSION) > 0 ){
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];
    $img   = $_SESSION['img'];
    include "include/header.php";

  }else{
    header('location: index.php');
    exit();
  }
?>
<body>
  <div class="wrapper">
    <section class="user">
      <header>
        <div class="content">
          <img src="images/<?php echo $img; ?>" alt="">
          <div class="details">
            <span><?php echo $fname." ".$lname ?></span>
            <p>active now</p>
          </div>
        </div>
        <a href="connect/connect.php?logout" class="logout">LogOut</a>
      </header>
      <div class="search">
        <span class="text">Select an user to start chat</span>
        <input type="text" placeholder="Enter name to Search...">
        <button><i class="fas fa-search"></i></button>
        <div class="resulte">

          <div class="list-search">
            <a href="" data-href="chat.php" class="copy" data-class="res-user">
              <img src="" alt="user Image">
              <span class="name">abdou sami</span>
            </a>
          </div>
          
          <span class="no-res">No resalte</span>

        </div>
      </div>
      <div class="user-list">

        <a href="" data-href="chat.php" data-class="user-row" class="copy">
          <img src="" alt="">
          <div class="details">
            <span class="name">Abdou</span>
            <p class="last">This is test</p>
          </div>
          <span class="status-dot"></span>
        </a>

      </div>
      
    </section>
  </div>

  <script src="js/user.js"></script>
</body>
</html>