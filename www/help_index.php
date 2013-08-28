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
	
	require "resources/header.php";

?>
				<h2>Help</h2>
				<table class="listing">
					<tr><td><a href="/help_overview.php">Overview</a></td></tr>
					<tr><td><a href="/help_items.php">Items</a></td></tr>
					<tr><td><a href="/help_projects.php">Projects</a></td></tr>
					<tr><td><a href="/help_ziparchives.php">ZIP Archives</a></td></tr>
					<tr><td><a href="/help_filetypes.php">Acceptable File Types</a></td></tr>
					<tr><td><a href="/help_administration.php">Administration</a></td></tr>
				</table>
<?php

	require "resources/footer.php";
	
?>