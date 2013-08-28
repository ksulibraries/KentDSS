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
				<h2>Items</h2>
				<a href="help_index.php"><img src="/resources/cancel.png" border="0" alt="Return to the help index" title="Return to the help index"></a>
				<p>Each object that is deposited into the storage system is referred to as an <i>item</i>. An item has a file which contains the digital object itself, for example, an image or a document, and the metadata describing the digital object. The maximum file size directly supported by the storage system is 2 GB. For larger files, contact the administrator (see the footer below) for special assistance. Only certain file types can be stored by the system. These are listed on the <a href="help_filetypes.php">Acceptable File Types</a> page.</p>
				<p>Any single item belongs to a single project. An item is added to a project from the Item List page. For more information, see the <a href="help_projects.php">Projects</a> page.</p>
				<p>To display all of the metadata for an item, click its title on the Item List page. This displays the Item Detail page for the item. From this page, the item can be edited or downloaded. For image and movie file types, a thumbnail image is created by the system and displayed in the upper-right-hand corner of the Item Detail page. Thumbnails are automatically created overnight on the day the item is uploaded to the system.</p>
				<p>The metadata for an item consists of two types: system-supplied and user-supplied. System-supplied metadata is created and maintained by the storage system and cannot be directly edited. System-supplied metadata includes: file name, file size, file type, expiration date, file checksum, date added, person who added the item, date last updated, and the person who last updated the item. Note that the expiration date is supplied by the system and not by the user. The expiration date is based on the retention period of the retention group assigned to the item. Items that have not had a retention group assigned to them will expire one year from the date when they were uploaded.</p>
				<p><img src="/resources/pencil.png" border="0" align="left"> User-supplied metadata can be directly edited. Click the <i>Edit this item</i> button to edit the user-supplied metadata for an item. The user-supplied metadata includes:
				<ul>
					<li><b>Title</b> - the title of the item</li>
					<li><b>Retention Group</b> - the retention group assigned to the item</li>
					<li><b>Description</b> - a description of the item</li>
					<li><b>Creator</b> - the name of the creator of the item</li>
					<li><b>Creation Date</b> - the date the item was created</li>
					<li><b>Geographical Location</b> - the geographical location at which the item was created</li>
					<li><b>Significant Item</b> - an indicator that this item may have long-term significance (see below)</li>
				</ul>
				</p>
				<p>The storage system is intended for medium-term storage of digital items. As such, each item is assigned an expiration date. Three months prior to an item's expiration date, all active members of the workgroup to which the item's project belongs will receive an email message warning of the upcoming deletion of the item. This message will be repeated two months prior and again one month prior to the expiration date. The item will be permanently deleted from the system at the end of the day on its expiration date.</p> 
				<p>Some items may be considered important enough to move to long-term storage once they have reached their expiration dates. The <i>significant item</i> checkbox is used to identify these items. Significant items will be reviewed by the administrator prior to their expiration dates. Those that should be archived will be moved to the long-term storage facility.</p>
				<p>When an item is added to a project, the file fingerprint for the item is automatically calculated and stored with the item's system-supplied metadata. This fingerprint is called the <i>checksum</i> of the file and can be used to verify that the file has not become corrupted. Every night, each item is checked to verify that its file in the storage system still has the checksum that was calculated when it was added. If any items fail this test, the nightly backup is cancelled and the system administrator is notified by email. This allows the system administrator to replace a corrupted file with an intact version from the backup system before that file is over-written.</p>
				<p><a href="/help_projects.php">Next topic: Projects</a></p>
<?php

	require "resources/footer.php";
	
?>