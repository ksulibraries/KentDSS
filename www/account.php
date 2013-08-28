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
	
	$userResult = db_fetch(db_query("SELECT * FROM user WHERE id=$dss_userId"));

	$required["firstName"] = "First name";
	$required["lastName"] = "Last name";
	$required["displayListSize"] = "Display List Size";
	$dss_onLoad = "document.main.firstName.focus()";
		
	require "resources/header.php";

?>
				<h2>Account</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<form action="account_update.php" method="post" name="main">
					<table>
						<tr>
							<td>First Name:</td>
							<td><input type="text" name="firstName" size="40" maxlength="255" value="<?php echo $userResult["firstName"]; ?>"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td>Last Name:</td>
							<td><input type="text" name="lastName" size="40" maxlength="255" value="<?php echo $userResult["lastName"]; ?>"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td>Display List Size:</td>
							<td><input type="text" name="displayListSize" size="4" maxlength="3" value="<?php echo $userResult["displayListSize"]; ?>"><span class="requiredField">*</span> <small>Must be at least ten.</small></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" name="submit_button" value="Update Account Information" onClick="<?php addRequired($required); ?> return verify(document.main);"></td>
						</tr>
					</table>
				</form>
<?php

	require "resources/footer.php";
	
?>