<?php
	class member{

		/*function member($mysqli){

			// ������
			$this->mysql = $mysqli;
			// print_r($mysqli) �� ���� print_r($tihs->mysql) �� �� X

			//print_r($tihs->mysql);exit;
		}*/

		function search_member_to_no($no){
			
			global $mysqli;
			$query = $mysqli->query("SELECT * FROM `member` WHERE `no` = " . $mysqli->real_escape_string($no));

			$data = $query->fetch_array();

			return $data;
		}

	}


	
?>