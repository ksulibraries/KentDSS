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
	
	/* Can this user edit this item. */
	
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

	$itemQuery = db_query("SELECT * FROM item WHERE id=".escapeValue($itemId));
		
	if (db_numrows($itemQuery) == 1) $itemResult = db_fetch($itemQuery);
	else {
	
		header("Location: /index.php");
		exit;
		
	}
	
	$required["title"] = "Item title";
	$required["retentionGroup"] = "Retention group";
	$required["creationDate"] = "Creation date";
	$dss_onLoad = "document.main.title.focus()";
		
	require "resources/header.php";

?>
				<h2>Edit Item</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<form action="item_update.php" method="post" name="main">
					<input type="hidden" name="itemId" value="<?php echo $itemId; ?>">
					<input type="hidden" name="search" value="<?php echo $_REQUEST["search"]; ?>">
					<table>
						<tr>
							<td class="label">Item Title:</td>
							<td><input type="text" name="title" size="60" maxlength="255" value="<?php echo $itemResult["title"]; ?>"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td class="label">Project:</td>
							<td>
								<select name="projectId">
									<?php selectOptions("project",$itemResult["projectId"],"name","id","name","workgroupId=$dss_currentWorkgroup"); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label">Retention Group:<br><a href="http://www.kent.edu/universitycounsel/records/index.cfm" target="_new"><small>More information</small></a></td>
							<td>
								<select name="retentionGroup">
									<option value="">--- Select One ---</option>
									<?php selectOptions("retention",$itemResult["retentionGroup"],"retentionGroup","retentionGroup","retentionGroup"); ?>
								</select><span class="requiredField">*</span>
							</td>
						</tr>
						<tr>
							<td class="label">Significant Item:</td>
							<td>
								<input type="checkbox" name="significant" value="1"<?php if ($itemResult["significant"]) echo " checked"; ?>> <small>If checked, the item will be reviewed for permanent archival at the end of its retention period.<br>Some items may be required to be reviewed before deletion. <a href="http://www.kent.edu/universitycounsel/records/index.cfm" target="_new">More information</a></small>
							</td>
						</tr>
						<tr>
							<td class="label">Description:</td>
							<td><textarea name="description" cols="40" rows="10" class="textFieldBackground"><?php echo $itemResult["description"]; ?></textarea></td>
						</tr>
						<tr>
							<td class="label">Creation Date:</td>
							<td>
								<input type="text" name="creationDate" size="60" maxlength="255" value="<?php echo $itemResult["creationDate"]; ?>"><span class="requiredField">*</span><br><small>the date or date range on which the item was created.</small>
							</td>
						</tr>
						<tr>
							<td class="label">Creator:</td>
							<td><input type="text" name="creator" size="60" maxlength="255" value="<?php echo $itemResult["creator"]; ?>"><br><small>the name of the creator of the item</small></td>
						</tr>
						<tr>
							<td class="label">Geographical Location:</td>
							<td><input type="text" name="location" size="60" maxlength="255" value="<?php echo $itemResult["location"]; ?>"><br><small>the geographical location at which the item was created</small></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<input type="submit" name="submit_button" value="Update" onClick="<?php addRequired($required); ?> return verify(document.main);">
								<input type="submit" name="cancel_button" value="Cancel">
<?php if ($dss_accessLevel > 1) { ?>
								<input type="submit" name="delete_button" value="Delete">
<?php } ?>
							</td>
						</tr>
					</table>
				</form>
<?php

	require "resources/footer.php";
	
?>
