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
	
	$projectId = $_REQUEST["projectId"];
	$projectQuery = db_query("SELECT name,workgroupId FROM project WHERE id=".escapeValue($projectId));
	if (db_numrows($projectQuery) == 1) $projectResult = db_fetch($projectQuery);
	$itemResult = db_fetch(db_query("SELECT count(id) AS numItems FROM item WHERE projectId=".escapeValue($projectId)));
	$numItems = $itemResult["numItems"];
	
	$required["name"] = "Project name";
	$dss_onLoad = "document.main.name.focus()";
		
	require "resources/header.php";

?>
				<h2>Edit Project</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<form action="project_update.php" method="post" name="main">
					<input type="hidden" name="projectId" value="<?php echo $projectId; ?>">
					<table>
						<tr>
							<td class="label">Project Name:</td>
							<td><input type="text" name="name" size="60" maxlength="255" value="<?php echo $projectResult["name"]; ?>"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td class="label">Workgroup:</td>
							<td>
								<select name="workgroupId">
<?php 

	if ($dss_accessLevel > 0) selectOptions("workgroup",$projectResult["workgroupId"],"name","id","name");
	else {
	
		$workgroupQuery = db_query("SELECT b.id,b.name FROM workgroupUser a, workgroup b WHERE a.userId=$dss_userId AND a.workgroupId=b.id");
		
		while ($workgroupResult = db_fetch($workgroupQuery)) {
		
?>
									<option value="<?php echo $workgroupResult["id"]; ?>"<?php if ($workgroupResult["id"] == $projectResult["workgroupId"]) echo " selected"; ?>><?php echo $workgroupResult["name"]; ?></option>
<?php

		}
	
	}
	
?>
								</select>
							</td>
						</tr>
						<tr><td colspan="2" style="padding: 30px 0 30px 0">Any information provided below will be applied to <b>all</b> items belonging to this project.<br><b>Use extreme caution!</b></td></tr>
						<tr>
							<td class="label">Retention Group:<br><a href="http://www.kent.edu/universitycounsel/records/index.cfm" target="_new"><small>More information</small></a></td>
							<td>
								<select name="retentionGroup">
									<option value=""> </option>
									<?php selectOptions("retention",$itemResult["retentionGroup"],"retentionGroup","retentionGroup","retentionGroup"); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label">Significant Item:</td>
							<td>
								<input type="checkbox" name="significant" value="1"> <small>If checked, the item will be reviewed for permanent archival at the end of its retention period.</small>
							</td>
						</tr>
						<tr>
							<td class="label">Description:</td>
							<td><textarea name="description" cols="40" rows="10" class="textFieldBackground"></textarea></td>
						</tr>
						<tr>
							<td class="label">Creation Date:</td>
							<td>
								<input type="text" name="creationDate" size="60" maxlength="255" value="<?php echo $itemResult["creationDate"]; ?>"><br><small>the date or date range on which the item was created.</small>
							</td>
						</tr>
						<tr>
							<td class="label">Creator:</td>
							<td><input type="text" name="creator" size="60" maxlength="255" value=""><br><small>the name of the creator of the item</small></td>
						</tr>
						<tr>
							<td class="label">Geographical Location:</td>
							<td><input type="text" name="location" size="60" maxlength="255" value=""><br><small>the geographical location at which the item was created</small></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<input type="submit" name="submit_button" value="Update" onClick="<?php addRequired($required); ?> return verify(document.main);">
								<input type="submit" name="cancel_button" value="Cancel">
<?php if ($numItems == 0) { ?>
								<input type="submit" name="delete_button" value="Delete" onClick="return confirm('This will permanently delete this project. This cannot be undone. Are you sure you want to delete this project?')">
<?php } ?>
							</td>
						</tr>
					</table>
				</form>
<?php

	require "resources/footer.php";
	
?>
