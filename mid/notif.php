<?php
	if($_SESSION['logininfo']){
		if($_GET['group_num']){
			// �׷����� ��������

			$gpInfo_query = $mysqli->query("SELECT * FROM `groups` WHERE `group_no` = " . $mysqli->real_escape_string($_GET['group_num']));
			$gpInfo_data = $gpInfo_query->fetch_array();

			if($gpInfo_data){ // �׷����� ���� ���� ����
				
				$gpMemberInfo_query = $mysqli->query("SELECT * FROM `group_members` WHERE `member_no` = " . $mysqli->real_escape_string($_SESSION['logininfo']['no']) . " AND `group_no` = " . $mysqli->real_escape_string($_GET['group_num']));
				$gpMemberInfo_data = $gpMemberInfo_query->fetch_array();

				if($gpMemberInfo_data){ // Ÿ�� ���� ������ �õ��� ȸ���� �׷쿡 ���ԵǾ��ִ��� ����
				}

			}

		}

	}
	//INSERT INTO `twt_comment` (`no`, `doc_no`, `c_content`, `comm_delete`, `date`, `ip`) VALUES (NULL, '58', '�����ϴ� �κ��̱���', 'N', '1463137113', '127.0.0.1');
?>


<div class="collection">
	<a href="#!" class="collection-item">
		<div>dddddddd</div>
	</a>
	<a href="#!" class="collection-item active">Alvin</a>
	<a href="#!" class="collection-item">Alvin</a>
	<a href="#!" class="collection-item">Alvin</a>
</div>

