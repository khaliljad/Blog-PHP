<?php 
session_start();
$title = 'Articles';
// $noNav = '';
include 'init.php';

if (isset($_SESSION['userid'])) {
    $sessionId = $_SESSION['userid'];
}


   $singlPage = isset($_GET['page']) ? $page = $_GET['page'] : $page = 'article';

    //get category id:
    $articleId = isset($_GET['articleId']) && is_numeric($_GET['articleId']) ? $_GET['articleId'] : 0;

    //SELECT Article statment:
    $sql = "SELECT articles.*, users.* FROM articles
            INNER JOIN users ON users.id = articles.id_user
            WHERE id_article = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array($articleId));
    $row = $stmt->fetch();

    $views = $row['views'] + 1;
    $countViews = $conn->prepare("UPDATE articles SET views = ? WHERE id_article = ?");
    $countViews->execute(array($views, $articleId));

    //SELECT ADMIN: 
    $adminSlct = $conn->prepare("SELECT * FROM users WHERE groupID = 1");
    $adminSlct->execute(array());
    $admin = $adminSlct->fetch();

    //add comments statment:
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $comment = $_POST['comment'];
        $comUser = $sessionId;
        $artId = $row['id_article'];

        if($_SESSION['userid'] == $admin['id']) {

            $addSql = "INSERT INTO `comments` (`comment`, `status`, `id_user`, `id_article`) VALUES (:com, 1, :usr, :art)";
            $addCom = $conn->prepare($addSql);
            $addCom->execute(array(
                'com' => $comment,
                'usr' => $comUser,
                'art' => $artId
            ));

        } else {

            $addSql = "INSERT INTO `comments` (`comment`, `id_user`, `id_article`) VALUES (:com, :usr, :art)";
            $addCom = $conn->prepare($addSql);
            $addCom->execute(array(
                'com' => $comment,
                'usr' => $comUser,
                'art' => $artId
            ));
        }
    }

    if($page == 'article') { //------------------------------------------------ Default page -------------------------------------------//
    ?>
    <div class="container ">
        <div class="article-header">
            <img class="article-image-header" src="theme/uploads/articleImages/<?php echo $row['image'] ?>" alt="">
            <div class="absolute-position">
               <span class="contact100-form-title article"><?php echo $row['titre']; ?></span>
            </div>
        </div>

        <div class="article-content">
            <div class="content-info">
                <img class="image-info" src="<?php if (!empty($row['userimg'])) { echo "theme/uploads/userImages/" . $row['userimg']; } else { echo "theme/images/users/unnamed.jpg"; } ?>" alt="userimage">
                <h4><?php echo $row['prenom'] . ' ' . $row['nom']; ?></h4>
                <span class="date-pub"><i class="far fa-clock"></i><?php echo $row['date_publication']; ?></span>

                <?php
                if (isset($_SESSION['userid']) > 0) { 
                    $check = $conn->prepare("SELECT id FROM likes WHERE user = ? AND article = ?");
                    $check->execute(array($sessionId, $row['id_article']));
                    if ($check->rowCount() == 0) { ?>
                        <a class="heart-link" href="articles.php?page=likes&articleid=<?php echo $row['id_article'] ?>&userid=<?php echo $sessionId; ?>"><i class="far fa-heart fa-3x"></i></a>
                    <?php } else { ?>
                        <a class="heart-link" href="articles.php?page=likes&articleid=<?php echo $row['id_article'] ?>&userid=<?php echo $sessionId; ?>"><i class="fas fa-heart fa-3x"></i></a>
                    <?php } } ?>

            </div>
            <div class="content-center">
                <p id="content"><?php echo $row['contenu']; ?></p>
            </div>
        </div>

        <?php 
        
            $getCom = $conn->prepare("SELECT comments.*, users.nom, users.prenom, users.userimg FROM comments
                                     INNER JOIN users ON users.id = comments.id_user
                                     WHERE id_article = ? AND status = 1");
            $getCom->execute(array($row['id_article']));
            $rowCom = $getCom->fetchAll();
            $firstCount = $getCom->rowCount();
            ?>

<h2 class="comment-title"><i class="far fa-comments"></i>Commentaires</h2>


        <?php foreach($rowCom as $com) {?>
            <div class="article-comments">
                <div class="comments-conatainer">
                    <div class="comment-info">
                        <img class="image-comment" src="theme/uploads/userImages/<?php echo $com['userimg']; ?>" alt="userimage">
                        <div class="title-span">
                            <div class="comment-modify-flex">
                                <h4><?php echo $com['prenom'] . ' ' . $com['nom']; ?></h4>

                                <?php if($com['id_user'] == $sessionId) { 
                                        if($com['status'] == 0) {
                                ?>
                                    <p class="not-approved">Not approved</p>
                                <?php } } ?>

                            </div>

                            <span class="date-pub"><i class="far fa-clock"></i><?php echo $com['date']; ?></span>
                            <div class="comment-modify-flex">
                                <p><?php echo $com['comment']; ?></p>
                                <?php if($com['id_user'] == $sessionId) { ?>
                                <div class="edit-category-dropdown">
                                    <i class="fas fa-bars article"></i>
                                    <div class="edit-category-dropdown-content">
                                        <form action="profil.php?page=editCommments&commentid=<?php echo $com['id'] ?>" method="POST">
                                            <input type="submit" value="Update" class="btn-update">
                                        </form>
                                        <form action="profil.php?page=deleteComment&commentid=<?php echo $com['id'] ?>" method="POST">
                                            <input type="submit" value="Delete" class="btn-delete" onclick="return confirm('Are you sure?');">
                                        </form>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } 
        
        $disablCom = $conn->prepare("SELECT comments.*, users.nom, users.prenom, users.userimg, users.approuv FROM comments
                                    INNER JOIN users ON users.id = comments.id_user
                                    WHERE id_user = ? AND id_article = ? AND status = 0");
        $disablCom->execute(array($sessionId, $row['id_article']));
        $disComs = $disablCom->fetchAll();
        $secondCount = $getCom->rowCount();
        
         
        foreach($disComs as $disCom) {?>
            <div class="article-comments">
                <div class="comments-conatainer">
                    <div class="comment-info">
                        <img class="image-comment" src="theme/uploads/userImages/<?php echo $disCom['userimg']; ?>" alt="userimage">
                        <div class="title-span">
                            <div class="comment-modify-flex">
                                <h4><?php echo $disCom['prenom'] . ' ' . $disCom['nom']; ?></h4>

                                <?php
                                    if($disCom['id_user'] == $sessionId) { 
                                        if($disCom['status'] == 0) {
                                ?>
                                    <p class="not-approved">Not approved</p>
                                <?php } } ?>

                            </div>

                            <span class="date-pub"><i class="far fa-clock"></i><?php echo $disCom['date']; ?></span>
                            <div class="comment-modify-flex">
                                <p><?php echo $disCom['comment']; ?></p>
                                <?php if($disCom['id_user'] == $sessionId) { ?>
                                <div class="edit-category-dropdown">
                                    <i class="fas fa-bars article"></i>
                                    <div class="edit-category-dropdown-content">
                                        <form action="profil.php?page=editCommments&commentid=<?php echo $disCom['id'] ?>" method="POST">
                                            <input type="submit" value="Update" class="btn-update">
                                        </form>
                                        <form action="profil.php?page=deleteComment&commentid=<?php echo $disCom['id'] ?>" method="POST">
                                            <input type="submit" value="Delete" class="btn-delete" onclick="return confirm('Are you sure?');">
                                        </form>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
            if(($firstCount && $secondCount) == 0) { {echo "<p class='acount-approved comment'><i class='fas fa-exclamation'></i>There is No Comment To Show</p>" ;} }
        ?>

        <?php if (isset($_SESSION['userid']) && ArtComments($row['id_article']) > 0) { ?>
            <h2 class="comment-title"><i class="far fa-comment"></i>Laisser un commentaire</h2>
            <div class="add-comment">
                <?php
                    $appSlct = $conn->query("SELECT approuv FROM users WHERE id = {$sessionId}");
                    foreach($appSlct as $apprv) {
                        
                    if($apprv['approuv'] == 1) { ?>
                    <form action="articles.php?articleId=<?php echo $articleId; ?>" method="POST">
                        <textarea name="comment"" rows="8" placeholder="Votre commentaire"></textarea>
                        <input type="submit" name="submit">
                    </form>
                <?php } else { {echo "<p class='acount-not-approved comment'><i class='fas fa-exclamation'></i>Your Account is Not Approved</p>" ;} }}; ?>
            </div>
        <?php } ?>
        
                
     </div> <!-- **end container** -->

    <?php

    } elseif ($page == 'likes') { // ------------------------------------------ like page --------------------------------------------//

            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
            $idArt = isset($_GET['articleid']) && is_numeric($_GET['articleid']) ? $_GET['articleid'] : 0;
            

            $check = $conn->prepare("SELECT id FROM likes WHERE user = ? AND article = ?");
            $check->execute(array($userid, $idArt));
            
            if ($check->rowCount() == 0) {

                $inssql = ("INSERT INTO `likes` (`user`, `article`) VALUES (:user, :article);");
                $insert = $conn->prepare($inssql);
                $insert->execute(array(
                    'user'    => $userid,
                    'article' => $idArt
                ));
                    $msg = 'felicitation your article is liked';
                    $url = $_SERVER['HTTP_REFERER'];
                    redirectFunc($msg, 0, $url);
            } else {
                $delete_like = $conn->prepare("DELETE FROM likes WHERE user = ? AND article = ?");
                $delete_like->execute(array($userid, $idArt));

                $msg = 'vous avez retirez le jaime';
                $url = $_SERVER['HTTP_REFERER'];
                redirectFunc($msg, 0, $url);
            }
                
            
            
        

    }


    include $tpl . "footer.php ";

// } else {
// header('location:login.php');
// exit;
// }