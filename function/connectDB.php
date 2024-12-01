<?php
    $servername = "localhost";
    $database = "beka_db";
    $username = "root";
    $password = "";

    //berfungsi mengkonekan
    $conn = mysqli_connect($servername, $username, $password, $database);

    //cek apakah sudah terhubung dengan database atau belum
    if(!$conn){
        die("Koneksi gagal: " .mysqli_connect_error());
    }
?>