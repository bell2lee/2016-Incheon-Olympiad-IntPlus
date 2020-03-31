<?php
	//or trigger_error($mysqli->error)
	if($_SESSION['logininfo']){
		if($_GET['group_num'] && $_GET['type']){
			// 그룹정보 가져오기

			$gpInfo_query = $mysqli->query("SELECT * FROM `groups` WHERE `group_no` = " . $mysqli->real_escape_string($_GET['group_num']));
			$gpInfo_data = $gpInfo_query->fetch_array();

			if($gpInfo_data){ // 그룹정보 존재 여부 검증
				
				$gpMemberInfo_query = $mysqli->query("SELECT * FROM `group_members` WHERE `member_no` = " . $mysqli->real_escape_string($_SESSION['logininfo']['no']) . " AND `group_no` = " . $mysqli->real_escape_string($_GET['group_num']));
				$gpMemberInfo_data = $gpMemberInfo_query->fetch_array();

				if($gpMemberInfo_data){ // 타임 라인 접속을 시도한 회원이 그룹에 가입되어있는지 검증
					$gpMemberTotal_query = $mysqli->query("SELECT * FROM `group_members` WHERE `group_no` = " . $mysqli->real_escape_string($_GET['group_num']));
					/**   타임라인 시작 부   **/
					
					// 그룹 타임라인에 기본 그룹 정보 출력 부
					
					switch($_GET['type']){
						case "new_doc":
							/* 새로운 글 내보내기 소스코드 시작 부 */
							
							if(!$_GET['lastDocNum']){ // 최신글 로드인지?
								// 타임라인 첫 로드 시 내보내기
								
								//$gp_twt_output_query = $mysqli->query("SELECT * FROM `twt` WHERE `group_no` = " . $mysqli->real_escape_string($_GET['group_num']) . " ORDER BY `twt`.`last_date` DESC Limit 0, 9;");
								
								$gp_twt_output_query = $mysqli->query("SELECT * FROM `twt` WHERE `group_no` = " . $mysqli->real_escape_string($_GET['group_num']) . " AND `doc_lock` LIKE 'N' AND `doc_delete` LIKE 'N' ORDER BY `twt`.`last_date` DESC Limit 0, 9;");

							}else{ // 예전글 로드인지?
								
								// 마지막 twt의 문서 번호를 통해 업데이트 시간 체크
								$gp_twt_lastDocNum_lastDate_query = $mysqli->query("SELECT * FROM `twt` WHERE `no` = " . $mysqli->real_escape_string($_GET['lastDocNum']) . " AND `group_no` = " . $mysqli->real_escape_string($_GET['group_num'])); $gp_twt_lastDocNum_lastDate_data = $gp_twt_lastDocNum_lastDate_query->fetch_array();
								
								$gp_twt_output_query = $mysqli->query("SELECT * FROM `twt` WHERE `group_no` = " . $mysqli->real_escape_string($_GET['group_num']) . " AND `last_date` < " . $gp_twt_lastDocNum_lastDate_data['last_date'] . " AND `doc_lock` LIKE 'N' AND `doc_delete` LIKE 'N' ORDER BY `twt`.`last_date` DESC Limit 0, 9;");

							}


							$i = 0;

							while($gp_twt_output_data = $gp_twt_output_query->fetch_array()){
								
								$gp_twt_memInfo_query = $mysqli->query("SELECT * FROM `member` WHERE `no` = " . $mysqli->real_escape_string($gp_twt_output_data['have_member']));
								$gp_twt_memInfo_data = $gp_twt_memInfo_query->fetch_array();

								/* 설문조사, 첨부 이미지 처리 부 */
								$j = 0;

								$img_query = $mysqli->query("SELECT * FROM `twt_img` WHERE `twt_no` = " . $gp_twt_output_data['no'] . " ORDER BY `twt_img`.`file_no` ASC");


								while($img_data = $img_query->fetch_array()){
									$img_da1[$j] = $img_data['file_url'];

									$j++;
								}
								
								/* 데이터 저장 */

								$gp_twt_name[$i] = $gp_twt_memInfo_data['real_name'];
								$gp_twt_name_num[$i] = $gp_twt_output_data['have_member'];
								$gp_twt_doc_num[$i] = $gp_twt_output_data['no'];
								$gp_twt_document[$i] = $gp_twt_output_data['document'];
								$gp_twt_date[$i] = $gp_twt_output_data['date'];
								$gp_twt_lastDate[$i] = $gp_twt_output_data['last_date'];
								$gp_twt_imgData[$i] = $img_da1;

								if($gp_twt_name_num[$i] == $_SESSION['logininfo']['no']){
									$gp_twt_mine[$i] = true;
								}else { $gp_twt_mine[$i] = false; }
								
								unset($img_da1);
								$i++;
							}

							$data = array('state'=>'success', 'document_num'=>$gp_twt_doc_num,'writer'=>$gp_twt_name, 'writer_num'=>$gp_twt_name_num, 'date'=>$gp_twt_date, 'lastDate'=>$gp_twt_lastDate, 'document'=>$gp_twt_document, "mine"=>$gp_twt_mine, "img"=>$gp_twt_imgData);



							
							echo json_encode($data);
							break;
						
						case "write_doc":
							/* 새로운 글 쓰기 소스코드 시작 부 */


							//http://int.kr3.kr/?mid=group_timeline_query&group_num=2&type=write_doc&p-type=one   &doc_text=헬로%20마더뻐커

							if($_POST['doc_text']){

								//print_r($_POST['imgs']);exit;
								
								$_POST['doc_text'] = nl2br(htmlspecialchars($_POST['doc_text']));
								$now_time = time();

								$mysqli->query("INSERT INTO `twt` (`no`, `group_no`, `have_member`, `date`, `last_date`, `ip`, `doc_lock`, `doc_delete`, `document`) VALUES (NULL, '" . $mysqli->real_escape_string($_GET['group_num']) . "', '" . $_SESSION['logininfo']['no'] . "', '" . $now_time . "', '" . $now_time . "', '" . $_SERVER['REMOTE_ADDR'] . "', 'N', 'N', '" . $_POST['doc_text'] . "');") or trigger_error($mysqli->error);

								
								$thisTwtNo_query = $mysqli->query("SELECT * FROM `twt` WHERE `have_member` = " . $_SESSION['logininfo']['no'] . " AND `date` = " . $now_time . " ORDER BY `no` DESC");
								$thisTwtNo_data = $thisTwtNo_query->fetch_array();

								$thisTwtNo = $thisTwtNo_data['no'];
								
								$img_list = json_decode($_POST['imgs'][0]);
								

								$i = 0;
								while($img_list[$i]){
									$mysqli->query("INSERT INTO `twt_img` (`no`, `twt_no`, `file_no`, `file_url`, `file_area`) VALUES (NULL, '" . $thisTwtNo . "', '" . $i . "', '" . $mysqli->real_escape_string($img_list[$i][0]) . "', 'null');");
									$i++;
								}

								echo json_encode(array('state'=>'success'));
								
							}else{
								echo json_encode(array('state'=>'error2'));
							}
							
									
							break;

						case "delete_doc":
							/* 글 삭제 */

							if($_SESSION['logininfo']['admin'] == "Y"){
								
							}else{
								
							}

							// 삭제ㄱㄱ
						case "group_img_write":
							if($gpMemberInfo_data['lavel'] == 10 || $_SESSION['logininfo']['admin'] == "Y"){ // 만약 그룹 관리자의 경우
								

							}else{ echo json_encode(array('state'=>'error5')); } // 관리자 아님

							break;
					}
					

				}else{ echo json_encode(array('state'=>'error4')); } // 가입 검증 실패, 권한이없다

			}else{ echo json_encode(array('state'=>'error3')); } // 그룹 없는 그룹이거나 삭제된 그룹임

		}else{ echo json_encode(array('state'=>'error2')); } // 헤더 검증 실패

	}else{ echo json_encode(array('state'=>'error1')); } // 로그인 낫
	
?>