<?php
	if($_SESSION['logininfo']){
		$data = array('state'=>'error2');
		echo json_encode($data);
		exit();
	}
	function isValidEmail($email){ 
	    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
	}
	if($_POST['id'] && $_POST['pw'] && $_POST['namez'] && $_POST['email']){
		
		$check_id = $mysqli->query("SELECT * FROM `member` WHERE `username` LIKE '" . $mysqli->real_escape_string($_POST['id']) . "'");
		$id_data = $check_id->fetch_array();
		if(!$id_data){
			if(isValidEmail($_POST['email'])){

				$email_re = $mysqli->query("SELECT * FROM `member` WHERE `e-mail` LIKE '" . $mysqli->real_escape_string(urlencode($_POST['email'])) . "'");
				$email_re_data = $email_re->fetch_array();
				
				if($email_re_data){
					$data = array('state'=>'error4');
					echo json_encode($data);
				}else{
					$check_query = $mysqli->query("INSERT INTO `member` (`no`, `username`, `password`, `ip`, `IS_ADMIN`, `e-mail`, `email_cert`, `date`, `last_ip`) VALUES (NULL, '" . $mysqli->real_escape_string($_POST['id']) . "', '" . itube_sha($_POST['pw']) . "', '" . $_SERVER['REMOTE_ADDR'] . "', 'N', '" . $mysqli->real_escape_string(urlencode($_POST['email'])) . "', '0', '" . time() . "', '" . time() . "');");

					$check_query = $mysqli->query("INSERT INTO `member` (`no`, `username`, `real_name`, `password`, `ip`, `IS_ADMIN`, `e-mail`, `email_cert`, `date`, `last_ip`) VALUES (NULL, '" . $mysqli->real_escape_string($_POST['id']) . "', '" . $_POST['namez'] . "', '" . itube_sha($_POST['pw']) . "', '" . $_SERVER['REMOTE_ADDR'] . "', 'N', '" . $mysqli->real_escape_string(urlencode($_POST['email'])) . "', '0', '" . time() . "', '" . time() . "');");

					
					$data = array('state'=>'success');
					echo json_encode($data);
				}
				
			}else{
				$data = array('state'=>'error3');
				echo json_encode($data);
			}

		}else{
			$data = array('state'=>'error1');
			echo json_encode($data);
		}
	}else{
		$data = array('state'=>'shiting_error');
		echo json_encode($data);
	}
?>