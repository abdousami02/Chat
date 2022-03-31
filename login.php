<?php
  session_start();

  if(count($_SESSION) > 0 ){
    header('location: user.php');
    exit();

  }else{
    include "include/header.php";
  }
?>

<body>
  <div class="wrapper">
    <section class="form signup">
      <header>Realtime Chat App</header>
      <form data-page="login">
        <div class="error-txt">This is an error message!</div>

        <div class="field input">
          <label>Email</label>
          <input type="email" name="email" placeholder="Enter Email">
        </div>
        <div class="field input">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enater password">
          <i class="fas fa-eye show"></i>
        </div>
        <div class="field submit">
          <input type="submit" value="Continue to Chat">
        </div>
      </form>
      <div class="link">Already signed up?<a href="index.php">Login now</a></div>
    </section>
  </div>




  <script src="js/main.js"></script>
</body>
</html>