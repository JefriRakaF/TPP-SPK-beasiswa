
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Laporan</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item">Laporan</div>
            </div>
          </div>

          <div class="section-body">




                <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="text-center">Pilih Jenis Laporan</h4>
                  </div>
                  <div class="card-body p-0">
                   <form action="<?=$_SERVER['REQUEST_URI']?>"  method="POST">
  <div class="form-group col-8">
                    <label for="kd_beasiswa">Beasiswa</label>
                    <select class="form-control" name="kd_beasiswa">
                      <option>---</option>
                      <?php $query = $connection->query("SELECT * FROM beasiswa"); while ($row = $query->fetch_assoc()): ?>
                        <option value="<?=$row["kd_beasiswa"]?>"> <?=$row["nama"]?></option>
                      <?php endwhile; ?>
                    </select>
                  </div>

                  <div class="form-group  col-4">
                   <button type="submit" class="btn btn-primary btn-lg btn-block">Pilih</button>
          </div>  
                </form>

                  </div>
                </div>
              </div> 
            </div>


<?php if (isset($_POST["kd_beasiswa"])) {
    $sqlKriteria = "";
    $namaKriteria = [];
    $queryKriteria = $connection->query("SELECT a.kd_kriteria, a.nama FROM kriteria a JOIN model b USING(kd_kriteria) WHERE b.kd_beasiswa=$_POST[kd_beasiswa]");
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
            ROUND(
              IF(kriteria.sifat='max', MAX(nilai.nilai), MIN(nilai.nilai)), 1
            ) AS normalization
          FROM nilai
          JOIN kriteria USING(kd_kriteria)
          JOIN beasiswa ON kriteria.kd_beasiswa=beasiswa.kd_beasiswa
          WHERE beasiswa.kd_beasiswa=$_POST[kd_beasiswa]
        GROUP BY nilai.kd_kriteria
      ) c USING(kd_kriteria)
    WHERE kd_beasiswa=$_POST[kd_beasiswa]
    GROUP BY nilai.nim
    ORDER BY rangking DESC"; ?>



            <div class="invoice">
              <div class="invoice-print">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="invoice-title">
                     <div class="panel-heading"><h3 class="text-center"><h2 class="text-center"><?php $query = $connection->query("SELECT * FROM beasiswa WHERE kd_beasiswa=$_POST[kd_beasiswa]"); echo $query->fetch_assoc()["nama"]; ?></h2></h3></div>
                    </div>
                    <hr>


                    <div class="row">
                      <div class="col-md-6">
                        <address>
                          <strong>Payment Method:</strong><br>
                          Visa ending **** 4242<br>
                          ujang@maman.com
                        </address>
                      </div>

                      <div class="col-md-6 text-md-right">
                        <address>
                          <strong>Order Date:</strong><br>
                          September 19, 2018<br><br>
                        </address>
                      </div>

                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-12">
                    <div class="section-title">Daftar Hasil Perangkingan</div>
                    <div class="table-responsive">
                      <table class="table table-striped table-hover table-md">

                        <tr>
                          <th>No</th>
                          <th>NIM</th>
                          <th>Nama</th>
                          <th>Nilai</th>
                        </tr>
                          <?php $no = 1; ?>
                          <?php $query = $connection->query($sql); while($row = $query->fetch_assoc()): ?>
                          <?php
                          $rangking = number_format((float) $row["rangking"], 8, '.', '');
                          $q = $connection->query("SELECT nim FROM hasil WHERE nim='$row[nim]' AND kd_beasiswa='$_POST[kd_beasiswa]' AND tahun='$row[tahun]'");
                          if (!$q->num_rows) {
                          $connection->query("INSERT INTO hasil VALUES(NULL, '$_POST[kd_beasiswa]', '$row[nim]', '".$rangking."', '$row[tahun]')");
                         }
                          ?>
                         <tr>
                          <td><?=$no++?></td>
                           <td><?=$row["nim"]?></td>
                           <td><?=$row["nama"]?></td>
                           <?php for($i=0; $i<count($namaKriteria); $i++): ?>
                           <?php endfor ?>
                           <td><?=$rangking?></td>
                         </tr>
                         <?php endwhile;?>

                      </table>
                    </div>
                    <div class="row mt-4">
                      <div class="col-lg-8">
                       
                        
                      </div>
                      <div class="col-lg-4 text-right">
                        
                        <hr class="mt-2 mb-2">
                        <div class="invoice-detail-item">
                          
                          <div class="invoice-detail-name">Total</div>
                          <div class="invoice-detail-value invoice-detail-value-lg"><?=$no-1?> Siswa </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="text-md-right">
                <div class="float-lg-left mb-lg-0 mb-3">
                  
                </div>
                <button class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>
              </div>
            </div>

  <?php } else { ?>
    
  <?php } ?>



          </div>
        </section>
      </div>