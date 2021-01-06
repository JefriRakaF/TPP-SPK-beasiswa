<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $connection->query("SELECT * FROM model WHERE kd_model='$_GET[key]'");
	$row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$validasi = false; $err = false;
	if ($update) {
		$sql = "UPDATE model SET kd_kriteria='$_POST[kd_kriteria]', kd_beasiswa='$_POST[kd_beasiswa]', bobot='$_POST[bobot]' WHERE kd_model='$_GET[key]'";
	} else {
		$sql = "INSERT INTO model VALUES (NULL, '$_POST[kd_beasiswa]', '$_POST[kd_kriteria]', '$_POST[bobot]')";
		$validasi = true;
	}

	if ($validasi) {
		$q = $connection->query("SELECT kd_model FROM model WHERE kd_beasiswa=$_POST[kd_beasiswa] AND kd_kriteria=$_POST[kd_kriteria] AND bobot LIKE '%$_POST[bobot]%'");
		if ($q->num_rows) {
			echo alert("Model sudah ada!", "?page=model");
			$err = true;
		}
	}

  if (!$err AND $connection->query($sql)) {
		echo alert("Berhasil!", "?page=model");
	} else {
		echo alert("Gagal!", "?page=model");
	}
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM model WHERE kd_model='$_GET[key]'");
	echo alert("Berhasil!", "?page=model");
}
?>


    <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Data Model</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item">Data Model</div>
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
										<select class="form-control" name="kd_beasiswa" id="beasiswa">
											<option>---</option>
											<?php $sql = $connection->query("SELECT * FROM beasiswa") ?>
											<?php while ($data = $sql->fetch_assoc()): ?>
												<option value="<?=$data["kd_beasiswa"]?>"<?= (!$update) ?: (($row["kd_beasiswa"] != $data["kd_beasiswa"]) ?: ' selected="on"') ?>><?=$data["nama"]?></option>
											<?php endwhile; ?>
										</select>
									</div>
									<div class="form-group  col-12">
	                  <label for="kd_kriteria">Kriteria</label>
										<select class="form-control" name="kd_kriteria" id="kriteria">
											<option>---</option>
											<?php $sql = $connection->query("SELECT * FROM kriteria") ?>
											<?php while ($data = $sql->fetch_assoc()): ?>
												<option value="<?=$data["kd_kriteria"]?>" class="<?=$data["kd_beasiswa"]?>"<?= (!$update) ?: (($row["kd_kriteria"] != $data["kd_kriteria"]) ?: ' selected="on"') ?>><?=$data["nama"]?></option>
											<?php endwhile; ?>
										</select>
									</div>
	                <div class="form-group  col-12">
	                    <label for="bobot">Bobot</label>
	                    <input type="text" name="bobot" class="form-control" <?= (!$update) ?: 'value="'.$row["bobot"].'"' ?>>
	                </div>
	                <div class="form-group  col-4">
	                <button type="submit" class="btn btn-<?= ($update) ? "warning" : "info" ?> btn-block">Simpan</button>
	                <?php if ($update): ?>
										<a href="?page=model" class="btn btn-info btn-block">Batal</a>
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
	                        <th>Bobot</th>
	                        <th></th>
                        </tr>
                        
                 <tbody>
                       <?php $no = 1; ?>
	                    <?php if ($query = $connection->query("SELECT c.nama AS nama_beasiswa, b.nama AS nama_kriteria, a.bobot, a.kd_model FROM model a JOIN kriteria b ON a.kd_kriteria=b.kd_kriteria JOIN beasiswa c ON a.kd_beasiswa=c.kd_beasiswa")): ?>
	                        <?php while($row = $query->fetch_assoc()): ?>
	                        <tr>
	                            <td><?=$no++?></td>
															<td><?=$row['nama_beasiswa']?></td>
	                            <td><?=$row['nama_kriteria']?></td>
	                            <td><?=$row['bobot']?></td>
	                            <td>
	                                <div class="btn-group">
	                                    <a href="?page=model&action=update&key=<?=$row['kd_model']?>" class="btn btn-warning btn-xs">Edit</a>
	                                    <a href="?page=model&action=delete&key=<?=$row['kd_model']?>" class="btn btn-danger btn-xs">Hapus</a>
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

