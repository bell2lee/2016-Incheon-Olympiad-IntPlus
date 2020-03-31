<?php
	if($_SESSION['logininfo']){
		header("Location:/");
	}

?>
<script>
$(document).ready(function(){
	$(".login_realForm").submit(function(){
		if(!$('.id_form').val() || !$('.pw_form').val()){
			alert('모든 정보를 입력해주세요');
			return false;
		}else{
			$.post("/?mid=login_query&p-type=one", {id: $('.id_form').val(), pw:$('.pw_form').val()}, function(result){
		        var obj = JSON.parse(result);
		        switch(obj.state){
			        case "error1":
			        	alert("존재하지 않는 아이디거나 패스워드가 일치하지 않습니다.");
			        	break;
			        case "error2":
			        	alert("이미 로그인 되어있습니다.");
			        	break;
			        case "success":
						var referrer =  document.referrer;
						
						if(referrer != "http://int.kr3.kr/?mid=join"){
							location = referrer;
						}
			        	break;
		        }
		    });
			return false;
		}
	});
});
</script>
<div class="login_form">
	<form class="login_realForm col s12" action="/?mid=login_query" method="post">
		<div class="row">
			<div class="input-field col s12">
				<input type="text" name="id" class="id_form" class="validate">
				<label for="id">아이디</label>
			</div>

			<div class="input-field col s12">
				<input type="password" name="pw" class="pw_form" class="validate">
				<label for="pw">패스워드</label>
			</div>
		</div>
		<button class="btn waves-effect waves-light" style="width:100%;" type="submit" name="action">로그인</button>
		<div class="_clear"></div>
		<p style="text-align:center;">
			<a href="javascript:alert_open('error', '준비중인 서비스입니다');" style="margin-right:30px;">아이디/비밀번호 찾기</a><a href="/?mid=join" data-pjax='content'>회원가입</a>
		</p>
	</form>
	<div class="_clear"></div>
</div>