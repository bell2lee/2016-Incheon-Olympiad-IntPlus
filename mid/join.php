<script>
function eng(obj) {
	var pattern = /[^(a-zA-Z0-9)]/; //영문만 허용
	if (pattern.test(obj.value)) {
		obj.value = '';
		obj.focus();
		return false;
	}
}
$(document).ready(function(){
	$(".join_btn").click(function(){
		if(!$('.id_filed').val() || !$('.pw_filed').val() || !$('.email_filed').val() || !$('.namez_filed').val()){
			alert('모든 정보를 입력해주세요');
			return false;
		}else{
			if($(".pw_filed").val() == $(".pwre_filed").val()){
				$.post("/?mid=join_query&p-type=one", {id: $('.id_filed').val(), pw:$('.pw_filed').val(), email:$('.email_filed').val(), namez:$('.namez_filed').val()}, function(result){
					var obj = JSON.parse(result);
					switch(obj.state){
						case "error1":
							alert("이미 존재하는 아이디 입니다.");
							break;
						case "error2":
							alert("이미 로그인 되어있습니다.");
							break;
						case "error3":
							alert("올바른 이메일 형식을 입력하세요");
							break;
						case "error4":
							alert("이 이메일 주소로 가입한 계정이 이미 있습니다");
							break;
						case "shiting_error":
							alert("모든 칸을 채워주세요");
							break;
						case "success":
							alert("Int+에 가입하신것을 환영합니다!\n이제 여러 그룹에 가입하고 정보를 공유하세요!");
							location='/';
							break;
					}
				});
				return false;
			}else{ alert('비밀번호와 비밀번호 확인이 일치하지 않습니다'); return false; }
		}
	});
	
	$('.id_filed').css("ime-mode", "disabled");
});
</script>
<div class="_clear"></div>


<div class="row">
	<div class="input-field col s12">
		<input type="text" pattern="[A-Za-z0-9]*" placeholder="사용하실 계정 아이디를 입력해주세요" onkeyup="eng(this)" class="validate id_filed">
		<label for="id">아이디 (ID)</label>
	</div>

	<div class="input-field col s12">
		<input type="password" placeholder="패스워드는 문자+숫자(선택;특수문자)조합으로 사용해주세요" class="validate pw_filed">
		<label for="id">비밀번호 (Password)</label>
	</div>

	<div class="input-field col s12">
		<input type="password" placeholder="패스워드를 한번 더 입력하세요" class="validate pwre_filed">
		<label for="id">비밀번호 확인 (Confirm Password)</label>
	</div>

	<div class="input-field col s12">
		<input type="text" placeholder="성+이름 조합으로 실명을 입력해주세요 (EX:홍길동)" class="validate namez_filed">
		<label for="id">이름 (Last name + First name)</label>
	</div>

	<div class="input-field col s12">
		<input type="email" placeholder="사용하시는 이메일 계정주소를 입력해주세요" class="validate email_filed">
		<label for="id">이메일 주소 (E-Mail)</label>
	</div>

	<div class="input-field col s12">
		<button style="width:100%;" class="waves-effect waves-light btn join_btn">회원가입</button>
	</div>
</div>