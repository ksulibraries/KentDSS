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
				<h2>Projects</h2>
				<a href="help_index.php"><img src="/resources/cancel.png" border="0" alt="Return to the help index" title="Return to the help index"></a>
				<p>Projects provide a way to collect items into logical groups. A project belongs to a single workgroup, so is only editable by members of that workgroup. All members of a workgroup can add and edit items in the projects that belong to that workgroup. There is no way to restrict access by member.</p>
				<p>File names may be duplicated between projects, i.e. the same file name may appear in different projects, however, file names cannot be duplicated within a project. If a file name already exists for a project, an error message will be displayed indicating this.</p>
				<p><img src="/resources/add.png" border="0" align="left"> Items can be added individually by clicking the <i>Add new items</i> button or by uploading a <a href="help_ziparchive.php">ZIP Archive</a> file. Item file types are limited to those listed on the <a href="help_filetypes.php">Acceptable File Types</a> page. Only basic metadata, such as file name and file size, will be added to the items when they are uploaded. Edit individual items by clicking on the item title on the Item List page. To make changes to all of the items in a project at once, use the method described below.</p>
				<p>For items with an image file type, e.g. JPG, several pieces of metadata will be harvested from the image's <a href="http://en.wikipedia.org/wiki/IPTC_Information_Interchange_Model" target="_new">IPTC</a> metadata if present.  These metadata are: 
					<table>
						<tr>
							<th>Label</th>
							<th>IPTC Name</th>
							<th>IPTC Property</th>
						</tr>
						<tr>
							<td>Title</td>
							<td>Title</td>
							<td>2:05 Object Name</td>
						</tr>
						<tr>
							<td>Description</td>
							<td>Description</td>
							<td>2:120 Caption/Abstract</td>
						</tr>
						<tr>
							<td>Creator</td>
							<td>Creator</td>
							<td>2:80 By-line</td>
						</tr>
						<tr>
							<td>Creation Date</td>
							<td>Date Created</td>
							<td>2:55 Date Created</td>
						</tr>
						<tr>
							<td>Geographical Location</td>
							<td>Sublocation</td>
							<td>2:92 Sublocation</td>
						</tr>
					</table>
				</p>
				<p><img src="/resources/pencil.png" border="0" align="left"> Changes to metadata can be applied to all items in a project by editing the project. On the Item List page, click the <i>Edit this project</i> button edit the project. The name of the project may be edited here. Additionally, many of the user-supplied item metadata fields are available for editing. If information is present in these fields, it is applied to <b>all</b> items in the project. This will over-write any existing metadata and cannot be undone. Use extreme caution when applying metadata in this way.</p>
				<p><a href="/help_ziparchives.php">Next topic: ZIP Archives</a></p>
<?php

	require "resources/footer.php";
	
?>