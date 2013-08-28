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
	
	if (trim($_REQUEST["submit_button"]) != "") {
	
		superstore_save("report_audit_list",$dss_sessionCookie,"action",$_REQUEST["action"]);
		superstore_save("report_audit_list",$dss_sessionCookie,"actionByUserId",$_REQUEST["actionByUserId"]);
		superstore_save("report_audit_list",$dss_sessionCookie,"workgroupName",$_REQUEST["workgroupName"]);
		superstore_save("report_audit_list",$dss_sessionCookie,"projectName",$_REQUEST["projectName"]);
		
	} elseif (trim($_REQUEST["reset_button"]) != "") {
	
		superstore_save("report_audit_list",$dss_sessionCookie,"action","");
		superstore_save("report_audit_list",$dss_sessionCookie,"actionByUserId","");
		superstore_save("report_audit_list",$dss_sessionCookie,"workgroupName","");
		superstore_save("report_audit_list",$dss_sessionCookie,"projectName","");
		
	}

	$action = superstore_fetch("report_audit_list",$dss_sessionCookie,"action");
	$actionByUserId = superstore_fetch("report_audit_list",$dss_sessionCookie,"actionByUserId");
	$workgroupName = superstore_fetch("report_audit_list",$dss_sessionCookie,"workgroupName");
	$projectName = superstore_fetch("report_audit_list",$dss_sessionCookie,"projectName");

	require "resources/header.php";

?>
				<h2>Audit Log Review</h2>
				<a href="report_list.php"><img src="/resources/cancel.png" border="0" alt="Return to the report list" title="Return to the report list"></a>
				<form action="report_audit_list.php" method="post" style="padding: 10px 0 10px 0">
					Limit to
					<select name="action">
						<option value="">Any</option>
<?php

	$query = db_query("SELECT action FROM auditlog GROUP BY action ORDER BY action");
	
	while ($result = db_fetch($query)) {
	
?>
						<option value="<?php echo $result["action"]; ?>"<?php if ($result["action"] == $action) echo " selected"; ?>><?php echo $result["action"]; ?></option>
<?php

	}
	
?>
					</select>
					by
					<select name="actionByUserId">
						<option value="">Any</option>
<?php

	$userArray = array();
	$query = db_query("SELECT actionByUserId FROM auditlog");
	
	while ($result = db_fetch($query)) $userArray[$result["actionByUserId"]] = fullNameById($result["actionByUserId"],1);
	asort($userArray);
	
	foreach ($userArray as $userId=>$name) {
		
?>
						<option value="<?php echo $userId; ?>"<?php if ($userId == $actionByUserId) echo " selected"; ?>><?php echo $name; ?></option>
<?php

	}
	
?>
					</select>
					in workgroup
					<select name="workgroupName">
						<option value="">Any</option>
<?php

	$query = db_query("SELECT workgroupName FROM auditlog GROUP BY workgroupName ORDER BY workgroupName");
	
	while ($result = db_fetch($query)) {
	
?>
						<option value="<?php echo $result["workgroupName"]; ?>"<?php if ($result["workgroupName"] == $workgroupName) echo " selected"; ?>><?php echo $result["workgroupName"]; ?></option>
<?php

	}
	
?>
					</select>
					in project
					<select name="projectName">
						<option value="">Any</option>
<?php

	$query = db_query("SELECT projectName FROM auditlog GROUP BY projectName ORDER BY projectName");
	
	while ($result = db_fetch($query)) {
	
?>
						<option value="<?php echo $result["projectName"]; ?>"<?php if ($result["projectName"] == $projectName) echo " selected"; ?>><?php echo $result["projectName"]; ?></option>
<?php

	}
	
?>
					</select><br><br>
					<input type="submit" name="submit_button" value="Display">
					<input type="submit" name="reset_button" value="Reset">
					You must select one limit to display results.
				</form>
				<table class="listing">
					<tr><th>Action</th><th>Action Date</th><th>Action By</th><th>Workgroup</th><th>Project</th><th>Filename</th></tr>
<?php

	$where = array();
	if (trim($action) != "") $where[] = "action=".escapeQuote($action);
	if (trim($actionByUserId) != "") $where[] = "actionByUserId=".escapeValue($actionByUserId);
	if (trim($workgroupName) != "") $where[] = "workgroupName=".escapeQuote($workgroupName);
	if (trim($projectName) != "") $where[] = "projectName=".escapeQuote($projectName);
	
	if (sizeof($where) > 0) {
	
		$sql = "SELECT id,action,actionDate,actionByUserId,workgroupName,projectName,filename FROM auditlog WHERE ".implode(" AND ",$where)." ORDER BY actionDate DESC";
		//echo $sql;
		$auditQuery = db_query($sql);
	
		while ($auditResult = db_fetch($auditQuery)) {
	
			$userResult = db_fetch(db_query("SELECT firstName,lastName FROM user WHERE id=".$auditResult["actionByUserId"]))
?>
					<tr>
						<td><?php echo $auditResult["action"]; ?></td>
						<td><?php echo date("Y-m-d",$auditResult["actionDate"]); ?></td>
						<td><?php echo $userResult["firstName"]." ".$userResult["lastName"]; ?></td>
						<td><?php echo $auditResult["workgroupName"]; ?></td>
						<td><?php echo $auditResult["projectName"]; ?></td>
						<td><a href="report_audit_detail.php?logId=<?php echo $auditResult["id"]; ?>"><?php echo $auditResult["filename"]; ?></a></td>
					</tr>
<?php

		}
		
	}
	
?>
				</table>
<?php

	require "resources/footer.php";
	
?>