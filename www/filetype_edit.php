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
	
	$filetypeId = $_REQUEST["filetypeId"];
	$allowDelete = 0;
		
	if (trim($filetypeId) != "") {
	
		$filetypeQuery = db_query("SELECT * FROM filetype WHERE id=".escapeValue($filetypeId));
		
		if (db_numrows($filetypeQuery) != 1) {
		
			header("Location: /filetype_list.php?errorMessage=".rawurlencode("Unable to access that filetype."));
			exit;
			
		}
		
		$filetypeResult = db_fetch($filetypeQuery);
		
		/* See it there are items using this filetype. */
		
		$itemQuery = db_query("SELECT id FROM item WHERE filetype=".escapeQuote($filetypeResult["extension"]));		
		if (db_numrows($itemQuery) == 0) $allowDelete = 1;

	}
	
	if (trim($filetypeId) == "") $submitButton = "Add";
	else $submitButton = "Update";

	$required["extension"] = "Extension";
	$required["mimeType"] = "MIME Type";
	$dss_onLoad = "document.main.extension.focus()";
		
	require "resources/header.php";

?>
				<h2>Filetype Edit</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<form action="filetype_update.php" method="post" name="main">
					<input type="hidden" name="filetypeId" value="<?php echo $filetypeId; ?>">
					<table>
						<tr>
							<td>Extension:</td>
							<td><input type="text" name="extension" size="40" maxlength="255" value="<?php echo $filetypeResult["extension"]; ?>"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td>MIME Type:</td>
							<td><input type="text" name="mimeType" size="40" maxlength="255" value="<?php echo $filetypeResult["mimeType"]; ?>"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<input type="submit" name="submit_button" value="<?php echo $submitButton; ?>" onClick="<?php addRequired($required); ?> return verify(document.main);">
								<input type="submit" name="cancel_button" value="Cancel">
<?php if ($allowDelete) { ?>
								<input type="submit" name="delete_button" value="Delete" onClick="return confirm('This will permanently delete this filetype. This cannot be undone. Do you want to delete this filetype?')">
<?php } ?>
							</td>
						</tr>
					</table>
<?php if (!$allowDelete && trim($filetypeId) != "") { ?>
					<p><small>There are items using this filetype. Only filetypes that are not being used may be deleted.</small></p>
<?php } ?>
				</form>
<?php

	require "resources/footer.php";
	
?>