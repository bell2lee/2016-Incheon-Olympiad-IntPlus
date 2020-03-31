<script>
	var gp_num = <?=urlencode(htmlspecialchars($_GET['group_num']))?>;

</script>
<style>
	#center_content {border:none; background:none;}
	#center_content .center_co_pad {padding:0px;}
</style>
<div id="timeline_bottom">
	ddd
</div>
<?php
	//INSERT INTO `twt` (`no`, `group_no`, `date`, `last_date`, `ip`, `doc_lock`, `doc_delete`, `document`) VALUES (NULL, '1', '11111111', '11111111', '127.0.0.1', 'N', 'N', '테스트글'); 작성

	/*
		그룹형태
	
		normal : 정상. 즉 모른 사람들이 가입 신청을 할 수 있으며 즉시 승인됨.
		check_gp : 체크그룹. 가입신청은 모든 사람들이 되나 관리자가 승인 시 가입됨.
		hider : 비공개그룹. 가입신청은 발급받은 초대장 URL로만 가입신청가능.
	
	*/
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
					/**   타임라인 시작 부   **/
					
					if($gpMemberInfo_data['lavel'] == 10){ // 만약 그룹 관리자의 경우
						if($gpInfo_data['date'] < ($gpInfo_data['date'] + 259200)){ // 그룹 생성으로 부터 3일째까지 기본 설정 출력
							
							echo "<div class='timeline_box notice text_center'><i class='fonti um-heart-o'></i> 그룹 설명과 사진 등 그룹 정보를 추가해보세요! <a href='/?mid=group_adm&group_num=" . $_GET['group_num'] . "'>그룹 관리자 바로가기</a> <i class='fonti um-heart-o'></i></div>";
						}else{
							// 3일 지남
						}
					}
					// 그룹 타임라인에 기본 그룹 정보 출력 부


					// 설문조사 출력 부
						
						
						// 만료되지 않은 설문 리스트 가져오기
						$voteList_query = $mysqli->query("SELECT * FROM `vote` WHERE `has_group_no` = " . $mysqli->real_escape_string($_GET['group_num']) . " AND `finish_date` > " . time() . " AND `vote_dead` LIKE 'N' AND `vote_delete` LIKE 'N'");


						$voteList_data = $voteList_query->fetch_array();
						
						if($voteList_data){
							echo "<div class='timeline_box notice text_center'>진행 중인 설문조사가 <b>" . $voteList_query->num_rows . "개</b>있습니다. &nbsp;<button onClick='location=\"/?mid=vote_line&group_num=" . $_GET['group_num'] . "\"' class='waves-effect waves-light btn' style='background:#F44336;'>설문조사 하러 가기</button></div>";
						}
					?>
					<div class="group_info"><div class="write_block"><a href="javascript:write_layer_show();" class="waves-effect waves-light btn">글쓰기</a></div><div class="group_name"><?=$gpInfo_data["name"]?></div> 그룹 멤버 <b><?=$gpMemberTotal_query->num_rows?></b></div>
					
					<!--타임라인 존 시작-->
					<div class="timeline_zone"></div>
					<!--타임라인 존 끝-->
					
					<!--글쓰기 부분 시작-->
					<div class="write_layer">
						<!--doc_text-->
						<nav class="write_header">
							<div class="container">
								<div class="nav-wrapper">
									<a href="javascript:write_layer_hide();" class="left">닫기</a>
									<a href="#" class="brand-logo" style="font-size:10pt;">글쓰기</a>
								</div>
	
								<button onClick="newdoc_write()" style="border:none; background:#93c8b4;" class="right">작성</button>
							</div>
						</nav>
						
						<div class="container">
							<div class="editer">
								<div class="row">

									<div class="input-field col s12">
									<textarea id="textarea1" style="margin-boottom:55px;" class="materialize-textarea"></textarea>
									<label for="textarea1">Textarea</label>
									</div>
										<div class="_clear"></div>
										<div id="fileuploader">Upload</div>

								</div>
							</div>
						</div>
						
						<div class="write_bottom"><div class="container">
							<div class='menu' onClick="$('input[type=file]').trigger('click');"><div class='menu_pad'><i class='fonti um-photo um-2x'></i><div class="menu_name">사진추가</div></div></div>
							<div class='menu' onClick="$('input[type=file]').trigger('click');"><div class='menu_pad'><i class='fonti um-map-marker um-2x'></i><div class="menu_name">위치추가</div></div></div>

							 
							</div></div>

						<!--다음 주소 플러그인-->
						<div class="mapEdit_class" style="display:none;">
							<input type="text" id="sample2_postcode" placeholder="우편번호">
							<input type="button" onclick="sample2_execDaumPostcode()" value="우편번호 찾기"><br>
							<input type="text" id="sample2_address" placeholder="한글주소">
							
							<!-- iOS에서는 position:fixed 버그가 있음, 적용하는 사이트에 맞게 position:absolute 등을 이용하여 top,left값 조정 필요 -->
							<div id="layer" style="display:none;position:fixed;overflow:hidden;z-index:1;-webkit-overflow-scrolling:touch;"><img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="closeDaumPostcode()" alt="닫기 버튼"></div>
							<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
							<script>var element_layer = document.getElementById('layer');function closeDaumPostcode() {element_layer.style.display = 'none';}function sample2_execDaumPostcode() {new daum.Postcode({oncomplete: function(data) {var fullAddr = data.address;var extraAddr = '';if(data.addressType === 'R'){if(data.bname !== ''){extraAddr += data.bname;}if(data.buildingName !== ''){extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);}fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');}document.getElementById('sample2_postcode').value = data.zonecode;document.getElementById('sample2_address').value = fullAddr;element_layer.style.display = 'none';},width : '100%',height : '100%'}).embed(element_layer);element_layer.style.display = 'block';initLayerPosition();}
							function initLayerPosition(){var width = 300; //우편번호서비스가 들어갈 element의 width
							var height = 460; //우편번호서비스가 들어갈 element의 height
							var borderWidth = 5; //샘플에서 사용하는 border의 두께
							// 위에서 선언한 값들을 실제 element에 넣는다.
							element_layer.style.width = width + 'px';
							element_layer.style.height = height + 'px';
							element_layer.style.border = borderWidth + 'px solid';
							// 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
							element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
							element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
							}</script>
						</div>
					</div>
					<!--글쓰기 부분 끝-->
					<?php
					$gp_twt_output_query = $mysqli->query("SELECT * FROM `twt` WHERE `group_no` = " . $mysqli->real_escape_string($_GET['group_num']) . " ORDER BY `twt`.`last_date` DESC");

					//print_r($data);
					//echo "<div class='timeline_box text_center'><i class='fonti um-heart-o'></i> " . date("y년 n월 j일", $gpInfo_data["date"]) . "에 '" . $gpInfo_data["name"] . "'이(가) 생성되었습니다. <i class='fonti um-heart-o'></i></div>";
					
				}else{
					// 검증 실패
					// 검증 실패 시 그룹 상태에 따라 가입화면 출력과 차단 여부 결정

					$gpMemberTotal_query = $mysqli->query("SELECT * FROM `group_members` WHERE `group_no` = " . $mysqli->real_escape_string($_GET['group_num']));


					?>
					<script>
						function join_group(){
							$.get("/?mid=group_action_query&type=group_join&p-type=one", {'group_num': gp_num}, function(obj){
								switch(obj.state){
									case "error1":
										alert("잘못된 접근이거나 만료된 세션입니다");
										break;
									case "error2":
										alert("필수 파라미터를 충족하지 못하였습니다");
										break;
									case "error3":
										alert("잘못된 접근이거나, 그룹이 존재하지 않거나 비공개 그룹입니다");
										break;
									case "error4":
										alert("이미 가입되어있는 그룹");
										break;
									case "error5":
										alert("잘못된 접근이거나, 그룹이 존재하지 않거나 비공개 그룹입니다");
										break;
									case "success":
										alert('축하드립니다! 가입되었습니다');
										location='/';
										
										break;
								}
							}, "json");

						}
					</script>

					<div class="col s12 m6 l4"><div class="card hoverable large" onClick="location='/?mid=group_timeline&group_num=<?=$_GET['group_num']?>"><div class="card-image"><img src="/tpl/img/default_group_img_1.png"><span class="card-title"><?=$gpInfo_data['name']?></span></div><div class="card-content"><p><?=$gpInfo_data['intro']?><br>맴버 수 : <?=$gpMemberTotal_query->num_rows?>명</p></div>
					<div class="card-action">
					<a href="javascript:join_group();">바로 가입하기</a>
					<a href="/">돌아가기</a>
					</div>
					</div></div>
					
					<?php

				}
			}else{ echo "잘못 접근했거나, 없어진 그룹입니다."; }

		}else{ echo "헤더값 검증에 실패했습니다."; }

	}else{ echo "로그인 사용자만 접근 가능합니다"; }
?>


<script>
$(document).ready(function(){
	$(".header_ajaxLoading").fadeIn(300);
	timeline_document_load(0, gp_num);
	$(".header_ajaxLoading").fadeOut(300);

});

/*
	#center_content {padding-top:100px; border:none; width:100%;}
	#center_content .center_co_pad {padding:0px;}
*/
</script>

