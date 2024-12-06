<?php
    include '../../../function/connectDB.php';
    $id = $_GET['id'];
    $sql = "DELETE FROM kelas Where id=?";
    $delStmt = $conn->prepare($sql);
    $delStmt->bind_param("i", $id);
    $delStmt->execute();

    if($delStmt->affected_rows > 0){
        header("Location:index.php");
    }else{
        $_SESSION['error'] = "Menghapus data gagal!";
    }
?>