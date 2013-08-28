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

	if ($dss_userId == 0 || $dss_accessLevel < 2) {
	
		header("Location: /index.php");
		exit;
		
	}
	
	$comment = $_POST["comment"];
	$adminPassword = $_POST["adminPassword"];
	$itemId = $_POST["itemId"];

	if (trim($_REQUEST["cancel_button"]) != "") {
	
		header("Location: item_detail.php?search=".$_POST["search"]."&itemId=$itemId");
		exit;
	
	}

	$itemQuery = db_query("SELECT * FROM item WHERE id=".escapeValue($itemId));
		
	if (db_numrows($itemQuery) == 1) $itemResult = db_fetch($itemQuery);
	else {
	
		header("Location: /index.php");
		exit;
		
	}
	
	/* Verify the password entered. */
	
	$userResult = db_fetch(db_query("SELECT username FROM user WHERE id=$dss_userId"));
	
	if (!verify_password($userResult["username"],$adminPassword)) {
		
		header("Location: /item_detail.php?itemId=".escapeValue($itemId)."&errorMessage=".rawurlencode("The password entered was incorrect."));
		exit;

	}
	
	/* Get the project and workgroup for this item. */
	
	$projectResult = db_fetch(db_query("SELECT workgroupId,name FROM project WHERE id=".$itemResult["projectId"]));
	$workgroupResult = db_fetch(db_query("SELECT name FROM workgroup WHERE id=".$projectResult["workgroupId"]));
	
	/* Log this action in the auditlog. */

	$logId = db_insert("auditlog");
	db_query("UPDATE auditlog SET action='DELETE'".
		",actionByUserId=$dss_userId".
		",actionDate=".date("U").
		",actionComment=".escapeQuote($comment).
		",workgroupName=".escapeQuote($workgroupResult["name"]).
		",projectName=".escapeQuote($projectResult["name"]).
		",retentionGroup=".escapeQuote($itemResult["retentionGroup"]).
		",filetype=".escapeQuote($itemResult["filetype"]).
		",filesize=".escapeValue($itemResult["filesize"]).
		",filename=".escapeQuote($itemResult["filename"]).
		",expirationDate=".escapeValue($itemResult["expirationDate"]).
		",addedByUserId=".escapeValue($itemResult["addedByUserId"]).
		",addedDate=".escapeValue($itemResult["addedDate"]).
		",lastUpdatedByUserId=".escapeValue($itemResult["lastUpdatedByUserId"]).
		",lastUpdatedDate=".escapeValue($itemResult["lastUpdatedDate"]).
		",significant=".escapeValue($itemResult["significant"]).
		",title=".escapeQuote($itemResult["title"]).
		",description=".escapeQuote($itemResult["description"]).
		",creator=".escapeQuote($itemResult["creator"]).
		",creationDate=".escapeQuote($itemResult["creationDate"]).
		",location=".escapeQuote($itemResult["location"]).
		" WHERE id=$logId");
		
	/* Remove the item and its thumbnail and delete the item record from the database. */
	
	unlink("$dss_fileshare/".$itemResult["projectId"]."/".$itemResult["filename"]);
	unlink("$dss_docRoot/thumbnail/".md5($itemResult["id"]).".jpg");
	db_query("DELETE FROM item WHERE id=".escapeValue($itemId));
	
	header("Location: item_list.php?search=".$_POST["search"]."&projectId=".$itemResult["projectId"]."&infoMessage=".rawurlencode("The item has been deleted."));
	
?>