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
	
	$numberSynced = metasync($dss_currentProject);
	
	if (trim($numberSynced["success"]) != "") $infoMessage = rawurlencode("There ".displayNumberWords($numberSynced["success"],"was","were","item","items")." synced.");
	elseif (trim($numberSynced["error"]) != "") $errorMessage = rawurlencode($numberSynced["error"]);
	else $infoMessage = rawurlencode("No items where synced.");
	
	header("Location: item_list.php?projectId=$dss_currentProject&infoMessage=$infoMessage&errorMessage=$errorMessage");
					
?>