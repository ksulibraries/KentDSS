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
	
	$groupId = $_REQUEST["groupId"];
	$allowDelete = 0;
		
	if (trim($groupId) != "") {
	
		$groupQuery = db_query("SELECT * FROM retention WHERE id=".escapeValue($groupId));
		
		if (db_numrows($groupQuery) != 1) {
		
			header("Location: /group_list.php?errorMessage=".rawurlencode("Unable to access that group."));
			exit;
			
		}
		
		$groupResult = db_fetch($groupQuery);
		
		/* See it there are items using this retention group. */
		
		$itemQuery = db_query("SELECT id FROM item WHERE retentionGroup=".escapeQuote($groupResult["retentionGroup"]));		
		if (db_numrows($itemQuery) == 0) $allowDelete = 1;

	}
	
	if (trim($groupId) == "") $submitButton = "Add";
	else $submitButton = "Update";

	$required["retentionGroup"] = "Retention group";
	$required["retentionPeriod"] = "Retention period";
	$dss_onLoad = "document.main.retentionGroup.focus()";
		
	require "resources/header.php";

?>
				<h2>Retention Group Edit</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<form action="group_update.php" method="post" name="main">
					<input type="hidden" name="groupId" value="<?php echo $groupId; ?>">
					<table>
						<tr>
							<td>Retention Group:</td>
							<td><input type="text" name="retentionGroup" size="40" maxlength="255" value="<?php echo $groupResult["retentionGroup"]; ?>"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td>Retention Period:</td>
							<td>
								<select name="retentionPeriod">
									<option value="">--- Select One ---</option>
									<?php echo selectNumbers(1,$dss_maximumRetentionPeriod,$groupResult["retentionPeriod"]); ?>
									<option value="999"<?php if (intval($groupResult["retentionPeriod"]) == 999) echo " selected"; ?>>Indefinite</option>
								</select><span class="requiredField">*</span>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<input type="submit" name="submit_button" value="<?php echo $submitButton; ?>" onClick="<?php addRequired($required); ?> return verify(document.main);">
								<input type="submit" name="cancel_button" value="Cancel">
<?php if ($allowDelete) { ?>
								<input type="submit" name="delete_button" value="Delete" onClick="return confirm('This will permanently delete this retention group. This cannot be undone. Do you want to delete this retention group?')">
<?php } ?>
							</td>
						</tr>
					</table>
<?php if (!$allowDelete && trim($groupId) != "") { ?>
					<p><small>There are items using this retention group. Only retention groups that are not being used may be deleted.</small></p>
<?php } ?>
				</form>
<?php

	require "resources/footer.php";
	
?>