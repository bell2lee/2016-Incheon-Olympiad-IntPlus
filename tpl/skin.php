<?php
	if(isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true'){
		require("./mid/" . $_GET['mid']) . ".php";
		exit();
	}else if($_GET['p-type'] == "one"){
		require("./mid/" . $_GET['mid']) . ".php";
		exit();
	}
	
	preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
	if(count($matches)<2){
		preg_match('/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
	}
	if(count($matches)>1){ $version = $matches[1];//$matches변수값이 있으면 IE브라우저
		if($version<=8){

		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<title>Int Plus</title>
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="./tpl/js/materialize.min.js"></script>
	<script src="./tpl/js/pc_script.js"></script>
	<script src="./tpl/js/jquery.uploadfile.min.js"></script>
	
	<!--CSS-->
	<link rel="stylesheet" href="http://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="./tpl/css/materialize.min.css"  media="screen,projection">
	<link rel="stylesheet" href="./tpl/css/fontium.css">
	<link rel="stylesheet" href="./tpl/css/uploadfile.css">
	<link rel="stylesheet" href="./tpl/css/pc_style.css">
	
</head>
<body>
<?php if(!$_SESSION['logininfo']): ?>

<?php endif; ?>

<div class="progress header_ajaxLoading"><div class="indeterminate"></div></div>
<header id="header" class="">

	<nav class="header_color">
		<div class="container">
			<div class="nav-wrapper">
				
				<?php if(!$_SESSION['logininfo']): ?>
					<a href="/?mid=login" class="right"><i class="fonti um-user um-2x" style="margin:12px 0;"></i></a>
				<?php else: ?>
					<a href="#" data-activates="slide-out" class="button-collapse"><i class="fonti um-navicon" style="color:#fff; font-size:30px;"></i></a>
					<a href="/?mid=logout" class="right">로그아웃</a>
				<?php endif; ?>
			
				<a href="/" class="brand-logo">Int+</a>
				<?php if($_SESSION['logininfo']): ?>
				<ul id="slide-out" class="side-nav">
					<li><a href="/">메인 페이지</a></li>
					<li class="no-padding">
						<ul class="collapsible collapsible-accordion">
							<li>
								<a class="collapsible-header">가입된 그룹 목록 보기<i class="mdi-navigation-arrow-drop-down"></i></a>
								<div class="collapsible-body">
									<ul>
										<?php
											
											$gpList_query = $mysqli->query("SELECT * FROM `group_members` WHERE `member_no` = " . $mysqli->real_escape_string($_SESSION["logininfo"]["no"]));
											
											while($gpList_data = $gpList_query->fetch_array()){
												$gpInfo_query = $mysqli->query("SELECT * FROM `groups` WHERE `group_no` = " . $mysqli->real_escape_string($gpList_data["group_no"]));
												$gpInfo_data = $gpInfo_query->fetch_array();
												
												$gpMemberTotal_query = $mysqli->query("SELECT * FROM `group_members` WHERE `group_no` = " . $mysqli->real_escape_string($gpList_data['group_no']));
												echo '<li><a href="/?mid=group_timeline&group_num=' . $gpInfo_data['group_no'] . '">' . htmlspecialchars($gpInfo_data["name"]) . '</a></li>';
											}
										?>
									</ul>
								</div>
							</li>
						</ul>
					</li>
					<li><a href="#!">로그아웃</a></li>
				</ul>
				<?php endif; ?>
			</div>
		</div>
	</nav>
</header>

<div id="center_content" class="container">
	<div class="center_co_pad">
		<?php require("./mid/" . $_GET['mid']) . ".php"; ?>
	</div>
</div>
<div class="preloader-wrapper small active">
    <div class="spinner-layer spinner-green-only">
      <div class="circle-clipper left">
        <div class="circle"></div>
      </div><div class="gap-patch">
        <div class="circle"></div>
      </div><div class="circle-clipper right">
        <div class="circle"></div>
      </div>
    </div>
  </div>
</body>
</html>