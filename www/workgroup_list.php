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
	
	/* Keep track of the char the user is viewing. */
	
	if (trim($_REQUEST["char"]) != "") superstore_save("workgroup_list",$dss_sessionCookie,"char",$_REQUEST["char"]);
	$char = superstore_fetch("workgroup_list",$dss_sessionCookie,"char");

	require "resources/header.php";
	
?>
				<h2>Workgroups</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
<?php 

	if ($dss_accessLevel > 0) { 
	
?>
				<a href="workgroup_edit.php"><img src="/resources/add.png" border="0" alt="Add new workgroup" title="Add new workgroup"></a>
<?php 

		$char = displayAlphaBar("workgroup","name","",$char,$dss_displayListSize);

	} else {

		$char = displayAlphaBar("workgroup a, workgroupUser b","a.name","WHERE b.userId=$dss_userId AND b.workgroupId=a.id",$char,$dss_displayListSize);

	}
		
?>
				<table class="listing">
					<tr><th>Name</th><th>Last Updated</th></tr>
<?php

	if ($dss_accessLevel > 0) {
	
		$where = "";
		if ($char == "#") $where = "WHERE left(name,1)>='0' AND left(name,1)<='9'";
		elseif ($char != "all") $where = "WHERE left(name,1)='$char'";
		$workgroupQuery = db_query("SELECT id,name,lastUpdatedDate FROM workgroup $where ORDER BY name");
	
	} else {
	
		$where = "";
		if ($char == "#") $where = "WHERE left(a.name,1)>='0' AND left(a.name,1)<='9'";
		elseif ($char != "all") $where = "AND left(a.name,1)='$char'";
		$workgroupQuery = db_query("SELECT a.id,a.name,a.lastUpdatedDate FROM workgroup a, workgroupUser b WHERE b.userId=$dss_userId AND b.workgroupId=a.id $where ORDER BY a.name");
	
	}
	
	while ($workgroupResult = db_fetch($workgroupQuery)) {
	
?> 
					<tr>
						<td><a href="/project_list.php?workgroupId=<?php echo $workgroupResult["id"]; ?>"><?php echo $workgroupResult["name"]; ?></a></td>
						<td><?php echo date($dss_dateFormat[2],$workgroupResult["lastUpdatedDate"]); ?></td>
					</tr>		
<?php

	}
	
?>
				</table>	
<?php

	require "resources/footer.php";
	
?>