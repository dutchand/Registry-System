<?php


require_once(dirname(__FILE__) . "/TControlclass.php");

	
	
	
	
 	
$newvalue;

$errors = array();
$data = "";
$success = "false";

function return_result($success,$errors,$data) {
	echo("<?xml version=\"1.0\" encoding=\"utf-8\"?>");	
	?>
	<results>
	<success><?=$success;?></success>
	<?=$data;?>
	<?=echo_errors($errors);?>
	</results>
	<?
}

function echo_errors($errors) {

	for($i=0;$i<count($errors);$i++) {
		?>
		<error><?=$errors[$i];?></error>
		<?
	}
}
//   $showorg = new TControlclass();
//   $showorg->setfolderlocation;
     
     
     //$pathto="gg";  
       $pathto= $_REQUEST['location'];
switch($_REQUEST['action']) {
    
    case "upload":

    $file_temp = $_FILES['file']['tmp_name'];
    $file_name = $_FILES['file']['name'];

    $file_path = $_SERVER['DOCUMENT_ROOT']."/".$pathto;
    //checks for duplicate files
    if(!file_exists($file_path."/".$file_name)) {

         //complete upload
         $filestatus = move_uploaded_file($file_temp,$file_path."/".$file_name);

         if(!$filestatus) {
         $success = "false";
         array_push($errors,"Upload failed. Please try again.");
         }

    }
    else {
    $success = "false";
    array_push($errors,"File already exists on server.");
    }

    break;

    default:
    $success = "false";
    array_push($errors,"No action was requested.");

}

return_result($success,$errors,$data);






$ret = array(
	"data" => array("error" => "No operation"), 
	"metadata" => array()
);

/**
 * check for the database connection 
 * 
 * 
 */
if ($conn === false) {
	$ret = array(
		"data" => array("error" => "database connection error, please check your settings !"), 
		"metadata" => array()
	);
} else {
	mysql_select_db($database_conn, $conn);
	/**
	 * simple dispatcher. The $_REQUEST["method"] parameter selects the operation to execute. 
	 * must be one of the values findAll, insert, update, delete, Count
	 */
	// execute the necessary function, according to the operation code in the post variables
	switch (@$_REQUEST["method"]) {
		case "FindAll":
			$ret = findAll();
		break;
	
	}
}


$serializer = new XmlSerializer();
echo $serializer->serialize($ret);
die();



?>


