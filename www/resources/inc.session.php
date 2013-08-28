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
	
	/* If rsync is running, don't allow anyone to do anything. */
	
	if (file_exists($dss_fileshare."RSYNC_IN_PROGRESS")) {

		echo "The system is currently backing up content and cannot be used. This begins at 11:00 PM each day.  It may take several hours to complete. Please come back at a later time.";
		exit;
		
	}

	require "resources/inc.constants.php";
	require "resources/inc.config.php";
	require "resources/inc.utilities.php";
	
	$TESTMODE = 0;
	
	/* Set the global values for this session. */
	
	$dss_userId = 0;
	$dss_accessLevel = 0;
	$dss_numWorkgroups = 0;
	$dss_currentWorkgroup = 0;
	$dss_currentProject = 0;
	$dss_displayListSize = 10;
	$dss_sessionTimedOut = 0;

	/* Update the session table. */
	
	db_query("DELETE FROM session WHERE timeout<".date("U"));
	
	$dss_sessionCookie = trim($_COOKIE["damsession"]);
	
	if ($dss_sessionCookie == "") {
	
		$dss_sessionId = md5(date("U").uniqid());
		setcookie("damsession",$dss_sessionId,0,"/");
		
		$dss_sessionId = db_insert("session");
		db_query("INSERT INTO session (id,ipAddress,timeout,userId,accessLevel,numWorkgroups,currentWorkgroup,currentProject,displayListSize) VALUES ('$dss_sessionId','".$_SERVER["REMOTE_ADDR"]."',".(date("U")+14400).",0,0,$dss_numWorkgroups,$dss_currentWorkgroup,0,$dss_displayListSize)");
		
	} else {
	
		$dss_sessionQuery = db_query("SELECT * FROM session WHERE id=".escapeQuote($dss_sessionCookie));
		
		if (db_numrows($dss_sessionQuery) == 0) {
		
			/* The session timed out. */
		
			$dss_sessionId = md5(date("U").uniqid());
			$dss_sessionTimedOut = 1;
			setcookie("damsession",$dss_sessionId,0,"/");
			db_query("INSERT INTO session (id,ipAddress,timeout,userId,accessLevel,numWorkgroups,currentWorkgroup,currentProject,displayListSize) VALUES ('$dss_sessionId','".$_SERVER["REMOTE_ADDR"]."',".(date("U")+14400).",0,0,$dss_numWorkgroups,$dss_currentWorkgroup,0,$dss_displayListSize)");
					
		} else {
		
			$dss_sessionResult = db_fetch($dss_sessionQuery);
			$dss_sessionId = $dss_sessionCookie;
			$dss_userId = $dss_sessionResult["userId"];
			$dss_accessLevel = $dss_sessionResult["accessLevel"];
			$dss_numWorkgroups = $dss_sessionResult["numWorkgroups"];
			$dss_currentWorkgroup = $dss_sessionResult["currentWorkgroup"];
			$dss_currentProject = $dss_sessionResult["currentProject"];
			$dss_displayListSize = $dss_sessionResult["displayListSize"];
			db_query("UPDATE session SET timeout=".(date("U")+14400)." WHERE id='$dss_sessionCookie'");
			
		}
		
	}
	
?>
