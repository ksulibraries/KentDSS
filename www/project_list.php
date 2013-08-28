<?php
/*

	Copyright 2013 Kent State University

	Licensed under the Apache License, Version 2.0 (the "License");
	you may not use this file except in compliance with the License.
	You may obtain a copy of the License at

		http://www.apache.org/licenses/LICENSE-2.0

	Unless required by applicable law or agreed to in writing, software
	distributed under the License is distributed on an "AS IS" BASIS,
	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	See the License for the specific language governing permissions and
	limitations under the License.
   
*/

	if ($dss_userId == 0) {
	
		header("Location: /index.php");
		exit;
		
	}
	
	if (trim($_REQUEST["workgroupId"]) != "") {
	
		$dss_currentWorkgroup = $_REQUEST["workgroupId"];
		db_query("UPDATE session SET currentWorkgroup=".escapeValue($dss_currentWorkgroup)." WHERE id=".escapeQuote($dss_sessionCookie));
		
	}
	
	$workgroupResult = db_fetch(db_query("SELECT name FROM workgroup WHERE id=$dss_currentWorkgroup"));

	/* Keep track of the char & page number the user is viewing. */
	
	if (trim($_REQUEST["char"]) != "") {
	
		superstore_save("$dss_currentWorkgroup",$dss_sessionCookie,"char",$_REQUEST["char"]);
		superstore_save("$dss_currentWorkgroup",$dss_sessionCookie,"currentPage",0);
	
	}
	
	if (trim($_REQUEST["currentPage"]) != "") superstore_save("$dss_currentWorkgroup",$dss_sessionCookie,"currentPage",$_REQUEST["currentPage"]);

	$char = superstore_fetch("$dss_currentWorkgroup",$dss_sessionCookie,"char");
	$currentPage = escapeValue(superstore_fetch("$dss_currentWorkgroup",$dss_sessionCookie,"currentPage"));

	require "resources/header.php";
	
?>
				<h2>Projects for <?php echo $workgroupResult["name"]; ?></h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
<?php if ($dss_numWorkgroups > 1 || $dss_accessLevel > 0) { ?>
				<a href="workgroup_list.php"><img src="/resources/cancel.png" border="0" alt="Return to workgroup list" title="Return to workgroup list"></a>
<?php } ?>
				<a href="project_add.php"><img src="/resources/add.png" border="0" alt="Add new project" title="Add new project"></a>
<?php if ($dss_accessLevel > 0) { ?>
				<a href="workgroup_edit.php?workgroupId=<?php echo $dss_currentWorkgroup; ?>"><img src="/resources/pencil.png" border="0" alt="Edit this workgroup" title="Edit this workgroup"></a>
<?php } ?>
<?php 		$char = displayAlphaBar("project","name","WHERE workgroupId=$dss_currentWorkgroup",$char,$dss_displayListSize); ?>
				<table class="listing">
					<tr><th>&nbsp;</th><th>Name</th><th>Last Updated</th></tr>
<?php

	$where = "WHERE workgroupId=$dss_currentWorkgroup";
	if ($char == "#") $where .= " AND left(name,1)>='0' AND left(name,1)<='9'";
	elseif ($char != "all") $where .= " AND left(name,1)='$char'";
	$projectQuery = db_query("SELECT id,name,lastUpdatedDate FROM project $where ORDER BY name");
	$projectQuery = db_query("SELECT count(id) AS numItems FROM project $where");
	$projectResult = db_fetch($projectQuery);
	$numItems = $projectResult["numItems"];
	$limit = "";
		
	if ($char != "all") {
	
		displayPageBar($numItems,$dss_displayListSize, $currentPage);
		$start = $currentPage * $dss_displayListSize;
		$limit = "LIMIT $start,$dss_displayListSize";
		
	}


	$projectQuery = db_query("SELECT id,name,lastUpdatedDate FROM project $where ORDER BY name $limit");

	while ($projectResult = db_fetch($projectQuery)) {
		
		$itemQuery = db_query("SELECT id FROM item WHERE metadataCompleted=0 AND projectId=".$projectResult["id"]);
		$metadataCompleted = "green";
		$metadataCompletedMessage = "All required information has been provided";

		if (db_numrows($itemQuery) > 0) {
		
			$metadataCompleted = "red";
			$metadataCompletedMessage = "Required information has not been provided";

		}
		
?>
					<tr>
						<td class="metadataStatus"><img src="/resources/bullet_<?php echo $metadataCompleted; ?>.png" border="0" alt="<?php echo $metadataCompletedMessage; ?>" title="<?php echo $metadataCompletedMessage; ?>"></td>
						<td><a href="item_list.php?projectId=<?php echo $projectResult["id"]; ?>"><?php echo $projectResult["name"]; ?></a></td>
						<td width="240"><?php echo date($dss_dateFormat[2],$projectResult["lastUpdatedDate"]); ?></td>
					</tr>		
<?php

	}
		
?>
				</table>	
<?php

	require "resources/footer.php";
	
?>