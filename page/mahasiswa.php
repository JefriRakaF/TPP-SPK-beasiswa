 <?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
  $sql = $connection->query("SELECT * FROM mahasiswa WHERE nim='$_GET[key]'");
  $row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $validasi = false; $err = false;
  if ($update) {
    $sql = "UPDATE mahasiswa SET nim='$_POST[nim]', nama='$_POST[nama]', alamat='$_POST[alamat]', jenis_kelamin='$_POST[jenis_kelamin]', tahun_mengajukan='".date("Y")."' WHERE nim='$_GET[key]'";
  } else {
    $sql = "INSERT INTO mahasiswa VALUES ('$_POST[nim]', '$_POST[nama]', '$_POST[alamat]', '$_POST[jenis_kelamin]', '".date("Y")."')";
    $validasi = true;
  }

  if ($validasi) {
    $q = $connection->query("SELECT nim FROM mahasiswa WHERE nim=$_POST[nim]");
    if ($q->num_rows) {
      echo alert($_POST["nim"]." sudah terdaftar!", "?page=mahasiswa");
      $err = true;
    }
  }

  if (!$err AND $connection->query($sql)) {
    echo alert("Berhasil!", "?page=mahasiswa");
  } else {
    echo alert("Gagal!", "?page=mahasiswa");
  }
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM mahasiswa WHERE nim=$_GET[key]");
  echo alert("Berhasil!", "?page=mahasiswa");
}
?>


  <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Data Mahasiswa</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item">Data Mahasiswa</div>
            </div>
          </div>

          <div class="section-body">
            
       <div class="row">
        <div class=" col-12 col-md-6 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="text-center"><?= ($update) ? "EDIT" : "TAMBAH" ?></h4>
                  </div>
                  <div class="card-body p-0">
                   <form action="<?=$_SERVER['REQUEST_URI']?>"  method="POST">
<div class=" col-12 col-md-6 col-lg-12">
                 <div class="row">

                  <div class="form-group col-6">
                      <label for="nim">NIM</label>
                      <input type="text" name="nim" class="form-control" <?= (!$update) ?: 'value="'.$row["nim"].'"' ?>>
                  </div>
                  <div class="form-group col-6">
                      <label for="nama">Nama Lengkap</label>
                      <input type="text" name="nama" class="form-control" <?= (!$update) ?: 'value="'.$row["nama"].'"' ?>>
                  </div>
                </div>
</div>
<div class=" col-12 col-md-6 col-lg-12">
                 <div class="row">
                  <div class="form-group col-6">
                      <label for="alamat">Alamat</label>
                      <input type="text" name="alamat" class="form-control" <?= (!$update) ?: 'value="'.$row["alamat"].'"' ?>>
                  </div>
                  <div class="form-group col-6">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select class="form-control" name="jenis_kelamin">
                      <option>---</option>
                      <option value="Laki-laki" <?= (!$update) ?: (($row["jenis_kelamin"] != "Laki-laki") ?: 'selected="on"') ?>>Laki-laki</option>
                      <option value="Perempuan" <?= (!$update) ?: (($row["jenis_kelamin"] != "Perempuan") ?: 'selected="on"') ?>>Perempuan</option>
                    </select>
                  </div>
                </div>
</div>

                <div class="form-group col-6">
                  <button type="submit" class="btn btn-<?= ($update) ? "warning" : "info" ?> btn-block">Simpan</button>
                  <?php if ($update): ?>
                    <a href="?page=mahasiswa" class="btn btn-info btn-block">Batal</a>
                  <?php endif; ?>
                </div>
                </form>

                  </div>
                </div>
              </div> 
            </div>

            <div class="row">
              <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Table Data Mahasiswa</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered table-md">
                        <tr>
                          <th>No</th>
                          <th>NIK</th>
                          <th>Nama</th>
                          <th>Alamat</th>
                          <th>Jenis Kelamin</th>
                          <th>Tahun Mengajukan</th>
                          <th>Action</th>
                        </tr>
                        
                 <tbody>
                      <?php $no = 1; ?>
                      <?php if ($query = $connection->query("SELECT * FROM mahasiswa")): ?>
                          <?php while($row = $query->fetch_assoc()): ?>
                          <tr>
                              <td><?=$no++?></td>
                              <td><?=$row['nim']?></td>
                              <td><?=$row['nama']?></td>
                              <td><?=$row['alamat']?></td>
                              <td><?=$row['jenis_kelamin']?></td>
                              <td><?=$row['tahun_mengajukan']?></td>
                              <td>
                                  <div class="btn-group">
                                      <a href="?page=mahasiswa&action=update&key=<?=$row['nim']?>" class="btn btn-warning btn-xs">Edit</a>
                                      <a href="?page=mahasiswa&action=delete&key=<?=$row['nim']?>" class="btn btn-danger btn-xs">Hapus</a>
                                  </div>
                              </td>
                          </tr>
                          <?php endwhile ?>
                      <?php endif ?>
                  </tbody>
                        
                      </table>
                    </div>
                  </div>

                </div>
              </div>



            </div>
          </div>
        </section>
      </div>
    </div>

