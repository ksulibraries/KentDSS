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
	
	$itemId = $_REQUEST["itemId"];
	$itemQuery = db_query("SELECT * FROM item WHERE id=".escapeValue($itemId));
		
	if (db_numrows($itemQuery) == 1) $itemResult = db_fetch($itemQuery);
	else {
	
		header("Location: /index.php");
		exit;
		
	}
	
	/* Get the project and workgroup for this item. */
	
	$projectResult = db_fetch(db_query("SELECT workgroupId,name FROM project WHERE id=".$itemResult["projectId"]));
	$workgroupResult = db_fetch(db_query("SELECT name FROM workgroup WHERE id=".$projectResult["workgroupId"]));
	
	$required["comment"] = "The reason to delete";
	$required["password"] = "Your password";
	$dss_onLoad = "document.main.comment.focus()";
		
	require "resources/header.php";

?>
				<h2>Delete Item</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<p>You are about to delete the following item. Once the item has been deleted there is no way to recover the item. Before you proceed, please verify that this item should be deleted.</p>
				<table cellpadding="0" cellspacing="0" border="0">
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Item Title:</td>
						<td class="data"><?php echo $itemResult["title"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Project:</td>
						<td class="data"><a href="item_list.php?projectId=<?php echo $itemResult["projectId"]; ?>"><?php echo $projectResult["name"]; ?></a></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Workgroup:</td>
						<td class="data"><a href="project_list.php?workgroupId=<?php echo $projectResult["workgroupId"]; ?>"><?php echo $workgroupResult["name"]; ?></a></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">File Name:</td>
						<td class="data"><?php echo $itemResult["filename"]; ?></td>
					</tr>
				</table>
				<form action="item_delete_process.php" method="post" name="main">
					<input type="hidden" name="itemId" value="<?php echo $itemId; ?>">
					<input type="hidden" name="search" value="<?php echo $_REQUEST["search"]; ?>">
					<table>
						<tr>
							<td class="label">Reason to delete:</td>
							<td><input type="text" name="comment" size="80" maxlength="255" value="" autocomplete="off"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td class="label">Your password:</td>
							<td><input type="password" name="adminPassword" size="80" maxlength="255" value="" autocomplete="off"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<input type="submit" name="delete_button" value="Delete" onClick="<?php addRequired($required); ?> return verify(document.main);">
								<input type="submit" name="cancel_button" value="Cancel">
							</td>
						</tr>
					</table>
				</form>
<?php

	require "resources/footer.php";
	
?>
