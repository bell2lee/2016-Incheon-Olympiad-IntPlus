<?php
	// 뷰를 시도한 회원이 이 회원을 조회할 수 있는 권한을 가지고 있는지 체크.
	if($_SESSION['logininfo']){
		if($_GET['member_num']){
			// or trigger_error($mysqli->error)
			// 뷰를 시도한 회원의 가입된 그룹 조회 
			$mygpList_query = $mysqli->query("SELECT * FROM `group_members` WHERE `member_no` = " . $mysqli->real_escape_string($_SESSION['logininfo']['no']));
			
			$view_true = false;
			while($mygpList_data = $mygpList_query->fetch_array()){
				$viewmemberGpList_query = $mysqli->query("SELECT * FROM `group_members` WHERE `member_no` = " . $mysqli->real_escape_string($_GET['member_num']) . " AND `group_no` = " . $mysqli->real_escape_string($mygpList_data['group_no']));
				
				if($viewmemberGpList_query->num_rows >= 1){
					// 같은 그룹에 속해 있는 경우
					$view_true = true;

					break;
				}
			}
			
			if($view_true === true){ // 권한 충족
				// 여기에 $_GET['member_num'] 회원에 대한 정보 출력
				$viewmemberInfo_query = $mysqli->query("SELECT * FROM `member` WHERE `no` = " . $mysqli->real_escape_string($_GET['member_num']));
				$viewmemberInfo_data = $viewmemberInfo_query->fetch_array();
				if($viewmemberInfo_data){
			?>
				<div>
					<div style="float:left; margin-right:15px;"><img src="./tpl/img/non_profile.png" style="border:1px solid #dcdcdc; border-radius:3px;"></div>
					<div style="padding:15px; font-size:15pt;"><?=$viewmemberInfo_data['real_name']?></div>

					<div class="_clear"></div>
				</div>

				<?php

				}else{ echo "비공개 회원이거나 존재하지 않는 회원입니다"; }

			}else{ echo "권한이 없는 접근입니다"; }

		}else{ echo "필수 헤더값 검증에 실패했습니다"; }

	}else{ echo "잘못된 접근이거나 존재하지 않는 세션입니다."; }
?>
