
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
                    <h4 class="text-center">Pilih Jenis Beasiswa</h4>
                  </div>
                  <div class="card-body p-0">

                   <form action="<?=$_SERVER['REQUEST_URI']?>"  method="POST">
<div class=" col-12 col-md-6 ol-lg-12">
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
                  <button type="submit"  class="btn btn-info btn-block"><a href="?page=perhitungan&beasiswa=<?=$row["kd_beasiswa"]?>"> Simpan</a></button>
                </div>
            </div>
</div>
                </form>

                  </div>
                </div>
              </div> 
            </div>

            <div class="row">
              <div class="col-12 col-md-6 col-lg-12">
              	<?php if (isset($_GET["beasiswa"])) {
		$sqlKriteria = "";
		$namaKriteria = [];
		$queryKriteria = $connection->query("SELECT a.kd_kriteria, a.nama FROM kriteria a JOIN model b USING(kd_kriteria) WHERE b.kd_beasiswa=$_GET[beasiswa]");
		while ($kr = $queryKriteria->fetch_assoc()) {
			$sqlKriteria .= "SUM(
				IF(
					c.kd_kriteria=".$kr["kd_kriteria"].",
					IF(c.sifat='max', nilai.nilai/c.normalization, c.normalization/nilai.nilai), 0
				)
			) AS ".strtolower(str_replace(" ", "_", $kr["nama"])).",";
			$namaKriteria[] = strtolower(str_replace(" ", "_", $kr["nama"]));
		}
		$sql = "SELECT
			(SELECT nama FROM mahasiswa WHERE nim=mhs.nim) AS nama,
			(SELECT nim FROM mahasiswa WHERE nim=mhs.nim) AS nim,
			(SELECT tahun_mengajukan FROM mahasiswa WHERE nim=mhs.nim) AS tahun,
			$sqlKriteria
			SUM(
				IF(
						c.sifat = 'max',
						nilai.nilai / c.normalization,
						c.normalization / nilai.nilai
				) * c.bobot
			) AS rangking
		FROM
			nilai
			JOIN mahasiswa mhs USING(nim)
			JOIN (
				SELECT
						nilai.kd_kriteria AS kd_kriteria,
						kriteria.sifat AS sifat,
						(
							SELECT bobot FROM model WHERE kd_kriteria=kriteria.kd_kriteria AND kd_beasiswa=beasiswa.kd_beasiswa
						) AS bobot,
						RcOUND(
							IF(kriteria.sifat='max', MAX(nilai.nilai), MIN(nilai.nilai)), 1
						) AS normalization
					FROM nilai
					JOIN kriteria USING(kd_kriteria)
					JOIN beasiswa ON kriteria.kd_beasiswa=beasiswa.kd_beasiswa
					WHERE beasiswa.kd_beasiswa=$_GET[beasiswa]
				GROUP BY nilai.kd_kriteria
			) c USING(kd_kriteria)
		WHERE kd_beasiswa=$_GET[beasiswa]
		GROUP BY nilai.nim
		ORDER BY rangking DESC"; ?>
                <div class="card">
                  <div class="card-header">
                    <h4><?php $query = $connection->query("SELECT * FROM beasiswa WHERE kd_beasiswa=$_GET[beasiswa]"); echo $query->fetch_assoc()["nama"]; ?></h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered table-md">
                        <tr>
                         <th>NIM</th>
							<th>Nama</th>
							<?php //$query = $connection->query("SELECT nama FROM kriteria WHERE kd_beasiswa=$_GET[beasiswa]"); while($row = $query->fetch_assoc()): ?>
								<!-- <th><?//=$row["nama"]?></th> -->
							<?php //endwhile ?>
							<th>Nilai</th>
                        </tr>
                        
                 <tbody>
                    <?php $query = $connection->query($sql); while($row = $query->fetch_assoc()): ?>
					<?php
					$rangking = number_format((float) $row["rangking"], 8, '.', '');
					$q = $connection->query("SELECT nim FROM hasil WHERE nim='$row[nim]' AND kd_beasiswa='$_GET[beasiswa]' AND tahun='$row[tahun]'");
					if (!$q->num_rows) {
					$connection->query("INSERT INTO hasil VALUES(NULL, '$_GET[beasiswa]', '$row[nim]', '".$rangking."', '$row[tahun]')");
					}
					?>
					<tr>
						<td><?=$row["nim"]?></td>
						<td><?=$row["nama"]?></td>
						<?php for($i=0; $i<count($namaKriteria); $i++): ?>
						<!-- <th><?//=number_format((float) $row[$namaKriteria[$i]], 8, '.', '');?></th> -->
						<?php endfor ?>
						<td><?=$rangking?></td>
					</tr>
					<?php endwhile;?>

					  <?php } else { ?>
		<h1>Beasiswa belum dipilih...</h1>
	<?php } ?>
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

