<?php
	if($_SESSION['logininfo']){
		if($_GET['group_num']){
			// 그룹정보 가져오기

			$gpInfo_query = $mysqli->query("SELECT * FROM `groups` WHERE `group_no` = " . $mysqli->real_escape_string($_GET['group_num']));
			$gpInfo_data = $gpInfo_query->fetch_array();

			if($gpInfo_data){ // 그룹정보 존재 여부 검증
				
				$gpMemberInfo_query = $mysqli->query("SELECT * FROM `group_members` WHERE `member_no` = " . $mysqli->real_escape_string($_SESSION['logininfo']['no']) . " AND `group_no` = " . $mysqli->real_escape_string($_GET['group_num']));
				$gpMemberInfo_data = $gpMemberInfo_query->fetch_array();

				if($gpMemberInfo_data){ // 타임 라인 접속을 시도한 회원이 그룹에 가입되어있는지 검증
				}

			}

		}

	}
	//INSERT INTO `twt_comment` (`no`, `doc_no`, `c_content`, `comm_delete`, `date`, `ip`) VALUES (NULL, '58', '인정하는 부분이구요', 'N', '1463137113', '127.0.0.1');
?>


<div class="collection">
	<a href="#!" class="collection-item">
		<div>dddddddd</div>
	</a>
	<a href="#!" class="collection-item active">Alvin</a>
	<a href="#!" class="collection-item">Alvin</a>
	<a href="#!" class="collection-item">Alvin</a>
</div>

