<?php
    session_start();
    include "config/connection.php";

    if(isset($_SESSION['username'])){
      if($_SESSION['username'] == 'admin1' || $_SESSION['username'] == 'admin'){
        header('Location: admin-page.php');
      }
      else{
        header('Location: user.php');
      }
    }
    
    $errors = array('username' => '', 'password' => '', 'authenticate' => '');
    $username = $password = '';

    if(isset($_POST['signin'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(empty($username)){
			$errors['username'] = 'Username is required';
        }
        if(empty($password)){
			$errors['password'] = 'Password is required';
        }

        
        if(! array_filter($errors)){
            $username = $conn->real_escape_string($username);
            $password = $conn->real_escape_string($password);
            
            //CHECK CORRECT USERNAME PASSWORD
            $query1 = "CALL check_admin_credentials('$username', '$password')";
            if ($conn->query($query1) === FALSE) {
              $errors['authenticate'] .= $conn->error;
            }
            else{
              $_SESSION['username'] = $username;
              header('Location: admin-page.php');
            }
            $conn->close();
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
</head>
<?php include "template/header.php" ?>

<div style="margin-top:100px;">
<form action="admin-login.php" method="POST">
    <h3 class="heading">Welcome Admin</h3>
  <label>
    <p class="label-txt">ENTER YOUR USERNAME</p>
    <input type="text" class="input" name="username" value="<?php echo htmlspecialchars($username) ?>">
    <div class="line-box">
      <div class="line"></div>
    </div>
    <p class= "bg-danger text-white"><?php echo htmlspecialchars($errors['username'])?></p>
  </label>
  <label>
    <p class="label-txt">ENTER YOUR PASSWORD</p>
    <input type="password" class="input" name="password" value="<?php echo htmlspecialchars($password) ?>">
    <div class="line-box">
      <div class="line"></div>
    </div>
    <p class= "bg-danger text-white"><?php echo htmlspecialchars($errors['password'])?></p>
  </label>
  <p class= "bg-danger text-white"><?php echo htmlspecialchars($errors['authenticate'])?></p>
  <button type="submit" name="signin" value="submit">Sign-In</button>

</form>
</div>
<?php include "template/footer.php" ?>

</html>