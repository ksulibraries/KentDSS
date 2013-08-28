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
	
	$logId = $_REQUEST["logId"];
	
	$logQuery = db_query("SELECT * FROM auditlog WHERE id=".escapeValue($logId));
		
	if (db_numrows($logQuery) == 1) $logResult = db_fetch($logQuery);
	else {
	
		header("Location: /index.php");
		exit;
		
	}
	
	require "resources/header.php";

?>
				<h2>Log Detail</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"]); ?>
				<a href="report_audit_list.php"><img src="/resources/cancel.png" border="0" alt="Return to log list" title="Return to log list"></a>
				<table cellpadding="0" cellspacing="0" border="0">
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Action:</td>
						<td class="data"><?php echo $logResult["action"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Action Date:</td>
						<td class="data"><?php echo date($dss_dateFormat[1],$logResult["actionDate"]); ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Action By:</td>
						<td class="data"><?php echo fullNameById($logResult["actionByUserId"]); ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Action Comment:</td>
						<td class="data"><?php echo $logResult["actionComment"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Title:</td>
						<td class="data"><?php echo $logResult["title"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Project:</td>
						<td class="data"><?php echo $logResult["projectName"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Workgroup:</td>
						<td class="data"><?php echo $logResult["workgroupName"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>"r>
						<td class="label">Retention Group:</td>
						<td class="data"><?php echo $logResult["retentionGroup"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Significant log:</td>
						<td class="data"><?php if ($logResult["significant"]) echo "Yes"; else echo "No"; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Description:</td>
						<td class="data"><?php echo $logResult["description"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Creation Date:</td>
						<td class="data"><?php echo $logResult["creationDate"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Creator:</td>
						<td class="data"><?php echo $logResult["creator"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>"r>
						<td class="label">Geographical Location:</td>
						<td class="data"><?php echo $logResult["location"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">File Type:</td>
						<td class="data"><?php echo $logResult["filetype"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">File Size:</td>
						<td class="data"><?php echo filesize_format($logResult["filesize"]); ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">File Name:</td>
						<td class="data"><?php echo $logResult["filename"]; ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Expiration Date:</td>
						<td class="data"><?php echo date($dss_dateFormat[1],$logResult["expirationDate"]); ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Added By:</td>
						<td class="data"><?php echo fullNameById($logResult["addedByUserId"]); ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Added On:</td>
						<td class="data"><?php echo date($dss_dateFormat[2],$logResult["addedDate"]); ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Last Updated By:</td>
						<td class="data"><?php echo fullNameById($logResult["lastUpdatedByUserId"]); ?></td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Last Updated On:</td>
						<td class="data"><?php echo date($dss_dateFormat[2],$logResult["lastUpdatedDate"]); ?></td>
					</tr>
				</table>
<?php

	require "resources/footer.php";
	
?>
