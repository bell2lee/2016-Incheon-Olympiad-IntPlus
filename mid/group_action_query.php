<?php

	if($_SESSION['logininfo']){
		if($_GET['group_num'] && $_GET['type']){

			// 그룹정보 가져오기

			$gpInfo_query = $mysqli->query("SELECT * FROM `groups` WHERE `group_no` = " . $mysqli->real_escape_string($_GET['group_num']));
			$gpInfo_data = $gpInfo_query->fetch_array();

			if($gpInfo_data){ // 그룹정보 존재 여부 검증
				
				$gpMemberInfo_query = $mysqli->query("SELECT * FROM `group_members` WHERE `member_no` = " . $mysqli->real_escape_string($_SESSION['logininfo']['no']) . " AND `group_no` = " . $mysqli->real_escape_string($_GET['group_num']));


				switch($_GET['type']){
					case "group_join":
						//가입 신청
						if(!$gpMemberInfo_query->num_rows){ // 그룹 가입여부 검증 false 시 통과
							echo "";
							$gpState_query = $mysqli->query("SELECT * FROM `groups` WHERE `group_no` = " . $mysqli->real_escape_string($_GET['group_num']) . " AND `group_state` LIKE 'normal'"); // 넘어온 그룹 번호를 가진 그룹이 전체공개 그룹인지 검증
							if($gpState_query->num_rows){
								


								$mysqli->query("INSERT INTO `group_members` (`no`, `member_no`, `group_no`, `lavel`) VALUES (NULL, '" . $mysqli->real_escape_string($_SESSION['logininfo']['no']) . "', '" . $mysqli->real_escape_string($_GET['group_num']) . "', '1');");


								echo json_encode(array('state'=>'success'));

							}else{ echo json_encode(array('state'=>'error5')); } // 전체공개가 아닌 그룹

						}else{ echo json_encode(array('state'=>'error4')); } // 이미 가입되있는 그룹
						
						break;

					case "create_group":

						/*
						INSERT INTO `groups` (`group_no`, `name`, `group_state`, `date`) VALUES (NULL, '테스트', 'normal', '1111');



		INSERT INTO `group_members` (`no`, `member_no`, `group_no`, `lavel`) VALUES (NULL, '3', '1', '10');
		NULL, 회원번호, 그룹번호, 레벨
						*/
						if($_POST['group_name'] && $_POST['group_area'] && $_POST['group_type']){

							$check_gp_name_query = $mysqli->query("SELECT * FROM `groups` WHERE `name` LIKE '" . $mysqli->real_escape_string(htmlspecialchars($_POST['group_name'])) . "'");

							if(!$check_gp_name_query->num_rows){
								// 그룹 생성
								$now_time = time();

								switch($_POST['group_type']){
									case 1:
										$_POST['group_type'] = "normal";
										break;
									case 2:
										$_POST['group_type'] = "ashow";
										break;
									case 3:
										$_POST['group_type'] = "notshow";
										break;
									default:
										$_POST['group_type'] = "normal";
										break;
								}



								$create_gp_query = $mysqli->query("INSERT INTO `groups` (`group_no`, `name`, `intro`, `group_state`, `date`) VALUES (NULL, '" . $mysqli->real_escape_string(htmlspecialchars($_POST['group_name'])) . "', '" . $mysqli->real_escape_string(htmlspecialchars($_POST['group_area'])) . "', '" . $_POST['group_type'] . "', '" . $now_time . "');");
								

								// 생성한 그룹의 번호 알아내기
								$know_num_query = $mysqli->query("SELECT * FROM `groups` WHERE `name` LIKE '" . $mysqli->real_escape_string(htmlspecialchars($_POST['group_name'])) . "' AND `date` = " . $now_time);
								
								$know_num_data = $know_num_query->fetch_array();
								
								
								$mysqli->query("INSERT INTO `group_members` (`no`, `member_no`, `group_no`, `lavel`) VALUES (NULL, '" . $_SESSION['logininfo']['no'] . "', '" . $know_num_data['group_no'] . "', '1');");

								echo json_encode(array('state'=>'success'));




							}else { echo json_encode(array('state'=>'error6')); } // 이미 존재하는 이름의 그룹

						}else{ echo json_encode(array('state'=>'error2')); }

						break;

					case "make_vote":
						//INSERT INTO `vote` (`no`, `vote_name`, `vote_type`, `has_group_no`, `date`, `finish_date`, `vote_dead`, `vote_delete`) VALUES (NULL, '태후니는 여자일까?', '1', '2', '1464696155', '1464696152', 'N', 'N');
						//INSERT INTO `vote` (`no`, `vote_name`, `vote_type`, `has_group_no`, `date`, `finish_date`, `vote_dead`, `vote_delete`) VALUES (NULL, '테스트 설문2', '1', '2', '1464940280', '1484940280', 'N', 'N');

					case "group_img_change":
						echo "";
						break;

				}

			}else{ echo json_encode(array('state'=>'error3')); } // 그룹 존재 X

		}else{ echo json_encode(array('state'=>'error2')); } // 필수 파라미터 충족 X

	}else{ echo json_encode(array('state'=>'error1')); } // 세션 X
?>