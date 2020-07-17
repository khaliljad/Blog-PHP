<?php 
$userid = $_SESSION['userId'];
$sql = "SELECT userName FROM users WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute(array($userid));
$row = $stmt->fetch();

// $stmt = $conn->prepare("SELECT userName FROM users WHERE id = ?");
// $stmt->execute(array($userid));
// $row = $stmt->fetch();


?>
    <nav>
        <div id="a1">   
            <a href="members.php"><?php echo Lang('Profil'); ?></a>
            <a href="categories.php"><?php echo Lang('Catégories'); ?></a>
            <a href="articles.php"><?php echo Lang('articles'); ?></a>
            <a href="comments.php"><?php echo Lang('commentaires'); ?></a>
        </div>
        <div id="title">
            <a href="dashbord.php">Khalil Blog</a>
        </div>
        <div class="nav-dropdown">
            <span class="username-dropdown"><?php echo $row['userName']; ?></span>
            <div class="nav-dropdown-content">
                <a href="<?php echo 'members.php?page=edit&userid=' . $_SESSION['userId'] ?>"><i class="fas fa-user-edit"></i>Edit profil</a>
                <a href="<?php echo 'members.php?page=editPassword&userid=' . $_SESSION['userId'] ?>"><i class="fas fa-unlock-alt"></i>Edit Password</a>
                <a href="#"><i class="fas fa-cogs"></i>parametre</a>
                <a href="../home.php"><i class="fas fa-home"></i>Show Home</a>
                <div class="logout">
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i><?php echo Lang('Se deconnecter'); ?></a>
                </div>
            </div>
        </div>
    </nav>