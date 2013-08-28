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
	
	$required["name"] = "Project name";
	$dss_onLoad = "document.main.name.focus()";
		
	require "resources/header.php";

?>
				<h2>Project Add</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<form action="project_insert.php" method="post" name="main">
					<table>
						<tr>
							<td>Project Name:</td>
							<td><input type="text" name="name" size="40" maxlength="255" value=""><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<input type="submit" name="submit_button" value="Add" onClick="<?php addRequired($required); ?> return verify(document.main);">
								<input type="submit" name="cancel_button" value="Cancel">
							</td>
						</tr>
					</table>
				</form>
<?php

	require "resources/footer.php";
	
?>