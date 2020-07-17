<?php 

session_start();
// ******* title for header ******* \\
$title = 'Articles';

// ******* check if user login ****** \\
if (isset($_SESSION['userName'])) {

    include 'init.php';

    // ******** check and get the name of page ******** \\
    $singlPage = isset($_GET['page']) ? $page = $_GET['page'] : $page = 'manage';

        if ($page == 'manage') {

            $sql = "SELECT * FROM articles";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll();

    
            ?>  <div class="manage-container">

                    <table class="category-table">
                        <thead>
                            <tr class="tr-category">
                                <th class="th-category">#ID</th>
                                <th class="th-category">Title</th>
                                <th class="th-category content">Content</th>
                                <th class="th-category">Full Name</th>
                                <th class="th-category">Category</th>
                                <th class="th-category">Registred date</th>
                                <th class="th-category">Status</th>
                                <th class="th-category">comments</th>
                                <th class="th-category"><i class="fas fa-angle-down"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if (!empty($rows)) {
                                foreach( $rows as $row ) { 
                            ?>
                            <tr class="tr-category content">
                                <td class="td-category" id="culumn-id"> # <?php echo $row['id_article']; ?> </td>
                                <td class="td-category"> <?php echo $row['titre']; ?> </td>
                                <td class="td-category content"><div class="content-scroll"> <?php echo $row['contenu']; ?></div> </td>
                                <td class="td-category">
                                    <?php 
                                        $userid = $row['id_user'];
                                        $slcuser = $conn->prepare("SELECT nom, prenom FROM users WHERE id = ?");
                                        $slcuser->execute(array($userid));
                                        $Fname = $slcuser->fetch();
                                        echo $Fname['prenom'] . ' ' . $Fname['nom'];
                                    ?> 
                                </td>
                                <td class="td-category">
                                    <?php
                                        $categoryid = $row['id_category']; 
                                        $slccat = $conn->prepare("SELECT name FROM categories WHERE id = ?");
                                        $slccat->execute(array($categoryid));
                                        $name = $slccat->fetch();
                                        echo $name['name'];
                                    ?> 
                                </td>
                                <td class="td-category date"> <?php echo $row['date_publication']; ?> </td>
                                <td class="td-category" id="control">
                                    <div class="btn-control">
                                        <?php 
                                        if( $row['status'] == 1) { ?> 
                                            <form action="?page=updateVisibility&articlid=<?php echo $row['id_article']; ?>" method="POST"> 
                                                <input type="submit" name="appr-status" value="approved" id="approuv"> 
                                            </form>
                                        <?php } else { ?>
                                            <form action="?page=updateVisibility&articlid=<?php echo $row['id_article']; ?>" method="POST"> 
                                               <input type="submit" name="appr-status" value="Disabled" id="disapprouv"> 
                                            </form>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="td-category cat">
                                    <div class="btn-control">
                                    <?php 
                                        if( $row['allow_comments'] == 1) { ?> 
                                            <form action="?page=updateComments&articlid=<?php echo $row['id_article']; ?>" method="POST"> 
                                                <input type="submit" name="appr-comments" value="Allow" id="approuv"> 
                                            </form>
                                        <?php } else { ?>
                                            <form action="?page=updateComments&articlid=<?php echo $row['id_article']; ?>" method="POST"> 
                                                <input type="submit" name="appr-comments" value="Blocked" id="disapprouv" class="disapprouv-comments"> 
                                            </form>
                                    <?php } ?>
                                    </div>
                                </td>
                                <td class="td-category">
                                <div class="edit-category-dropdown">
                                    <i class="fas fa-bars"></i>
                                    <div class="edit-category-dropdown-content">
                                        <form action="?page=edit&articlid=<?php echo $row['id_article']; ?>" method="POST">
                                            <input type="submit" value="Update" class="btn-update">
                                        </form>
                                        <form action="?page=delete&articlid=<?php echo $row['id_article']; ?>" method="POST">
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
                                        <td class="empty-article" colspan="8"><span>There is No articles To Show<i class="far fa-calendar-times"></i></span></td>
                                    </tr>
                                <?php }
                            ?>
                        </tbody>
                    </table>

                    <a href="?page=add" class="new-member"><i class="plus fas fa-plus"></i>Add new article</a>
                </div> 
            <?php      

        } elseif ($page == 'edit') { // ----------------------------------- edit article page ------------------------------------------------//
        
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
                    <form class="contact100-form validate-form" action="?page=update" method="POST">
                        <input type="hidden" name="articlid" value="<?php echo $articlid ?>">
                        <span class="contact100-form-title">
                            Edit your article
                        </span>

                        <div class="wrap-input100">
                            <label for="title"></label>
                            <input class="input100" type="text" name="title" placeholder="title" value="<?php echo $row['titre'] ?>" required="required">
                        </div>

                        <div class="wrap-input100">
                            <textarea class="input100" name="content" placeholder="Your content .." required="required"><?php echo $row['contenu'] ?></textarea>
                        </div>

                        <div class="radio">
                            <div class="radiobtn">
                                <input type="radio" id="visible-yes" name="visibility" value="1" <?php if( $row['status'] == 1 ) {echo 'checked';} ?> />
                                <label for="visible-yes">approve</label>
                            </div>
                    
                            <div class="radiobtn">
                                <input type="radio" id="visible-no" name="visibility" value="0" <?php if( $row['status'] == 0 ) {echo 'checked';} ?>/>
                                <label for="visible-no">hidden</label>
                            </div>
                        </div>

                        <div class="radio">
                            <div class="radiobtn">
                                <input type="radio" id="comment-yes" name="comment" value="1" <?php if( $row['allow_comments'] == 1 ) {echo 'checked';}  ?> />
                                <label for="comment-yes">Allow commets</label>
                            </div>
                    
                            <div class="radiobtn">
                                <input type="radio" id="comment-no" name="comment" value="0" <?php if( $row['allow_comments'] == 0 ) {echo 'checked';}  ?>/>
                                <label for="comment-no">block comments</label>
                            </div>
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
                $url = 'articles.php';
                redirectFunc($error, 3, $url);
            }

        } elseif ($page == 'update') { // ----------------------------------- update article page ------------------------------------------------//
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
                //  get inputs value :
                $title = $_POST['title'];
                $content = $_POST['content'];
                $visibility = $_POST['visibility'];
                $comments = $_POST['comment'];
                $articlid = $_POST['articlid'];
                $catid = $_POST['category'];
    

                $errors = array();
                if (empty($title)) { $errors[] =  "title cant be empty" ;} elseif (strlen($title) < 3)  { $errors[] = "The title can be greater than 2 caracter"; };
                if (empty($content)) { $errors[] =  "content cant be empty" ;} elseif (strlen($content) < 10)  { $errors[] = "The content can be greater than 6 caracter"; };
              
                
                if(!empty($errors)) {
                    foreach($errors as $error) {
                        echo $error . '<br>';
                    };
                    $url = $_SERVER['HTTP_REFERER'];
                    redirectFunc("", 3, $url);
    
                }
                // update inputs value :
                if (empty($errors)) {

                    $sql = 'UPDATE articles SET titre = ?, contenu = ?, status = ?, allow_comments = ?, id_category = ?  WHERE id_article = ?';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array($title, $content, $visibility, $comments, $catid, $articlid));
        
                    $message = 'félicitation vous avez modifiez un nombre de : ' . $stmt->rowCount();
                    $url = 'articles.php';
                    redirectFunc($message, 0, $url);
    
                }
    
            } else {
    
                $error = "you cant browz this page directly";
                $url = $_SERVER['HTTP_REFERER'];
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

                        <div class="radio">
                            <div class="radiobtn">
                                <input type="radio" id="visible-yes" name="visibility" value="1" checked />
                                <label for="visible-yes">approv</label>
                            </div>
                    
                            <div class="radiobtn">
                                <input type="radio" id="visible-no" name="visibility" value="0" />
                                <label for="visible-no">hidden</label>
                            </div>
                        </div>

                        <div class="radio">
                            <div class="radiobtn">
                                <input type="radio" id="comment-yes" name="comment" value="1" checked />
                                <label for="comment-yes">Allow commets</label>
                            </div>
                    
                            <div class="radiobtn">
                                <input type="radio" id="comment-no" name="comment" value="0" />
                                <label for="comment-no">block comments</label>
                            </div>
                        </div>

                        <input type="hidden" name="iduser" value="<?php echo $_SESSION['userId']; ?>">

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
                $visibility = $_POST['visibility'];
                $comments   = $_POST['comment'];
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
                if (empty($content)) { $errors[] =  "content cant be empty" ;} elseif (strlen($content) < 10)  { $errors[] = "The content can be greater than 6 caracter"; };
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
                    move_uploaded_file($artimgTmp, "..\\theme\uploads\articleImages\\" . $avatar);
    
                    $sql = "INSERT INTO articles (titre, date_publication, image, contenu, status, allow_comments, id_user, id_category) VALUE (:title, now(), :img, :content, :status, :comments, :userid, :catid)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array(
                        'title'    => $title,
                        'img'      => $avatar,
                        'content'  =>$content,
                        'status'   =>$visibility,
                        'comments' => $comments,
                        'userid'   => $iduser,
                        'catid'    => $idcategory
                    ));

                    $msg = 'your categorie is added succefully';
                    $url = 'articles.php';
                    redirectFunc($msg, 0, $url);

                }

            } else {

                $error = "you cant browz this page directly";
                $url = 'articles.php?page=Add';
                redirectFunc($error, 3, $url);

            }

        } elseif ($page == 'updateVisibility') {   // ----------------------------------- update visibility page ------------------------------------------------//

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $apprVisibility = $_POST['appr-status'];

                $articlid = isset($_GET['articlid']) && is_numeric($_GET['articlid']) ? $_GET['articlid'] : 0;

                    if ($apprVisibility == 'approved') {
                        $sql = 'UPDATE articles SET status = 0 WHERE id_article = ?';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(array($articlid));
                    } else {
                        $sql = 'UPDATE articles SET status = 1 WHERE id_article = ?';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(array($articlid));
                    }

                    
                    $msg = 'felicitation your article aprrouved';
                    $url = $_SERVER['HTTP_REFERER'];
                    redirectFunc($msg, 0, $url);
            } else {

                $error = "you cant browz this page directly";
                $url = 'members.php';
                redirectFunc($error, 3, $url);
            }

        } elseif ($page == 'updateComments') {  // ----------------------------------- update comments page ------------------------------------------------//

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $apprComments = $_POST['appr-comments'];

                $articlid = isset($_GET['articlid']) && is_numeric($_GET['articlid']) ? $_GET['articlid'] : 0;

                    if ($apprComments == 'Allow') {
                        $sql = 'UPDATE articles SET allow_comments = 0 WHERE id_article = ?';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(array($articlid));
                    } else {
                        $sql = 'UPDATE articles SET allow_comments = 1 WHERE id_article = ?';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(array($articlid));
                    }
                
    
                $msg = 'felicitation your article aprrouved';
                $url = $_SERVER['HTTP_REFERER'];
                redirectFunc($msg, 0, $url);
            } else {
    
                $error = "you cant browz this page directly";
                $url = 'members.php';
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
                $url = 'articles.php';
                redirectFunc($error, 3, $url);
    
            }

        } else {

        };

    include $tpl . "footer.php ";

} else {

    header('Location: index.php');
    exit;

}