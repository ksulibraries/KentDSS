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
	
	require "resources/header.php";

?>
				<h2>Next 50 Items to Expire</h2>
				<a href="report_list.php"><img src="/resources/cancel.png" border="0" alt="Return to the report list" title="Return to the report list"></a>
				<table class="listing">
					<tr><th>Workgroup</th><th>Project</th><th>Title</th><th>Expiration Date</th></tr>
<?php

	$itemQuery = db_query("SELECT id,projectId,title,expirationDate FROM item ORDER BY expirationDate LIMIT 50");
	
	while ($itemResult = db_fetch($itemQuery)) {
	
		$projectResult = db_fetch(db_query("SELECT workgroupId,name FROM project WHERE id=".$itemResult["projectId"]));
		$workgroupResult = db_fetch(db_query("SELECT name FROM workgroup WHERE id=".$projectResult["workgroupId"]));

?>
					<tr>
						<td><?php echo $workgroupResult["name"]; ?></td>
						<td><?php echo $projectResult["name"]; ?></td>
						<td><a href="item_detail.php?itemId=<?php echo $itemResult["id"]; ?>" target="_itemDisplay"><?php echo $itemResult["title"]; ?></a></td>
						<td><?php echo date("Y-m-d",$itemResult["expirationDate"]); ?></td>
					</tr>
<?php

	}
	
?>
				</table>
<?php

	require "resources/footer.php";
	
?>