<?php if($_SESSION['logininfo']):
	if($_GET['type'] == "email"){
		function GenerateString($length)  
	    {  
	        $characters  = "0123456789";  
	        $characters .= "abcdefghijklmnopqrstuvwxyz";  
	        $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";  
	          
	        $string_generated = "";  
	          
	        $nmr_loops = $length;  
	        while ($nmr_loops--)  
	        {  
	            $string_generated .= $characters[mt_rand(0, strlen($characters))];  
	        }  
	          
	        return $string_generated;  
	    } 
	    
	    
		$member_search = $mysqli->query("SELECT * FROM `member` WHERE `username` LIKE '" . $mysqli->real_escape_string($_SESSION['logininfo']['id']) . "' ORDER BY `email_cert` ASC");
		$member_data = $member_search->fetch_array();
		
		if($member_data["email_cert"] != 1){
			$email_time = $mysqli->query("SELECT * FROM `email_key` WHERE `id` LIKE '" . $mysqli->real_escape_string($_SESSION['logininfo']['id']) . "' ORDER BY `email_key`.`no` DESC");
			$email_time_data = $email_time->fetch_array();
			
			if(($email_time_data['date'] + 60) > time()){
				$data = array('state'=>'error2');
				echo json_encode($data);
				exit();
			}
			
			
			
			$tmp_str = GenerateString(20);
			$mysqli->query("INSERT INTO `email_key` (`no`, `id`, `e_key`, `date`, `ip`) VALUES (NULL, '" . $mysqli->real_escape_string($_SESSION['logininfo']['id']) . "', '" . $tmp_str . "', '" . time() . "', '" . $_SERVER['REMOTE_ADDR'] . "');");
			
			$headers = 'From: webmaster@soundplace.net' . "\r\n" .
		    'Reply-To: webmaster@soundplace.net' . "\r\n" .
		    'Content-type: text/html; charset=utf-8';

			mail(urldecode($_SESSION['logininfo']['email']), "사운드플레이스 이메일 인증입니다.", "안녕하세요 사운드플레이스입니다.<br>하단 주소를 클릭해주시면 이메일 인증이 완료됩니다!<br><a href='http://soundplace.net/?mid=email_key&id=" . $_SESSION['logininfo']['id'] . "&key=" . $tmp_str . "&p-type=one'>인증하기</a>", $headers);
			$data = array('state'=>'success');
			echo json_encode($data);
		}else{
			$data = array('state'=>'error1');
			echo json_encode($data);
		}
		exit();
	}elseif($_GET['type'] == "chart_make"){
		$member_search = $mysqli->query("SELECT * FROM `member` WHERE `username` LIKE '" . $mysqli->real_escape_string($_SESSION['logininfo']['id']) . "' ORDER BY `email_cert` ASC");
		$member_data = $member_search->fetch_array();
		if($member_data["email_cert"] != 1){
			//미 인증 회원			
			$result = $mysqli->query("SELECT COUNT(*)  FROM `user_cate` WHERE `owner` = " . $_SESSION['logininfo']['no'] . " ORDER BY `no`  DESC");
			
			$co = $result->fetch_array();
			

			if($co['COUNT(*)'] >= 5){
				$data = array('state'=>'error1');
				echo json_encode($data);
				exit();
			}
		}
		
		if($_GET['chart_name'] && $_GET['dj_open']){
			$mysqli->query("INSERT INTO `user_cate` (`no`, `name`, `ex`, `rel`, `owner`, `recommend`, `view`, `open`) VALUES (NULL, '" . $mysqli->real_escape_string(htmlspecialchars($_GET['chart_name'])) . "', '" . $mysqli->real_escape_string(htmlspecialchars($_GET['chart_name'] . "입니다.")) . "', '1', '" . $_SESSION['logininfo']['no'] . "', '0', '0', '" . $mysqli->real_escape_string(htmlspecialchars($_GET['dj_open'])) . "')");
			$data = array('state'=>'success');
			echo json_encode($data);
		}
		
		exit();

	}elseif($_GET['type'] == "chart_del" && $_GET['cate_no']){
		if($_SESSION['logininfo']){
			$check_mine = $mysqli->query("SELECT * FROM `user_cate` WHERE `no` = " . $mysqli->real_escape_string($_GET['cate_no']) . " AND `owner` = " . $_SESSION['logininfo']['no']);
			$check_mine_data = $check_mine->fetch_array();
			if($check_mine_data){
				
				$mysqli->query("DELETE FROM `user_cate` WHERE `user_cate`.`no` = " . $mysqli->real_escape_string($_GET['cate_no']));
				$data = array('state'=>'success');
				echo json_encode($data);
				
				exit();
			}
		}
	}
?>
<div class="page_intro">
	<div class="page_icon"></div>
	<div class="page_intro_title">마이페이지</div>
	<div>마이페이지에서는 개인정보 관리, 내 리스트 수정이 가능합니다.</div>
</div>

<div class="page_line">개인정보</div>
<?php
	
	$member_search = $mysqli->query("SELECT * FROM `member` WHERE `username` LIKE '" . $mysqli->real_escape_string($_SESSION['logininfo']['id']) . "' ORDER BY `email_cert` ASC");
	$member_data = $member_search->fetch_array();
	if($member_data["email_cert"] != 1){
		echo "<div class='div_alert'>회원님은 이메일 인증 회원이 아닙니다. 이메일 인증 시 내 리스트 카테고리와 카테고리당 곡 수를 무제한으로 확장하실 수 있습니다!<a href='javascript:email_send();' class='email_send_btn btn'>인증하기</a></div>";
	}
?>
<p>아이디 : <?=urldecode($_SESSION['logininfo']['id'])?></p>
<p>이메일 : <?=urldecode($_SESSION['logininfo']['email'])?></p>
<p>비밀번호 변경 : 준비중</p>
<div class="page_line">내 리스트</div>
<div style="font-size:13pt; padding:10px;">새로운 리스트 추가하기</div>
<div style="padding:10px;">
	<input type="text" class="list_text_input" placeholder="리스트 이름"> <label style="margin-right:30px;"><input type="checkbox" checked="checked" class="user_dj_open"> 유저 DJ 공개</label> <a class="btn" href="javascript:chart_make();$.pjax.reload('.content');" style="padding:15px; margin-right:30px;">만들기</a>
	<select name="speed" class="mypage_cate" style="width:250px;">
		<option selected="selected">내 리스트 목록</option>
		<?php
			$mylist_search = $mysqli->query("SELECT * FROM `user_cate` WHERE `owner` = " . $mysqli->real_escape_string($member_data["no"]));
			
			while($mylistdata = $mylist_search->fetch_array()){
				echo "<option value='" . $mylistdata['no'] . "'>" . htmlspecialchars(urldecode($mylistdata["name"])) . "</option>";
			}
		?>
	</select>
	<a class="btn" style="padding:15px;" href="javascript:select_adm();">바로가기</a>
	<a class="btn" style="padding:15px;" href="javascript:select_adm('del');">삭제</a>
	
</div>
<?php else: ?>
로그인 후 이용가능한 서비스입니다.
<?php endif; ?>