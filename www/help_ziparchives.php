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
				<h2>ZIP Archives</h2>
				<a href="help_index.php"><img src="/resources/cancel.png" border="0" alt="Return to the help index" title="Return to the help index"></a>
				<p>A ZIP Archive is a collection of items and, optionally, a metadata file that has been bundled into a ZIP file, i.e. using a program to archive the items into a single .zip file. The file types of the files included in the ZIP Archive must be on the <a href="help_filetypes.php">Acceptable File Types</a> list. Other file types will not be stored. Do not include sub-directories. Files in sub-directories will not stored.</p>
				<p>Only basic metadata, such as file type and file size, will be added for the items in the ZIP Archive. For items with an image file type, e.g. JPG, additional metadata may be harvested from the item itself. See the <a href="help_projects.php">Projects</a> help page for details. In order to have more complete metadata automatically added for the items in the ZIP Archive, include a <i>metadata.txt</i> file with the items. The <i>metadata.txt</i> will be read and the metadata applied to the items during the upload process.</p>
				<p>The <i>metadata.txt</i> file <b>must</b> be a tab-delimited text file with a specific format. The simplest way to create this file is using a spreadsheet program like Excel. Once the metadata information has been entered, save the spreadsheet as a tab-delimited text file. The <i>metadata.txt</i> file <b>must</b> have the following format:</p>
				<p><b>Row 1</b> of the file must contain the metadata field names in separate columns. The metadata fields supported are:
				<ul>
					<li><b>filename</b> - the physical file name of the item</li>
					<li><b>title</b> - the title of the item</li>
					<li><b>group</b> - the retention group assigned to the item</li>
					<li><b>description</b> - a description of the item</li>
					<li><b>creator</b> - the name of the person who created this item</li>
					<li><b>date</b> - the date on which the item was created</li>
					<li><b>location</b> - the geographical location at which the item was created</li>
				</ul>
				The fields can appear in any order. Only the <b>filename</b> field is required to be present since this is used to match the files that have been uploaded. All other fields are optional.
				<p><b>Row 2 through the end of the file</b> will contain the actual metadata for each item. The metadata will be applied to the item with the matching file name.</p>
				<h3>How to upload a ZIP Archive</h3>
				<p><img src="/resources/zip.png" border="0" align="left"> On the Item List page, click the <i>Add a ZIP Archive</i> button to start the process. Select the ZIP Archive file to upload. Only select one file at a time. The file size must be less than 2 GB. Once uploaded, the ZIP Archive file will be un-zipped into the project directory. Each file present will be added to the storage system with basic metadata. The file name of each file will be used as the title of the item. If a <i>metadata.txt</i> file is present, it will be read and the metadata included will be added to the database. The <i>metadata.txt</i> will be deleted once it has been processed.</p>
				<p><a href="/help_filetypes.php">Next topic: Acceptable File Types</a></p>
<?php

	require "resources/footer.php";
	
?>