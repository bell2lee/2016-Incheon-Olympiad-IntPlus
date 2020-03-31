<?php
	if($_SESSION['logininfo']){
		if($_GET['doc_num'] ){
			// 그룹정보 가져오기
			
			$viewPerCheck_query = $mysqli->query("SELECT * FROM `twt` WHERE `no` = " . $mysqli->real_escape_string($_GET['doc_num']) . " AND `doc_delete` LIKE 'N' ORDER BY `no` DESC");
			$viewPerCheck_data = $viewPerCheck_query->fetch_array();

			if($viewPerCheck_data){
				

				$gpInfo_query = $mysqli->query("SELECT * FROM `groups` WHERE `group_no` = " . $mysqli->real_escape_string($viewPerCheck_data['group_no']));
				$gpInfo_data = $gpInfo_query->fetch_array();

				if($gpInfo_data){ // 그룹정보 존재 여부 검증
					$member = new member($mysqli);
				
					$gpMemberInfo_query = $mysqli->query("SELECT * FROM `group_members` WHERE `member_no` = " . $mysqli->real_escape_string($_SESSION['logininfo']['no']) . " AND `group_no` = " . $mysqli->real_escape_string($viewPerCheck_data['group_no']));
					
					$gpMemberInfo_data = $gpMemberInfo_query->fetch_array();
					if($gpMemberInfo_data){ // 타임 라인 접속을 시도한 회원이 그룹에 가입되어있는지 검증

						$thisDocMember_data = $member->search_member_to_no($viewPerCheck_data['have_member']);
						/* 설문조사, 첨부 이미지 처리 부 */
						$j = 0;

						$img_query = $mysqli->query("SELECT * FROM `twt_img` WHERE `twt_no` = " . $_GET['doc_num'] . " ORDER BY `twt_img`.`file_no` ASC");

?>


						<div class="timeline_box"><div class="writer"><img src="./tpl/img/non_profile.png" class="circle responsive-img"><div class="wirter_name"><a href="/?mid=member_view&amp;member_num=<?=$viewPerCheck_data['have_member']?>"><?=$thisDocMember_data['real_name']?></a></div></div><div class="doc"><?=$viewPerCheck_data['document']?></div>
						<?php while($img_data = $img_query->fetch_array()){ ?>
							<div class="img_zone"><i class="fonti um-chevron-right um-2x left_a"></i><ul><li><div class="material-placeholder"><img height="300" class="materialboxed" src="/php/upload_img/<?=$img_data['file_url']?>"></div></li></ul></div>
							
							</div>
						<?php $j++;}
							
						//댓글 부
						
						
						
						
					}else{ echo "<script>alert('그룹에 가입되어있지 않습니다.');location='/?mid=twt_view&twt_num=" . $_GET['group_num'] . "'</script>"; }

				}


			}else{
				echo "잘못된 접근이거나 삭제된 글입니다.";
			}

		}

	}
?>
<div style="border:1px solid #c8c8c8;">
	<div style="padding:15px; border-bottom:1px solid #c8c8c8;"><button class="waves-effect waves-light btn"><i class="material-icons" style="font-size:10pt;">thumb_up</i> 좋아요</button><span style="margin-left:15px;">5명이 이 게시물을 좋아합니다</span></div>
	<div style="padding:15px;">
		<div style="border-bottom:1px solid #e6e6e6; padding:7px 0;">
			<img src="./tpl/img/non_profile.png" style='width:40px; float:left;' class="circle responsive-img">
			<div style="padding:10px 20px 10px 10px; float:left;"><a href="">이종휘</a></div><div style="padding:10px;">ㅇㅈㅇㅈㅇㅈㅇㅈ</div>
		</div>
		<div calss="_clear"></div>
		<div style="border-bottom:1px solid #e6e6e6; padding:7px 0;">
			<img src="./tpl/img/non_profile.png" style='width:40px; float:left;' class="circle responsive-img">
			<div style="padding:10px 20px 10px 10px; float:left;"><a href="">이종휘</a></div><div style="padding:10px;">ㅇㅈㅇㅈㅇㅈㅇㅈ</div>
		</div>
		<div calss="_clear"></div>
		<div style="border-bottom:1px solid #e6e6e6; padding:7px 0;">
			<img src="./tpl/img/non_profile.png" style='width:40px; float:left;' class="circle responsive-img">
			<div style="padding:10px 20px 10px 10px; float:left;"><a href="">이종휘</a></div><div style="padding:10px;">ㅇㅈㅇㅈㅇㅈㅇㅈ</div>
		</div>
		<div calss="_clear"></div>
		<div class="row">
			<div class="input-field col s8"><input type="text" placeholder="댓글 내용을 입력하세요"></div>
			<button style="float:right; margin:15px 0;" class="waves-effect waves-light btn">글쓰기</button>
		</div>
			 
		</div>
	</div>
</div>