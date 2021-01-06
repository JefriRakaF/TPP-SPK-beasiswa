<?php


if (!$connection = new Mysqli("localhost", "root", "", "spkberau")) {
  echo "<h3>ERROR: Koneksi database gagal!</h3>";
}


if (isset($_GET["page"])) {
  $_PAGE = $_GET["page"];
} else {
  $_PAGE = "home";
}

function page($page) {
  return "page/" . $page . ".php";
}

function alert($msg, $to = null) {
  $to = ($to) ? $to : $_SERVER["PHP_SELF"];
  return "<script>alert('{$msg}');window.location='{$to}';</script>";
}
