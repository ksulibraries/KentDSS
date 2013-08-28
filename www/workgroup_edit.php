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
	
	$workgroupId = $_REQUEST["workgroupId"];
	if (trim($workgroupId) == "") $submitButton = "Add";
	else {
	
		$workgroupQuery = db_query("SELECT name FROM workgroup WHERE id=".escapeValue($workgroupId));
		if (db_numrows($workgroupQuery) == 1) $workgroupResult = db_fetch($workgroupQuery);
		$submitButton = "Update";

	}
	
	$required["name"] = "Workgroup name";
	$dss_onLoad = "document.main.name.focus()";
		
	require "resources/header.php";

?>
				<h2>Workgroup Edit</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<form action="workgroup_update.php" method="post" name="main">
					<input type="hidden" name="workgroupId" value="<?php echo $workgroupId; ?>">
					<table>
						<tr>
							<td>Workgroup Name:</td>
							<td><input type="text" name="name" size="40" maxlength="255" value="<?php echo $workgroupResult["name"]; ?>"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<input type="submit" name="submit_button" value="<?php echo $submitButton; ?>" onClick="<?php addRequired($required); ?> return verify(document.main);">
								<input type="submit" name="cancel_button" value="Cancel">
							</td>
						</tr>
					</table>
				</form>
<?php

	require "resources/footer.php";
	
?>