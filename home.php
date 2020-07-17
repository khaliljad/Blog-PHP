<?php 

    session_start();
    $title = 'Home';
    include 'init.php';


// if (isset($_SESSION['userid'])) {
    ?>
    <div class="container">
    <main>

        <!-- --------- vidoe header ----------------->
        <div class="v-header">
            <div class="screen">
            <!-- large file -->
                <img src="theme/uploads/articleImages/793_rock-731140_1280.jpg" alt="">
            </div>
            <div class="overlay"></div>
            <div class="centent">
                <h1>Paragliders in Alpine Valley</h1>
                <p>Drone shot revealing two paragliders over a valley in the Julian Alps, Slovenia. </p>
                <a href="#" class="btn">Read More</a>
            </div>
        </div>

        <!------------ cards1 ----------------->

        <section class="flex-card">
        <?php 
            //comments count: 
            $rows = SelectArtcile(18); 
            if(!empty($rows)) {
            foreach($rows as $row) {
                $countCmt = $conn->prepare("SELECT COUNT(comment) FROM comments WHERE id_article = ?");
                $countCmt->execute(array($row['id_article']));
                $countComment = $countCmt->fetchColumn();

            $CatVsblt = catVisibility($row['id_category']);
            if ($CatVsblt > 0) {
        ?>
            <a class="card-link" href="articles.php?articleId=<?php echo $row['id_article']; ?>">
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
            } }
            } else {
                echo "<p class='empty-art'>There is No Articles To Show</p>";
            }
        ?>
        </section>


        <!-- --------- vidoe mid ----------------->
        <div class="v-header">
            <div class="screen">
            <!-- large file -->
                <img src="theme/uploads/articleImages/3115_beach-1835213_1280.jpg" alt="">
            </div>
            <div class="overlay"></div>
            <div class="centent-fa">
                <h1>the Taj Mahal Through an Archway</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ab, quidem sed. Porro quae eos eum ad
                    accusamus cum veritatis recusandae inventore et nostrum. Voluptas nihil fuga non sunt odio
                    perferendis? Lorem, ipsum dolor sit amet consectetur adipisicing elit.</p>
                <a href="#" class="btn">Read More</a>
            </div>
        </div>

        <!------------ cards2 ----------------->

        <section class="flex-card">
        <?php 
            //comments count: 
            $rows = SelectArtcile(8); 
            if(!empty($rows)) {
            foreach($rows as $row) {
                $countCmt = $conn->prepare("SELECT COUNT(comment) FROM comments WHERE id_article = ?");
                $countCmt->execute(array($row['id_article']));
                $countComment = $countCmt->fetchColumn();

                $CatVsblt = catVisibility($row['id_category']);
                if ($CatVsblt > 0) {
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
                } }
                } else {
                    echo "<p class='empty-art'>There is No Articles To Show</p>";
                }
            ?>
        </section>

        <!-- --------- vidoe header ----------------->
        <div class="v-header">
            <div class="screen">
            <!-- large file -->
                <img src="theme/uploads/articleImages/2959_safari.jpg" alt="">
            </div>
            <div class="overlay"></div>
            <div class="centent-fa">
                <h1>Pair of Lionesses Walking Together</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ab, quidem sed. Porro quae eos eum ad
                    accusamus cum veritatis recusandae inventore et nostrum. Voluptas nihil fuga non sunt odio
                    perferendis? Lorem, ipsum dolor sit amet consectetur adipisicing elit.</p>
                <a href="#" class="btn">Read More</a>
            </div>
        </div>

        <!------------ cards 3 ----------------->
        <section class="flex-card">
        <?php 
            //comments count: 
            $rows = SelectArtcile(15); 
            if(!empty($rows)) {
            foreach($rows as $row) {
                $countCmt = $conn->prepare("SELECT COUNT(comment) FROM comments WHERE id_article = ?");
                $countCmt->execute(array($row['id_article']));
                $countComment = $countCmt->fetchColumn();

                $CatVsblt = catVisibility($row['id_category']);
                if ($CatVsblt > 0) {
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
                } else {
                    echo "<p class='empty-art'>There is No Articles To Show</p>";
                }
                }
                } else {
                    echo "<p class='empty-art'>There is No Articles To Show</p>";
                } 
            ?>
        </section>
        
        
    </main>

    </div>
    <?php

    include $tpl . "footer.php ";

// } else {
//     header('location:login.php');
//     exit;
// }
