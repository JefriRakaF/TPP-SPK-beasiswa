      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
                   <div class="section-body">
            

            <div class="invoice">
              <div class="invoice-print">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="invoice-title">
                     <div class="panel-heading"><h3 class="text-center">Sistem Penunjang Keputusan <br>
                      Penerimaan Beasiswa Dengan Metode Simple Additive Weighting (SAW) <br>
                    Di Kabupaten Berau</h3></div>
                    </div>
                    <hr>


                    <div class="row">
                      <div class="col-md-6">
                        <address>
                          <strong>Data Diri</strong><br>
                          Jefri Raka Fanshurna<br>
                          18.12.0750
                          Sistem Informasi 04
                        </address>
                      </div>

                      <div class="col-md-6 text-md-right">
                        <address>
                          <strong>Email</strong><br>
                          jefri.0707@students.amikom.ac.id<br><br>
                        </address>
                      </div>

                    </div>
                  </div>
                </div>




                <div class="row mt-4">
                  <div class="col-md-12">
                    <div class="section-title">Daftar User</div>
                    <div class="table-responsive">
                      <table class="table table-striped table-hover table-md">

                        <tr>
                          <th>No</th>
                          <th>Username</th>
                          <th>Status</th>
                        </tr>
                          <?php $no = 1; ?>
                      <?php if ($query = $connection->query("SELECT * FROM pengguna")): ?>
                          <?php while($row = $query->fetch_assoc()): ?>
                          <tr>
                              <td><?=$no++?></td>
                              <td><?=$row['username']?></td>
                              <td><?=$row['status']?></td>
                          </tr>
                          <?php endwhile ?>
                      <?php endif ?>

                      </table>
                    </div>
                    <div class="row mt-4">
                      <div class="col-lg-8">
                       
                        
                      </div>
                      <div class="col-lg-4 text-right">
                        
                        <hr class="mt-2 mb-2">
                        <div class="invoice-detail-item">
                          
                          <div class="invoice-detail-name">Total</div>
                          <div class="invoice-detail-value invoice-detail-value-lg"><?=$no-1?> User </div>
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


          </div>
        </section>
      </div>