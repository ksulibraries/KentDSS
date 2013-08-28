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
	
	$itemId = $_REQUEST["itemId"];
	
	/* Can this user edit this item. */
	
	if ($dss_accessLevel == 0) {
	
		$workgroupQuery = db_query("SELECT b.workgroupId FROM item a, project b WHERE a.id=".escapeValue($itemId)." AND a.projectId=b.id");
		if (db_numrows($workgroupQuery) == 1) $workgroupResult = db_fetch($workgroupQuery);
		else {
		
			header("Location: /index.php");
			exit;
			
		}
	
		$accessQuery = db_query("SELECT workgroupId FROM workgroupUser WHERE workgroupId=".$workgroupResult["workgroupId"]." AND userId=$dss_userId");
	
		if (db_numrows($accessQuery) == 0) {
		
			header("Location: /index.php");
			exit;
			
		}
	
	}

	$itemQuery = db_query("SELECT * FROM item WHERE id=".escapeValue($itemId));
		
	if (db_numrows($itemQuery) == 1) $itemResult = db_fetch($itemQuery);
	else {
	
		header("Location: /index.php");
		exit;
		
	}
	
	/* Get the project and workgroup for this item. */
	
	$projectResult = db_fetch(db_query("SELECT workgroupId,name FROM project WHERE id=".$itemResult["projectId"]));
	$workgroupResult = db_fetch(db_query("SELECT name FROM workgroup WHERE id=".$projectResult["workgroupId"]));
	
	/* See if a restore has been queued for this item. */
	
	if (!$itemResult["restorePending"] && !$itemResult["restoreInProgress"]) {
	
		/* Verify that the checksum of the file was correct when last checked. */
		
		$checksumLastChecked = $itemResult["checksumChecked"];
		
		/* If it is marked as not correct, check it again now. */
		
		if ($itemResult["checksumCorrect"] == 0) {
		
			$currentChecksum = md5_file($dss_fileshare.$dss_currentProject."/".$itemResult["filename"]);
			
			if ($currentChecksum == $itemResult["checksum"]) {
			
				db_query("UPDATE item SET checksumCorrect=1,checksumChecked=".date("U")." WHERE id=".$itemResult["id"]);
				$checksumLastChecked = date("U");
				$checksumStatus = 1;
			
			} else $checksumStatus = 0;
	
		} else $checksumStatus = 1;
	
	} else $checksumStatus = 0;

	/* Find the previous and next items and provide links to them. */
	
	$previousLinkId = 0;
	$nextLinkId = 0;
	$char = superstore_fetch("$dss_currentWorkgroup.$dss_currentProject",$dss_sessionCookie,"char");
	if (trim($char) == "") $char = "all";
	$where = "WHERE projectId=$dss_currentProject";
	if ($char == "#") $where .= " AND left(title,1)>='0' AND left(title,1)<='9'";
	elseif ($char != "all") $where .= " AND left(title,1)='$char'";
	$linkQuery = db_query("SELECT id FROM item $where ORDER BY title");
	$found = 0;
	
	while ($linkResult = db_fetch($linkQuery)) {
	
		if ($linkResult["id"] == $itemId) $found = 1;
		
		if ($linkResult["id"] != $itemId) {
		
			if ($found == 0) $previousLinkId = $linkResult["id"];
			if ($nextLinkId == 0 && $found == 1) $nextLinkId = $linkResult["id"];
			
		}
		
	}

	require "resources/header.php";

?>
<?php if ($itemResult["thumbnailCompleted"]) { ?>
				<a href="/preview/<?php echo md5($itemResult["id"]); ?>.jpg" target="_preview"><img src="/thumbnail/<?php echo md5($itemResult["id"]); ?>.jpg" border="0" align="right" /></a>
<?php } ?>
				<h2>Item Detail</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"]); ?>
<?php if ($_REQUEST["search"] == 1) { ?>
				<a href="search.php"><img src="/resources/cancel.png" border="0" alt="Return to search results" title="Return to search results"></a>
<?php } else { ?>
				<a href="item_list.php"><img src="/resources/cancel.png" border="0" alt="Return to item list" title="Return to item list"></a>
<?php } ?>
				<a href="item_edit.php?search=<?php echo $_REQUEST["search"]; ?>&itemId=<?php echo $itemId; ?>"><img src="/resources/pencil.png" border="0" alt="Edit this item" title="Edit this item"></a>
				<a href="item_download.php?itemId=<?php echo $itemId; ?>"><img src="/resources/download.png" border="0" alt="Download this item" title="Download this item"></a>
<?php 

	if ($_REQUEST["search"] == "") {
	
		if ($previousLinkId != 0) { 
		
?>
				<a href="item_detail.php?itemId=<?php echo $previousLinkId; ?>"><img src="/resources/previous.png" border="0" alt="Display previous item details" title="Display previous item details"></a>
<?php 

		} else { 
		
?>
				<img src="/resources/previous_gray.png" border="0" alt="No previous item available" title="No previous item available">
<?php 

		}
		
		if ($nextLinkId != 0) { 
		
?>
				<a href="item_detail.php?itemId=<?php echo $nextLinkId; ?>"><img src="/resources/next.png" border="0" alt="Display next item details" title="Display next item details"></a>
<?php 

		} else { 
		
?>
				<img src="/resources/next_gray.png" border="0" alt="No next item available" title="No next item available">
<?php 

		} 
		
	} 

?>
				<table cellpadding="0" cellspacing="0" border="0">
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Item Title:</td>
						<td class="data"><?php echo $itemResult["title"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Project:</td>
						<td class="data"><a href="item_list.php?projectId=<?php echo $itemResult["projectId"]; ?>"><?php echo $projectResult["name"]; ?></a></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Workgroup:</td>
						<td class="data"><a href="project_list.php?workgroupId=<?php echo $projectResult["workgroupId"]; ?>"><?php echo $workgroupResult["name"]; ?></a></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>"r>
						<td class="label">Retention Group:</td>
						<td class="data"><?php echo $itemResult["retentionGroup"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Significant Item:</td>
						<td class="data"><?php if ($itemResult["significant"]) echo "Yes"; else echo "No"; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Description:</td>
						<td class="data"><?php echo $itemResult["description"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Creation Date:</td>
						<td class="data"><?php echo $itemResult["creationDate"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Creator:</td>
						<td class="data"><?php echo $itemResult["creator"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>"r>
						<td class="label">Geographical Location:</td>
						<td class="data"><?php echo $itemResult["location"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">File Type:</td>
						<td class="data"><?php echo $itemResult["filetype"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">File Size:</td>
						<td class="data"><?php echo filesize_format($itemResult["filesize"]); ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">File Name:</td>
						<td class="data"><?php echo $itemResult["filename"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Expiration Date:</td>
						<td class="data"><?php echo date($dss_dateFormat[1],$itemResult["expirationDate"]); ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Checksum:</td>
						<td class="data">
<?php 

	if ($checksumStatus) echo "<span style='color: green'>File is intact - last checked on ".date($dss_dateFormat[7],$checksumLastChecked)."</span>"; 
	else {
	
		echo "<span class='highlighted'>ERROR!</span>"; 
		if ($dss_accessLevel > 0)
			if ($itemResult["restorePending"]) echo "&nbsp;Restore pending";
			elseif ($itemResult["restoreInProgress"]) echo "&nbsp;Restore in progress";
			else echo "&nbsp;<a href='item_restore.php?itemId=$itemId'>Restore from backup</a>";	
	
	}
	
?>
						</td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Added By:</td>
						<td class="data"><?php echo fullNameById($itemResult["addedByUserId"]); ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Added On:</td>
						<td class="data"><?php echo date($dss_dateFormat[2],$itemResult["addedDate"]); ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Last Updated By:</td>
						<td class="data"><?php echo fullNameById($itemResult["lastUpdatedByUserId"]); ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Last Updated On:</td>
						<td class="data"><?php echo date($dss_dateFormat[2],$itemResult["lastUpdatedDate"]); ?></td>
					</tr>
				</table>
<?php

	require "resources/footer.php";
	
?>
