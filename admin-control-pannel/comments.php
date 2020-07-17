<?php 

session_start();
// ******* title for header ******* \\
$title = '';

// ******* check if user login ****** \\
if (isset($_SESSION['userName'])) {

    include 'init.php';

    // ******** check and get the name of page ******** \\
    $singlPage = isset($_GET['page']) ? $page = $_GET['page'] : $page = 'manage';

        if ($page == 'manage') {

            $sql = "SELECT comments.*, users.nom, users.prenom, users.userimg, articles.titre FROM comments 
                    INNER JOIN users ON users.id = comments.id_user
                    INNER JOIN articles ON articles.id_article = comments.id_article
                    ORDER BY date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll();

        ?> 
            <div class="manage-container">
                <table class="category-table">
                    <thead>
                        <tr class="tr-category">
                            <th class="th-category cat">comment</th>
                            <th class="th-category cat">Full Name</th>
                            <th class="th-category cat">Article</th>
                            <th class="th-category cat">Date</th>
                            <th class="th-category cat">Status</th>
                            <th class="th-category cat"><i class="fas fa-angle-down"></i></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                            if (!empty($rows)) {
                            foreach( $rows as $row ) { 
                        ?>
                        <tr class="tr-category">
                            <td class="td-category cat bold"><?php echo $row['comment']; ?></td>
                            <td class="td-category cat"><div class="flex-height"><img class="nav-image" src="<?php if (!empty($row['userimg'])) { echo "../theme/uploads/userImages/" . $row['userimg']; } else { echo "../theme/images/users/unnamed.jpg"; } ?>" alt="user-image"><?php echo $row['prenom'] . ' ' . $row['nom']; ?></div></td>
                            <td class="td-category cat"><?php echo $row['titre']; ?></td>
                            <td class="td-category cat"><?php echo $row['date']; ?></td>
                            <td class="td-category cat">
                            <div class="btn-control">
                            <?php 
                                if( $row['status'] == 1) { ?> 
                                    <form action="?page=approve&commentid=<?php echo $row['id']; ?>" method="POST"> 
                                        <input type="submit" name="appr-visibility" value="Approved" id="approuv"> 
                                    </form>
                                <?php } else { ?>
                                    <form action="?page=approve&commentid=<?php echo $row['id']; ?>" method="POST"> 
                                        <input type="submit" name="appr-visibility" value="Disabled" id="disapprouv"> 
                                    </form>
                            <?php } ?>
                            </div>
                            </td>
                            <td class="td-category cat">
                            <div class="edit-category-dropdown">
                                <i class="fas fa-bars"></i>
                                <div class="edit-category-dropdown-content">
                                    <form action="?page=edit&commentid=<?php echo $row['id'] ?>" method="POST">
                                        <input type="submit" value="Update" class="btn-update">
                                    </form>
                                    <form action="?page=delete&commentid=<?php echo $row['id'] ?>" method="POST">
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
                                    <td class="empty-article" colspan="8"><span>There is No Comments To Show<i class="fas fa-exclamation-circle"></i></span></td>
                                </tr>
                            <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php

        } elseif ($page == 'approve') {

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $apprVisibility = $_POST['appr-visibility'];

                $commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? $_GET['commentid'] : 0;

                    if ($apprVisibility == 'Approved') {
                        $sql = 'UPDATE comments SET status = 0 WHERE id = ?';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(array($commentid));
                    } else {
                        $sql = 'UPDATE comments SET status = 1 WHERE id = ?';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(array($commentid));
                    }

                    $msg = 'felicitation your cpmment aprrouved';
                    $url = $_SERVER['HTTP_REFERER'];
                    redirectFunc($msg, 0, $url);
            } else {

                $error = "you cant browz this page directly";
                $url = 'comments.php';
                redirectFunc($error, 3, $url);
            }

        } elseif ($page == 'edit') {

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
                    <form class="contact100-form validate-form" action="?page=update" method="POST">
                        <input type="hidden" name="commentid" value="<?php echo $commentid ?>">
                        <span class="contact100-form-title">
                            Edit your Comment
                        </span>

                        <div class="wrap-input100">
                            <textarea class="input100" name="comment" placeholder="Your Comment .." required="required"><?php echo $row['comment'] ?></textarea>
                        </div>

                        <div class="radio">
                            <div class="radiobtn">
                                <input type="radio" id="visible-yes" name="visibility" value="1" <?php if( $row['status'] == 1 ) {echo 'checked';} ?> />
                                <label for="visible-yes">visible</label>
                            </div>
                    
                            <div class="radiobtn">
                                <input type="radio" id="visible-no" name="visibility" value="0" <?php if( $row['status'] == 0 ) {echo 'checked';} ?>/>
                                <label for="visible-no">hidden</label>
                            </div>
                        </div>
                        
                        <div class="container-contact100-form-btn">
                            <input type="submit" class="contact100-form-btn" value="Send">
                        </div>
                    </form>
                </div>
            </div>
            <?php } else {

                $error = "aucun comment exist";
                $url = 'comments.php';
                redirectFunc($error, 3, $url);
            }

        } elseif ($page == 'update') {

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
                //  get inputs value :
                $comment = $_POST['comment'];
                $visibility = $_POST['visibility'];
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

                    $sql = 'UPDATE comments SET comment = ?, status = ? WHERE id = ?';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array($comment, $visibility, $commentid));
        
                    $message = 'félicitation vous avez modifiez un nombre de : ' . $stmt->rowCount();
                    $url = 'comments.php';
                    redirectFunc($message, 0, $url);
    
                }
    
            } else {
    
                $error = "you cant browz this page directly";
                $url = $_SERVER['HTTP_REFERER'];
                redirectFunc($error, 3, $url);
            }


        } elseif ($page == 'delete') {

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


        } else {

        };

    include $tpl . "footer.php ";

} else {

    header('Location: index.php');
    exit;

}

