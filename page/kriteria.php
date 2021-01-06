<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $connection->query("SELECT * FROM kriteria WHERE kd_kriteria='$_GET[key]'");
	$row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$validasi = false; $err = false;
	if ($update) {
		$sql = "UPDATE kriteria SET kd_beasiswa=$_POST[kd_beasiswa], nama='$_POST[nama]', sifat='$_POST[sifat]' WHERE kd_kriteria='$_GET[key]'";
	} else {
		$sql = "INSERT INTO kriteria VALUES (NULL, $_POST[kd_beasiswa], '$_POST[nama]', '$_POST[sifat]')";
		$validasi = true;
	}

	if ($validasi) {
		$q = $connection->query("SELECT kd_kriteria FROM kriteria WHERE kd_beasiswa=$_POST[kd_beasiswa] AND nama LIKE '%$_POST[nama]%'");
		if ($q->num_rows) {
			echo alert("Kriteri sudah ada!", "?page=kriteria");
			$err = true;
		}
	}

  if (!$err AND $connection->query($sql)) {
		echo alert("Berhasil!", "?page=kriteria");
	} else {
		echo alert("Gagal!", "?page=kriteria");
	}
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM kriteria WHERE kd_kriteria='$_GET[key]'");
	echo alert("Berhasil!", "?page=kriteria");
}
?>


    <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Data Kriteria</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item">Data Kriteria</div>
            </div>
          </div>

          <div class="section-body">
            
       <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="text-center"><?= ($update) ? "EDIT" : "TAMBAH" ?></h4>
                  </div>
                  <div class="card-body p-0">
                   <form action="<?=$_SERVER['REQUEST_URI']?>"  method="POST">
	<div class="form-group col-12">
	                  <label for="kd_beasiswa">Beasiswa</label>
										<select class="form-control" name="kd_beasiswa">
											<option>---</option>
											<?php $query = $connection->query("SELECT * FROM beasiswa"); while ($data = $query->fetch_assoc()): ?>
												<option value="<?=$data["kd_beasiswa"]?>" <?= (!$update) ?: (($row["kd_beasiswa"] != $data["kd_beasiswa"]) ?: 'selected="on"') ?>><?=$data["nama"]?></option>
											<?php endwhile; ?>
										</select>
									</div>
									<div class="form-group  col-12">
	                 				 <label for="nama">Nama</label>
	                   				 <input type="text" name="nama" class="form-control" <?= (!$update) ?: 'value="'.$row["nama"].'"' ?>>
	                   				</div>
	                <div class="form-group  col-12">
	                    <label for="sifat">Sifat</label>
										<select class="form-control" name="sifat">
											<option>---</option>
											<option value="min" <?= (!$update) ?: (($row["sifat"] != "min") ?: 'selected="on"') ?>>Min</option>
											<option value="max" <?= (!$update) ?: (($row["sifat"] != "max") ?: 'selected="on"') ?>>Max</option>
										</select>
	                </div>
	                <div class="form-group  col-4">
	                 <button type="submit" class="btn btn-<?= ($update) ? "warning" : "info" ?> btn-block">Simpan</button>
	                <?php if ($update): ?>
										<a href="?page=kriteria" class="btn btn-info btn-block">Batal</a>
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
	                        <th>Sifat</th>
	                        <th></th>
                        </tr>
                        
                 <tbody>
                      <?php $no = 1; ?>
	                    <?php if ($query = $connection->query("SELECT a.nama AS kriteria, b.nama AS beasiswa, a.kd_kriteria, a.sifat FROM kriteria a JOIN beasiswa b USING(kd_beasiswa)")): ?>
	                        <?php while($row = $query->fetch_assoc()): ?>
	                        <tr>
	                            <td><?=$no++?></td>
	                            <td><?=$row['beasiswa']?></td>
	                            <td><?=$row['kriteria']?></td>
	                            <td><?=$row['sifat']?></td>
	                            <td>
	                                <div class="btn-group">
	                                    <a href="?page=kriteria&action=update&key=<?=$row['kd_kriteria']?>" class="btn btn-warning btn-xs">Edit</a>
	                                    <a href="?page=kriteria&action=delete&key=<?=$row['kd_kriteria']?>" class="btn btn-danger btn-xs">Hapus</a>
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

