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
	
	require "resources/header.php";

?>
				<h2>Admin Tools</h2>
				<table>
<?php if ($dss_accessLevel > 1) { ?>
					<tr><td><a href="filetype_list.php">Filetypes</a></td></tr>
<?php } ?>
					<tr><td><a href="report_list.php">Reports</a></td></tr>
					<tr><td><a href="group_list.php">Retention Groups</a></td></tr>
<?php if ($dss_accessLevel > 1) { ?>
					<tr><td><a href="user_list.php">Users</a></td></tr>
<?php } ?>
				</table>
<?php

	require "resources/footer.php";
	
?>