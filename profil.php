<?php 
session_start();
$title = 'profile';
// $noNav = '';
include 'init.php';

if (isset($_SESSION['userid'])) {

    // Select user information:
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array($sessionId));
    $row = $stmt->fetch();

    $singlPage = isset($_GET['page']) ? $page = $_GET['page'] : $page = 'profil';
    
    if($page == 'profil') {
    ?>
        <div class="container profil-container">
            <div class="bar-info">
                <?php if($row['approuv'] == 0) {echo "<p class='acount-not-approved'>Your Account is Not Approved</p>" ;} ?>
                <div class="profil-info">
                    <img src="<?php if (!empty($row['userimg'])) { echo "theme/uploads/userImages/" . $row['userimg']; } else { echo "theme/images/users/unnamed.jpg"; } ?>" alt="profil-img">
                    <a href="profil.php?page=edit"><i class="fas fa-user-edit"></i>Edit Profil</a>
                    <p><i class="fas fa-user"></i>FUll Name : <?php echo $row['prenom'] . ' ' . $row['nom'] ?></p>
                    <p><i class="fas fa-envelope"></i>Email : <?php echo $row['email']; ?></p>
                    <p><i class="fas fa-calendar-week"></i>Registred date : <?php echo $row['date']; ?></p>
                    <p><i class="fas fa-calendar-alt"></i>Articles added : <?php echo totalItems('id_article', 'articles', 'id_user', $row['id']) ?></p>
                    <p><i class="fas fa-comments"></i>Comments added : <?php echo totalItems('id','comments','id_user', $row['id']) ?></p>
                </div>
            </div>
            <div class="bar-show">
                <section class="flex-card profil">
                <?php 
                    //comments count: 
                    $sql = "SELECT * FROM articles WHERE id_user = ? ORDER BY id_article DESC ";
                    $dashStmt = $conn->prepare($sql);
                    $dashStmt->execute(array($_SESSION['userid']));
                    $rows = $dashStmt->fetchAll();

                    if (!empty($rows)) {
                    foreach($rows as $row) {
                        $countCmt = $conn->prepare("SELECT COUNT(comment) FROM comments WHERE id_article = ?");
                        $countCmt->execute(array($row['id_article']));
                        $countComment = $countCmt->fetchColumn();
                ?>
                    <a class="card-link"  href="articles.php?articleId=<?php echo $row['id_article']; ?>">
                        <div class="card profil">
                            <?php 
                                if($row['status'] == 1) { 
                                    echo "<span class='approved-article'>Approved</span>";
                                } else {
                                    echo "<span class='disabled-article'>Disabled</span>";
                                }
                            ?>
                            <div class="card-image">
                                <img class="test-image" src="theme/uploads/articleImages/<?php echo $row['image'] ?>" alt="">
                            </div>
                            <div class="card-text">
                                <h2><?php echo $row['titre'] ?></h2>
                                <span class="date"><?php echo $row['date_publication'] ?></span>
                                <p><?php echo $row['contenu'] ?></p>
                            </div>
                            <div class="card-stats profil">
                                <div class="stat">
                                    <div class="value"><?php getViews($row['id_article']) ?></div>
                                    <div class="type">views</div>
                                </div>
                                <div class="stat">
                                    <div class="value"><?php getLikes($row['id_article']) ?></div>
                                    <div class="type">likes</div>
                                </div>
                                <div class="stat">
                                    <div class="value"><?php echo $countComment ?></div>
                                    <div class="type">comments</div>
                                </div>
                                <!-- ------ start drop down ----- -->
                                <div class="stat">
                                    <div class="edit-category-dropdown">
                                        <i class="fas fa-bars"></i>
                                        <div class="edit-category-dropdown-content">
                                            <form action="?page=editArticle&articlid=<?php echo $row['id_article'] ?>" method="POST">
                                                <input type="submit" value="Update" class="btn-update">
                                            </form>
                                            <form action="?page=delete&articlid=<?php echo $row['id_article'] ?>" method="POST">
                                                <input type="submit" value="Delete" class="btn-delete" onclick="return confirm('Are you sure?');">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- ------ end drop down ----- -->
                            </div>
                        </div>
                    </a>
                <?php
                    }
                    } else {
                        echo "<p class='empty-art'>There is No Articles To Show</p>";
                    }
                ?>
                </section>
                <a class="add-new-article-btn" href="profil.php?page=add"><i class="fas fa-plus"></i>Add new article</a>
            </div>

        </div>

    <?php   
    } elseif ($page == 'edit') { // ------------------------------------------ edit members page --------------------------------------------//
            
                    // check if user id exist
        $userid = $_SESSION['userid'];
        
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
                
                <form action="?page=update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <div class="user-img">
                            <img src="<?php if (!empty($row['userimg'])) { echo "theme/uploads/userImages/" . $row['userimg']; } else { echo "theme/images/users/unnamed.jpg"; } ?>" alt="profile image">
                            <div class="input-container">
                                <i class="fas fa-pen"></i>
                                <input class="input-field" type="file" name="userimg">
                            </div>
                        </div>
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

    } elseif($page == 'update') { // ------------------------------------------------- update members page -------------------------------------------------//
       
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //  get inputs value :
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $username = $_POST['userName'];
            $imgname = $_FILES['userimg']['name'];
            $imgtype = $_FILES['userimg']['type'];
            $imgTmp = $_FILES['userimg']['tmp_name']; 
            $userid = $_POST['userid'];
            
            $imageExtension = array('jpg', 'png', 'jpeg');
            $explode = explode('.', $imgname);
            $getEndExtension = strtolower(end($explode));

            

            
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
            if (!in_array($getEndExtension, $imageExtension) && !empty($imgname)) { $errors[] = 'chose another image'; } elseif (empty($imgname)) {$errors[] = 'chose image';} ;

            if(!empty($errors)) {
                foreach($errors as $error) {
                    echo $error . '<br>';
                };
                $url = $_SERVER['HTTP_REFERER'];
                redirectFunc("", 3, $url);

            }
            // update inputs value :
            if (empty($errors)) {

                $avatar = rand(0, 10000) . '_' . $imgname;
                move_uploaded_file($imgTmp, "theme\uploads\userImages\\" . $avatar);

                    $sql = 'UPDATE users SET nom = ?, prenom = ?, email = ?, userName = ?, userimg = ? WHERE id = ?';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array($nom, $prenom, $email, $username, $avatar, $userid));
        
                    $message = 'félicitation vous avez modifiez un nombre de : ' . $stmt->rowCount();
                    $url = 'profil.php';
                    redirectFunc($message, 0, $url);
            } else {
                echo "this user is deja exist";
            }

        } else {

        $error = "you cant browz this page directly";
        $url = 'profil.php';
        redirectFunc($error, 3, $url);  
        }
        
    } elseif($page == 'editPassword') {  // -------------------------------------- edite password page ------------------------------------------//

            // check if user id exist
            $userid = $_SESSION['userid'];
    
            ?>

<div class="container profil-container">
            <div class="bar-info">
                <div class="profil-info">
                    <img src="theme/uploads/userImages/<?php echo $row['userimg'] ?>" alt="profil-img">
                    <a href="profil.php?page=edit"><i class="fas fa-user-edit"></i>Edit Profil</a>
                    <p><i class="fas fa-user"></i>FUll Name : <?php echo $row['prenom'] . ' ' . $row['nom'] ?></p>
                    <p><i class="fas fa-envelope"></i>Email : <?php echo $row['email']; ?></p>
                    <p><i class="fas fa-calendar-week"></i>Registred date : <?php echo $row['date']; ?></p>
                    <p><i class="fas fa-calendar-alt"></i>article added : 5</p>
                    <p><i class="fas fa-comments"></i>Comments added : 5</p>
                </div>
            </div>
            <div class="bar-show">
                <div class="container password-container">
                    <h1 id="member-title">Edit Password</h1>
        
                    <form class="form-password" action="?page=updatePassword" method="POST">
                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <div class="ancien">
                            <label for="pass">anciens mot de passe :</label>
                            <input class="input-pass" type="password" name="oldpassword" required="required">
                        </div>

                        <div class="ancien">
                            <label for="pass">nouveau mot de passe :</label>
                            <input class="input-pass" type="password" name="newpassword" required="required">
                        </div>

                        <div class="ancien">
                            <label for="pass">repeter le mot de passe :</label>
                            <input class="input-pass" type="password" name="rptpassword" required="required">
                        </div>

                        <input class="btn-submit-pass" type="submit" name="submit">
                    </form>
                </div>
            </div>

        </div>

            <?php 
    
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
                $url = 'profil.php';
                redirectFunc($msg, 3, $url);
    
                } else {
    
                $error = "your password incorect";
                $url = $_SERVER['HTTP_REFERER'];
                redirectFunc($error, 3, $url);
    
    
                };
            } else {
    
                $error = "you cant browz this page directly";
                $url = 'members.php?page=editPassword&userid=' . $_SESSION['userId'];
                redirectFunc($error, 3, $url);
            }
    } elseif ($page == 'add') { // ----------------------------------- add article page ------------------------------------------------//

        //SELECT CATEGORIES STATMENT:
        $catSql = "SELECT id, name FROM categories";
        $catStmt = $conn->prepare($catSql);
        $catStmt->execute();
        $cats = $catStmt->fetchAll();

        ?>
        <div class="container-contact100">
            <div class="wrap-contact100">
                <form class="contact100-form validate-form" action="?page=insert" method="POST" enctype="multipart/form-data">
                    <span class="contact100-form-title">
                        Add New Article
                    </span>

                    <div class="wrap-input100">
                        <label for="title"></label>
                        <input type="text" name="title" class="input100" placeholder="Article title" required="required">
                    </div>

                    <div class="wrap-input100">
                        <label for="image"></label>
                        <input class="input100" type="file" name="image" required="required">
                    </div>

                    <div class="wrap-input100">
                        <textarea class="input100" name="content" placeholder="Add Your Content .." required="required"></textarea>
                    </div>

                    <input type="hidden" name="iduser" value="<?php echo $_SESSION['userid']; ?>">

                    <div class="select category">
                        <select name="category">
                            <option value="0">chose your category</option>
                            <?php foreach($cats as $cat) { ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    
                    <div class="container-contact100-form-btn">
                        <input type="submit" class="contact100-form-btn" value="Send">
                    </div>
                </form>
            </div>
        </div>

    <?php
    } elseif ($page == 'insert') { // ----------------------------------- insert article page ------------------------------------------------//

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $title      = $_POST['title'];
            $content    = $_POST['content'];
            $iduser     = $_POST['iduser'];
            $idcategory = $_POST['category'];
            $artimgName = $_FILES['image']['name'];
            $artimgType = $_FILES['image']['type'];
            $artimgTmp = $_FILES['image']['tmp_name']; 

            $imageExtension = array('jpg', 'png', 'jpeg');
            $explode = explode('.', $artimgName);
            $getEndExtension = strtolower(end($explode));

            $errors = array();
            if (empty($title)) { $errors[] =  "title cant be empty" ;} elseif (strlen($title) < 3)  { $errors[] = "The title can be greater than 2 caracter"; };
            if (empty($content)) { $errors[] =  "content cant be empty" ;} elseif (strlen($content) < 10)  { $errors[] = "The content can be greater than 10 caracter"; };
            if ($idcategory == 0) { $errors[] = "chose category" ;};
            if (!in_array($getEndExtension, $imageExtension) && !empty($artimgName)) { $errors[] = 'chose another image'; } elseif (empty($artimgName)) {$errors[] = 'chose image';} ;


            if(!empty($errors)) {
                foreach ($errors as $error) {
                    echo $error . '<br>';
                }
                $url = $_SERVER['HTTP_REFERER'];
                redirectFunc("", 3, $url);
            }

            if(empty($errors)) {

                $avatar = rand(0, 10000) . '_' . $artimgName;
                move_uploaded_file($artimgTmp, "theme\uploads\articleImages\\" . $avatar); 

                $sql = "INSERT INTO articles (titre, date_publication, image, contenu, id_user, id_category) VALUE (:title, now(), :img, :content, :userid, :catid)";
                $stmt = $conn->prepare($sql);
                $stmt->execute(array(
                    'title'    => $title,
                    'img'      => $avatar,
                    'content'  =>$content,
                    'userid'   => $iduser,
                    'catid'    => $idcategory
                ));

                $msg = 'your categorie is added succefully';
                $url = 'profil.php';
                redirectFunc($msg, 0, $url);

            }

        } else {

            $error = "you cant browz this page directly";
            $url = $_SERVER['HTTP_REFERER'];
            redirectFunc($error, 3, $url);

        }

    } elseif ($page == 'editArticle') { // ----------------------------------- edit article page ------------------------------------------------//
        
        $articlid = isset($_GET['articlid']) && is_numeric($_GET['articlid']) ? $_GET['articlid'] : 0;
        
        $sql = "SELECT * FROM articles WHERE id_article = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($articlid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        //SELECT CATEGORIES STATMENT:
        $catid = $row['id_category'];
        $catSql = "SELECT id, name FROM categories WHERE id = ? LIMIT 1";
        $catStmt = $conn->prepare($catSql);
        $catStmt->execute(array($catid));
        $cat = $catStmt->fetch();

        //check if user id is numeric and affiche la page.
        if ($count > 0) { 
        ?>
        <div class="container-contact100">
            <div class="wrap-contact100">
                <form class="contact100-form validate-form" action="?page=updateArticle" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="articlid" value="<?php echo $articlid ?>">
                    <span class="contact100-form-title">
                        Edit your article
                    </span>

                    <div class="wrap-input100">
                        <label for="title"></label>
                        <input class="input100" type="text" name="title" placeholder="title" value="<?php echo $row['titre'] ?>" required="required">
                    </div>

                    <div class="wrap-input100">
                        <label for="image"></label>
                        <input class="input100" type="file" name="image" required="required">
                    </div>

                    <div class="wrap-input100">
                        <textarea class="input100" name="content" placeholder="Your content .." required="required"><?php echo $row['contenu'] ?></textarea>
                    </div>

                    <div class="select category">
                        <select name="category">
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                        <?php 
                            $catname = $cat['name'];
                            $slct = $conn->prepare("SELECT id, name FROM categories WHERE name <> ?");
                            $slct->execute(array($catname));
                            $cats = $slct->fetchAll();
                        ?>
                            <?php foreach($cats as $cate) { ?>
                            <option value="<?php echo $cate['id']; ?>"><?php echo $cate['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    
                    <div class="container-contact100-form-btn">
                        <input type="submit" class="contact100-form-btn" value="Send">
                    </div>
                </form>
            </div>
        </div>
        <?php } else {

            $error = "aucun category exist";
            $url = 'profil.php';
            redirectFunc($error, 3, $url);
        }

    } elseif ($page == 'updateArticle') { // ----------------------------------- update article page ------------------------------------------------//
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //  get inputs value :
            $title = $_POST['title'];
            $content = $_POST['content'];
            $articlid = $_POST['articlid'];
            $catid = $_POST['category'];
            $artimgName = $_FILES['image']['name'];
            $artimgType = $_FILES['image']['type'];
            $artimgTmp = $_FILES['image']['tmp_name']; 

            $imageExtension = array('jpg', 'png', 'jpeg');
            $explode = explode('.', $artimgName);
            $getEndExtension = strtolower(end($explode));


            $errors = array();
            if (empty($title)) { $errors[] =  "title cant be empty" ;} elseif (strlen($title) < 3)  { $errors[] = "The title can be greater than 2 caracter"; };
            if (empty($content)) { $errors[] =  "content cant be empty" ;} elseif (strlen($content) < 10)  { $errors[] = "The content can be greater than 6 caracter"; };
            if (!in_array($getEndExtension, $imageExtension) && !empty($artimgName)) { $errors[] = 'chose another image'; } elseif (empty($artimgName)) {$errors[] = 'chose image';} ;

          
            
            if(!empty($errors)) {
                foreach($errors as $error) {
                    echo $error . '<br>';
                };
                $url = $_SERVER['HTTP_REFERER'];
                redirectFunc("", 3, $url);

            }
            // update inputs value :
            if (empty($errors)) {

                $avatar = rand(0, 10000) . '_' . $artimgName;
                move_uploaded_file($artimgTmp, "theme\uploads\articleImages\\" . $avatar);  

                $sql = 'UPDATE articles SET titre = ?, image = ?, contenu = ?, id_category = ?  WHERE id_article = ?';
                $stmt = $conn->prepare($sql);
                $stmt->execute(array($title, $avatar, $content, $catid, $articlid));
    
                $message = 'félicitation vous avez modifiez un nombre de : ' . $stmt->rowCount();
                $url = 'profil.php';
                redirectFunc($message, 0, $url);

            }

        } else {

            $error = "you cant browz this page directly";
            $url = $_SERVER['HTTP_REFERER'];
            redirectFunc($error, 3, $url);


        }


    } elseif ($page == 'editCommments') { // ----------------------------------- edit comments page ------------------------------------------------//

        $commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? $_GET['commentid'] : 0;
        
        $sql = "SELECT * FROM comments WHERE id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($commentid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        //check if user id is numeric and affiche la page.
        if ($count > 0) { 
        ?>
        <div class="container-contact100">
            <div class="wrap-contact100">
                <form class="contact100-form validate-form" action="?page=updateComments" method="POST">
                    <input type="hidden" name="commentid" value="<?php echo $commentid ?>">
                    <span class="contact100-form-title">
                        Edit your Comment
                    </span>

                    <div class="wrap-input100">
                        <textarea class="input100" name="comment" placeholder="Your Comment .." required="required"><?php echo $row['comment'] ?></textarea>
                    </div>
                    
                    <div class="container-contact100-form-btn">
                        <input type="submit" class="contact100-form-btn" value="Send">
                    </div>
                </form>
            </div>
        </div>
        <?php } else {

            $error = "aucun comment exist";
            $url = 'profil.php';
            redirectFunc($error, 3, $url);
        }

    } elseif ($page == 'updateComments') {  // ----------------------------------- update comments page ------------------------------------------------//

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //  get inputs value :
            $comment = $_POST['comment'];
            $commentid = $_POST['commentid'];


            $errors = array();
            if (empty($comment)) { $errors[] =  "comment cant be empty" ;};
          
            
            if(!empty($errors)) {
                foreach($errors as $error) {
                    echo $error . '<br>';
                };

                $url = $_SERVER['HTTP_REFERER'];
                redirectFunc("", 3, $url);

            }
            // update inputs value :
            if (empty($errors)) {

                $sql = 'UPDATE comments SET comment = ? WHERE id = ?';
                $stmt = $conn->prepare($sql);
                $stmt->execute(array($comment, $commentid));
    
                $message = 'félicitation vous avez modifiez un nombre de : ' . $stmt->rowCount();
                $url = 'profil.php';
                redirectFunc($message, 0, $url);

            }

        } else {

            $error = "you cant browz this page directly";
            $url = 'home.php';
            redirectFunc($error, 3, $url);
        }

    } elseif ($page == 'deleteComment') { // ----------------------------------- update comments page ------------------------------------------------//

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
            $commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? $_GET['commentid'] : 0;
            
            $sql = 'DELETE FROM comments WHERE id = ?';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($commentid));
            
            $msg = 'your comment is deleted';
            $url = $_SERVER['HTTP_REFERER'];
            redirectFunc($msg, 0, $url);

        } else {
        
            $error = "you cant browz this page directly";
            $url = 'comments.php';
            redirectFunc($error, 3, $url);

        }




    
    } elseif ($page == 'delete') {  // ----------------------------------- delete article page ------------------------------------------------//

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
            $articlid = isset($_GET['articlid']) && is_numeric($_GET['articlid']) ? $_GET['articlid'] : 0;
            
            $sql = 'DELETE FROM articles WHERE id_article = ?';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($articlid));
            
            $msg = 'your article is deleted';
            $url = $_SERVER['HTTP_REFERER'];
            redirectFunc($msg, 0, $url);
        } else {
            
            $error = "you cant browz this page directly";
            $url = 'profil.php';
            redirectFunc($error, 3, $url);

        }
    }

    include $tpl . "footer.php ";

} else {

header('location:login.php');
exit;
}