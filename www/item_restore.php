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
	
	$itemId = $_REQUEST["itemId"];
	$itemQuery = db_query("SELECT filename,checksum FROM item WHERE id=".escapeValue($itemId));
		
	if (db_numrows($itemQuery) == 1) $itemResult = db_fetch($itemQuery);
	else {
	
		header("Location: /index.php");
		exit;
		
	}
	
	$pod0checksum = md5_file($dss_fileshare.$dss_currentProject."/".$itemResult["filename"]);
	$pod1checksum = file_get_contents("http://".$dss_pod1name."/file_checksum.php?file=".urlencode($dss_fileshare.$dss_currentProject."/".$itemResult["filename"]));
	$pod2checksum = file_get_contents("http://".$dss_pod2name."/file_checksum.php?file=".urlencode($dss_fileshare.$dss_currentProject."/".$itemResult["filename"]));
	
	require "resources/header.php";

?>
				<h2>Item Restore</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"]); ?>
				<a href="item_detail.php?itemId=<?php echo $itemId; ?>"><img src="/resources/cancel.png" border="0" alt="Return to item detail" title="Return to item detail"></a>
				<table cellpadding="0" cellspacing="0" border="0">
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label">Stored Checksum:</td>
						<td class="data"><?php echo $itemResult["checksum"]; ?></td>
						<td width="20">&nbsp;</td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label"><?php echo $dss_pod0label; ?> File Checksum:</td>
<?php if ($itemResult["checksum"] != $pod0checksum) {?>
						<td class="highlighted"><?php echo $pod0checksum; ?></td>
<?php } else { ?>
						<td class="data"><?php echo $pod0checksum; ?></td>
<?php } ?>
						<td width="20">&nbsp;</td>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label"><?php echo $dss_pod1label; ?> File Checksum:</td>
<?php if ($itemResult["checksum"] != $pod1checksum) {?>
						<td class="highlighted"><?php echo $pod1checksum; ?></td>
						<td width="20">&nbsp;</td>
<?php } else { ?>
						<td class="data"><?php echo $pod1checksum; ?></td>
						<td width="20"><a href="file_restore.php?pod=1&itemId=<?php echo $itemId; ?>"><img src="/resources/restore.png" border="0" alt="Restore this file from Pod 1" title="Restore this file from Pod 1"></a></td>
<?php } ?>
					</tr>
					<tr class="<?php $i++; echo $dss_rowStyles[$i%2]; ?>">
						<td class="label"><?php echo $dss_pod2label; ?> File Checksum:</td>
<?php if ($itemResult["checksum"] != $pod2checksum) {?>
						<td class="highlighted"><?php echo $pod2checksum; ?></td>
						<td width="20">&nbsp;</td>
<?php } else { ?>
						<td class="data"><?php echo $pod2checksum; ?></td>
						<td width="20"><a href="file_restore.php?pod=2&itemId=<?php echo $itemId; ?>"><img src="/resources/restore.png" border="0" alt="Restore this file from Pod 2" title="Restore this file from Pod 2"></a></td>
<?php } ?>
					</tr>
				</table>
<?php

	require "resources/footer.php";
	
?>
