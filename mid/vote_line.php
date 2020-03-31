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
					$gpMemberTotal_query = $mysqli->query("SELECT * FROM `group_members` WHERE `group_no` = " . $mysqli->real_escape_string($_GET['group_num']));
					
					
					$voteList_query = $mysqli->query("SELECT * FROM `vote` WHERE `has_group_no` = " . $mysqli->real_escape_string($_GET['group_num']) . " AND `finish_date` > " . time() . " AND `vote_delete` LIKE 'N' ORDER BY `vote`.`no` DESC"); // AND `vote_dead` LIKE 'N'


					while($voteList_data = $voteList_query->fetch_array()){ ?>
						<div class="card">
							<?php if($voteList_data['vote_dead'] == "Y") echo '<div class="card-disabled">조기 마감된 설문</div>'; ?>
							<div class="card-content">
								<span class="card-title grey-text text-darken-4"><small>설문내용 : </small><?=$voteList_data['vote_name']?><span class="right"><small><?=date("n월 j일 까지", $voteList_data['finish_date'])?></small></span></span>
								<!--답 출력 부-->
								<?php if($voteList_data['vote_type'] == 1): ?>
								<p><button class="waves-effect waves-light btn">네</button> <button class="waves-effect waves-light btn">아니오</button></p>
								<?php endif; ?>
							</div>
						</div>
					<?php 
					
					}

				}else { header("Location:/?mid=group_timeline&group_num=" . $_GET['group_num']); }

			}else{ echo json_encode(array('state'=>'error3')); } // 그룹 존재 X

		}else{ echo json_encode(array('state'=>'error2')); } // 필수 파라미터 충족 X

	}else{ echo json_encode(array('state'=>'error1')); } // 세션 X

?>
<!--activator-->

<div class="card">
	<div class="card-content">
		<span class="card-title grey-text text-darken-4"><small>설문내용 : </small>관리자 점심좀 골라주세요<span class="right"><small>1월 1일까지</small></span></span>
		<!--답 출력 부-->
			<form action="#">
			<p>
			<input name="group1" type="radio" id="test1" />
			<label for="test1">라면</label>
			</p>
			<p>
			<input name="group1" type="radio" id="test2" />
			<label for="test2">치킨</label>
			</p>
			<p>
			<input name="group1" type="radio" id="test3"  />
			<label for="test3">피자</label>
			</p>
			<p>
			<input name="group1" type="radio" id="test4"  />
			<label for="test4">짜장면</label>
			</p>
			<p>
			<input name="group1" type="radio" id="test5"  />
			<label for="test5">삼계탕</label>
			</p>
			<p><button class="waves-effect waves-light btn">설문완료</button></p>
			</form>

	</div>
</div>