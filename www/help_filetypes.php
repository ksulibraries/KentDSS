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
				<h2>Acceptable File Types</h2>
				<a href="help_index.php"><img src="/resources/cancel.png" border="0" alt="Return to the help index" title="Return to the help index"></a>
				<table cellpadding="0" cellspacing="0">
					<tr><th><a href="help_filetypes.php?order=extension">Extension</a></th><th><a href="help_filetypes.php?order=mimeType">MIME Type</th></tr>
<?php

	if (trim($_REQUEST["order"]) == "mimeType") $query = db_query("SELECT * FROM filetype ORDER BY mimeType");
	else $query = db_query("SELECT * FROM filetype ORDER BY extension");
	
	while ($result = db_fetch($query)) {
	
?>
					<tr><td><?php echo $result["extension"]; ?></td><td><?php echo $result["mimeType"]; ?></td></tr>
<?php

	}
	
?>
				</table>
				<p><a href="/help_administration.php">Next topic: Administration</a></p>
<?php

	require "resources/footer.php";
	
?>