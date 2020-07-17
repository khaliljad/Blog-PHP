<?php
session_start();
$title = 'Members';



if(isset($_SESSION['userName'])) {
    include 'init.php';
    
    // ---------------- manage pages ---------------- //
    $singlPage = isset($_GET['page']) ? $page = $_GET['page'] : $page = 'manage';
    
    // --------------- start check the pages --------------//

    if ($page == 'manage') { // ----------------------------------- manage page ------------------------------------------------//

        $sql = "SELECT * FROM users WHERE groupID <> 1 ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();

    
        ?>  <div class="manage-container">

                <table class="category-table">
                    <thead>
                        <tr class="tr-category">
                            <th class="th-category">#ID</th>
                            <th class="th-category">Avatar</th>
                            <th class="th-category">Full Name</th>
                            <th class="th-category">Email</th>
                            <th class="th-category">User Name</th>
                            <th class="th-category">Registred date</th>
                            <th class="th-category">Status</th>
                            <th class="th-category"><i class="fas fa-angle-down"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (!empty($rows)) {
                            foreach( $rows as $row ) { 
                        ?>
                        <tr class="tr-category">
                            <td class="td-category" id="culumn-id"> # <?php echo $row['id']; ?> </td>
                            <td class="td-category"><img class="nav-image" src="<?php if (!empty($row['userimg'])) { echo "../theme/uploads/userImages/" . $row['userimg']; } else { echo "../theme/images/users/unnamed.jpg"; } ?>" alt="user-image"></td>
                            <td class="td-category"> <?php echo $row['nom'] . ' ' . $row['prenom']; ?> </td>
                            <td class="td-category"> <?php echo $row['email']; ?> </td>
                            <td class="td-category"> <?php echo $row['userName']; ?> </td>
                            <td class="td-category"> <?php echo $row['date']; ?> </td>
                            <td class="td-category" id="control">
                            <div class="btn-control">
                            <?php 
                                if( $row['approuv'] == 1) { ?> 
                                    <form action="?page=updatePrv&userid=<?php echo $row['id'] ?> " method="POST"> 
                                        <input type="submit" name="appr" value="Active" id="approuv">
                                    </form>
                                <?php } else { ?>
                                    <form action="?page=updatePrv&userid=<?php echo $row['id'] ?> " method="POST"> 
                                        <input type="submit" name="appr" value="Disabled" id="disapprouv"> 
                                    </form>
                            <?php } ?>
                            </div>
                            </td>
                            <td class="td-category">
                            <div class="edit-category-dropdown">
                                <i class="fas fa-bars"></i>
                                <div class="edit-category-dropdown-content">
                                    <form action="?page=edit&userid=<?php echo $row['id'] ?>" method="POST">
                                        <input type="submit" value="Update" class="btn-update">
                                    </form>
                                    <form action="?page=delete&userid=<?php echo $row['id'] ?>" method="POST">
                                        <input type="submit" value="Delete" class="btn-delete" onclick="return confirm('Are you sure?');">
                                    </form>
                                </div>
                            </div>
                            </td>
                        </tr>
                        <?php
                            } 
                            } else { ?>
                                <tr>
                                    <td class="empty-article" colspan="8"><span>There is No Members To Show<i class="fas fa-user-times"></i></span></td>
                                </tr>
                            <?php }
                        ?>
                    </tbody>
                </table>

                <a href="?page=Add" class="new-member"><i class="plus fas fa-plus"></i>Add new member</a>
            </div> 
        <?php      
        
      
    } elseif ($page == 'approuv') {   // ---------------------------------------- approuved members page ------------------------------------------//


        $sql = "SELECT * FROM users WHERE groupID <> 1 AND approuv = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();

    
        ?>  <div class="manage-container">

                <table class="category-table">
                    <thead>
                        <tr class="tr-category">
                            <th class="th-category">#ID</th>
                            <th class="th-category">Nom</th>
                            <th class="th-category">Prénome</th>
                            <th class="th-category">Email</th>
                            <th class="th-category">User Name</th>
                            <th class="th-category">Registred date</th>
                            <th class="th-category">status</th>
                            <th class="th-category"><i class="fas fa-angle-down"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rows as $row) { ?>
                        <tr class="tr-category">
                            <td class="td-category" id="culumn-id"> # <?php echo $row['id']; ?> </td>
                            <td class="td-category"> <?php echo $row['nom']; ?> </td>
                            <td class="td-category"> <?php echo $row['prenom']; ?> </td>
                            <td class="td-category"> <?php echo $row['email']; ?> </td>
                            <td class="td-category"> <?php echo $row['userName']; ?> </td>
                            <td class="td-category"> <?php echo $row['date']; ?> </td>
                            <td class="td-category" id="control">
                            <div class="btn-control">
                                <?php 
                                    if( $row['approuv'] == 0) { ?> 
                                    <form action="?page=updatePrv&userid=<?php echo $row['id'] ?> " method="POST"> 
                                        <input type="submit" value="Activer" id="approuv"> 
                                    </form> 
                                <?php } ?>
                            </div>
                            </td>
                            <td class="td-category">
                            <div class="edit-category-dropdown">
                                <i class="fas fa-bars"></i>
                                <div class="edit-category-dropdown-content">
                                    <form action="?page=edit&userid=<?php echo $row['id'] ?>" method="POST">
                                        <input type="submit" name ="appr" value="Update" class="btn-update">
                                    </form>
                                    <form action="?page=delete&userid=<?php echo $row['id'] ?>" method="POST">
                                        <input type="submit" value="Delete" class="btn-delete" onclick="return confirm('Are you sure?');">
                                    </form>
                                </div>
                            </div>
                            </td>   
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <a href="?page=Add" class="new-member"><i class="plus fas fa-plus"></i>Add new member</a>
            </div> 
        <?php      

    } elseif ($page == 'updatePrv') {   // ---------------------------------------- approuv update page ------------------------------------------//

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $appr = $_POST['appr'];

            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;

            if ($appr == 'Active') {

                $sql = 'UPDATE users SET approuv = 0 WHERE id = ?';
                $stmt = $conn->prepare($sql);
                $stmt->execute(array($userid));

            } else {

                $sql = 'UPDATE users SET approuv = 1 WHERE id = ?';
                $stmt = $conn->prepare($sql);
                $stmt->execute(array($userid));

            }

            $msg = 'felicitation your member aprrouved';
            $url = $_SERVER['HTTP_REFERER'];
            redirectFunc($msg, 0, $url);
        } else {

            $error = "you cant browz this page directly";
            $url = 'members.php';
            redirectFunc($error, 3, $url);
        }


    } elseif ($page == 'edit') {   // ---------------------------------------- edite members page ------------------------------------------//
        // check if user id exist
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        
        //select all users table 
        
        $sql = 'SELECT * FROM users WHERE id = ? LIMIT 1';
        $slcStmt = $conn->prepare($sql);
        $slcStmt->execute(array($userid)); 
        $row = $slcStmt->fetch();
        $count = $slcStmt->rowCount();
        
        //check if user id is numeric and affiche la page.
        if ($count > 0) { 
        ?> 
            <div class="members-container">
                <span class="contact100-form-title">
                    Edit member
                </span>

                <div class="user-img">
                    <img src="theme/images/profile2.jpg" alt="profile image">
                    <div class="input-container">
                        <i  class="fas fa-trash-alt icon"></i>
                        <input class="input-field" type="file" >
                    </div>
                </div>
                
                <form action="?page=update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>">
                    <div class="input-flex">
                        <div class="members-left">
                            <label for="prenom">First Name : </label>
                            <input class="edit-input" type="text" name="prenom" placeholder="Enter your first name" value="<?php echo $row['prenom'] ?>" required="required">
                        
                            <label for="prenom">Last Name : </label>
                            <input class="edit-input" type="text" name="nom" placeholder="Enter your last name" value="<?php echo $row['nom'] ?>" required="required">
                        </div>

                        <div class="members-right">
                            <label for="prenom">Email : </label>
                            <input class="edit-input" type="email" name="email" placeholder="Enter your email" value="<?php echo $row['email'] ?>" required="required">

                            <label for="prenom">User Name : </label>
                            <input class="edit-input" type="text" name="userName" placeholder="Enter you user name" value="<?php echo $row['userName'] ?>" required="required">
                        </div>
                    </div>
                    <input type="submit" class="change-submit" value="Save Changes">
                </form>
            </div>
        <?php
        } else {

            $error = "aucun user exist";
            $url = 'members.php';
            redirectFunc($error, 3, $url);

        }

    } elseif ($page == 'update') {  // ------------------------------------------ update members page --------------------------------------------//

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //  get inputs value :
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $username = $_POST['userName'];
            $userid = $_POST['userid'];
            
            //  check id user name exist
            $chekname = $conn->prepare("SELECT userName FROM users WHERE userName = ? AND id != ?");
            $chekname->execute(array($username, $userid));
            $chekCountN = $chekname->rowCount();
            //  check id email exist
            $chekemail = $conn->prepare("SELECT email FROM users WHERE email = ? AND id != ?");
            $chekemail->execute(array($email, $userid));
            $chekCountE = $chekemail->rowCount();

            $errors = array();
            if (empty($nom)) { $errors[] =  "name cant be empty" ;} elseif (strlen($nom) < 3)  { $errors[] = "The name can be greater than 2 caracter"; } elseif (strlen($nom) > 15)  { $errors[] = "The name can be smaler than 15 caracter"; };
            if (empty($prenom)) { $errors[] =  "prenom cant be empty" ;} elseif (strlen($prenom) < 2)  { $errors[] = "The name can be greater than 2 caracter"; } elseif (strlen($prenom) > 15)  { $errors[] = "The name can be smaler than 15 caracter"; };
            if (empty($email)) { $errors[] =  "email cant be empty" ;} elseif ($chekCountE <> 0) { $errors[] = 'vous avez utiliser un email deja existe';}
            if (empty($username)) { $errors[] =  "user name cant be empty" ;} elseif (strlen($username) < 3)  { $errors[] = "The user name can be greater than 3 caracter"; } elseif (strlen($username) > 20)  { $errors[] = "The user name can be smaler than 20 caracter"; };
            if ($chekCountN <> 0) { $errors[] = 'vous avez utiliser un user name deja existe';}

            if(!empty($errors)) {
                foreach($errors as $error) {
                    echo $error . '<br>';
                };
                $url = $_SERVER['HTTP_REFERER'];
                redirectFunc("", 3, $url);

            }
            // update inputs value :
            if (empty($errors)) {

                    $sql = 'UPDATE users SET nom = ?, prenom = ?, email = ?, userName = ? WHERE id = ?';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array($nom, $prenom, $email, $username, $userid));
        
                    $message = 'félicitation vous avez modifiez un nombre de : ' . $stmt->rowCount();
                    $url = 'dashbord.php';
                    redirectFunc($message, 0, $url);
                } else {
                    echo "this user is deja exist";
            }

        } else {

            $error = "you cant browz this page directly";
            $url = 'members.php?page=edit&userid=' . $_SESSION['userId'];
            redirectFunc($error, 3, $url);


        }

    } elseif($page == 'editPassword') {  // -------------------------------------- edite password page ------------------------------------------//

        // check if user id exist
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;

        // check the user 
        if ($_SESSION['userId'] == $_GET['userid']) {
        ?>

            <h1 id="member-title">Edit Password</h1>

            <form action="?page=updatePassword" method="POST">
                <input type="hidden" name="userid" value="<?php echo $userid ?>">

                <label for="pass">anciens mot de passe</label>
                <input type="password" name="oldpassword" required="required">

                <label for="pass">nouveau mot de passe</label>
                <input type="password" name="newpassword" required="required">

                <label for="pass">repeter le mot de passe</label>
                <input type="password" name="rptpassword" required="required">

                <input type="submit" name="submit">
            </form>

        <?php 
        }

   } elseif ($page == 'updatePassword') {   // -------------------------------------- update password page ------------------------------------------//

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 id='member-title'>Update Password</h1>";
        // variables
        $oldpassword = sha1($_POST['oldpassword']);
        $newpassword = sha1($_POST['newpassword']);
        $rptpassword = sha1($_POST['rptpassword']);
        $userid = $_POST['userid'];

        //select all users table 
        $sql = 'SELECT motpass, id FROM users WHERE id = ? LIMIT 1';
        $slcStmt = $conn->prepare($sql);
        $slcStmt->execute(array($userid)); 
        $row = $slcStmt->fetch();
        $count = $slcStmt->rowCount();


            if ($row['motpass'] == $oldpassword && $newpassword == $rptpassword) {

            $sql = 'UPDATE users SET motpass = ? WHERE id = ?';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($newpassword, $userid));
                
            $msg = 'your password has apdated';
            $url = 'dashbord.php';
            redirectFunc($msg, 3, $url);

            } else {

            $error = "your password incorect";
            $url = 'members.php?page=editPassword&userid=' . $_SESSION['userId'];
            redirectFunc($error, 3, $url);


            };
        } else {

            $error = "you cant browz this page directly";
            $url = 'members.php?page=editPassword&userid=' . $_SESSION['userId'];
            redirectFunc($error, 3, $url);
        }


    } elseif ($page == 'Add') {   // -------------------------------------- Add new member page ------------------------------------------//
        ?>

        <div class="container-contact100">
        <div class="wrap-contact100">
            <form class="contact100-form validate-form" action="?page=insert" method='POST'>
                <span class="contact100-form-title">
                    Add New Member
                </span>

                <div class="wrap-input100"">
                    <label for="firstname"></label>
                    <input type="text" class="input100" name="firstname" placeholder="First Name.." required="required">
                </div>

                <div class="wrap-input100">
                <label for="lastname"></label>
                <input type="text" class="input100" name="lastname" placeholder="Your last name.." required="required">
                </div>

                <div class="wrap-input100">
                <label for="Email"></label>
                <input type="email" class="input100" name="Email" placeholder="Your Email.." required="required">
                </div>

                <div class="wrap-input100">
                <label for="image"></label>
                <input type="file" class="input100" name="image" placeholder="Your image..">
                </div>

                <div class="wrap-input100">
                <label for="username"></label>
                <input type="text" class="input100" name="username" placeholder="Your user name.." required="required">
                </div>

                <div class="wrap-input100">
                <label for="country"></label>
                <input type="password" class="input100" name="password" placeholder="Your password.." required="required">
                </div>
            
                <div class="container-contact100-form-btn">
                    <input type="submit" class="contact100-form-btn" value="Send">
                </div>
            </form>
        </div>
        </div>

        <?php
    } elseif ($page == 'insert') {   // -------------------------------------- insert page ------------------------------------------//

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $nom = $_POST['lastname'];
            $prenom = $_POST['firstname'];
            $email = $_POST['Email'];
            $password = sha1($_POST['password']);
            $username = $_POST['username'];
            $image = $_POST['image'];

            $checkUserName = checkUser('userName', 'users', $username);
            $checkEmail = checkUser('email', 'users', $email);

                $errors = array();
                if ($checkUserName <> 0) { $errors[] = 'vous avez utiliser un user name deja existe';} elseif (empty($username)) { $errors[] =  "user name cant be empty" ;} elseif (strlen($username) < 3)  { $errors[] = "The user name can be greater than 2 caracter"; } elseif (strlen($nom) > 15)  { $errors[] = "The user name can be smaler than 15 caracter"; };
                if ($checkEmail <> 0) { $errors[] = 'vous avez utiliser un email deja existe';} elseif (empty($email)) { $errors[] =  "email cant be empty" ;};
                if (empty($nom)) { $errors[] =  "name cant be empty" ;} elseif (strlen($nom) < 3)  { $errors[] = "The name can be greater than 2 caracter"; } elseif (strlen($nom) > 15)  { $errors[] = "The name can be smaler than 15 caracter"; };
                if (empty($prenom)) { $errors[] =  "prenom cant be empty" ;} elseif (strlen($prenom) < 2)  { $errors[] = "The name can be greater than 2 caracter"; } elseif (strlen($prenom) > 15)  { $errors[] = "The name can be smaler than 15 caracter"; };

                if(!empty($errors)) {
                    foreach ($errors as $error) {
                        echo $error . '<br>';
                    }
                    $url = $_SERVER['HTTP_REFERER'];
                    redirectFunc("", 3, $url);
                }

                if (empty($errors)) {

                    $sql = "INSERT INTO users (nom, prenom, email, motpass, userName, userimg, date, approuv) VALUES (:nom, :prenom, :email, :mdp, :username, :userimg, now(), 1)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array(
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'email' => $email,
                        'mdp' => $password,
                        'username' => $username,
                        'userimg' => $image
                    ));
        
                    $error = 'user added seccefully';
                    $url = 'members.php?page=manage';
                    redirectFunc($error, 0, $url);
                }
                
        } else {

            $error = "you cant browz this page directly";
            $url = 'members.php?page=Add';
            redirectFunc($error, 3, $url);
        }
        
    } elseif ($page == 'delete') {   // -------------------------------------- delete page ------------------------------------------//
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
            
            $sql = 'DELETE FROM users WHERE id = ?';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($userid));
            
            $msg = 'your member is deleted';
            $url = $_SERVER['HTTP_REFERER'];
            redirectFunc($msg, 0, $url);
        } else {
            
            $error = "you cant browz this page directly";
            $url = 'members.php';
            redirectFunc($error, 3, $url);

        }
    
    } else {

        $error = "this page is not definde";
        $url = 'dashbord.php';
        redirectFunc($error, 3, $url);

    } // --------------- end check the pages --------------//

    
    include $tpl . "footer.php ";


} else {
    header('Location: index.php');
    exit;
};



?>