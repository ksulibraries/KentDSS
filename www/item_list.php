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
	
	if (trim($_REQUEST["projectId"]) != "") {
	
		$dss_currentProject = $_REQUEST["projectId"];

		/* See if this user can view this project. */
		
		if ($dss_accessLevel == 0) {
		
			$projectQuery = db_query("SELECT id FROM project WHERE workgroupId=$dss_currentWorkgroup AND id=".escapeValue($dss_currentProject));
			
			if (db_numrows($projectQuery) == 0) {
			
				header("Location: /project_list.php?errorMessage=".rawurlencode("Invalid project identifier."));
				exit;
				
			}
			
		}
	
		db_query("UPDATE session SET currentProject=".escapeValue($dss_currentProject)." WHERE id=".escapeQuote($dss_sessionCookie));
		
	}
	
	$projectResult = db_fetch(db_query("SELECT name FROM project WHERE id=$dss_currentProject"));

	/* Keep track of the char & page number the user is viewing. */
	
	if (trim($_REQUEST["char"]) != "") {
	
		superstore_save("$dss_currentWorkgroup.$dss_currentProject",$dss_sessionCookie,"char",$_REQUEST["char"]);
		superstore_save("$dss_currentWorkgroup.$dss_currentProject",$dss_sessionCookie,"currentPage",0);

	}

	if (trim($_REQUEST["currentPage"]) != "") superstore_save("$dss_currentWorkgroup.$dss_currentProject",$dss_sessionCookie,"currentPage",$_REQUEST["currentPage"]);
	
	$char = superstore_fetch("$dss_currentWorkgroup.$dss_currentProject",$dss_sessionCookie,"char");
	$currentPage = escapeValue(superstore_fetch("$dss_currentWorkgroup.$dss_currentProject",$dss_sessionCookie,"currentPage"));

	require "resources/header.php";
	
?>
				<h2>Items for <?php echo $projectResult["name"]; ?></h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<a href="project_list.php"><img src="/resources/cancel.png" border="0" alt="Return to project list" title="Return to project list"></a>
				<a href="item_add.php?projectId=<?php echo $dss_currentProject; ?>"><img src="/resources/add.png" border="0" alt="Add new items" title="Add new items"></a>
				<a href="item_upload.php?projectId=<?php echo $dss_currentProject; ?>"><img src="/resources/zip.png" border="0" alt="Add a ZIP archive" title="Add a ZIP archive"></a>
				<a href="project_edit.php?projectId=<?php echo $dss_currentProject; ?>"><img src="/resources/pencil.png" border="0" alt="Edit this project" title="Edit this project"></a>
<?php if ($dss_accessLevel > 0) { ?>
				<a href="project_sync.php?projectId=<?php echo $dss_currentProject; ?>"><img src="/resources/sync.png" border="0" alt="Sync this project" title="Sync this project"></a>
<?php } ?>
<?php

	$char = displayAlphaBar("item","title","WHERE projectId=$dss_currentProject",$char,$dss_displayListSize); 

	$where = "WHERE projectId=$dss_currentProject";
	if ($char == "#") $where .= " AND left(title,1)>='0' AND left(title,1)<='9'";
	elseif ($char != "all") $where .= " AND left(title,1)='$char'";
	$itemQuery = db_query("SELECT count(id) AS numItems FROM item $where");
	$itemResult = db_fetch($itemQuery);
	$numItems = $itemResult["numItems"];
	$limit = "";
		
	if ($char != "all") {
	
		displayPageBar($numItems, $dss_displayListSize, $currentPage);
		$start = $currentPage * $dss_displayListSize;
		$limit = "LIMIT $start,$dss_displayListSize";
		
	}
		
?>
				<table class="listing">
					<tr><th>&nbsp;</th><th>Title</th><th>Last Updated</th></tr>
<?php

	
	$itemQuery = db_query("SELECT id,metadataCompleted,title,lastUpdatedDate FROM item $where ORDER BY title $limit");

	while ($itemResult = db_fetch($itemQuery)) {
	
		$metadataCompleted = "red";
		$metadataCompletedMessage = "Required information has not been provided";
		
		if ($itemResult["metadataCompleted"]) {
		
			$metadataCompleted = "green";
			$metadataCompletedMessage = "All required information has been provided";
			
		}
		
?>
					<tr>
						<td class="metadataStatus"><img src="/resources/bullet_<?php echo $metadataCompleted; ?>.png" border="0" alt="<?php echo $metadataCompletedMessage; ?>" title="<?php echo $metadataCompletedMessage; ?>"></td>
						<td><a href="item_detail.php?itemId=<?php echo $itemResult["id"]; ?>"><?php echo $itemResult["title"]; ?></a></td>
						<td width="240"><?php echo date($dss_dateFormat[2],$itemResult["lastUpdatedDate"]); ?></td>
					</tr>		
<?php

	}
		
?>
				</table>	
<?php

	require "resources/footer.php";
	
?>