<?php 

if (isset($_SESSION['userid'])) {
    $sessionId = $_SESSION['userid'];
}

    //select name of categories: 
    $catName = $conn->prepare("SELECT id,name FROM categories WHERE visibility = 1");
    $catName->execute();
    $rows = $catName->fetchAll();

    //select users statement:
    $userSlct = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $userSlct->execute(array($sessionId));
    $user = $userSlct->fetch();
?>
<nav>
    <div class="nav-flex">
        <div id="title">
            <a href="home.php">Khalil Blog</a>
        </div>

        <div id="a1">
            <a href="home.php">Home</a>
            <a href="profil.php">Profil</a>
            <div class="nav-dropdown category">
                <a class="nav-category" href="#">Categories<i class="fas fa-angle-down"></i></a>
                <div class="nav-dropdown-content categoreis">
                    <?php foreach($rows as $row) { ?>
                        <a id="a-category" href="categories.php?categoryId=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
                    <?php } ?>
                </div>
            </div>
            
        </div>
    </div>
    <?php if (isset($_SESSION['userid'])) { ?>
    <div class="nav-dropdown">
        <div class="nav-img-flex">
            <a href="profil.php"><img class="nav-image" src="<?php if (!empty($user['userimg'])) { echo "theme/uploads/userImages/" . $user['userimg']; } else { echo "theme/images/users/unnamed.jpg"; } ?>" alt="nav-image"></a>
            <span class="username-dropdown"><?php echo $user['prenom']; ?><i class="fas fa-angle-down"></i></span>
        </div>
        <div class="nav-dropdown-content">
            <a href="profil.php?page=edit"><i class="fas fa-user-edit"></i>Edit profil</a>
            <a href="profil.php?page=editPassword"><i class="fas fa-unlock-alt"></i>Edit Password</a>
            <?php 
                $adminSlct = $conn->prepare("SELECT * FROM users WHERE groupID = 1");
                $adminSlct->execute(array());
                $admin = $adminSlct->fetch();
                if ($admin['id'] == $sessionId) {
            ?>
            <a href="admin-control-pannel/dashbord.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
            <?php } ?>
            <div class="logout">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i><?php echo Lang('Se deconnecter'); ?></a>
            </div>
        </div>
    </div>
    <?php } else { ?>
                <a id="singInNav" href="login.php">Sign In</a>
    <?php } ?>
</nav>