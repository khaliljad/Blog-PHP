<?php
session_start();
$title = 'Dashboard';

if(isset($_SESSION['userName'])) {

    include 'init.php';

    //SELECT MEMBERS STATMENT:
    $memqsl = "SELECT * FROM users WHERE groupID <> 1 AND approuv = 0 ORDER BY id DESC ";
    $memStmt = $conn->prepare($memqsl);
    $memStmt->execute();
    $memRows = $memStmt->fetchAll();

    //SELECT ARTICLES STATMENT:
    $artCount = 3;
    $artsql = "SELECT * FROM articles ORDER BY id_article DESC LIMIT $artCount";
    $artStmt = $conn->prepare($artsql);
    $artStmt->execute();
    $artRows = $artStmt->fetchAll();

    //SELECT COMMENTS STATMENT:
    $comSql = "SELECT comments.*, users.prenom FROM comments
                INNER JOIN users ON users.id = comments.id_user
                WHERE status = 0";
    $comStmt = $conn->prepare($comSql);
    $comStmt->execute();
    $comRow = $comStmt->fetchAll();
    
    ?>
        <div class="dashboard-container">

        <!-- ------------------- left ----------------- -->
            <div class="dashboard-left">
                <div class="links-container">
                    <a class="dashboard-link" href="dashbord.php">dashboard</a>  
                    <a class="dashboard-link" href="members.php"><?php echo Lang('Profil'); ?></a>
                    <a class="dashboard-link" href="categories.php"><?php echo Lang('Catégories'); ?></a>
                    <a class="dashboard-link" href="articles.php"><?php echo Lang('articles'); ?></a>
                    <a class="dashboard-link" href="comments.php"><?php echo Lang('commentaires'); ?></a>
                </div>
            </div>

        <!-- ------------------- right ----------------- -->
            <div class="dashboard-right">
                <h1 id="member-title">Dashboard</h1>
                <div class="blocs">
                    <a href="members.php">
                        <div class="total-members">
                            <i class="fas fa-users fa-6x"></i>
                            <p class="blocs-title">Total Members</p>
                            <div class="bloc-number"> <?php echo totalItems('userName', 'users WHERE groupID <> 1'); ?> </div>
                        </div>
                    </a>
                    <a href="comments.php">
                        <div class="pending-members">
                            <i class="fas fa-comments fa-6x"></i>
                            <p class="blocs-title">Total Comments</p>
                            <div class="bloc-number"> <?php echo totalItems('id', 'Comments'); ?> </div>
                        </div>
                    </a>
                    <a href="categories.php">
                        <div class="total-articles">
                            <i class="fas fa-clone fa-6x"></i>
                            <p class="blocs-title">Total Categories</p>
                            <div class="bloc-number"><?php echo totalItems('id', 'categories'); ?></div>
                        </div>
                    </a>
                    <a href="articles.php">
                    <div class="total-comments">
                        <i class="fas fa-newspaper fa-6x"></i>
                        <p class="blocs-title">Total artciles</p>
                        <div class="bloc-number"><?php echo totalItems('id_article', 'articles'); ?></div>
                    </div>
                    </a>
                </div><!--  end blocs -->


                <div class="full-mem-table">
                    <table class="category-table dashboard">
                        <thead>
                            <tr class="tr-latest">
                                <th class="th-latest" colspan="5"><i class="fas fa-users"></i>Pending Members : <?php echo totalItems('userName', 'users WHERE groupID <> 1 AND approuv = 0'); ?> </th>
                            </tr>
                            <tr class="tr-category dashboard">
                                <th class="th-category cat">#ID</th>
                                <th class="th-category">User Name</th>
                                <th class="th-category">Email</th>
                                <th class="th-category">articles added</th>
                                <th class="th-category">comments added</th>
                                <th class="th-category">Registred date</th>
                                <th class="th-category">Status</th>
                                <th class="th-category cat"><i class="fas fa-angle-down dashboard"></i></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                if(!empty($memRows)) {
                                foreach($memRows as $row) { 
                            ?>
                            <tr class="tr-category body">
                                <td class="td-category" id="culumn-id"># <?php echo $row['id']; ?></td>
                                <td class="td-category cat"><?php echo $row['userName']; ?></td>
                                <td class="td-category cat"><?php echo $row['email']; ?></td>
                                <td class="td-category cat">
                                    <?php
                                        $titStmt = $conn->prepare("SELECT COUNT(titre) FROM articles WHERE id_user = ?");
                                        $titStmt->execute(array($row['id']));
                                        $countTitle = $titStmt->fetchColumn();
                                        echo $countTitle;
                                    ?>
                                </td>
                                <td class="td-category cat">
                                    <?php
                                        $comStmt = $conn->prepare("SELECT COUNT(comment) FROM comments WHERE id_user = ?");
                                        $comStmt->execute(array($row['id']));
                                        $countTitle = $comStmt->fetchColumn();
                                        echo $countTitle;
                                    ?>
                                </td>
                                <td class="td-category cat"><?php echo $row['date']; ?></td>
                                <td class="td-category cat" id="control">
                                <div class="btn-control">
                                    <?php 
                                        if( $row['approuv'] == 1) { ?> 
                                            <form action="members.php?page=updatePrv&userid=<?php echo $row['id'] ?> " method="POST"> 
                                                <input type="submit" name="appr" value="Active" id="approuv"> 
                                            </form>
                                        <?php } else { ?>
                                            <form action="members.php?page=updatePrv&userid=<?php echo $row['id'] ?> " method="POST"> 
                                                <input type="submit" name="appr" value="Disabled" id="disapprouv"> 
                                            </form>
                                    <?php } ?>
                                </div>
                                </td>
                                <td class="td-category cat">
                                    <div class="edit-category-dropdown">
                                        <i class="fas fa-bars"></i>
                                        <div class="edit-category-dropdown-content">
                                            <form action="members.php?page=edit&userid=<?php echo $row['id'] ?>" method="POST">
                                                <input type="submit" value="Update" class="btn-update">
                                            </form>
                                            <form action="membres.php?page=delete&userid=<?php echo $row['id'] ?>" method="POST">
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
                                        <td class="empty-article" colspan="8"><a href="comments.php"><span>There is No Members To Show<i class="fas fa-user-times"></i></span></a></td>
                                    </tr>
                                <?php }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-container">
                    <table class="category-table dashboard">
                        <thead>
                            <tr class="tr-latest">
                                <th class="th-latest" colspan="5"><i class="fas fa-comments"></i>Comments Not Approved : <?php echo totalItems('id', 'comments WHERE status = 0'); ?></th>
                            </tr>
                            <tr class="tr-category dashboard">
                                <th class="th-category cat">Comments</th>
                                <th class="th-category">Full Name</th>
                                <th class="th-category">Registred date</th>
                                <th class="th-category">Status</th>
                                <th class="th-category cat"><i class="fas fa-angle-down dashboard"></i></th>
                            </tr>
                        </thead>

                        <tbody>
                            
                            <?php 
                                if(!empty($comRow)) {
                                foreach($comRow as $row) { 
                            ?>
                            <tr class="tr-category body">
                                <td class="td-category" id="culumn-id"># <?php echo $row['comment']; ?></td>
                                <td class="td-category cat"><?php echo $row['prenom']; ?></td>
                                <td class="td-category cat"><?php echo $row['date']; ?></td>
                                <td class="td-category cat" id="control">
                                <div class="btn-control">
                                    <?php 
                                        if( $row['status'] == 1) { ?> 
                                            <form action="comments.php?page=approve&commentid=<?php echo $row['id']; ?>" method="POST"> 
                                                <input type="submit" name="appr-visibility" value="Approved" id="approuv"> 
                                            </form>
                                        <?php } else { ?>
                                            <form action="comments.php?page=approve&commentid=<?php echo $row['id']; ?>" method="POST"> 
                                                <input type="submit" name="appr-visibility" value="Disabled" id="disapprouv"> 
                                            </form>
                                    <?php } ?>
                                </div>
                                </td>
                                <td class="td-category cat">
                                    <div class="edit-category-dropdown">
                                        <i class="fas fa-bars"></i>
                                        <div class="edit-category-dropdown-content">
                                            <form action="comments.php?page=edit&commentid=<?php echo $row['id'] ?>" method="POST">
                                                <input type="submit" value="Dpdate" class="btn-update">
                                            </form>
                                            <form action="comments.php?page=delete&commentid=<?php echo $row['id'] ?>" method="POST">
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
                                     <td class="empty-comment" colspan="5"><a href="comments.php"><span>All Comments is approved<i class="fas fa-thumbs-up"></i></span></a></td>
                                 </tr>
                             <?php }
                        ?>
                        </tbody>
                    </table>

                    <table class="category-table dashboard">
                        <thead>
                            <tr class="tr-latest">
                                <th class="th-latest" colspan="5"><i class="fas fa-newspaper"></i>Latest <?php echo $artCount ?> Articles added</th>
                            </tr>
                            <tr class="tr-category dashboard">
                                <th class="th-category cat">#ID</th>
                                <th class="th-category">Title</th>
                                <th class="th-category">Status</th>
                                <th class="th-category">Comments</th>
                                <th class="th-category cat"><i class="fas fa-angle-down dashboard"></i></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                if(!empty($artRows)) {
                                foreach($artRows as $row) { 
                            ?>
                            <tr class="tr-category body">
                                <td class="td-category" id="culumn-id"># <?php echo $row['id_article']; ?></td>
                                <td class="td-category cat"><?php echo $row['titre']; ?></td>
                                <td class="td-category cat" id="control">
                                    <div class="btn-control">
                                        <?php 
                                        if( $row['status'] == 1) { ?> 
                                            <form action="articles.php?page=updateVisibility&articlid=<?php echo $row['id_article']; ?>" method="POST"> 
                                                <input type="submit" name="appr-status" value="approved" id="approuv"> 
                                            </form>
                                        <?php } else { ?>
                                            <form action="articles.php?page=updateVisibility&articlid=<?php echo $row['id_article']; ?>" method="POST"> 
                                               <input type="submit" name="appr-status" value="Disabled" id="disapprouv"> 
                                            </form>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="td-category cat">
                                    <div class="btn-control">
                                    <?php 
                                        if( $row['allow_comments'] == 1) { ?> 
                                            <form action="articles.php?page=updateComments&articlid=<?php echo $row['id_article']; ?>" method="POST"> 
                                                <input type="submit" name="appr-comments" value="Allow" id="approuv"> 
                                            </form>
                                        <?php } else { ?>
                                            <form action="articles.php?page=updateComments&articlid=<?php echo $row['id_article']; ?>" method="POST"> 
                                                <input type="submit" name="appr-comments" value="Blocked" id="disapprouv" class="disapprouv-comments"> 
                                            </form>
                                    <?php } ?>
                                    </div>
                                </td>
                                <td class="td-category cat">
                                    <div class="edit-category-dropdown">
                                        <i class="fas fa-bars"></i>
                                        <div class="edit-category-dropdown-content">
                                            <form action="articles.php?page=edit&articlid=<?php echo $row['id_article'] ?>" method="POST">
                                                <input type="submit" value="Update" class="btn-update">
                                            </form>
                                            <form action="articles.php?page=delete&articlid=<?php echo $row['id_article'] ?>" method="POST">
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
                                        <td class="empty-article" colspan="5"><a href="articles.php"><span>There is No Articles To Show<i class="far fa-calendar-times"></i></span></a></td>
                                    </tr>
                                <?php }
                            ?>
                        </tbody>
                    </table>
                </div> <!-- end table-container -->
            </div> <!--  end dashboard-right -->
        </div> <!-- end dashboard-container -->

    <?php
    include $tpl . "footer.php ";

} else {
    header('Location: index.php');
    exit;
}



?>
