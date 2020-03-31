<?php
	if($_GET['id'] && $_GET['key']){
		$key_search = $mysqli->query("SELECT * FROM `email_key` WHERE `id` LIKE '" . $mysqli->real_escape_string($_GET['id']) . "' AND `e_key` LIKE '" . $mysqli->real_escape_string($_GET['key']) . "'");
		$key_data = $key_search->fetch_array();
		
		if(!$key_data){
			echo "잘못된 접근입니다.";
		}else{
			if(($key_data['date']  + 1800) > time()){
				
				$ee = $mysqli->query("SELECT * FROM `member` WHERE `username` LIKE '" . $mysqli->real_escape_string($key_data['id']) . "'");
				$ee_data = $ee->fetch_array();

				$mysqli->query("UPDATE `member` SET `email_cert` = '1' WHERE `member`.`no` = " . $mysqli->real_escape_string($ee_data['no']) . ";");
				$mysqli->query("DELETE FROM `email_key` WHERE `id` like '" . $mysqli->real_escape_string($_GET['id']) . "'");
				
				//delete from ABCDE where body like 'dd'
				
				echo "인증 성공입니다. 5초후 사운드 플레이스로 향합니다. <script>setTimeout(function(){location='http://soundplace.net/'}, 5000)</script>";
				
			}else{
				echo "인증기간이 지났습니다.";
			}
		}
		
	}else{
		echo "잘못된 접근입니다.";
	}
?>