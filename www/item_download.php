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
	
	$itemId = $_REQUEST["itemId"];
	
	/* Can this user download this item. */
	
	if ($dss_accessLevel == 0) {
	
		$workgroupQuery = db_query("SELECT b.workgroupId FROM item a, project b WHERE a.id=".escapeValue($itemId)." AND a.projectId=b.id");
		if (db_numrows($workgroupQuery) == 1) $workgroupResult = db_fetch($workgroupQuery);
		else {
		
			header("Location: /index.php");
			exit;
			
		}
	
		$accessQuery = db_query("SELECT workgroupId FROM workgroupUser WHERE workgroupId=".$workgroupResult["workgroupId"]." AND userId=$dss_userId");
	
		if (db_numrows($accessQuery) == 0) {
		
			header("Location: /index.php");
			exit;
			
		}
	
	}

	$itemQuery = db_query("SELECT filename FROM item WHERE id=".escapeValue($itemId));
		
	if (db_numrows($itemQuery) == 1) $itemResult = db_fetch($itemQuery);
	else {
	
		header("Location: /index.php");
		exit;
		
	}

	$filename = $itemResult["filename"];
	$fullPath = "/data/files/$dss_currentProject/$filename";
	
	if ($fp = fopen($fullPath,"r")) {

		header("Cache-Control: ");# leave blank to avoid IE errors
		header("Pragma: ");# leave blank to avoid IE errors
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-length:".(string)(filesize($fullPath)));
		sleep(1);
		fpassthru($fp);

		exit;

	}
	
?>
