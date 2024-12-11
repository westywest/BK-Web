<?php
include '../../../function/connectDB.php';

if (isset($_GET['kelas_id'])) {
    $kelas_id = $_GET['kelas_id'];
    
    // Query untuk mengambil siswa yang ada di kelas tertentu
    $sql = "SELECT id, name FROM siswa WHERE kelas_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kelas_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Simpan data siswa dalam array
    $siswaArray = [];
    while ($row = $result->fetch_assoc()) {
        $siswaArray[] = $row;
    }
    
    // Kembalikan data siswa dalam format JSON
    echo json_encode($siswaArray);
}
?>