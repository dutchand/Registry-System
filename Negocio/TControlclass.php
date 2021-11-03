<?php



class TControlclass {
	
	
	private $folderlocation;
	


	function setfolderlocation($var)
	{
		$folderlocation = $var;
		
	} 
	
 function getfolderlocation($folderlocation)
 {
 	
 	return $folderlocation;
 }



  function getdistricts($value)
{
	
	global $conn;
	$query_select = "SELECT id_district FROM tb_district where descriptiondist='$value'";
	$query_result = mysql_query($query_select,$conn);
	$saldo = mysql_fetch_array($query_result);
	return $saldo[0];	
	
}


function showcategories()
		{
			global $conn;
			
			$query_select = "SELECT DISTINCT id_category, descriptioncategory
                             from tb_maincategory ORDER BY descriptioncategory ASC";
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

   



//agency

 function maincategory()
{
	global $conn;
			
			$query_select = "SELECT id_category,descriptioncategory FROM `tb_maincategory`";
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
 
   
  function mainfilesubcategory($variable)
{
	global $conn;
			//$variable = $_REQUEST["mfc"];
			$query_select = "SELECT tbs.id_subcategory,  tbs.descriptionsubcategory 
                             FROM tb_subcategory tbs , tb_maincategory tbm
                             WHERE tbs.`id_category` = tbm.`id_category`  AND tbs.`id_category` = '$variable'";
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




  function mainfilesubcategoryshort($variable)
{
	global $conn;
			//$variable = $_REQUEST["mfc2"];
			$query_select = "SELECT tbss.id_subsubcategory, tbss.descriptionsubsubcategory
                             FROM tb_subcategory tbs , `tb_subsubcategory` tbss
                             WHERE tbs.`id_subcategory` = tbss.`id_subcategory`  AND tbss.`id_subcategory` = '$variable'";
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
 
 
 function ministries()
{
	global $conn;
			
			$query_select = "SELECT id_govagency,descriptiongovagency FROM `tb_governmentagency`";
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
 
 
 
  function ministrynsubs2()
{
	global $conn;
			
			$query_select = "SELECT tbga.id_govagency, tbga.descriptiongovagency, tbsga.`descriptionsubagency`
                             FROM `tb_governmentagency`  tbga, `tb_subgovagency` tbsga
                             WHERE tbga.`id_govagency` = tbsga.`id_govagency` ORDER BY tbga.id_govagency ASC";
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
 
 
 
   function ministrynsubs()
{
	global $conn;
			
			$query_select = "SELECT DISTINCT tbga.id_govagency, tbga.descriptiongovagency
                             FROM `tb_governmentagency`  tbga, `tb_subgovagency` tbsga
                             WHERE tbga.`id_govagency` = tbsga.`id_govagency` ORDER BY tbga.id_govagency ASC";
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
  
   function subagency($variable)
{
	global $conn;
			//$variable = $_REQUEST['ministry'];
			$query_select = "SELECT DISTINCT id_subgovagency,id_govagency ,descriptionsubagency
                             from tb_subgovagency where id_govagency = '$variable' ORDER BY id_govagency ASC";
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


function straigthminfile()
{
	
		global $conn;
		
			$query_select = "SELECT tbmf.`id_minfile`, tbmf.`dateopen`, tbmf.`volume`, tbmf.`path`, tbmf.`status`, tbg.`id_govagency`, tbg.`descriptiongovagency`, tbsc.`id_subcategory`, tbsc.`descriptionsubcategory`, tbmc.`id_category`, tbmc.`descriptioncategory`
                             FROM  `tb_minfile` tbmf, `tb_file` tbf, `tb_governmentagency` tbg, `tb_subcategory` tbsc, `tb_maincategory` tbmc
                             WHERE tbmf.`id_subgovagency` IS NULL AND tbf.`id_subsubcategory` IS NULL AND tbf.`id_file` = tbmf.`id_file` AND tbg.`id_govagency` = tbmf.`id_govagency` AND tbsc.`id_subcategory` = tbf.`id_subcategory` AND tbmc.`id_category` = tbsc.`id_category` ORDER BY tbg.`descriptiongovagency`";
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

function straigthminfile1()
{
	
		global $conn;
		
			$query_select = "SELECT tbmf.`id_minfile`, tbmf.`dateopen`, tbmf.`volume`, tbmf.`path`, tbmf.`status`, tbg.`id_govagency`, tbg.`descriptiongovagency`, tbsc.`id_subcategory`, tbsc.`descriptionsubcategory`, tbmc.`id_category`, tbmc.`descriptioncategory`, tbsgc.`id_subgovagency`, tbsgc.`descriptionsubagency`
                             FROM  `tb_minfile` tbmf, `tb_file` tbf, `tb_governmentagency` tbg, `tb_subcategory` tbsc, `tb_maincategory` tbmc, `tb_subgovagency` tbsgc
                             WHERE tbmf.`id_subgovagency` IS NOT NULL  AND tbf.`id_subsubcategory` IS NULL AND tbf.`id_file` = tbmf.`id_file` AND tbg.`id_govagency` = tbmf.`id_govagency` AND tbsc.`id_subcategory` = tbf.`id_subcategory` AND tbmc.`id_category` = tbsc.`id_category` AND tbmf.`id_subgovagency` = tbsgc.`id_subgovagency` ORDER BY tbg.`descriptiongovagency`";
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



function straigthminfile2()
{
	
		global $conn;
		
			$query_select = "SELECT tbmf.`id_minfile`, tbmf.`dateopen`, tbmf.`volume`, tbmf.`path`, tbmf.`status`, tbg.`id_govagency`, tbg.`descriptiongovagency`, tbsc.`id_subcategory`, tbsc.`descriptionsubcategory`, tbmc.`id_category`, tbmc.`descriptioncategory`, tbssc.`id_subsubcategory`, tbssc.`descriptionsubsubcategory`
                             FROM  `tb_minfile` tbmf, `tb_file` tbf, `tb_governmentagency` tbg, `tb_subcategory` tbsc, `tb_maincategory` tbmc, `tb_subsubcategory` tbssc
                             WHERE tbmf.`id_subgovagency` IS NULL  AND tbf.`id_subsubcategory` IS NOT NULL AND tbf.`id_file` = tbmf.`id_file` AND tbg.`id_govagency` = tbmf.`id_govagency` AND tbsc.`id_subcategory` = tbf.`id_subcategory` AND tbmc.`id_category` = tbsc.`id_category` AND tbssc.`id_subcategory` = tbsc.`id_subcategory` AND tbssc.`id_subsubcategory` = tbf.`id_subsubcategory` ORDER BY tbg.`descriptiongovagency`";
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



function straigthminfile4()
{
	
		global $conn;
		
			$query_select = "SELECT DISTINCT tbmf.`id_minfile`, tbmf.`dateopen`, tbmf.`volume`, tbmf.`path`, tbmf.`status`, tbg.`id_govagency`, tbg.`descriptiongovagency`
FROM  `tb_minfile` tbmf, `tb_governmentagency` tbg
Where tbmf.`path` ='ok' AND tbmf.`id_govagency` = tbg.`id_govagency` AND tbmf.`id_subgovagency` is NULL";
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


function straigthminfile5()
{
	
		global $conn;
		
			$query_select = "SELECT DISTINCT tbmf.`id_minfile`, tbmf.`dateopen`, tbmf.`volume`, tbmf.`path`, tbmf.`status`, tbg.`id_govagency`, tbg.`descriptiongovagency`, tbsca.`descriptionsubagency`
FROM  `tb_minfile` tbmf, `tb_governmentagency` tbg, `tb_subgovagency` tbsca
Where tbmf.`path` ='ok' AND tbmf.`id_govagency` = tbg.`id_govagency` AND tbmf.`id_subgovagency` is  not NULL AND tbsca.`id_subgovagency`= tbmf.`id_subgovagency`";
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

}
?>
