<?php
	
	
	if($_SESSION['logininfo']){
		$data = array('state'=>'error2');
		echo json_encode($data);
		exit();
	}
	
	if($_POST['id'] && $_POST['pw']){
		$check_query = $mysqli->query("SELECT * FROM `member` WHERE `username` LIKE '" . $mysqli->real_escape_string($_POST['id']) . "' AND `password` LIKE '" . itube_sha($_POST['pw']) . "'");
		$check_data = $check_query->fetch_array();
		
		if($check_data){
			$mysqli->query("UPDATE `member` SET `last_ip` = '" . time() . "' WHERE `member`.`no` = " . $check_data['no'] . ";");
			$_SESSION['logininfo']['no'] = $check_data['no'];
			$_SESSION['logininfo']['id'] = $check_data['username'];
			$_SESSION['logininfo']['admin'] = $check_data['IS_ADMIN'];
			$_SESSION['logininfo']['email'] = $check_data['e-mail'];
			$data = array('state'=>'success',
							'id'=>$check_data['username']);
			echo json_encode($data);
		}else{
			$data = array('state'=>'error1');
			echo json_encode($data);
		}
	}
?>