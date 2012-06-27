<?php
$home_dir        = $_SERVER['HOME'];
$save_path       = $home_dir . "/Desktop/CardMinder";
$cardminder_path = $home_dir . "/Library/Application Support/CardMinder/CardMinder DB.cmdb";
$sqlite_db       = $cardminder_path . "/CardMinder.sqldb";
$pdf_path        = $cardminder_path . "/Images";
$log_file        = $save_path . "/.lastupdate.txt";
$sql             = "";
if ( !file_exists($save_path) ) 
  {
    mkdir($save_path);
  } 
      
$db = "sqlite:" . $sqlite_db;

$pdo = new PDO($db);
if ( file_exists($log_file) )
  {
    $fp = fopen($log_file, "r");
    $last_id = fread($fp,10);
    if ( $last_id > 0) {
      $sql = "select Z_PK,ZFULL_NAME, ZFACE_IMAGE_FILE from ZCARD where Z_PK > " .$last_id;
      $id = $last_id;
    }
    fclose($fp);
  }

// SQLが無いと言う事は、最初っから全件取得
if (!$sql)
  {
    $sql = "select Z_PK,ZFULL_NAME, ZFACE_IMAGE_FILE from ZCARD";
  }
$entries = $pdo->query($sql);

while($entry = $entries->fetch())
  {
    //echo $entry['Z_PK'].$entry['ZFULL_NAME'] . ": ". $entry['ZFACE_IMAGE_FILE'] . "\n";
    copy($pdf_path."/".$entry['ZFACE_IMAGE_FILE'], $save_path."/".$entry['Z_PK'].$entry['ZFULL_NAME'].".pdf");
    $id = $entry['Z_PK'];
  }
$fp = fopen($log_file, "w");
fwrite($fp, $id);
fclose($fp);

/*
* Local Variables:
* mode: php
* coding: utf-8-unix
* tab-width: 4
* c-basic-offset: 4
* c-hanging-comment-ender-p: nil
* indent-tabs-mode: nil
* End:
*/
?>