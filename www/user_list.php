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
		
	/* Keep track of the char the user is viewing. */
	
	if (trim($_REQUEST["char"]) != "") superstore_save("user_list.php",$dss_sessionCookie,"char",$_REQUEST["char"]);
	$char = superstore_fetch("user_list.php",$dss_sessionCookie,"char");

	require "resources/header.php";

?>
				<h2>Users</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<a href="admin.php"><img src="/resources/cancel.png" border="0" alt="Return to admin tools" title="Return to admin tools"></a>
				<a href="user_edit.php"><img src="/resources/add.png" border="0" alt="Add new user" title="Add new user"></a>
				<?php $char = displayAlphaBar("user","lastName","",$char,$dss_displayListSize); ?>
				<table class="listing">
					<tr><th>Name</th><th>Access Level</th><th>Last Login</th></tr>
<?php

	$where = "";
	if ($char == "#") $where = " WHERE left(lastName,1)>='0' AND left(lastName,1)<='9'";
	elseif ($char != "all") $where = " WHERE left(lastName,1)='$char'";
	$userQuery = db_query("SELECT id,active,accessLevel,lastName,firstName,lastLoginDate FROM user $where ORDER BY lastName,firstName");

	while ($userResult = db_fetch($userQuery)) {
		
		$name = $userResult["lastName"].", ".$userResult["firstName"];
		if (!$userResult["active"]) $name = "<strike>".$userResult["lastName"].", ".$userResult["firstName"]."</strike>";
		if ($userResult["lastLoginDate"] == 0) $lastLogin = "Never";
		else $lastLogin = date($dss_dateFormat[2],$userResult["lastLoginDate"]);
		
?>
					<tr>
						<td><a href="user_edit.php?userId=<?php echo $userResult["id"]; ?>"><?php echo $name; ?></a></td>
						<td><?php echo $dss_accessLevelArray[$userResult["accessLevel"]]; ?></td>
						<td><?php echo $lastLogin; ?></td>
					</tr>		
<?php

	}
		
?>
				</table>	
<?php

	require "resources/footer.php";
	
?>