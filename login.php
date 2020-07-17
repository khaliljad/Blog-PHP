<?php   

    session_start();
    $noNav = '';
    $title = 'login';
    
    if(isset($_SESSION['userid'])) {
        header('location: home.php');
    }
    
    include 'init.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        //******* login ********\\
        if (isset($_POST['logSabmit'])) {

            $logName = $_POST['logName'];
            $logPassword = $_POST['logPassword'];
            $hashPassword = sha1($logPassword);
        
            $logSql = "SELECT * FROM users WHERE userName = ? AND motpass = ? LIMIT 1";
            $logStmt = $conn->prepare($logSql);
            $logStmt->execute(array($logName, $hashPassword));
            $rows = $logStmt->fetch();
            $logCount = $logStmt->rowCount();

            // echo $logCount;
        
            if($logCount > 0) {
                $_SESSION['userid'] = $rows['id'];
                header('location: home.php');
                exit;
            }
        }


        //******* register ********\\
        if (isset($_POST['regSabmit'])) {

            // -------------------------------- check if user exist --------------------------

            $lastName = $_POST['last-name'];
            $firstName = $_POST['first-name'];
            $email    = $_POST['email'];
            $password = $_POST['regPassword'];
            $userName = $_POST['userName'];
            $hashmdp = sha1($password);

            $regSql = "INSERT INTO users (nom, prenom, email, motpass, userName, date) VALUE (:nom, :prenom, :email, :mdp, :username, now())";
            $regStmt = $conn->prepare($regSql);
            $regStmt->execute(array(
                'nom' => $lastName,
                'prenom' => $firstName,
                'email' => $email,
                'mdp' => $hashmdp,
                'username' => $userName
            ));

        }



    }


    

?>

<nav>
    <div class="nav-flex">
        <div id="title">
            <a href="home.php">Khalil Blog</a>
        </div>

</nav>  

<div class="left">
    <img src="theme/images/woj.jpg" alt="login-image">
</div>

<div class="right">
    <div id="login">
        <h1 id="login-title">Login To continue</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="logName" class='login-user-name' placeholder="User Name" autocomplete="off" required="required">
            <div class="password">
                <input type="password" name="logPassword" placeholder="Password" required="required">
                <i class="far fa-eye-slash"></i>
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
                <input type="submit" name="logSabmit" class="submit" value="Sign In">
                <a class="a-submit" id="signUp" href="#">Sign Up<i class="fas fa-long-arrow-alt-right"></i></a>
            </div>
        </form>
    </div>

    <div class="hidden" id="registred">
        <h1 id="login-title">Create your account</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

            <div class="flex-input">
                <input type="text" name="first-name" class='login-user-name login-name' placeholder="First Name" autocomplete="off" required="required">
                <input type="text" name="last-name" class='login-user-name login-name'  placeholder="Last Name" autocomplete="off" required="required">
            </div>

            <div class="larg-input">
                <input type="text" name="email" class='login-user-name' placeholder="Email Address" autocomplete="off" required="required">
                <input type="text" name="userName" class='login-user-name' placeholder="User Name" autocomplete="off" required="required">
            </div>

            <div class="password">
                <input type="password" class='login-mdp' name="regPassword" placeholder="Password" required="required">
                <i class="far fa-eye-slash"></i>
            </div>


            <div class="signing">
                <input type="submit" name="regSabmit" class="submit" value="Sign In">
                <a class="a-submit" id="singIn" href="#">Sign In<i class="fas fa-long-arrow-alt-right"></i></a>
            </div>
        </form>
    </div>
</div>




<script src="<?php echo $js; ?>script.js"></script>
