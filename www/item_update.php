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
	
	if (trim($_REQUEST["cancel_button"]) != "") {
	
		header("Location: item_detail.php?search=".$_POST["search"]."&itemId=$itemId");
		exit;
	
	}
	
	if (trim($_REQUEST["delete_button"]) != "") {
	
		header("Location: item_delete.php?search=".$_POST["search"]."&itemId=$itemId");
		exit;
	
	}
	
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

	/* Get the date the item was added, the current project ID, and the filename. */	

	$itemQuery = db_query("SELECT addedDate,projectId,filename FROM item WHERE id=$itemId");
	
	if (db_numrows($itemQuery) == 0) {
	
		header("Location: /index.php");
		exit;
		
	}
	
	$itemResult = db_fetch($itemQuery);
	$addedDateDay = date("d",$itemResult["addedDate"]);
	$addedDateMonth = date("m",$itemResult["addedDate"]);
	$addedDateYear = date("Y",$itemResult["addedDate"]);
	
	/* If the new project ID does not match the current one, we will need to move the file to the new project directory. */
	
	$projectId = escapeValue($_REQUEST["projectId"]);
	
	if ($itemResult["projectId"] != $projectId) {
			
		/* See if this filename already exists in the new project. */
		
		if (file_exists($dss_fileshare.$projectId."/".$itemResult["filename"])) {
		
			header("Location: item_detail.php?search=".$_POST["search"]."&itemId=$itemId&errorMessage=".rawurlencode("An item with the same filename is already present in the new project. No updates were made."));
			exit;
		
		} else {
		
			rename($dss_fileshare.$itemResult["projectId"]."/".$itemResult["filename"],$dss_fileshare.$projectId."/".$itemResult["filename"]);
			db_query("UPDATE item SET projectId=$projectId WHERE id=$itemId");

		}
		
	}
		
	/* Calculate the new expiration date. */
	
	if (trim($_REQUEST["retentionGroup"]) != "") {
	
		$retentionPeriodResult = db_fetch(db_query("SELECT retentionPeriod FROM retention WHERE retentionGroup=".escapeQuote($_REQUEST["retentionGroup"])));
		$retentionPeriod = $retentionPeriodResult["retentionPeriod"];
		
		if ($retentionPeriod > 0)
			$expirationDate = mktime(6,0,0,$addedDateMonth,$addedDateDay,$addedDateYear+$retentionPeriod);
		else
			$expirationDate = mktime(6,0,0,12,31,2037);
			
	}
	
	/* If required metadata has been supplied, set the metadata complete indicator. */
	
	$metadataCompleted = 0;
	if (trim($_REQUEST["retentionGroup"]) != "" && trim($_REQUEST["creationDate"]) != "") $metadataCompleted = 1;
	
	/* Update item information. */
	
	db_query("UPDATE item SET metadataCompleted=$metadataCompleted".
		",lastUpdatedByUserId=$dss_userId".
		",lastUpdatedDate=".date("U").
		",significant=".escapeValue($_REQUEST["significant"]).
		",retentionGroup=".escapeQuote($_REQUEST["retentionGroup"]).
		",expirationDate=$expirationDate".
		",title=".escapeQuote($_REQUEST["title"]).
		",description=".escapeQuote($_REQUEST["description"]).
		",creator=".escapeQuote($_REQUEST["creator"]).
		",creationDate=".escapeQuote($_REQUEST["creationDate"]).
		",location=".escapeQuote($_REQUEST["location"]).
		" WHERE id=$itemId");
		
	header("Location: item_detail.php?search=".$_POST["search"]."&itemId=$itemId&infoMessage=".rawurlencode("Item information updated."));
	
?>
