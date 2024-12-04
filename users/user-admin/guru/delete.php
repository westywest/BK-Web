<?php
    include '../../../function/connectDB.php';
    $id = $_GET['id'];
    $sql = "DELETE guru, users
            FROM guru
            JOIN users ON guru.user_id = users.id
            WHERE guru.id='$id'";
    $datas = $conn->query($sql);

    if(mysqli_affected_rows($conn) > 0){
        header("Location:index.php");
    }else{
        $_SESSION['error'] = "Menghapus data gagal!";
    }
?>