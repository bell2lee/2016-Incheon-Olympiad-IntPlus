<?php
	header("Content-type: text/html; charset=UTF-8");
	ini_set("session.cache_expire", 3600);
	ini_set("session.gc_maxlifetime", 10800); // 세션 만료시간을 한시간으로 설정

	if(preg_match('/(?i)msie [5-8]/', $_SERVER['HTTP_USER_AGENT'])){
		// if IE<=8
		require("./mid/__king_ie.php");
		exit();
	}

	require("./config/db.config.php");
	require("./config/security.config.php");
	require("./tpl/skin.php");

?>

