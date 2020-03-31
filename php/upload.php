<?php
$output_dir = "upload_img/";

if($_POST['m'] == 1){
	//$img = $_POST['myfile'];
	//$img = str_replace('data:image/png;base64,', '', $img);
	//data Çü½Ä
	
	$exa = explode('/' , $_POST['data']);

	if (!in_array(strtolower($exa[1]), array('png','jpeg','jpg', 'gif', 'bmp'))){
		exit;
	}

	$data = base64_decode($_POST['basea']);
	$file = $output_dir . uniqid() . '.png';
	$success = file_put_contents($file, $data);

	$md5_name = md5_file($file);
	

	if(!file_exists($output_dir . $md5_name . "." . $exa[1])){//$md5_name
		@copy($file, $output_dir . $md5_name . "." . $exa[1]);
	}
	@unlink($file);
	
	echo $md5_name . "." . $exa[1];

	exit;
}



if(isset($_FILES["myfile"]))
{
	$ret = array();
	
//	This is for custom errors;	
/*	$custom_error= array();
	$custom_error['jquery-upload-file-error']="File already exists";
	echo json_encode($custom_error);
	die();
*/
	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
		$f_info = pathinfo($_FILES["myfile"]["name"]);

		if (!in_array(strtolower($f_info['extension']), array('png','jpeg','jpg', 'gif', 'bmp'))){
			exit;
		}

 	 	$fileName = md5_file($_FILES["myfile"]["tmp_name"]) . "." . $f_info['extension'];
 		move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
		
    	$ret[]= $fileName;

	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["myfile"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
		  
		if (!in_array(strtolower($f_info['extension']), array('png','jpeg','jpg', 'gif', 'bmp'))) {
			continue;
		}
	
	  	$fileName = md5_file($_FILES["myfile"]["tmp_name"]) . "." . $f_info['extension'];
	  	
		move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
	  	$ret[]= $fileName;
	  }
	
	}
    echo json_encode($ret);
 }
 ?>