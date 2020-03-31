<?php
	/* 검색페이지 */
	if($_GET['p-type'] == "one"){
		
		// type 검증

		if($_SESSION['logininfo']){
			// 만약 타입이 있을경우
			// 이곳은 쿼리 페이지입니다.
			if($_POST['group_search_area']){
				// 노멀상태의 그룹 검색 후 출력
				$gp_search_query = $mysqli->query("SELECT * FROM `groups` WHERE `name` LIKE '%" . $mysqli->real_escape_string($_POST['group_search_area']) . "%' AND `group_state` LIKE 'normal'");
				if($gp_search_query->num_rows >= 1){ // 검색결과가 존재할경우
					$i = 0;
					while($gp_search_data = $gp_search_query->fetch_array()){
						$gp_no[$i] = $gp_search_data['group_no'];
						$gp_name[$i] = $gp_search_data['name'];
						
						// 멤버 수 구하기
						$mem_num_query = $mysqli->query("SELECT * FROM `group_members` WHERE `group_no` = " . $gp_search_data['group_no']);
						
						$gp_area[$i] = $gp_search_data['intro'];

						$gp_members[$i] = $mem_num_query->num_rows;
						
						$i++;
					}
					
					echo json_encode(array('state'=>'success', 'group_num'=>$gp_no, 'group_name'=>$gp_name, 'group_members'=>$gp_members, 'group_area'=>$gp_area));
				
				}else{ echo json_encode(array('state'=>'error3')); } // 검색결과가 없습니다!
				
			}else{ echo json_encode(array('state'=>'error2')); } // 검색어 쿼리값이 날라오지 않거나 NULL 값임

		}else { echo json_encode(array('state'=>'error1')); } // 잘못된 접근이거나 만료된 세션
		//echo $_POST['group_search_area'];
		exit();
	}
	
	

	//검색 query 접속방법 http://int.kr3.kr/?mid=group_timeline&group_num=2&p-type=one
	
	// 이미지활용 https://unsplash.com/ https://unsplash.com/photos/c8TWWQ5ZnUw
?>
<script>
$(document).ready(function(){
	$(".group_search_form").submit(function(){
		
		if(!$('#group_search_area').val()){
			alert('검색어를 입력하세요'); return false;
		}else{
			// 시작
			$(".header_ajaxLoading").fadeIn(200);
			
			$.post("/?mid=group_search&p-type=one", {'group_search_area': $('#group_search_area').val()}, function(obj){
		        switch(obj.state){
			        case "error1":
			        	alert("잘못된 접근이거나 만료된 세션입니다");
			        	break;
			        case "error2":
			        	alert("검색어를 입력하세요(E3)");
			        	break;
			        case "error3":
			        	alert("검색결과가 없습니다");
			        	break;
			        case "success":
			        	i = 0;
						$(".group_search_result").html('</div>' + $(".group_search_result").html());
						$(".group_search_result").html("");
			        	while(obj.group_num[i]){
				        	
				        	$(".group_search_result").append('<div class="col s12 m6 l6"><div class="card hoverable" onClick="location=\'/?mid=group_timeline&group_num=' + obj.group_num[i] + '\'"><div class="card-image"><img src="/tpl/img/default_group_img_1.png"><span class="card-title">' + obj.group_name[i] + '</span></div><div class="card-content"><p>' + obj.group_area[i] + '<br>맴버 수 : ' + obj.group_members[i] + '명</p></div></div></div>');

							i++;
				        	
			        	}
						$(".group_search_result").html('<div class="row">' + $(".group_search_result").html());
						
						break;
		        }
				$(".header_ajaxLoading").fadeOut(100);
		    }, "json");
			return false;
		}
	});
});

</script>

<h4>공개그룹만 검색됩니다</h4>


<div class="row">
	<form class="group_search_form col s12" action="/?mid=group_search&p-type=one" method="post">
		<div class="row">
			<div class="input-field col s8">
				<input id="group_search_area" type="text" name="group_search_area" placeholder="(검색예: 웹개발자 모임)">
				<label for="group_search_area">그룹명</label>
			</div>

			<div class="input-field col s4">
				<button class="btn waves-effect waves-light" style="width:100%;" type="submit" name="action">검색</button>
			</div>
		</div>
	</form>
</div>

<div class="group_search_result"></div>
