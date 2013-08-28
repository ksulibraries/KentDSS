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
	
	if (trim($_REQUEST["cancel_button"]) != "") {

		header("Location: /project_list.php");
		exit;
		
	}

	/* See if this is a duplicate project name. */
	
	$query = db_query("SELECT id FROM project WHERE workgroupId=$dss_currentWorkgroup AND name=".escapeQuote($_REQUEST["name"]));
	
	if (db_numrows($query) == 0) {
	
		$projectId = db_insert("project");
	
		/* Create the sub-directory for this project. */
		
		if (!mkdir("/data/files/$projectId")) {		
		
			db_query("DELETE FROM project WHERE id=".escapeValue($projectId));
			header("Location: project_list.php?errorMessage=".rawurlencode("The project sub-directory could not be created and the project was not added."));
			exit;
				
		} else {
	
			db_query("UPDATE project SET workgroupId=$dss_currentWorkgroup".
				",name=".escapeQuote($_REQUEST["name"]).
				",createdByUserId=$dss_userId".
				",createdDate=".date("U").
				",lastUpdatedByUserId=$dss_userId".
				",lastUpdatedDate=".date("U").
				" WHERE id=".escapeValue($projectId));
			
			header("Location: item_list.php?projectId=".escapeValue($projectId));

		}
			
	} else {
	
		header("Location: project_list.php?errorMessage=".rawurlencode("The project name already exists and was not added."));
		exit;
		
	}
		
?>