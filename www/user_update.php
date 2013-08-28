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
	
	if (trim($_REQUEST["cancel_button"]) != "") {
	
		header("Location: /user_list.php");
		exit;
		
	}
	
	$userId = $_REQUEST["userId"];
	$infoMessage = "User was updated.";
	
	if (trim($userId) == "") {
	
		/* See if this is a duplicate user. */
		
		$query = db_query("SELECT id FROM user WHERE username=".escapeQuote($_REQUEST["username"]));
		
		if (db_numrows($query) == 0) {
		
			$infoMessage = "User was added.";
			$userId = db_insert("user");
			db_query("UPDATE user SET createdByUserId=$dss_userId".
				",createdDate=".date("U").
				" WHERE id=".escapeValue($userId));
		
		} else {
		
			header("Location: user_list.php?errorMessage=".rawurlencode("The username already exists and was not added."));
			exit;
			
		}
		
	}
	
	/* See if this is a duplicate username. */
	
	$query = db_query("SELECT id FROM user WHERE id<>".escapeValue($userId)." AND username=".escapeQuote($_REQUEST["username"]));
	
	if (db_numrows($query) == 0) {

		db_query("UPDATE user SET active=".escapeValue($_REQUEST["active"]).
			",accessLevel=".escapeValue($_REQUEST["accessLevel"]).
			",firstName=".escapeQuote($_REQUEST["firstName"]).
			",lastName=".escapeQuote($_REQUEST["lastName"]).
			",username=".escapeQuote($_REQUEST["username"]).
			",emailAddress=".escapeQuote($_REQUEST["emailAddress"]).
			",lastUpdatedByUserId=$dss_userId".
			",lastUpdatedDate=".date("U").
			" WHERE id=".escapeValue($userId));
			
		/* Update workgroups. */
		
		db_query("DELETE FROM workgroupUser WHERE userId=".escapeValue($userId));
		foreach ($_REQUEST["workgroupId"] as $workgroupId=>$value) if ($value) db_query("INSERT INTO workgroupUser VALUES ($workgroupId,$userId)");
	
	} else {
	
		header("Location: user_list.php?errorMessage=".rawurlencode("The username belongs to another user and no changes were made."));
		exit;
		
	}

	header("Location: user_list.php?infoMessage=".rawurlencode($infoMessage));
	
?>