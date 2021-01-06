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
			echo alert($_POST["nim"]." sudah terdaftar!", "?page=datadiri");
			$err = true;
		}
	}

  if (!$err AND $connection->query($sql)) {
    echo alert("Berhasil!", "?page=datadiri");
  } else {
		echo alert("Gagal!", "?page=datadiri");
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
                    <h4 class="text-center">Isi Data Diri</h4>
                  </div>
                  <div class="card-body p-0">
                   <form action="<?=$_SERVER['REQUEST_URI']?>"  method="POST">

						<div class="form-group col-12">
	                 			 <label for="nim">NIM</label>
	                    		<input type="text" name="nim" class="form-control" <?= (!$update) ?: 'value="'.$row["nim"].'"' ?>>
						</div>

						<div class="form-group  col-12">
	                  			<label for="nama">Nama Lengkap</label>
	                    		<input type="text" name="nama" class="form-control" <?= (!$update) ?: 'value="'.$row["nama"].'"' ?>>
						</div>

	                <div class="form-group  col-12">
	                    <label for="alamat">Alamat</label>
	                    <input type="text" name="alamat" class="form-control" <?= (!$update) ?: 'value="'.$row["alamat"].'"' ?>>
	                </div>

	                <div class="form-group  col-12">
	                     <label for="jenis_kelamin">Jenis Kelamin</label>
										<select class="form-control" name="jenis_kelamin">
											<option>---</option>
											<option value="Laki-laki" <?= (!$update) ?: (($row["jenis_kelamin"] != "Laki-laki") ?: 'selected="on"') ?>>Laki-laki</option>
											<option value="Perempuan" <?= (!$update) ?: (($row["jenis_kelamin"] != "Perempuan") ?: 'selected="on"') ?>>Perempuan</option>
										</select>
	                </div>

	                <div class="form-group  col-4">
	                <button type="submit" class="btn btn-<?= ($update) ? "warning" : "info" ?> btn-block">Simpan</button>
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

