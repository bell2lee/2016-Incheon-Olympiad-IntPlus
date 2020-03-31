<?php 
	if($_SESSION['logininfo']){
		if($_GET['group_num']){
			// 그룹정보 가져오기
			$gpInfo_query = $mysqli->query("SELECT * FROM `groups` WHERE `group_no` = " . $mysqli->real_escape_string($_GET['group_num']));
			$gpInfo_data = $gpInfo_query->fetch_array();

			if($gpInfo_data){ // 그룹정보 존재 여부 검증
				
				$gpMemberInfo_query = $mysqli->query("SELECT * FROM `group_members` WHERE `member_no` = " . $mysqli->real_escape_string($_SESSION['logininfo']['no']) . " AND `group_no` = " . $mysqli->real_escape_string($_GET['group_num']));
				$gpMemberInfo_data = $gpMemberInfo_query->fetch_array();

				if($gpMemberInfo_data['lavel'] == 10){ // 만약 그룹 관리자의 경우 ?>
					<h3 style='color:#66a091;'>관리자 페이지</h3>
					
					<div class="row">
						<div class="col s12">
							<ul class="tabs">
								<li class="tab col s3"><a class="active" href="#test1">기본 그룹 정보</a></li>
								<li class="tab col s3"><a href="#test2">멤버 관리</a></li>
							</ul>
						</div>
						<div id="test1" class="col s12">
							<form class="col s12">
								<div class="row">
									<div class="input-field col s12">
										<input placeholder="Placeholder" id="first_name" value="<?=$gpInfo_data['name']?>" type="text">
										<label for="first_name">First Name</label>
									</div>
								</div>
							</form>
							
						</div>
						<div id="test2" class="col s12">Test 2</div>
					</div>
					
					
				<?php }else{ echo "그룹 관리자만 접근할 수 있습니다."; }

			}else{ echo "잘못 접근했거나, 없어진 그룹입니다."; }

		}else{ echo "헤더값 검증에 실패했습니다."; }

	}else{ echo "잘못된 접근이거나 존재하지 않는 세션입니다."; }
?>