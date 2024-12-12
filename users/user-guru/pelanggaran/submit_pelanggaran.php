<?php
session_start();
include '../../../function/connectDB.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $siswa_id = $_POST['siswa_id'];
    $jenis_id = $_POST['jenis_id'];
    $note = $_POST['note'];
    $date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO pelanggaran (id, siswa_id, jenis_id, note, date) VALUES (null, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $siswa_id, $jenis_id, $note, $date);

    if ($stmt->execute()) {
        echo "<script>
                alert('Data berhasil disimpan!');
                window.location.href = '/BK/users/user-guru/pelanggaran/index.php';
              </script>";
        exit(); // Pastikan untuk keluar agar tidak ada kode lain yang dijalankan
    } else {
        echo "<script>
                alert('Gagal menyimpan pelanggaran.');
                window.location.href = '/BK/users/user-guru/pelanggaran/create.php';
              </script>";
        exit();
    }
}
?>
