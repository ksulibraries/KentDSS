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

	if ($dss_userId == 0 || $dss_currentWorkgroup == 0 || $dss_currentProject == 0) {
	
		header("Location: /index.php");
		exit;
		
	}
	
	$projectId = $_REQUEST["projectId"];
	$projectQuery = db_query("SELECT name FROM project WHERE id=".escapeValue($projectId));
	if (db_numrows($projectQuery) == 1) $projectResult = db_fetch($projectQuery);
	
	$dss_javascriptLibraries = array("/resources/fileloader.js");		
	require "resources/header.php";

?>
				<h2>Add Items to <?php echo $projectResult["name"]; ?></h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<a href="item_list.php"><img src="/resources/cancel.png" border="0" alt="Return to item list" title="Return to item list"></a>
				<form name="main" action="item_list.php" method="post">
					<input type="hidden" name="projectId" value="<?php echo $dss_currentProject; ?>">
				</form>
				<p>To upload one or more files, click on the button below. Drag-and-drop is supported in Firefox and Chrome.</p>
				<p>The progress-bar is supported in Firefox, Chrome, and Safari.</p>
				<p>The maximum file size supported is 2 GB.</p>
				<div id="file-uploader">		
				</div>
				<script>        
					function createUploader(){            
						var uploader = new qq.FileUploader({
							element: document.getElementById('file-uploader'),
							action: 'file_adder.php',
							debug: true
						});           
					}
					
					// in your app create uploader as soon as the DOM is ready
					// don't wait for the window to load  
					window.onload = createUploader;     
				</script>    
<?php

	require "resources/footer.php";
	
?>