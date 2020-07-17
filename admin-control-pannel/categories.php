<?php 

session_start();
// ******* title for header ******* \\
$title = 'categories';

// ******* check if user login ****** \\
if (isset($_SESSION['userName'])) {

    include 'init.php';

    // ******** check and get the name of page ******** \\
    $singlPage = isset($_GET['page']) ? $page = $_GET['page'] : $page = 'manage';

        if ($page == 'manage') {  // ----------------------------------- manage page ------------------------------------------------//

            $sql = "SELECT * FROM categories";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll();

        ?> 
            <div class="manage-container">
                <table class="category-table">
                    <thead>
                        <tr class="tr-category">
                            <th class="th-category cat">Category name</th>
                            <th class="th-category cat">description</th>
                            <th class="th-category cat">visibility</th>
                            <!-- <th class="th-category cat">Comments</th> -->
                            <th class="th-category cat"><i class="fas fa-angle-down"></i></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                            if (!empty($rows)) {
                            foreach( $rows as $row ) { 
                        ?>
                        <tr class="tr-category">
                            <td class="td-category cat bold"><?php echo $row['name']; ?></td>
                            <td class="td-category cat"><?php echo $row['description']; ?></td>
                            <td class="td-category cat">
                            <div class="btn-control">
                                <?php 
                                    if( $row['visibility'] == 1) { ?> 
                                        <form action="?page=updateVisibility&categoryid=<?php echo $row['id']; ?>" method="POST"> 
                                            <input type="submit" name="appr-visibility" value="Visible" id="approuv"> 
                                        </form>
                                    <?php } else { ?>
                                        <form action="?page=updateVisibility&categoryid=<?php echo $row['id']; ?>" method="POST"> 
                                            <input type="submit" name="appr-visibility" value="Disabled" id="disapprouv"> 
                                        </form>
                                <?php } ?>
                            </div>

                            </td>
                            <!-- <td class="td-category cat">
                            <div class="btn-control">
                            <php 
                                if( $row['allow_comments'] == 1) { ?> 
                                    <form action="?page=updateComments&categoryid=<php echo $row['id']; ?>" method="POST"> 
                                        <input type="submit" name="appr-comments" value="Allow" id="approuv"> 
                                    </form>
                                <php } else { ?>
                                    <form action="?page=updateComments&categoryid=<php echo $row['id']; ?>" method="POST"> 
                                        <input type="submit" name="appr-comments" value="Blocked" id="disapprouv" class="disapprouv-comments"> 
                                    </form>
                            <php } ?>
                            </div>
                            </td> -->
                            <td class="td-category cat">
                            <div class="edit-category-dropdown">
                                <i class="fas fa-bars"></i>
                                <div class="edit-category-dropdown-content">
                                    <form action="?page=edit&categoryid=<?php echo $row['id'] ?>" method="POST">
                                        <input type="submit" value="Update" class="btn-update">
                                    </form>
                                    <form action="?page=delete&categoryid=<?php echo $row['id'] ?>" method="POST">
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
                                    <td class="empty-article" colspan="8"><span>There is No Categories To Show<i class="fas fa-exclamation-circle"></i></span></td>
                                </tr>
                            <?php }
                        ?>
                    </tbody>
                </table>
                <a href="?page=add" class="new-member"><i class="plus fas fa-plus"></i>Add new Category</a>
            </div>
        <?php

        } elseif ($page == 'updateVisibility') {   // ----------------------------------- update visibility page ------------------------------------------------//

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $apprVisibility = $_POST['appr-visibility'];

                $categoryid = isset($_GET['categoryid']) && is_numeric($_GET['categoryid']) ? $_GET['categoryid'] : 0;

                    if ($apprVisibility == 'Visible') {
                        $sql = 'UPDATE categories SET visibility = 0 WHERE id = ?';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(array($categoryid));
                    } else {
                        $sql = 'UPDATE categories SET visibility = 1 WHERE id = ?';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(array($categoryid));
                    }

                    $msg = 'felicitation your member aprrouved';
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

                $categoryid = isset($_GET['categoryid']) && is_numeric($_GET['categoryid']) ? $_GET['categoryid'] : 0;

                    if ($apprComments == 'Allow') {
                        $sql = 'UPDATE categories SET allow_comments = 0 WHERE id = ?';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(array($categoryid));
                    } else {
                        $sql = 'UPDATE categories SET allow_comments = 1 WHERE id = ?';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(array($categoryid));
                    }
                
    
                $msg = 'felicitation your member aprrouved';
                $url = $_SERVER['HTTP_REFERER'];
                redirectFunc($msg, 0, $url);
            } else {
    
                $error = "you cant browz this page directly";
                $url = 'members.php';
                redirectFunc($error, 3, $url);
            }
        } elseif ($page == 'add') {   // ----------------------------------- Add category page ------------------------------------------------//
        ?>
            <div class="container-contact100">
                <div class="wrap-contact100">
                    <form class="contact100-form validate-form" action="?page=insert" method="POST" enctype="multipart/form-data">
                        <span class="contact100-form-title">
                            Add new category
                        </span>

                        <div class="wrap-input100">
                            <label for="name"></label>
                            <input class="input100" type="text" name="name" placeholder="Category Name" required="required">
                        </div>

                        <div class="wrap-input100">
                            <label for="cat_img"></label>
                            <input class="input100" type="file" name="cat_img" required="required">
                        </div>

                        <div class="wrap-input100">
                            <textarea class="input100" name="description" placeholder="Your description .." required="required"></textarea>
                        </div>

                        <div class="radio">
                            <div class="radiobtn">
                                <input type="radio" id="visible-yes" name="visibility" value="1" checked />
                                <label for="visible-yes">visible</label>
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

                        
                        <div class="container-contact100-form-btn">
                            <input type="submit" class="contact100-form-btn" value="Send">
                        </div>
                    </form>
                </div>
            </div>

        <?php
        } elseif ($page == 'insert') {   // ----------------------------------- insert category page ------------------------------------------------//

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $name = $_POST['name'];
                $description = $_POST['description'];
                $visibility = $_POST['visibility'];
                $comments = $_POST['comment'];
                $catimgName = $_FILES['cat_img']['name'];
                $catimgType = $_FILES['cat_img']['type'];
                $catimgTmp = $_FILES['cat_img']['tmp_name']; 

                $imageExtension = array('jpg', 'png', 'jpeg');
                $explode = explode('.', $catimgName);
                $getEndExtension = strtolower(end($explode));

                $checkName = checkUser("name", "categories", $name);

                    $errors = array();
                    if ($checkName <> 0) { $errors[] = 'vous avez utiliser un name deja existe';} elseif (empty($name)) { $errors[] =  "name cant be empty" ;} elseif (strlen($name) < 3)  { $errors[] = "The name can be greater than 2 caracter"; } elseif (strlen($name) > 40)  { $errors[] = "The name can be smaler than 40 caracter"; };
                    if (empty($description)) { $errors[] =  "prenom cant be empty" ;} elseif (strlen($description) < 6)  { $errors[] = "The name can be greater than 6 caracter"; };
                    if (!in_array($getEndExtension, $imageExtension) && !empty($catimgName)) { $errors[] = 'chose another image'; } elseif (empty($catimgName)) {$errors[] = 'chose image';} ;


                    if(!empty($errors)) {
                        foreach ($errors as $error) {
                            echo $error . '<br>';
                        }
                        $url = $_SERVER['HTTP_REFERER'];
                        redirectFunc("", 3, $url);
                    }

                    if(empty($errors)) {

                        $avatar = rand(0, 10000) . '_' . $catimgName;
                        move_uploaded_file($catimgTmp, "..\\theme\uploads\categoryImages\\" . $avatar);

                        $sql = "INSERT INTO categories (name, description, cat_img, visibility, allow_comments) VALUE (:name, :description, :img, :visibility, :comments)";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(array(
                            'name'        => $name,
                            'description' => $description,
                            'img'         => $avatar,
                            'visibility'  => $visibility,
                            'comments'    => $comments
                        ));
    
                        $msg = 'your categorie is added succefully';
                        $url = 'categories.php';
                        redirectFunc($msg, 0, $url);
                    }

            } else {

                $error = "you cant browz this page directly";
                $url = 'categories.php?page=Add';
                redirectFunc($error, 3, $url);

            }

        } elseif ($page == 'edit') {  // ----------------------------------- edit category page ------------------------------------------------//
        
            $categoryid = isset($_GET['categoryid']) && is_numeric($_GET['categoryid']) ? $_GET['categoryid'] : 0;
            
            $sql = "SELECT * FROM categories WHERE id = ? LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($categoryid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            //check if user id is numeric and affiche la page.
            if ($count > 0) { 
            ?>
            <div class="container-contact100">
                <div class="wrap-contact100">
                    <form class="contact100-form validate-form" action="?page=update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="categoryid" value="<?php echo $categoryid ?>">
                        <span class="contact100-form-title">
                            Edit your category
                        </span>

                        <div class="wrap-input100" data-validate="Please enter your name">
                            <label for="name"></label>
                            <input class="input100" type="text" name="name" placeholder="Category Name" value="<?php echo $row['name'] ?>" required="required">
                        </div>

                        <div class="wrap-input100">
                            <label for="cat_img"></label>
                            <input class="input100" type="file" name="cat_img" required="required" >
                        </div>

                        <div class="wrap-input100">
                            <textarea class="input100" name="description" placeholder="Your description .." required="required"><?php echo $row['description'] ?></textarea>
                        </div>

                        <div class="radio">
                            <div class="radiobtn">
                                <input type="radio" id="visible-yes" name="visibility" id="" value="1" <?php if( $row['visibility'] == 1 ) {echo 'checked';} ?> />
                                <label for="visible-yes">visible</label>
                            </div>
                    
                            <div class="radiobtn">
                                <input type="radio" id="visible-no" name="visibility" value="0" <?php if( $row['visibility'] == 0 ) {echo 'checked';} ?>/>
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

                        
                        <div class="container-contact100-form-btn">
                            <input type="submit" class="contact100-form-btn" value="Send">
                        </div>
                    </form>
                </div>
            </div>
        <?php } else {

            $error = "aucun category exist";
            $url = 'categories.php';
            redirectFunc($error, 3, $url);
        }

        } elseif ($page == 'update') {  // ----------------------------------- update category page ------------------------------------------------//

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
                //  get inputs value :
                $name = $_POST['name'];
                $description = $_POST['description'];
                $visibility = $_POST['visibility'];
                $comments = $_POST['comment'];
                $categoryid = $_POST['categoryid'];
                $catimgName = $_FILES['cat_img']['name'];
                $catimgType = $_FILES['cat_img']['type'];
                $catimgTmp = $_FILES['cat_img']['tmp_name']; 

                $imageExtension = array('jpg', 'png', 'jpeg');
                $explode = explode('.', $catimgName);
                $getEndExtension = strtolower(end($explode));
                
                //  check name name exist
                $chekname = $conn->prepare("SELECT name FROM categories WHERE name = ? AND id != ?");
                $chekname->execute(array($name, $categoryid));
                $chekCount = $chekname->rowCount();

                $errors = array();
                if ($chekCount <> 0) { $errors[] = 'vous avez utiliser un name deja existe';} elseif (empty($name)) { $errors[] =  "name cant be empty" ;} elseif (strlen($name) < 3)  { $errors[] = "The name can be greater than 2 caracter"; } elseif (strlen($name) > 30)  { $errors[] = "The name can be smaler than 15 caracter"; };
                if (empty($description)) { $errors[] =  "description cant be empty" ;} elseif (strlen($description) < 10)  { $errors[] = "The name can be greater than 2 caracter"; };
                if (!in_array($getEndExtension, $imageExtension) && !empty($catimgName)) { $errors[] = 'chose another image'; } elseif (empty($catimgName)) {$errors[] = 'chose image';} ;


                
                if(!empty($errors)) {
                    foreach($errors as $error) {
                        echo $error . '<br>';
                    };
                    $url = $_SERVER['HTTP_REFERER'];
                    redirectFunc("", 3, $url);
    
                }
                // update inputs value :
                if (empty($errors)) {

                    $avatar = rand(0, 10000) . '_' . $catimgName;
                    move_uploaded_file($catimgTmp, "..\\theme\uploads\categoryImages\\" . $avatar);

                    $sql = 'UPDATE categories SET name = ?, description = ?, cat_img = ?, visibility = ?, allow_comments = ? WHERE id = ?';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array($name, $description, $avatar, $visibility, $comments, $categoryid));
        
                    $message = 'félicitation vous avez modifiez un nombre de : ' . $stmt->rowCount();
                    $url = 'categories.php';
                    redirectFunc($message, 0, $url);
    
                }
    
            } else {
    
                $error = "you cant browz this page directly";
                $url = $_SERVER['HTTP_REFERER'];
                redirectFunc($error, 3, $url);
    
    
            }


        } elseif ($page == 'delete') {  // ----------------------------------- delete category page ------------------------------------------------//

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
                $categoryid = isset($_GET['categoryid']) && is_numeric($_GET['categoryid']) ? $_GET['categoryid'] : 0;
                
                $sql = 'DELETE FROM categories WHERE id = ?';
                $stmt = $conn->prepare($sql);
                $stmt->execute(array($categoryid));
                
                $msg = 'your category is deleted';
                $url = $_SERVER['HTTP_REFERER'];
                redirectFunc($msg, 0, $url);
            } else {
                
                $error = "you cant browz this page directly";
                $url = 'categories.php';
                redirectFunc($error, 3, $url);
    
            }
        
        } else {
    
            $error = "this page is not definde";
            $url = 'dashbord.php';
            redirectFunc($error, 3, $url);


        }

    include $tpl . "footer.php ";

} else {

    header('Location: index.php');
    exit;

}