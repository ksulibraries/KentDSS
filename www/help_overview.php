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
				<h2>Overview</h2>
				<a href="help_index.php"><img src="/resources/cancel.png" border="0" alt="Return to the help index" title="Return to the help index"></a>
				<p>The digital storage system provides a centralized location for the medium-term storage of digital objects -- known as <i>items</i>. Items can be images, movies, documents, or other digital assets, however, only specific file types are permitted. The <a href="help_filetypes.php">Acceptable File Types</a> page lists which file types can be stored by the system.</p>
				<p>Items are assigned an expiration date based on the retention period for the item. The retention period for an item is based on its assigned Retention Group. A warning message is emailed prior to the expiration date so that items can be reviewed before being deleted. An item may be identified as a <i>significant</i> item if there is some reason it should be stored beyond its expiration date. An item identified as significant will be reviewed by the administrator for possible long-term storage prior to its expiration date. For more information, see the <a href="help_items.php">Items</a> page.</p>
				<p>An item belongs to a single project. A project belongs to a single workgroup. Users of the system can be members of any number of workgroups. Users can create any number of projects and projects can contain any number of items. For more information, see the <a href="help_projects.php">Projects</a> page.</p>
				<p>Each item has various metadata associated with it. In addition to metadata assigned by the system, such as file type and file size, users may provide other metadata about the item, such as title, description, creator, creation date, and retention group. The metadata for items in a project can be set automatically by uploading a ZIP Archive file. For more information, see the <a href="help_ziparchives.php">ZIP Archives</a> page. Certain metadata, such as creator, can be assigned to every item in a project at one time by editing the project information. For more information, see the <a href="help_projects.php">Projects</a> page.</p>
				<p><a href="/help_items.php">Next topic: Items</a></p>
<?php

	require "resources/footer.php";
	
?>