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

	$errorMessage = rawurlencode("Your username and password could not be authenticated.");
	
	$username = $_POST["username"];
	$password = $_POST["password"];
	
	if (trim($username) != "" && trim($password) != "") {
	
		$usernameArray = explode("@",$username);
		$userQuery = db_query("SELECT id,accessLevel,displayListSize FROM user WHERE active=1 AND username=".escapeQuote($usernameArray[0]));
		
		if (db_numrows($userQuery) == 1) {

			$userResult = db_fetch($userQuery);
			
			if (!verify_password($usernameArray[0],$password)) {
				
				header("Location: /index.php?errorMessage=$errorMessage");
				exit;
	
			}
				
			/* Count the number of workgroups this user is a member of. */
			
			$workgroupQuery = db_query("SELECT workgroupId FROM workgroupUser WHERE userId=".$userResult["id"]);
			$numWorkgroups = db_numrows($workgroupQuery);
			$workgroupResult = @db_fetch($workgroupQuery);
			
			/* Update the session table. */
			
			db_query("UPDATE session SET timeout=".(date("U")+14400).
				",userId=".$userResult["id"].
				",accessLevel=".$userResult["accessLevel"].
				",displayListSize=".$userResult["displayListSize"].
				",numWorkgroups=$numWorkgroups".
				",currentWorkgroup=".$workgroupResult["workgroupId"].
				" WHERE id=".escapeQuote($dss_sessionCookie));
				
			/* User the last login date. */
				
			db_query("UPDATE user SET lastLoginDate=".date("U")." WHERE id=".$userResult["id"]);
			
			header("Location: /index.php");
	
		} else header("Location: /index.php?errorMessage=$errorMessage");
		
	} else header("Location: /index.php?errorMessage=$errorMessage");
	
?>
