<?php 

function getTitle() {

    global $title;
    
    if (isset($title)) {
        echo $title;
    } else {
        echo 'Blog';
    }
}

//-------------------- redirect function ----------------------// 

function redirectFunc($errors, $time = 3, $url) {

    echo $errors ;
    header('refresh:' . $time . ';' . $url);
    exit;

}

// --------------- check empty items ----------------//

function checkUser($champ, $table, $value) {
 
    global $conn;

    $sql = "SELECT $champ FROM $table WHERE $champ = ?";
    $statment = $conn->prepare($sql);
    $statment->execute(array($value));
    $count = $statment->rowCount();

    return $count;
}

// --------------- latest articles in category ----------------//

function SelectArtcile($catId, $condition = 'id_category' ,$limit = 3) {
    
    global $conn;

    $sql = "SELECT * FROM articles WHERE $condition = ? AND status = 1 ORDER BY id_article DESC LIMIT $limit";
    $dashStmt = $conn->prepare($sql);
    $dashStmt->execute(array($catId));
    $rows = $dashStmt->fetchAll();
    
    return $rows;
}


// -------------- total items -------------- //

function totalItems($champ, $table, $cond, $value) {
    
    global $conn;

    $sql = "SELECT COUNT($champ) FROM $table WHERE $cond = ?";
    $dashStmt = $conn->prepare($sql);
    $dashStmt->execute(array($value));
    $count = $dashStmt->fetchColumn();
    
    return $count;
}


// ----------- getViews -------------- //

function getViews($value) {

    global $conn;

    $sql = "SELECT views FROM articles WHERE id_article = ?";
    $viewStmt = $conn->prepare($sql);
    $viewStmt->execute(array($value));
    $views = $viewStmt->fetch();
    echo $views['views'];
}

// ----------- getlikes -------------- //

function getLikes($value) {

    global $conn;

    $sql = "SELECT COUNT(id) FROM likes WHERE article = ?";
    $likeStmt = $conn->prepare($sql);
    $likeStmt->execute(array($value));
    $likes = $likeStmt->fetchColumn();
    echo $likes;
}


// -------------- categories visibility ----------------//

function catVisibility($value) {

    global $conn;

    $catName = $conn->prepare("SELECT id FROM categories WHERE visibility = 1 AND id = ?");
    $catName->execute(array($value));
    $count = $catName->rowCount();

    return $count;
}


// -------------- Articless Comments ----------------//

function ArtComments($value) {

    global $conn;

    $catName = $conn->prepare("SELECT titre FROM articles WHERE allow_comments = 1 AND id_article = ?");
    $catName->execute(array($value));
    $count = $catName->rowCount();

    return $count;
}

?>