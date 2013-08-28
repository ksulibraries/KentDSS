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
	
	$userId = $_REQUEST["userId"];
	
	if (trim($userId) != "") {
	
		$userQuery = db_query("SELECT * FROM user WHERE id=".escapeValue($userId));
		
		if (db_numrows($userQuery) != 1) {
		
			header("Location: /user_list.php?errorMessage=".rawurlencode("Unable to access that user."));
			exit;
			
		}
		
		$userResult = db_fetch($userQuery);

	}
	
	if (trim($userId) == "") $submitButton = "Add";
	else $submitButton = "Update";

	$required["firstName"] = "First name";
	$required["lastName"] = "Last name";
	$required["username"] = "Username";
	$required["emailAddress"] = "Email address";
	$dss_onLoad = "document.main.firstName.focus()";
		
	require "resources/header.php";

?>
				<h2>User Edit</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<form action="user_update.php" method="post" name="main">
					<input type="hidden" name="userId" value="<?php echo $userId; ?>">
					<table>
						<tr>
							<td>Active:</td>
							<td><input type="checkbox" name="active" value="1"<?php if ($userResult["active"]) echo " checked"; ?>> <small>Uncheck to prevent this user from using the system.</small></td>
						</tr>
						<tr>
							<td>First Name:</td>
							<td><input type="text" name="firstName" size="40" maxlength="255" value="<?php echo $userResult["firstName"]; ?>"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td>Last Name:</td>
							<td><input type="text" name="lastName" size="40" maxlength="255" value="<?php echo $userResult["lastName"]; ?>"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td>Username:</td>
							<td><input type="text" name="username" size="40" maxlength="255" value="<?php echo $userResult["username"]; ?>"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td>Email Address:</td>
							<td><input type="text" name="emailAddress" size="40" maxlength="255" value="<?php echo $userResult["emailAddress"]; ?>"><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td>Access Level:</td>
							<td>
								<select name="accessLevel">
									<option value="0"<?php if ($userResult["accessLevel"] == 0) echo " selected"; ?>>Contributor</option>
									<option value="1"<?php if ($userResult["accessLevel"] == 1) echo " selected"; ?>>Admin</option>
									<option value="2"<?php if ($userResult["accessLevel"] == 2) echo " selected"; ?>>Super Admin</option>
								</select>
							</td>
						</tr>
						<tr>
							<td valign="top">Workgroup(s):</td>
							<td>
								<table>
<?php

	$workgroupQuery = db_query("SELECT id,name FROM workgroup ORDER BY name");
	
	while ($workgroupResult = db_fetch($workgroupQuery)) {
	
		$checkQuery = db_query("SELECT workgroupId FROM workgroupUser WHERE workgroupId=".$workgroupResult["id"]." AND userId=".escapeValue($userId));
		if (db_numrows($checkQuery) == 1) $member = 1;
		else $member = 0;

?>
									<tr>
										<td><?php echo $workgroupResult["name"]; ?></td>
										<td><input type="checkbox" name="workgroupId[<?php echo $workgroupResult["id"]; ?>]" value="1"<?php if ($member) echo " checked"; ?>></td>
									</tr>
<?php

	}
	
?>
								</table>
							</td>
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