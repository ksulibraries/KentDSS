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

	if ($dss_userId == 0 || $dss_accessLevel == 0) {
	
		header("Location: /index.php");
		exit;
		
	}
	
	$workgroupId = $_REQUEST["workgroupId"];
	
	if (trim($_REQUEST["cancel_button"]) != "") {
	
		if (trim($workgroupId) == "")
			header("Location: /workgroup_list.php");
		else
			header("Location: /project_list.php");
		exit;
		
	}
	
	if (trim($workgroupId) == "") {
	
		/* See if this is a duplicate workgroup name. */
		
		$query = db_query("SELECT id FROM workgroup WHERE name=".escapeQuote($_REQUEST["name"]));
		
		if (db_numrows($query) == 0) {
		
			$workgroupId = db_insert("workgroup");
			db_query("UPDATE workgroup SET createdByUserId=$dss_userId".
				",createdDate=".date("U").
				" WHERE id=".escapeValue($workgroupId));
		
		} else {
		
			header("Location: workgroup_list.php?errorMessage=".rawurlencode("The workgroup name already exists and was not added."));
			exit;
			
		}
		
	}
	
	/* If the workgroup name changed, update it. */
	
	$result = db_fetch(db_query("SELECT name FROM workgroup WHERE id=".escapeValue($workgroupId)));
	
	if ($result["name"] != $_REQUEST["name"]) {

		db_query("UPDATE workgroup SET name=".escapeQuote($_REQUEST["name"]).
			",lastUpdatedByUserId=$dss_userId".
			",lastUpdatedDate=".date("U").
			" WHERE id=".escapeValue($workgroupId));
		$infoMessage = "Workgroup name updated.";

	}
		
	header("Location: workgroup_list.php?infoMessage=".rawurlencode($infoMessage));
	
?>