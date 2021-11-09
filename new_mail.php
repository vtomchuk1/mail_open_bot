<?php

if(true){
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
}

include "sql.php";

$mail = $_GET['mail'];
$comment = $_GET['comment'];

$po = addMail($mail, $comment);

echo "<plaintext><body>Тут напишіть текст листа<br><img src='https://stiv.vtl.vn.ua/mail/img.php?id=$po' width=1 height=1><br></body></plaintext>";

?>