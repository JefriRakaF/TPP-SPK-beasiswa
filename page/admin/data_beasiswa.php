<?php

$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
  $sql = $connection->query("SELECT * FROM beasiswa WHERE kd_beasiswa='$_GET[key]'");
  $row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $validasi = false; $err = false;
  if ($update) {
    $sql = "UPDATE beasiswa SET nama='$_POST[nama]' WHERE kd_beasiswa='$_GET[key]'";
  } else {
    $sql = "INSERT INTO beasiswa VALUES (NULL, '$_POST[nama]')";
    $validasi = true;
  }

  if ($validasi) {
    $q = $connection->query("SELECT kd_beasiswa FROM beasiswa WHERE nama LIKE '%$_POST[nama]%'");
    if ($q->num_rows) {
      echo alert("Beasiswa sudah ada!", "?page=beasiswa");
      $err = true;
    }
  }

  if (!$err AND $connection->query($sql)) {
    echo alert("Berhasil!", "?page=beasiswa");
  } else {
    echo alert("Gagal!", "?page=beasiswa");
  }
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM beasiswa WHERE kd_beasiswa='$_GET[key]'");
  echo alert("Berhasil!", "?page=beasiswa");
}
?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Data Beasiswa</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item">Data Beasiswa</div>
            </div>
          </div>

          <div class="section-body">
            

            <div class="row">
              <div class="col-12 col-md-6 col-lg-8">
                <div class="card">
                  <div class="card-header">
                    <h4>Simple Table</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered table-md">
                        <tr>
                          <th>No</th>
                          <th>Nama Beasiswa</th>
                          <th>Action</th>
                        </tr>
                        
                        <tbody>
                      <?php $no = 1; ?>
                      <?php if ($query = $connection->query("SELECT * FROM beasiswa")): ?>
                          <?php while($row = $query->fetch_assoc()): ?>
                          <tr>
                              <td><?=$no++?></td>
                              <td><?=$row['nama']?></td>
                              <td>
                                  <div class="btn-group">
                                      <a href="?page=beasiswa&action=update&key=<?=$row['kd_beasiswa']?>" class="btn btn-warning btn-xs">Edit</a>
                                      <a href="?page=beasiswa&action=delete&key=<?=$row['kd_beasiswa']?>" class="btn btn-danger btn-xs">Hapus</a>
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

              <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                  <div class="card-header">
                    <h4 class="text-center"><?= ($update) ? "EDIT" : "TAMBAH" ?></h4>
                  </div>
                  <div class="card-body p-0">
                   <form action="<?=$_SERVER['REQUEST_URI']?>"  method="POST">
                 
                  <div class="form-group col-12" >
                    <input type="text" class="form-control" name="nama" <?= (!$update) ?: 'value="'.$row["nama"].'"' ?>>
                    <div class="invalid-feedback">
                    </div>
                  </div>


                  <div class="form-group col-12">
                    <button type="submit" class="btn btn-<?= ($update) ? "warning" : "info" ?> btn-block">Simpan</button>
                  <?php if ($update): ?>
                    <a href="?page=beasiswa" class="btn btn-info btn-block">Batal</a>
                  <?php endif; ?>
                  </div>
                </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
