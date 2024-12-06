<?php
    include '../../../function/connectDB.php';
    $id = $_GET['id'];
    try {
        // Query untuk menghapus data
        $sql = "DELETE guru, users
                FROM guru
                JOIN users ON guru.user_id = users.id
                WHERE guru.id=?";
        $delStmt = $conn->prepare($sql);
        $delStmt->bind_param("i", $id);
        $delStmt->execute();

        if ($delStmt->affected_rows > 0) {
            // Jika berhasil dihapus
            echo "<script>
                    alert('Data berhasil dihapus!');
                    window.location.href = '/BK/users/user-admin/guru/index.php';
                  </script>";
        } else {
            // Jika tidak ada baris yang dihapus (misalnya data tidak ditemukan)
            echo "<script>
                    alert('Gagal menghapus data! Data tidak ditemukan.');
                    window.location.href = '/BK/users/user-admin/guru/index.php';
                  </script>";
        }
    } catch (mysqli_sql_exception $e) {
        // Jika terjadi error karena constraint (misalnya foreign key)
        echo "<script>
                alert('Gagal menghapus data! Data terkait dengan tabel lain.');
                window.location.href = '/BK/users/user-admin/guru/index.php';
              </script>";
    }
?>