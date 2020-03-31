<?php
	/*
		INSERT INTO `groups` (`group_no`, `name`, `group_state`, `date`) VALUES (NULL, '테스트', 'normal', '1111');



		INSERT INTO `group_members` (`no`, `member_no`, `group_no`, `lavel`) VALUES (NULL, '3', '1', '10');
		NULL, 회원번호, 그룹번호, 레벨
	*/
?>

<script>
$(document).ready(function(){
	$(".create_groupForm").submit(function(){
		if(!$("#group_name").val() || !$("#group_area").val() || !$("#group_type").val()){
			alert('모든 정보를 입력해주세요');
			return false;
		}else{
			$.post("/?mid=group_action_query&p-type=one&type=create_group&group_num=1", {group_name: $("#group_name").val(), group_area:$("#group_area").val(), group_type:$("#group_type").val()}, function(obj){
		        switch(obj.state){
			        case "error1":
			        	alert("세션이 만료되었거나 잘못된 접근입니다");
			        	break;
			        case "error2":
			        	alert("필수파라미터 충족불가");
			        	break;
					case "error6":
			        	alert("이미 사용하고 있는 이름의 그룹입니다");
			        	break;
			        case "success":
			        	alert("생성되었습니다. 메인페이지로 이동합니다");
						location='/';
			        	break;
		        }
		    }, "json");
			return false;
		}
	});
});
</script>

<div class="row">
	<form class="create_groupForm col s12" action="/?mid=group_action_query&p-type=one&type=create_group&group_num=1" method="post">
		<div class="input-field col s12">
			<input placeholder="ex) 옥련동 편의점 협회, 옥련공부방" id="group_name" type="text">
			<label for="group_name">그룹명</label>
		</div>

		<div class="input-field col s12">
			<textarea id="group_area" placeholder="간단한 자기소개를 입력해주세요" style="margin-boottom:55px;" class="materialize-textarea"></textarea>
			<label for="group_area">그룹 소개</label>
		</div>


		<div class="input-field col s12">
			<select id="group_type">
				<option value="" disabled selected>그룹 가입 방법 선택</option>
				<option value="1">전체공개:가입신청 즉시 가입</option>
				<option value="2">부분공개:가입신청 시 관리자의 승인으로 가입</option>
				<option value="3">비밀그룹:초대장으로 가입</option>
			</select>
			<label>그룹 형태를 선택해주세요</label>
		</div>
		<div class="col s12">
			<button class="btn waves-effect waves-light" style="width:100%;" type="submit" name="action">그룹 생성</button>
		</div>
	</form>
</div>
