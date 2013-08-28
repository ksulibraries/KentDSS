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
	
	/* Keep track of the char the user is viewing. */
	
	if (trim($_REQUEST["char"]) != "") superstore_save("group_list.php",$dss_sessionCookie,"char",$_REQUEST["char"]);
	$char = superstore_fetch("group_list.php",$dss_sessionCookie,"char");

	require "resources/header.php";

?>
				<h2>Retention Groups</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<a href="admin.php"><img src="/resources/cancel.png" border="0" alt="Return to admin tools" title="Return to admin tools"></a>
				<a href="group_edit.php"><img src="/resources/add.png" border="0" alt="Add new retention group" title="Add new retention group"></a>
				<?php $char = displayAlphaBar("retention","retentionGroup","",$char,$dss_displayListSize); ?>
				<table class="listing">
					<tr><th>Retention Group</th><th>Retention Period</th></tr>
<?php

	$where = "";
	if ($char == "#") $where = " WHERE left(retentionGroup,1)>='0' AND left(retentionGroup,1)<='9'";
	elseif ($char != "all") $where = " WHERE left(retentionGroup,1)='$char'";
	$groupQuery = db_query("SELECT id,retentionGroup,retentionPeriod FROM retention $where ORDER BY retentionGroup");

	while ($groupResult = db_fetch($groupQuery)) {
		
		if ($groupResult["retentionPeriod"] == 999) $retentionPeriod = "Indefinite";
		elseif ($groupResult["retentionPeriod"] == 1) $retentionPeriod = "1 year";
		else $retentionPeriod = $groupResult["retentionPeriod"]." years";
		
?>
					<tr>
						<td><a href="group_edit.php?groupId=<?php echo $groupResult["id"]; ?>"><?php echo $groupResult["retentionGroup"]; ?></a></td>
						<td><?php echo $retentionPeriod; ?></td>
					</tr>		
<?php

	}
		
?>
				</table>	
<?php

	require "resources/footer.php";
	
?>