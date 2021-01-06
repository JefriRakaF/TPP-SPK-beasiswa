<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $connection->query("SELECT * FROM nilai JOIN penilaian USING(kd_kriteria) WHERE kd_nilai='$_GET[key]'");
	$row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST["save"])) {
	$validasi = false; $err = false;
	if ($update) {
		$sql = "UPDATE nilai SET kd_kriteria='$_POST[kd_kriteria]', nim='$_POST[nim]', nilai='$_POST[nilai]' WHERE kd_nilai='$_GET[key]'";
	} else {
		$query = "INSERT INTO nilai VALUES ";
		foreach ($_POST["nilai"] as $kd_kriteria => $nilai) {
			$query .= "(NULL, '$_POST[kd_beasiswa]', '$kd_kriteria', '$_POST[nim]', '$nilai'),";
		}
		$sql = rtrim($query, ',');
		$validasi = true;
	}

	if ($validasi) {
		foreach ($_POST["nilai"] as $kd_kriteria => $nilai) {
			$q = $connection->query("SELECT kd_nilai FROM nilai WHERE kd_beasiswa=$_POST[kd_beasiswa] AND kd_kriteria=$kd_kriteria AND nim=$_POST[nim] AND nilai LIKE '%$nilai%'");
			if ($q->num_rows) {
				echo alert("Nilai untuk ".$_POST["nim"]." sudah ada!", "?page=nilai");
				$err = true;
			}
		}
	}

  if (!$err AND $connection->query($sql)) {
		echo alert("Berhasil!", "?page=pendaftaran");
	} else {
		echo alert("Gagal!", "?page=pendaftaran");
	}
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM nilai WHERE kd_nilai='$_GET[key]'");
	echo alert("Berhasil!", "?page=nilai");
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
                    <h4 class="text-center">Daftar</h4>
                  </div>
                  <div class="card-body p-0">
                   <form action="<?=$_SERVER['REQUEST_URI']?>"  method="POST">

                   	<div class="form-group  col-12">
	                    <label for="nim">Data Siswa</label>
										<?php if ($_POST): ?>
											<input type="text" name="nim" value="<?=$_POST["nim"]?>" class="form-control" readonly="on">
										<?php else: ?>
											<select class="form-control" name="nim">
												<option>---</option>
												<?php $sql = $connection->query("SELECT * FROM mahasiswa"); while ($data = $sql->fetch_assoc()): ?>
													<option value="<?=$data["nim"]?>" <?= (!$update) ? "" : (($row["nim"] != $data["nim"]) ? "" : 'selected="selected"') ?>><?=$data["nim"]?> | <?=$data["nama"]?></option>
												<?php endwhile; ?>
											</select>
										<?php endif; ?>
	                </div>


	<div class="form-group col-12">
	                  	                  <label for="kd_beasiswa">Beasiswa</label>
										<?php if ($_POST): ?>
											<?php $q = $connection->query("SELECT nama FROM beasiswa WHERE kd_beasiswa=$_POST[kd_beasiswa]"); ?>
											<input type="text"value="<?=$q->fetch_assoc()["nama"]?>" class="form-control" readonly="on">
											<input type="hidden" name="kd_beasiswa" value="<?=$_POST["kd_beasiswa"]?>">
										<?php else: ?>
											<select class="form-control" name="kd_beasiswa" id="beasiswa">
												<option>---</option>
												<?php $sql = $connection->query("SELECT * FROM beasiswa"); while ($data = $sql->fetch_assoc()): ?>
													<option value="<?=$data["kd_beasiswa"]?>"<?= (!$update) ? "" : (($row["kd_beasiswa"] != $data["kd_beasiswa"]) ? "" : 'selected="selected"') ?>><?=$data["nama"]?></option>
												<?php endwhile; ?>
											</select>
										<?php endif; ?>
									</div>

									<?php if ($_POST): ?>
										<?php $q = $connection->query("SELECT * FROM kriteria WHERE kd_beasiswa=$_POST[kd_beasiswa]"); while ($r = $q->fetch_assoc()): ?>


									<div class="form-group  col-12">
	                  <label for="nilai"><?=ucfirst($r["nama"])?></label>
														<select class="form-control" name="nilai[<?=$r["kd_kriteria"]?>]" id="nilai">
															<option>---</option>
															<?php $sql = $connection->query("SELECT * FROM penilaian WHERE kd_kriteria=$r[kd_kriteria]"); while ($data = $sql->fetch_assoc()): ?>
																<option value="<?=$data["bobot"]?>" class="<?=$data["kd_kriteria"]?>"<?= (!$update) ? "" : (($row["kd_penilaian"] != $data["kd_penilaian"]) ? "" : ' selected="selected"') ?>><?=$data["keterangan"]?></option>
															<?php endwhile; ?>
														</select>
									</div>

									<?php endwhile; ?>
										<input type="hidden" name="save" value="true">
									<?php endif; ?>
	                <button type="submit" id="simpan" class="btn btn-<?= ($update) ? "warning" : "info" ?> btn-block"><?=($_POST) ? "Simpan" : "Tampilkan"?></button>
	                <?php if ($update): ?>
										<a href="?page=nilai" class="btn btn-info btn-block">Batal</a>
									<?php endif; ?>


	               
                </form>

                  </div>
                </div>
              </div> 
            </div>


          </div>
        </section>
      </div>
    </div>

