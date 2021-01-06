<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
  $sql = $connection->query("SELECT * FROM penilaian WHERE kd_penilaian='$_GET[key]'");
  $row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $validasi = false; $err = false;
  if ($update) {
    $sql = "UPDATE penilaian SET kd_kriteria='$_POST[kd_kriteria]', keterangan='$_POST[keterangan]', bobot='$_POST[bobot]' WHERE kd_penilaian='$_GET[key]'";
  } else {
    $sql = "INSERT INTO penilaian VALUES (NULL, '$_POST[kd_beasiswa]', '$_POST[kd_kriteria]', '$_POST[keterangan]', '$_POST[bobot]')";
    $validasi = true;
  }

  if ($validasi) {
    $q = $connection->query("SELECT kd_penilaian FROM penilaian WHERE kd_beasiswa=$_POST[kd_beasiswa] AND kd_kriteria=$_POST[kd_kriteria] AND keterangan LIKE '%$_POST[keterangan]%' AND bobot=$_POST[bobot]");
    if ($q->num_rows) {
      echo alert("Penilaian sudah ada!", "?page=penilaian");
      $err = true;
    }
  }

  if (!$err AND $connection->query($sql)) {
    echo alert("Berhasil!", "?page=penilaian");
  } else {
    echo alert("Gagal!", "?page=penilaian");
  }
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM penilaian WHERE kd_penilaian='$_GET[key]'");
  echo alert("Berhasil!", "?page=penilaian");
}
?>


  <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Bobot Penilaian</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item">Bobot Penilaian</div>
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
                     <label for="kd_beasiswa">Beasiswa</label>
                    <select class="form-control" name="kd_beasiswa" id="beasiswa">
                      <option>---</option>
                      <?php $sql = $connection->query("SELECT * FROM beasiswa") ?>
                      <?php while ($data = $sql->fetch_assoc()): ?>
                        <option value="<?=$data["kd_beasiswa"]?>" <?= (!$update) ?: (($row["kd_beasiswa"] != $data["kd_beasiswa"]) ?: 'selected="selected"') ?>><?=$data["nama"]?></option>
                      <?php endwhile; ?>
                    </select>
                  </div>
                  <div class="form-group col-6">
                       <label for="kd_kriteria">Kriteria</label>
                    <select class="form-control" name="kd_kriteria" id="kriteria">
                      <option>---</option>
                      <?php $sql = $connection->query("SELECT * FROM kriteria") ?>
                      <?php while ($data = $sql->fetch_assoc()): ?>
                        <option value="<?=$data["kd_kriteria"]?>" class="<?=$data["kd_beasiswa"]?>" <?= (!$update) ?: (($row["kd_kriteria"] != $data["kd_kriteria"]) ?: 'selected="selected"') ?>><?=$data["nama"]?></option>
                      <?php endwhile; ?>
                    </select>
                  </div>
                </div>
</div>
<div class=" col-12 col-md-6 col-lg-12">
                 <div class="row">
                  <div class="form-group col-6">
                      <label for="keterangan">Keterangan</label>
                      <input type="text" name="keterangan" class="form-control" <?= (!$update) ?: 'value="'.$row["keterangan"].'"' ?>>
                  </div>
                  <div class="form-group col-6">
                     <label for="bobot">Bobot</label>
                      <input type="text" name="bobot" class="form-control" <?= (!$update) ?: 'value="'.$row["bobot"].'"' ?>>
                  </div>
                </div>
</div>
  
                <div class="form-group col-6">
                   <button type="submit" class="btn btn-<?= ($update) ? "warning" : "info" ?> btn-block">Simpan</button>
                  <?php if ($update): ?>
                    <a href="?page=penilaian" class="btn btn-info btn-block">Batal</a>
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
                          <th>Beasiswa</th>
                          <th>Kriteria</th>
                          <th>Keterangan</th>
                          <th>Bobot</th>
                          <th></th>
                        </tr>
                        
                 <tbody>
                     <?php $no = 1; ?>
                      <?php if ($query = $connection->query("SELECT a.kd_penilaian, c.nama AS nama_beasiswa, b.nama AS nama_kriteria, a.keterangan, a.bobot FROM penilaian a JOIN kriteria b ON a.kd_kriteria=b.kd_kriteria JOIN beasiswa c ON a.kd_beasiswa=c.kd_beasiswa")): ?>
                          <?php while($row = $query->fetch_assoc()): ?>
                          <tr>
                              <td><?=$no++?></td>
                              <td><?=$row['nama_beasiswa']?></td>
                              <td><?=$row['nama_kriteria']?></td>
                              <td><?=$row['keterangan']?></td>
                              <td><?=$row['bobot']?></td>
                              <td>
                                  <div class="btn-group">
                                      <a href="?page=bobotpenilaian&action=update&key=<?=$row['kd_penilaian']?>" class="btn btn-warning btn-xs">Edit</a>
                                      <a href="?page=bobotpenilaian&action=delete&key=<?=$row['kd_penilaian']?>" class="btn btn-danger btn-xs">Hapus</a>
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

