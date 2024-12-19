<?php
    include '../../../function/connectDB.php';
    try {
        $id = $_GET['id'];
        $sql = "DELETE FROM kasus Where id=?";
        $delStmt = $conn->prepare($sql);
        $delStmt->bind_param("i", $id);
        $delStmt->execute();

        if ($delStmt->affected_rows > 0) {
            // Jika berhasil dihapus
            echo "<script>
                    alert('Data berhasil dihapus!');
                    window.location.href = '/BK/users/user-guru/kasus/index.php';
                  </script>";
            exit();
        } else {
            // Jika tidak ada baris yang dihapus (misalnya data tidak ditemukan)
            echo "<script>
                    alert('Gagal menghapus data! Data tidak ditemukan.');
                    window.location.href = '/BK/users/user-guru/kasus/index.php';
                  </script>";
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // Jika terjadi error karena constraint (misalnya foreign key)
        echo "<script>
                alert('Gagal menghapus data! Data terkait dengan tabel lain.');
                window.location.href = '/BK/users/user-guru/kasus/index.php';
              </script>";
        exit();
    }

?>