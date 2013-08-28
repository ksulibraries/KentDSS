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
	
	if (trim($_REQUEST["cancel_button"]) != "") {
	
		header("Location: /group_list.php");
		exit;
		
	}
	
	$groupId = $_REQUEST["groupId"];

	if (trim($_REQUEST["delete_button"]) != "") {
	
		db_query("DELETE FROM retention WHERE id=".escapeValue($groupId));
		header("Location: /group_list.php?infoMessage=".rawurlencode('The retention group was deleted.'));
		exit;
		
	}
	
	$infoMessage = "Retention group was updated.";
	
	if (trim($groupId) == "") {
	
		/* See if this is a duplicate group. */
		
		$query = db_query("SELECT id FROM retention WHERE retentionGroup=".escapeQuote($_REQUEST["retentionGroup"]));
		
		if (db_numrows($query) == 0) {
		
			$infoMessage = "Retention group was added.";
			$groupId = db_insert("retention");
		
		} else {
		
			header("Location: group_list.php?errorMessage=".rawurlencode("The retention group already exists and was not added."));
			exit;
			
		}
		
	}
	
	/* See if this is a duplicate username. */
	
	$query = db_query("SELECT id FROM retention WHERE id<>".escapeValue($groupId)." AND retentionGroup=".escapeQuote($_REQUEST["retentionGroup"]));
	
	if (db_numrows($query) == 0) {

		db_query("UPDATE retention SET retentionGroup=".escapeQuote($_REQUEST["retentionGroup"]).
			",retentionPeriod=".escapeValue($_REQUEST["retentionPeriod"]).
			" WHERE id=".escapeValue($groupId));
			
	} else {
	
		header("Location: group_list.php?errorMessage=".rawurlencode("The retention group already exists and no changes were made."));
		exit;
		
	}

	header("Location: group_list.php?infoMessage=".rawurlencode($infoMessage));
	
?>