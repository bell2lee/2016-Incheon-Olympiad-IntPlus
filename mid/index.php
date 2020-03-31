<?php if($_SESSION['logininfo']): ?>
	<a href="/?mid=group_search" class="waves-effect waves-light" style="width:49%; height:50px; line-height:50px; font-size:14pt; text-align:center; background:#66a091; color:#fff; float:left;">단체가입</a>
	<a href="/?mid=create_group" class="waves-effect waves-light" style="width:49%; height:50px; line-height:50px; font-size:14pt; text-align:center; background:#66a091; color:#fff;">단체만들기</a>
	<?php
		//INSERT INTO `groups` (`group_no`, `name`, `group_state`, `date`) VALUES (NULL, '베스트2 그룹', 'normal', '111111');
		// 로그인 되어있는 회원의 그룹 리스트 불러오기
		$gpList_query = $mysqli->query("SELECT * FROM `group_members` WHERE `member_no` = " . $mysqli->real_escape_string($_SESSION["logininfo"]["no"]));
		// $gpList_data["no"] $gpList_data["member_no"] $gpList_data["group_no"] $gpList_data["lavel"]

	?>

	<?php if($gpList_query->num_rows >= 1): // 가입되어있는 그룹이 1개 이상일 경우
			echo "<h4 style='text-align:center;'>" . $gpList_query->num_rows . "개의 그룹에 가입됨</h4>";
			
			$gpArr = 0;
			echo "<div class='row'>";
			while($gpList_data = $gpList_query->fetch_array()){
				$gpInfo_query = $mysqli->query("SELECT * FROM `groups` WHERE `group_no` = " . $mysqli->real_escape_string($gpList_data["group_no"]));
				$gpInfo_data = $gpInfo_query->fetch_array();

				if($gpInfo_data){
					$gpMemberTotal_query = $mysqli->query("SELECT * FROM `group_members` WHERE `group_no` = " . $mysqli->real_escape_string($gpList_data['group_no']));
					echo '<div class="col s12 m6 l6"><div class="card hoverable" onClick="location=\'/?mid=group_timeline&group_num=' . $gpInfo_data['group_no'] . '\'"><div class="card-image"><img src="/tpl/img/default_group_img_1.png"><span class="card-title">' . htmlspecialchars($gpInfo_data["name"]) .  '</span></div><div class="card-content" style="height:100px;"><p>' . htmlspecialchars($gpInfo_data["intro"]) .  '<br>맴버 수 : ' . $gpMemberTotal_query->num_rows . '명</p></div></div></div>';


				}else{
					echo "<div class='mainSolid_box'><img class='responsive-img' src='./tpl/img/best_back.png'><div class='box_title'>오류이거나 없어진 그룹입니다</div></div>";
				}
			}
			echo "</div>";
	?>
	<?php else: ?>
		가입되어있는 그룹이 없습니다
	<?php endif; ?>

<?php else: ?>
	<div class="row">
		<div class="col s12 m4">
			<div class="center promo">
				<i class="material-icons">flash_on</i>
				<p class="promo-caption">빠른 전달</p>
				<p class="light center">인트플러스는 언제 어디서나 인터넷이 되는 환경에서 여러 활동에 참여할 수 있습니다.</p>
			</div>
			</div>

		<div class="col s12 m4">
			<div class="center promo">
				<i class="material-icons">textsms</i>
				<p class="promo-caption">소통, 정보교류</p>
				<p class="light center">인트플러스는 여러 그룹에 가입하고 참여하고 그룹을 만들어 그룹운영을 동시에 가능합니다. 그룹에서는 글, 글에 대한 댓글, 위치공유, 설문조사 등이 가능합니다.</p>
			</div>
		</div>

		<div class="col s12 m4">
			<div class="center promo">
				<i class="material-icons">code</i>
				<p class="promo-caption">기술</p>
				<p class="light center">인트플러스는 인터넷 브라우징이 되는 모든 환경을 지원합니다. 아이폰, 안드로이드, PC 부터 Mac OS까지 지원하며 안드로이드는 전용 애플리케이션을 사용할 수 있습니다.</p>
			</div>
		</div>
	</div>

	<div>
		<p><button class="btn waves-effect waves-light" style="width:100%;" onClick="location='/?mid=join';" type="submit" name="action">지금 가입하기</button></p>
		<p style="text-align:center;"><a href="/?mid=login">기존 사용자 로그인 하기</a></p>
	</div>
<?php endif; ?>