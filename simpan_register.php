<?php 
 include "lib/koneksi.php";

$username = $_POST['username'];
$password = md5($_POST['password']);
$status = $_POST['status'];

if($status == "Mahasiswa"){
$querySimpan = mysqli_query($connection, "INSERT INTO pengguna  VALUES 
                                                  (NULL,'$username','$password','$status')");

            if ($querySimpan) {
                 
                echo "<script> alert('Data member Berhasil Masuk'); window.location='$admin_url'+'index.php';</script>";
            } 
            else {
                 exit();
			           //echo "<script> alert('Data Member Gagal Dimasukkan'); window.location='$admin_url'+'register.php'; </script>";
            }
}
else{
$querySimpan = mysqli_query($connection, "INSERT INTO pengguna (username, password,status) VALUES 
                                                  ('$username','$password','petugas')");

            if ($querySimpan) {
                echo "<script> alert('Data member Berhasil Masuk'); window.location='$admin_url'+'index.php';</script>";
            } 
            else {
                 echo "<script> alert('Data Member Gagal Dimasukkan'); window.location='$admin_url'+'register.php'; </script>";
            }
}
?>