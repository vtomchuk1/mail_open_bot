<?php

const TOKEN = '';
$base_url = 'https://api.telegram.org/bot' . TOKEN . '/';
$users_id = 00000001;


function connect() {
    $host = 'localhost';
    $db   = 'mail'; // Имя БД
    $user = 'root';  // Имя пользователя БД
    $pass = 'root'; // Пароль БД
    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    );
    try {
        $pdo = new PDO($dsn, $user, $pass, $opt);

        return $pdo;
    } catch (PDOException $e) {
        echo 'Подключение не удалось: ' . $e->getMessage();
        return false;
        // die('Подключение не удалось: ' . $e->getMessage());
    }
}

function addMail($mail, $comment)
{
    $pdo = connect();
    if ($pdo)
    {
    	$time_upload = gmdate("d.m.Y H:i:s", time()+ ( 3 * 60 * 60 ));
		$data_sql = array("mail" => $mail, "status" => "0", "comment" => $comment, 'time_upload' => $time_upload);
		$st = $pdo->prepare("INSERT INTO mail (mail, status, comment, time_upload) VALUES(:mail, :status, :comment, :time_upload)");
		$st->execute($data_sql); 
		//echo $st;
		return $pdo->lastInsertId();
    }
}


function updateMail($id)
{
	global $base_url;
	global $users_id;
	global $text_message;
	$pdo = connect();
	if($pdo){
		$local_time = gmdate("d.m.Y H:i:s", time()+ ( 3 * 60 * 60 ));

		$st = $pdo->prepare("SELECT status FROM mail WHERE id = ?");
		$data_sql = array($id);
		$st->execute($data_sql);

		foreach($st as $arr){
			$status = $arr['status'];
		}

		if($status == '0'){

			$data_sql = array("time_open" => $local_time, "id" => $id, "status" => "1");
			$st = $pdo->prepare("UPDATE mail set time_open = :time_open, status = :status where id = :id");
			$st->execute($data_sql);

			$data_sql = array("id" => $id);
			$st = $pdo->prepare("SELECT mail, comment, time_upload, time_open FROM mail where id = :id");
			$st->execute($data_sql);

			foreach($st as $arr){
				$sql_mail = $arr['mail'];
				$sql_comment = $arr['comment'];
				$sql_time_upload = $arr['time_upload'];
				$sql_time_open = $arr['time_open'];
			}

			$text_message = "
Лист Доставлено
Пошта    	  : $sql_mail
Коментар 	  : $sql_comment
Час відправлення  : $sql_time_upload
Час відкриття 	  : $sql_time_open
			";

			$params = array(
				'chat_id' => $users_id,
				'text' => $text_message
			);

			$url = $base_url . 'sendMessage?' . http_build_query($params);
			file_get_contents($url);
		}
	}
}





?>
