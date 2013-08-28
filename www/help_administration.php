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
				<h2>Administration</h2>
				<a href="help_index.php"><img src="/resources/cancel.png" border="0" alt="Return to the help index" title="Return to the help index"></a>
				<p>This section is intended for people administering the Digital Storage System (DSS). There are three types of uses of the DSS: Contributors, Admins, and Super Admins. The table below shows what each type
				of user is authorized to do.</p>
				<table>
					<tr>
						<th>Function</th>
						<th width="150">Contributors</th>
						<th width="150">Admins</th>
						<th width="150">Super Admins</th>
					</tr>
					<tr>
						<td>Upload Items</td>
						<td>&#10004;</td>
						<td>&#10004;</td>
						<td>&#10004;</td>
					</tr>
					<tr>
						<td>Edit Items</td>
						<td>&#10004;</td>
						<td>&#10004;</td>
						<td>&#10004;</td>
					</tr>
					<tr>
						<td>Create Projects</td>
						<td>&#10004;</td>
						<td>&#10004;</td>
						<td>&#10004;</td>
					</tr>
					<tr>
						<td>Create Workgroups</td>
						<td>&nbsp;</td>
						<td>&#10004;</td>
						<td>&#10004;</td>
					</tr>
					<tr>
						<td>Edit Any Item/Project/Workgroup</td>
						<td>&nbsp;</td>
						<td>&#10004;</td>
						<td>&#10004;</td>
					</tr>
					<tr>
						<td>View Reports</td>
						<td>&nbsp;</td>
						<td>&#10004;</td>
						<td>&#10004;</td>
					</tr>
					<tr>
						<td>Delete Items</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&#10004;</td>
					</tr>
					<tr>
						<td>Add/Edit Filetypes</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&#10004;</td>
					</tr>
					<tr>
						<td>Add/Edit Users</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&#10004;</td>
					</tr>
				</table>
				<p>Many aspects of the DSS are configurable by editing the <i>inc.config.php</i> file. In order to make edits, you must have
				write permission to this file in the <i>resources</i> directory in the DSS document root on the server. See the <i>install</i>
				file in the DSS document root for additional information.</p>
<?php

	require "resources/footer.php";
	
?>