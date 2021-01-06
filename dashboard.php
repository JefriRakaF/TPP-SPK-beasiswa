<?php
require_once "lib/koneksi.php";
session_start(); 
include "header.php";?>
<div class="row">
            <div class="col-md-12">
              <?php include page($_PAGE); ?>
            </div>
        </div>
    </div>
    <?php


include "footer.php";
?>