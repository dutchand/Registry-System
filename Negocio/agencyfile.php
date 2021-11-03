<?php
require_once(dirname(__FILE__) . "/Registryconn.php");
require_once(dirname(__FILE__) . "/functions.inc.php");
require_once(dirname(__FILE__) . "/XmlSerializer.class.php");
require_once(dirname(__FILE__) . "/TControlclass.php");






/**
 * This is the main PHP file that process the HTTP parameters, 
 * performs the basic db operations (FIND, INSERT, UPDATE, DELETE) 
 * and then serialize the response in an XML format.
 * 
 * XmlSerializer uses a PEAR xml parser to generate an xml response. 
 * this takes a php array and generates an xml according to the following rules:
 * - the root tag name is called "response"
 * - if the current value is a hash, generate a tagname with the key value, recurse inside
 * - if the current value is an array, generated tags with the default value "row"
 * for example, we have the following array: 
 * 
 * $arr = array(
 * 	"data" => array(
 * 		array("id_pol" => 1, "name_pol" => "name 1"), 
 * 		array("id_pol" => 2, "name_pol" => "name 2") 
 * 	), 
 * 	"metadata" => array(
 * 		"pageNum" => 1, 
 * 		"totalRows" => 345
 * 	)
 * 	
 * )
 * 
 * we will get an xml of the following form
 * 
 * <?xml version="1.0" encoding="ISO-8859-1"?>
 * <response>
 *   <data>
 *     <row>
 *       <id_pol>1</id_pol>
 *       <name_pol>name 1</name_pol>
 *     </row>
 *     <row>
 *       <id_pol>2</id_pol>
 *       <name_pol>name 2</name_pol>
 *     </row>
 *   </data>
 *   <metadata>
 *     <totalRows>345</totalRows>
 *     <pageNum>1</pageNum>
 *   </metadata>
 * </response>
 *
 * Please notice that the generated server side code does not have any 
 * specific authentication mechanism in place.
 */
 
 

/**
 * The filter field. This is the only field that we will do filtering after.
 */
$filter_field = "id_file";

/**
 * we need to escape the value, so we need to know what it is
 * possible values: text, long, int, double, date, defined
 */
$filter_type = "text";

/**
 * constructs and executes a sql select query against the selected database
 * can take the following parameters:
 * $_REQUEST["orderField"] - the field by which we do the ordering. MUST appear inside $fields. 
 * $_REQUEST["orderValue"] - ASC or DESC. If neither, the default value is ASC
 * $_REQUEST["filter"] - the filter value
 * $_REQUEST["pageNum"] - the page index
 * $_REQUEST["pageSize"] - the page size (number of rows to return)
 * if neither pageNum and pageSize appear, we do a full select, no limit
 * returns : an array of the form
 * array (
 * 		data => array(
 * 			array('field1' => "value1", "field2" => "value2")
 * 			...
 * 		), 
 * 		metadata => array(
 * 			"pageNum" => page_index, 
 * 			"totalRows" => number_of_rows
 * 		)
 * ) 
 */
function findAll() {
	global $conn, $filter_field, $filter_type;

	/**
	 * the list of fields in the table. We need this to check that the sent value for the ordering is indeed correct.
	 */
	$fields = array('id_file','volume','title','subtitle','status');

	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . $filter_field . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], $filter_type);	
	}

	$order = "";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) {
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}
	
	//calculate the number of rows in this table
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `tb_agencyfile` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	
	//get the page number, and the page size
	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];
	
	//calculate the start row for the limit clause
	$start = $pageNum * $pageSize;

	//construct the query, using the where and order condition
	$query_recordset = "SELECT id_file,volume,title,subtitle,status FROM `tb_agencyfile` $where $order";
	
	//if we use pagination, add the limit clause
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}

	$recordset = mysql_query($query_recordset, $conn);
	
	//if we have rows in the table, loop through them and fill the array
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	
	//create the standard response structure
	$toret = array(
		"data" => $toret, 
		"metadata" => array (
			"totalRows" => $totalrows,
			"pageNum" => $pageNum
		)
	);

	return $toret;
}

/**
 * constructs and executes a sql count query against the selected database
 * can take the following parameters:
 * $_REQUEST["filter"] - the filter value
 * returns : an array of the form
 * array (
 * 		data => number_of_rows, 
 * 		metadata => array()
 * ) 
 */
 
 
 function maincategory()
{
	
	
	$showorg = new TControlclass();
	$toret = $showorg->maincategory();
	return $toret;
	

 	
 }
 
  function mainfilesubcategory()
{
	
	$variable = $_REQUEST["mfc"];
	$showorg = new TControlclass();
	$toret = $showorg->mainfilesubcategory($variable);
	return $toret;
	
 	
 }
 
 
  function mainfilesubcategoryshort()
{
   $variable = $_REQUEST["mfc2"];	
	$showorg = new TControlclass();
	$toret = $showorg->mainfilesubcategoryshort($variable);
	return $toret;
	
 	
 }
 
 
   function ministries()
{
	
	
	$showorg = new TControlclass();
	$toret = $showorg->ministries();
	return $toret;
	
 	
 }
 
 
   function subagency()
{
	$variable = $_REQUEST['ministry'];
	
	$showorg = new TControlclass();
	$toret = $showorg->subagency($variable);
	return $toret;
	
 	
 }
 
 
   function ministrynsubs2()
{
	
	
	$showorg = new TControlclass();
	$toret = $showorg->ministrynsubs2();
	return $toret;
	
 }
 
    function ministrynsubs()
{
	
	
	$showorg = new TControlclass();
	$toret = $showorg->ministrynsubs();
	return $toret;
	
 }
 
     function straigthminfile()
{
	
	
	$showorg = new TControlclass();
	$toret = $showorg->straigthminfile();
	return $toret;
	
 }


    function straigthminfile1()
{
	
	
	$showorg = new TControlclass();
	$toret = $showorg->straigthminfile1();
	return $toret;
	
 }
 
     function straigthminfile2()
{
	
	
	$showorg = new TControlclass();
	$toret = $showorg->straigthminfile2();
	return $toret;
	
 }
 
   function straigthminfile4()
{
	
	
	$showorg = new TControlclass();
	$toret = $showorg->straigthminfile4();
	return $toret;
	
 }
 
    function straigthminfile5()
{
	
	
	$showorg = new TControlclass();
	$toret = $showorg->straigthminfile5();
	return $toret;
	
 }
 
 
 function setlocation()
 {
 	$variable = $_REQUEST['folder'];
 	$showorg = new TControlclass();
	$showorg->setfolderlocation($variable);
 }
  
function rowCount() {
	global $conn, $filter_field, $filter_type;

	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . $filter_field . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], $filter_type);	
	}

	//calculate the number of rows in this table
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `tb_agencyfile` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	
	//create the standard response structure
	$toret = array(
		"data" => $totalrows, 
		"metadata" => array()
	);

	return $toret;
}

/**
 * constructs and executes a sql insert query against the selected database
 * can take the following parameters:
 * $_REQUEST["field_name"] - the list of fields which appear here will be used as values for insert. 
 * If a field does not appear, null will be used.  
 * returns : an array of the form
 * array (
 * 		data => array(
 * 			"primary key" => primary_key_value, 
 * 			"field1" => "value1"
 * 			...
 * 		), 
 * 		metadata => array()
 * ) 
 */
 
 function insertfiledata() {
	global $conn;
   
	//build and execute the insert query//
	$query_insert = sprintf("INSERT INTO `tb_filedata` (id_minfile,refno,fromwhere,subjecttitle,dateadded,filefotoname) VALUES (%s,%s,%s,%s,%s,%s)" ,			
	        GetSQLValueString($_REQUEST["minfile"], "text"), # 
			GetSQLValueString($_REQUEST["refno"], "text"), # 
			GetSQLValueString($_REQUEST["from"], "text"), # 
			GetSQLValueString($_REQUEST["subject"], "text"), # 
			GetSQLValueString($_REQUEST["dateadded"], "text"), # 
			GetSQLValueString($_REQUEST["filename"], "text")# 
	);
	$ok = mysql_query($query_insert);
	
	
	if ($ok) {
		// return the new entry, using the insert id
		$toret = array(
			"data" => array(
				array(
									)
			), 
			"metadata" => array()
		);
	} 
	else {
		// we had an error, return it
		$toret = array(
			"data" => array("File Already Exists"), 
			"metadata" => array()
			// => mysql_error()
		);
	}	
	
	return $toret;
}
 
 
 
function insert() {
	global $conn;
   $status = "Active";
	//build and execute the insert query
	
		
	$query_insert = sprintf("INSERT INTO `tb_file` (id_file,id_subcategory,id_subsubcategory) VALUES (%s,%s,%s)" ,			
	        GetSQLValueString($_REQUEST["adcode"], "text"), # 
			GetSQLValueString($_REQUEST["title"], "text"), # 
			GetSQLValueString($_REQUEST["titleagain"], "text") #
	);
	$ok = mysql_query($query_insert);
	
	
	$query_insert2 = sprintf("INSERT INTO `tb_minfile` (id_minfile,id_file,volume,id_govagency,id_subgovagency,dateopen,path,status) VALUES (%s,%s,%s,%s,%s,%s,%s,'$status')" ,			
	        GetSQLValueString($_REQUEST["id_file"], "text"), #
	        GetSQLValueString($_REQUEST["adcode"], "text"), # 
			GetSQLValueString($_REQUEST["volume"], "text"), # 
			GetSQLValueString($_REQUEST["subtitle"], "text"), # 
			GetSQLValueString($_REQUEST["sustitleagain"], "text"), # 
			GetSQLValueString($_REQUEST["dateopen"], "text"), #
		    GetSQLValueString($_REQUEST["path"], "text") # 
			 
	);
	$ok *= mysql_query($query_insert2);
	
	
//	else
//	$query_insert2 = sprintf("INSERT INTO `tb_minfile` (id_minfile,id_file,volume,id_govagency,id_subgovagency,dateopen,path,status) VALUES (%s,%s,%s,%s,%s,%s,%s,'$status')" ,			
//	        GetSQLValueString($_REQUEST["id_file"], "text"), #
//	        GetSQLValueString($_REQUEST["adcode"], "text"), # 
//			GetSQLValueString($_REQUEST["volume"], "text"), # 
//			GetSQLValueString($_REQUEST["subtitle"], "text"), # 
//			GetSQLValueString($_REQUEST["sustitleagain"], "text"), # 
//			GetSQLValueString($_REQUEST["dateopen"], "text"), #
//			GetSQLValueString($_REQUEST["path"], "text") #  
//	);
//	$ok = mysql_query($query_insert2);
	   	
	if ($ok) {
		// return the new entry, using the insert id
		$toret = array(
			"data" => array(
				array(
					"id_file" => $_REQUEST["id_file"], 
					"volume" => $_REQUEST["volume"], # 
					"title" => $_REQUEST["title"], # 
					"subtitle" => $_REQUEST["subtitle"], # 
					"status" => $_REQUEST["status"] # 
				)
			), 
			"metadata" => array()
		);
	} 
	else {
		// we had an error, return it
		$toret = array(
			"data" => array("File Already Exists"), 
			"metadata" => array()
			// => mysql_error()
		);
	}	
	
	
// 
//$dir = $_SERVER['DOCUMENT_ROOT']."/registrystorage-debug/PublicService/".$_REQUEST["id_minfile"];
//mkdir("$dir"); 




	
	return $toret;
}



	function Show_Files()
		{
			global $conn;
			$business=$_REQUEST["foldername"];
			$query_select = "SELECT tbfd.`id_tbfiledata`, tbfd.`id_minfile`, tbfd.`refno`, tbfd.`fromwhere`, tbfd.`subjecttitle`, tbfd.`dateadded`, tbfd.`filefotoname`
                             FROM `tb_filedata` tbfd, `tb_minfile` tbm
                             WHERE tbfd.`id_minfile` = '$business' AND tbm.`id_minfile` = tbfd.`id_minfile`";
			$query_recordset = mysql_query($query_select,$conn);
			$toret = array();	
			while ($row_recordset = mysql_fetch_assoc($query_recordset)) {
				array_push($toret, $row_recordset);
			}	
			//create the standard response structure
			$toret = array( "data" => $toret,
							"metadata" => array() );
			return $toret;		
		}
		
function Show_Filesbydate()
		{
			global $conn;
			$business=$_REQUEST["foldername"];
			$showdate = $_REQUEST["date"];
			$query_select = "SELECT tbfd.`id_tbfiledata`, tbfd.`id_minfile`, tbfd.`refno`, tbfd.`fromwhere`, tbfd.`subjecttitle`, tbfd.`dateadded`, tbfd.`filefotoname`
                             FROM `tb_filedata` tbfd, `tb_minfile` tbm
                             WHERE tbfd.`id_minfile` = '$business' AND tbm.`id_minfile` = tbfd.`id_minfile` AND tbfd.`dateadded` = '$showdate'";
			$query_recordset = mysql_query($query_select,$conn);
			$toret = array();	
			while ($row_recordset = mysql_fetch_assoc($query_recordset)) {
				array_push($toret, $row_recordset);
			}	
			//create the standard response structure
			$toret = array( "data" => $toret,
							"metadata" => array() );
			return $toret;		
		}
		
		
		
		function Show_Filesbyrefno()
		{
			global $conn;
			$business=$_REQUEST["foldername"];
			$showref= $_REQUEST["ref"];
			$query_select = "SELECT tbfd.`id_tbfiledata`, tbfd.`id_minfile`, tbfd.`refno`, tbfd.`fromwhere`, tbfd.`subjecttitle`, tbfd.`dateadded`, tbfd.`filefotoname`
FROM `tb_filedata` tbfd, `tb_minfile` tbm
WHERE tbfd.`id_minfile` = '$business' AND tbm.`id_minfile` = tbfd.`id_minfile` AND tbfd.`refno` = '$showref'";
			$query_recordset = mysql_query($query_select,$conn);
			$toret = array();	
			while ($row_recordset = mysql_fetch_assoc($query_recordset)) {
				array_push($toret, $row_recordset);
			}	
			//create the standard response structure
			$toret = array( "data" => $toret,
							"metadata" => array() );
			return $toret;		
		}
		

/**
 * constructs and executes a sql update query against the selected database
 * can take the following parameters:
 * $_REQUEST[primary_key] - thethe value of the primary key
 * $_REQUEST[field_name] - the list of fields which appear here will be used as values for update. 
 * If a field does not appear, null will be used.  
 * returns : an array of the form
 * array (
 * 		data => array(
 * 			"primary key" => primary_key_value, 
 * 			"field1" => "value1"
 * 			...
 * 		), 
 * 		metadata => array()
 * ) 
 */
function update() {
	global $conn;

	// check to see if the record actually exists in the database
	$query_recordset = sprintf("SELECT * FROM `tb_agencyfile` WHERE id_file = %s",
		GetSQLValueString($_REQUEST["id_file"], "text")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);
	
	if ($num_rows > 0) {

		// build and execute the update query
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_update = sprintf("UPDATE `tb_agencyfile` SET volume = %s,title = %s,subtitle = %s,status = %s,id_govagency = %s WHERE id_file = %s", 
			GetSQLValueString($_REQUEST["volume"], "text"), 
			GetSQLValueString($_REQUEST["title"], "text"), 
			GetSQLValueString($_REQUEST["subtitle"], "text"), 
			GetSQLValueString($_REQUEST["status"], "text"), 
			GetSQLValueString($_REQUEST["id_govagency"], "text"), 
			GetSQLValueString($row_recordset["id_file"], "text")
		);
		$ok = mysql_query($query_update);
		if ($ok) {
			// return the updated entry
			$toret = array(
				"data" => array(
					array(
						"id_file" => $row_recordset["id_file"], 
						"volume" => $_REQUEST["volume"], #
						"title" => $_REQUEST["title"], #
						"subtitle" => $_REQUEST["subtitle"], #
						"status" => $_REQUEST["status"], #
						"id_govagency" => $_REQUEST["id_govagency"]#
					)
				), 
				"metadata" => array()
			);
		} else {
			// an update error, return it
			$toret = array(
				"data" => array("error" => mysql_error()), 
				"metadata" => array()
			);
		}
	} else {
		$toret = array(
			"data" => array("error" => "No row found"), 
			"metadata" => array()
		);
	}
	return $toret;
}

/**
 * constructs and executes a sql update query against the selected database
 * can take the following parameters:
 * $_REQUEST[primary_key] - thethe value of the primary key
 * returns : an array of the form
 * array (
 * 		data => deleted_row_primary_key_value, 
 * 		metadata => array()
 * ) 
 */
function delete() {
	global $conn;

	// check to see if the record actually exists in the database
	$query_recordset = sprintf("SELECT * FROM `tb_agencyfile` WHERE id_file = %s",
		GetSQLValueString($_REQUEST["id_file"], "text")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);

	if ($num_rows > 0) {
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_delete = sprintf("DELETE FROM `tb_agencyfile` WHERE id_file = %s", 
			GetSQLValueString($row_recordset["id_file"], "text")
		);
		$ok = mysql_query($query_delete);
		if ($ok) {
			// delete went through ok, return OK
			$toret = array(
				"data" => $row_recordset["id_file"], 
				"metadata" => array()
			);
		} else {
			$toret = array(
				"data" => array("error" => mysql_error()), 
				"metadata" => array()
			);
		}

	} else {
		// no row found, return an error
		$toret = array(
			"data" => array("error" => "No row found"), 
			"metadata" => array()
		);
	}
	return $toret;
}

/**
 * we use this as an error response, if we do not receive a correct method
 * 
 */
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
		case "Insert": 
			$ret = insert();
		break;
		case "Insertfiledata": 
			$ret = insertfiledata();
		break;
		case "Update": 
			$ret = update();
		break;
		case "Delete": 
			$ret = delete();
		break;
		case "Count":
			$ret = rowCount();
		break;
		case "FindAllcategone":
			$ret = maincategory();
		break;
		case "Findmaincategone":
			$ret = mainfilesubcategory();
		break;
		case "subcategoryshort":
			$ret = mainfilesubcategoryshort();
		break;
		case "ministries":
			$ret = ministries();
		break;
		case "ministrynsubs":
			$ret = ministrynsubs();
		break;
		case "ministrynsubs2":
			$ret = ministrynsubs2();
		break;
		case "subagency":
			$ret = subagency();
		break;
		case "straigthminfile":
			$ret = straigthminfile();
		break;
		case "straigthminfile1":
			$ret = straigthminfile1();
		break;
		case "straigthminfile2":
			$ret = straigthminfile2();
		break;
		case "straigthminfile4":
			$ret = straigthminfile4();
		break;
		case "straigthminfile5":
			$ret = straigthminfile5();
		break;
		case "folderlocation":
			$ret = setlocation();
		break;
		case "ShowPics":
			$ret = Show_Files();
		break;
		case "Show_Filesbydate":
			$ret = Show_Filesbydate();
		break;
		case "Show_Filesbyrefno":
			$ret = Show_Filesbyrefno();
		break;
	}
}


$serializer = new XmlSerializer();
echo $serializer->serialize($ret);
die();
?>
