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
	
	$projectId = escapeValue($_REQUEST["projectId"]);
	
	if (trim($_REQUEST["cancel_button"]) != "") {
	
		header("Location: item_list.php");
		exit;
	
	}
	
	
	/* See if this user can make changes to this project. */
	
	if ($dss_accessLevel == 0) {
	
		$projectQuery = db_query("SELECT id FROM project WHERE workgroupId=$dss_currentWorkgroup AND id=".escapeValue($projectId));
		
		if (db_numrows($projectQuery) == 0) {
		
			header("Location: /project_list.php?errorMessage=".rawurlencode("Invalid project identifier."));
			exit;
			
		}
		
	}
	
	if (trim($_REQUEST["delete_button"]) != "") {

		$itemResult = db_fetch(db_query("SELECT count(id) AS numItems FROM item WHERE projectId=".escapeValue($projectId)));
	
		if ($itemResult["numItems"] == 0) {
		
			db_query("DELETE FROM project WHERE id=".escapeValue($projectId));
			header("Location: project_list.php?infoMessage=".rawurlencode("The project was deleted."));
		
		} else header("Location: project_list.php?errorMessage=".rawurlencode("The project is not empty and was not deleted."));
		
		exit;
	
	}
	
	
	/* See if this is a duplicate project name. */
	
	$query = db_query("SELECT id FROM project WHERE id<>".escapeValue($projectId)." AND workgroupId=$dss_currentWorkgroup AND name=".escapeQuote($_REQUEST["name"]));
	
	if (db_numrows($query) > 0) {
	
		header("Location: project_edit.php?projectId=".escapeValue($projectId)."&errorMessage=".rawurlencode("The new project name already exists, no changes were made."));
		exit;
		
	}
		
	$infoMessage = array();
	
	/* If the project name changed, update it. */
	
	$result = db_fetch(db_query("SELECT name FROM project WHERE id=".escapeValue($projectId)));
	
	if ($result["name"] != $_REQUEST["name"]) {
	
		db_query("UPDATE project SET name=".escapeQuote($_REQUEST["name"]).
			",lastUpdatedByUserId=$dss_userId".
			",lastUpdatedDate=".date("U").
			" WHERE id=".escapeValue($projectId));
		$infoMessage[] = "The project name was changed.";
	
	}

	$result = db_fetch(db_query("SELECT workgroupId FROM project WHERE id=".escapeValue($projectId)));
	
	if ($result["workgroupId"] != $_REQUEST["workgroupId"]) {
	
		db_query("UPDATE project SET workgroupId=".escapeValue($_REQUEST["workgroupId"]).
			",lastUpdatedByUserId=$dss_userId".
			",lastUpdatedDate=".date("U").
			" WHERE id=".escapeValue($projectId));
		$infoMessage[] = "The workgroup for this project was changed.";
	
	}

	if (trim($_REQUEST["description"]) != "") {
	
		db_query("UPDATE item SET description=".escapeQuote($_REQUEST["description"]).
			",lastUpdatedByUserId=$dss_userId".
			",lastUpdatedDate=".date("U").
			" WHERE projectId=".escapeValue($projectId));
		$infoMessage[] = "The item descriptions were changed.";
	
	}
	
	if (trim($_REQUEST["creator"]) != "") {
	
		db_query("UPDATE item SET creator=".escapeQuote($_REQUEST["creator"]).
			",lastUpdatedByUserId=$dss_userId".
			",lastUpdatedDate=".date("U").
			" WHERE projectId=".escapeValue($projectId));
		$infoMessage[] = "The item creators were changed.";
	
	}
	
	if (trim($_REQUEST["creationDate"]) != "") {
	
		db_query("UPDATE item SET creationDate=".escapeQuote($_REQUEST["creationDate"]).
			",lastUpdatedByUserId=$dss_userId".
			",lastUpdatedDate=".date("U").
			" WHERE projectId=".escapeValue($projectId));
		$infoMessage[] = "The item creation dates were changed.";
	
	}
	
	if (trim($_REQUEST["location"]) != "") {
	
		db_query("UPDATE item SET location=".escapeQuote($_REQUEST["location"]).
			",lastUpdatedByUserId=$dss_userId".
			",lastUpdatedDate=".date("U").
			" WHERE projectId=".escapeValue($projectId));
		$infoMessage[] = "The item geographic locations were changed.";
	
	}
		
	if (trim($_REQUEST["retentionGroup"]) != "") {
	
		$retentionPeriodResult = db_fetch(db_query("SELECT retentionPeriod FROM retention WHERE retentionGroup=".escapeQuote($_REQUEST["retentionGroup"])));
		$retentionPeriod = $retentionPeriodResult["retentionPeriod"];
		$itemQuery = db_query("SELECT id,addedDate FROM item WHERE projectId=".escapeValue($projectId));
		
		while ($itemResult = db_fetch($itemQuery)) {
		
			$addedDateDay = date("d",$itemResult["addedDate"]);
			$addedDateMonth = date("m",$itemResult["addedDate"]);
			$addedDateYear = date("Y",$itemResult["addedDate"]);
			
			if ($retentionPeriod > 0)
				$expirationDate = mktime(6,0,0,$addedDateMonth,$addedDateDay,$addedDateYear+$retentionPeriod);
			else
				$expirationDate = mktime(6,0,0,12,31,2037);
			
			db_query("UPDATE item SET retentionGroup=".escapeQuote($_REQUEST["retentionGroup"]).
				",expirationDate=$expirationDate".
				",lastUpdatedByUserId=$dss_userId".
				",lastUpdatedDate=".date("U").
				" WHERE id=".$itemResult["id"]);
		
		}

		$infoMessage[] = "The item retention groups and expiration dates were changed.";
	
	}
	
	if (trim($_REQUEST["significant"]) != "") {
	
		db_query("UPDATE item SET significant=".escapeValue($_REQUEST["significant"]).
			",lastUpdatedByUserId=$dss_userId".
			",lastUpdatedDate=".date("U").
			" WHERE projectId=".escapeValue($projectId));
		$infoMessage[] = "The items were marked as significant.";
	
	}
	
	db_query("UPDATE item SET metadataCompleted=1 WHERE retentionGroup<>'' AND creationDate<>'' AND projectId=$projectId");
	
	header("Location: item_list.php?infoMessage=".rawurlencode(implode("<br />",$infoMessage)));
	
?>
