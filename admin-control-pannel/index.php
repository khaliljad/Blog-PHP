<?php   
        session_start();
        $noNav = '';
        $title = 'Login';
        
        
        if(isset($_SESSION['userName'])) {
            header('Location:dashbord.php');
        }
        
        include 'init.php';
            
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userName = $_POST['userName'];
            $mdp = $_POST['Password'];
            $hashmdp = sha1($mdp);


        $sql = 'SELECT id, userName, motpass FROM users WHERE userName = ? AND motpass = ? AND groupID = 1 LIMIT 1';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($userName, $hashmdp)); 

        $row = $stmt->fetch();

        $count = $stmt->rowCount();

        if( $count > 0 ) {
            $_SESSION['userName'] = $userName;
            $_SESSION['userId'] = $row['id'];
            header('Location: dashbord.php');
            exit;
        }
    }
?>

<div class="left">
    <img src="theme/images/woj.jpg" alt="login-image">
</div>

<div class="right">
    <h1 id="login-title">Login To continue</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="text" name="userName" id='login-user-name' placeholder="userName" autocomplete="off" required="required">
        <div class="password">
            <input type="password" name="Password" placeholder="Password" required="required">
            <i id="eyepass" class="far fa-eye-slash"></i>
        </div>
        <label for="checkbox" class="label-check">
            <div class="rem-left">
                <input type="checkbox" name="checkbox" id="check">
                Remember me
            </div>
            <div class="forgot-right">
                <a href="#" class="forgot">Forgot Password?</a href="#">
            </div>
        </label>
        <div class="signing">
            <input type="submit" name="sabmit" id="submit" value="Sign In">
            <a id="a-submit" href="#">Sign Up<i class="fas fa-long-arrow-alt-right"></i></a>
        </div>
    </form>
</div>




<?php include $tpl . "footer.php "?>
