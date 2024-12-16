<?php
    include '../../../function/connectDB.php';
    $id = $_GET['id'];
    // Cek apakah ada data yang ingin dihapus
    $sql = "SELECT foto FROM informasi WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Ambil nama file foto yang terkait dengan informasi
    if ($data = $result->fetch_assoc()) {
        $foto = $data['foto'];

        // Jika foto bukan default.png, kita akan menghapus file tersebut
        if ($foto !== 'default.png') {
            $fotoPath = "../../../assets/images/uploads/" . $foto;

            // Cek apakah file foto ada di folder dan hapus
            if (file_exists($fotoPath)) {
                unlink($fotoPath);  // Hapus file foto
            }
        }

        try {
            // Query untuk menghapus data
            $sqlDelete = "DELETE FROM informasi WHERE id=?";
            $delStmt = $conn->prepare($sqlDelete);
            $delStmt->bind_param("i", $id);
            $delStmt->execute();

            if ($delStmt->affected_rows > 0) {
                // Jika berhasil dihapus
                echo "<script>
                        alert('Data berhasil dihapus!');
                        window.location.href = '/BK/users/user-guru/informasi/index.php';
                      </script>";
                exit();
            } else {
                // Jika tidak ada baris yang dihapus (misalnya data tidak ditemukan)
                echo "<script>
                        alert('Gagal menghapus data! Data tidak ditemukan.');
                        window.location.href = '/BK/users/user-guru/informasi/index.php';
                      </script>";
                exit();
            }
        } catch (mysqli_sql_exception $e) {
            // Jika terjadi error karena constraint (misalnya foreign key)
            echo "<script>
                    alert('Gagal menghapus data! Data terkait dengan tabel lain.');
                    window.location.href = '/BK/users/user-guru/informasi/index.php';
                  </script>";
            exit();
        }
    } else {
        // Jika data tidak ditemukan di database
        echo "<script>
                alert('Data tidak ditemukan!');
                window.location.href = '/BK/users/user-guru/informasi/index.php';
              </script>";
        exit();
    }
?>