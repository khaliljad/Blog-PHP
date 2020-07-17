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

// --------------- calcul total items ----------------//

function totalItems($champ, $table) {
    
    global $conn;

    $sql = "SELECT COUNT($champ) FROM $table";
    $dashStmt = $conn->prepare($sql);
    $dashStmt->execute();
    $count = $dashStmt->fetchColumn();
    
    return $count;
}


?>