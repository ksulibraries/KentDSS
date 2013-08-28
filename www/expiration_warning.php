#!/usr/bin/php
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

	require "resources/inc.utilities.php";
	require "resources/inc.config.php";
	db_connect("dss");
	
	$secondsPerDay = 86400;

	/* Send warning messages with the following lead times. */
	
	$daysArray = array(90,60,30,15);
	
	foreach ($daysArray as $level=>$days) {
	
		/* Get the projects that have items at this warn level and are expiring within this lead time. */
		
		$workgroupArray = array();
		$itemQuery = db_query("SELECT count(id) AS itemCount, projectId FROM item WHERE warnLevel=$level AND expirationDate<".(date("U")+$days*$secondsPerDay)." GROUP BY projectId");
	
		while ($itemResult = db_fetch($itemQuery)) {
	
			$projectResult = db_fetch(db_query("SELECT workgroupId,name FROM project WHERE id=".$itemResult["projectId"]));
			$workgroupArray[$projectResult["workgroupId"]][$projectResult["name"]] = $itemResult["itemCount"];
	
		}
	
		foreach ($workgroupArray as $workgroupId=>$projectNameArray) {
	
			/* Build an email message for this workgroup. */
			
			$workgroupResult = db_fetch(db_query("SELECT id,name FROM workgroup WHERE id=$workgroupId"));
			$message = "Projects with files expiring in $days days in the ".$workgroupResult["name"]." Workgroup:\n\n";
			foreach ($projectNameArray as $name=>$itemCount) $message .= "   $name ($itemCount items)\n";
			
			/* Get the active users in this workgroup. */
			
			$userArray = array();
			$userQuery = db_query("SELECT b.emailAddress FROM workgroupUser a, user b WHERE a.workgroupId=$workgroupId AND a.userId=b.id AND b.active=1");
			while ($userResult = db_fetch($userQuery)) $userArray[] = $userResult["emailAddress"];
			
			/* Send the message to the administrator and all active users in this workgroup. */
			
			mail($dss_administratorEmail.",".implode(",",$userArray),"$days Day Storage Pod Expiration Warning",$message);

		}
	
		/* Move the notified items up one warn level. */
		
		db_query("UPDATE item SET warnLevel=warnLevel+1 WHERE warnLevel=$level AND expirationDate<".(date("U")+$days*$secondsPerDay));

	}
		
	/* Remove expired items.  WHERE warnLevel=4 AND expirationDate<".date("U") */
	
	
?>