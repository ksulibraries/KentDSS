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
	
	/* If this user is not an admin, get the workgroups the user belongs to. */
	
	$workgroupLimit = "";
	
	if ($dss_accessLevel == 0) {
	
		$workgroupIdArray = array();
		$workgroupQuery = db_query("SELECT workgroupId FROM workgroupUser WHERE userId=$dss_userId");
		while ($workgroupResult = db_fetch($workgroupQuery)) $workgroupIdArray[] = $workgroupResult["workgroupId"];
		$workgroupLimit = " AND c.id IN (".implode(",",$workgroupIdArray).")";
	
	}
	
	if (trim($_REQUEST["currentPage"]) != "") superstore_save("SEARCH",$dss_sessionCookie,"currentPage",$_REQUEST["currentPage"]);
	$currentPage = escapeValue(superstore_fetch("SEARCH",$dss_sessionCookie,"currentPage"));

	for ($i=0; $i<5; $i++) { 
	
		$thisField = "field".$i;
		$thisCondition = "condition".$i;
		$thisValue = "value".$i;

		if ($_POST["resetButton"] != "") {
		
			superstore_save("SEARCH",$dss_sessionCookie,$thisField,'');
			superstore_save("SEARCH",$dss_sessionCookie,$thisCondition,'');
			superstore_save("SEARCH",$dss_sessionCookie,$thisValue,'');
			superstore_save("SEARCH",$dss_sessionCookie,"currentPage",0);
			$currentPage = 0;
		
		} elseif ($_POST["searchButton"] != "") {
		
			superstore_save("SEARCH",$dss_sessionCookie,$thisField,$_POST[$thisField]);
			superstore_save("SEARCH",$dss_sessionCookie,$thisCondition,$_POST[$thisCondition]);
			superstore_save("SEARCH",$dss_sessionCookie,$thisValue,$_POST[$thisValue]);
			superstore_save("SEARCH",$dss_sessionCookie,"currentPage",0);
			$currentPage = 0;
			
		}
		
		$fieldArray[$i] = superstore_fetch("SEARCH",$dss_sessionCookie,$thisField);
		$conditionArray[$i] = superstore_fetch("SEARCH",$dss_sessionCookie,$thisCondition);
		$valueArray[$i] = superstore_fetch("SEARCH",$dss_sessionCookie,$thisValue);
	
	}
	
	$dss_onLoad = "document.main.value0.focus()";
	require "resources/header.php";

?>
				<h2>Search</h2>
				<form action="search.php" method="post" name="main">
<?php 

	for ($i=0; $i<5; $i++) { 
	
?>
					<div id="row<?php echo $i; ?>" style="<?php if ($i > 0 && $valueArray[$i] == '') echo 'display:none'; ?>">
						<span<?php if ($i > 0) echo ">AND"; else echo " style='padding-left: 30px'>&nbsp;"; ?></span>
						<span>
							<select name="field<?php echo $i; ?>">
								<option value="title"<?php if ($fieldArray[$i] == 'title') echo " selected"; ?>>Title</option>
								<option value="description"<?php if ($fieldArray[$i] == 'description') echo " selected"; ?>>Description -- Slow!</option>
								<option value="creator"<?php if ($fieldArray[$i] == 'creator') echo " selected"; ?>>Creator</option>
								<option value="creationDate"<?php if ($fieldArray[$i] == 'creationDate') echo " selected"; ?>>Creation date</option>
								<option value="location"<?php if ($fieldArray[$i] == 'location') echo " selected"; ?>>Geographic location</option>
								<option value="filename"<?php if ($fieldArray[$i] == 'filename') echo " selected"; ?>>File name</option>
							</select>
						</span>
						<span>
							<select name="condition<?php echo $i; ?>">
								<option value="="<?php if ($conditionArray[$i] == '=') echo " selected"; ?>>is</option>
								<option value="b"<?php if ($conditionArray[$i] == 'b') echo " selected"; ?>>begins with</option>
								<option value="c"<?php if ($conditionArray[$i] == 'c') echo " selected"; ?>>contains</option>
								<option value="e"<?php if ($conditionArray[$i] == 'e') echo " selected"; ?>>ends with</option>
							</select>
						</span>
						<span><input type="text" name="value<?php echo $i; ?>" size="40" value="<?php echo $valueArray[$i]; ?>" class="textFieldBackground"></span>
<?php 

		if ($i < 4) { 
		
?>
						<span id="add<?php echo $i; ?>" style="<?php if ($valueArray[$i] != '' && $valueArray[$i+1] != '') echo 'display:none'; ?>"><a href="" onClick="return addField(<?php echo $i; ?>)"><img src="/resources/add-small.gif" border="0" alt="Add another field" title="Add another field"></a></span>
<?php 

		} else echo "<span>&nbsp;</span>";
		
?> 
					</div>
<?php 

	} 
	
?>
					<div style="padding: 15px 0 0 240px">
						<input type="submit" name="searchButton" value="Search">
						<input type="submit" name="resetButton" value="Reset">
					</div>
				</form>
<?php

	$whereArray = array();
	
	for ($i=0; $i<5; $i++) {
	
		if ($valueArray[$i] != "")
			if ($conditionArray[$i] == "=") $whereArray[] = $fieldArray[$i]."=".escapeQuote($valueArray[$i]);
			elseif ($conditionArray[$i] == "b") $whereArray[] = $fieldArray[$i]." LIKE ".escapeQuote($valueArray[$i]."%");
			elseif ($conditionArray[$i] == "c") $whereArray[] = $fieldArray[$i]." LIKE ".escapeQuote("%".$valueArray[$i]."%");
			elseif ($conditionArray[$i] == "e") $whereArray[] = $fieldArray[$i]." LIKE ".escapeQuote("%".$valueArray[$i]);
			
	}
	
	if (sizeof($whereArray) > 0) {
	
		$searchQuery = db_query("SELECT count(a.id) AS numItems FROM item a, project b, workgroup c WHERE ".implode(" AND ",$whereArray)." AND a.projectId=b.id AND b.workgroupId=c.id $workgroupLimit");
		$searchResult = db_fetch($searchQuery);
		$numItems = $searchResult["numItems"];
	
		if ($numItems == 0) {
?>
				<p>No items matched your search.</p>
<?php

		} else {
	
			displayPageBar($numItems, $dss_displayListSize, $currentPage);
			$start = $currentPage * $dss_displayListSize;
			$limit = "LIMIT $start,$dss_displayListSize";

			$searchQuery = db_query("SELECT a.id,a.projectId,a.title,b.workgroupId,b.name AS projectName,c.name AS workgroupName FROM item a, project b, workgroup c WHERE ".implode(" AND ",$whereArray)." AND a.projectId=b.id AND b.workgroupId=c.id $workgroupLimit ORDER BY c.name,b.name,a.title $limit");

?>
				<table cellpadding="15" class="listing">
					<tr>
						<th>Workgroup</th>
						<th>Project</th>
						<th>Item</th>
					</tr>
<?php

			$workgroupName = "";
			$projectName = "";
			
			while ($searchResult = db_fetch($searchQuery)) {
			
				if ($searchResult["workgroupName"] != $workgroupName) {
				
					$workgroupName = $searchResult["workgroupName"];
					$workgroup = $workgroupName;
					$projectName = "";
					
				} else $workgroup = "&nbsp;";

				if ($searchResult["projectName"] != $projectName) {
				
					$projectName = $searchResult["projectName"];
					$project = $projectName;
					
				} else $project = "&nbsp;";

?>
					<tr>
						<td valign="top"><?php echo str_replace(" ","&nbsp;",$workgroup); ?></td>
						<td valign="top"><?php echo str_replace(" ","&nbsp;",$project); ?></td>
						<td><a href="item_detail.php?search=1&itemId=<?php echo $searchResult["id"]; ?>"><?php echo $searchResult["title"]; ?></a></td>
					</tr>
<?php

			}
		
?>
				</table>
<?php
	
		}
							
	}
	
?>	
<script language="javascript">
	function addField(fieldId) {
		var nextFieldId = fieldId + 1;
		var nextRow = "row" + nextFieldId;
		var currentAdd = "add" + fieldId;
		document.getElementById(nextRow).style.display='';
		document.getElementById(currentAdd).style.display='none';
		if (fieldId < 3) {
			var nextAdd = "add" + nextFieldId;
			document.getElementById(nextAdd).style.display='';
		}
		return false;
	}
</script>
<?php

	require "resources/footer.php";
	
?>