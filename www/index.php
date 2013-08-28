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

	if ($dss_userId > 0) {
	
		if ($dss_accessLevel > 0 || $dss_numWorkgroups > 1) {
		
			header("Location: /workgroup_list.php");
			exit;
			
		} else {
		
			header("Location: /project_list.php");
			exit;
			
		}
		
	}

	$required["username"] = "Username";
	$required["password"] = "Password";
	$dss_onLoad = "document.main.username.focus()";
		
	require "resources/header.php";

?>
				<h2>Login</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<form action="login_process.php" method="post" name="main">
					<table>
						<tr>
							<td><?php echo $dss_loginUsernameText; ?>:</td>
							<td><input type="text" name="username" size="40" maxlength="255" value=""><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td><?php echo $dss_loginPasswordText; ?>:</td>
							<td><input type="password" name="password" size="40" maxlength="255" value=""><span class="requiredField">*</span></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" name="submit_button" value="Login" onClick="<?php addRequired($required); ?> return verify(document.main);"></td>
						</tr>
					</table>
					<p>Forgot your password? <a href="<?php echo $dss_loginPasswordHelpURL; ?>" target="_helpdesk">Read what you should do.</a></p>
				</form>
<?php

	require "resources/footer.php";
	
?>