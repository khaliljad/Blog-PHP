<?php 
session_start();
$title = 'Categories';
include 'init.php';

// if (isset($_SESSION['userid'])) {

    //get category id:
    $categoryId = isset($_GET['categoryId']) && is_numeric($_GET['categoryId']) ? $_GET['categoryId'] : 0;

    //SELECT 
    $sql = "SELECT * FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array($categoryId));
    $row = $stmt->fetch();
    ?>
    <div class="container">
        <div class="categories-header">
            <img class="category-image-header" src="theme/uploads/categoryImages/<?php echo $row['cat_img'] ?>" alt="">
            <div class="absolute-position">
                <span class="contact100-form-title"><?php echo $row['name']; ?></span>
                <h2 class="category-description"><?php echo $row['description']; ?></h2>
            </div>
        </div>
        <section class="flex-card">
        <?php 
            //comments count: 
            $rows = SelectArtcile($row['id'], 'id_category' ,100);
            if (!empty($rows)) {
            foreach($rows as $row) {
                $countCmt = $conn->prepare("SELECT COUNT(comment) FROM comments WHERE id_article = ?");
                $countCmt->execute(array($row['id_article']));
                $countComment = $countCmt->fetchColumn();
        ?>
        <a class="card-link"  href="articles.php?articleId=<?php echo $row['id_article']; ?>">
            <div class="card">
                <div class="card-image">
                    <img class="test-image" src="theme/uploads/articleImages/<?php echo $row['image'] ?>" alt="">
                </div>
                <div class="card-text">
                    <h2><?php echo $row['titre'] ?></h2>
                    <span class="date"><?php echo $row['date_publication'] ?></span>
                    <p><?php echo $row['contenu'] ?></p>
                </div>
                <div class="card-stats">
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
                
    </div>

    <?php

    include $tpl . "footer.php ";

// } else {
// header('location:login.php');
// exit;
// }