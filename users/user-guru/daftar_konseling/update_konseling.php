<?php 
session_start();
// Cek apakah user sudah login dan memiliki role 'guru'
if (!isset($_SESSION['status']) || $_SESSION['role'] !== "guru") {
    // Redirect ke halaman login jika bukan guru
    header("Location:/BK/users/index.php");
    exit;
}

include '../../../function/connectDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['submit_pending'])) {
      $id = $_POST['submit_pending'];
      $date = $_POST['date'][$id]; // Ambil tanggal untuk ID spesifik

      if (!empty($date)) {
          $sql = "UPDATE konseling SET tanggal_konseling = ?, status = 'confirmed' WHERE id = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param('si', $date, $id);

          if ($stmt->execute()) {
              echo "<script>
                      alert('Konseling terkonfirmasi!');
                      window.location.href = '/BK/users/user-guru/daftar_konseling/index.php';
                    </script>";
          } else {
              echo "<script>
                      alert('Gagal mengkonfirmasi tanggal konseling!');
                      window.location.href = '/BK/users/user-guru/daftar_konseling/index.php';
                    </script>";
          }
          $stmt->close();
      }
  }

  if (isset($_POST['submit_confirmed'])) {
      $id = $_POST['submit_confirmed'];
      $tindakLanjut = htmlspecialchars(trim($_POST['tindak_lanjut'][$id])); // Ambil tindak lanjut untuk ID spesifik

      if (!empty($tindakLanjut)) {
          $sql = "UPDATE konseling SET tindak_lanjut = ?, status = 'completed' WHERE id = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param('si', $tindakLanjut, $id);

          if ($stmt->execute()) {
              echo "<script>
                      alert('Konseling selesai!');
                      window.location.href = '/BK/users/user-guru/daftar_konseling/index.php';
                    </script>";
          } else {
              echo "<script>
                      alert('Gagal menambahkan tindak lanjut!');
                      window.location.href = '/BK/users/user-guru/daftar_konseling/index.php';
                    </script>";
          }
          $stmt->close();
      }
  }
}

$conn->close();

?>