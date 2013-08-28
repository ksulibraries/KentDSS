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

	if ($dss_userId == 0 || $dss_accessLevel < 2) {
	
		header("Location: /index.php");
		exit;
		
	}
	
	/* Keep track of the char the user is viewing. */
	
	if (trim($_REQUEST["char"]) != "") superstore_save("filetype_list.php",$dss_sessionCookie,"char",$_REQUEST["char"]);
	$char = superstore_fetch("filetype_list.php",$dss_sessionCookie,"char");

	require "resources/header.php";

?>
				<h2>Acceptable Filetypes</h2>
				<?php displayMessages($_REQUEST["infoMessage"], $_REQUEST["errorMessage"],$required); ?>
				<a href="admin.php"><img src="/resources/cancel.png" border="0" alt="Return to admin tools" title="Return to admin tools"></a>
				<a href="filetype_edit.php"><img src="/resources/add.png" border="0" alt="Add new filetype" title="Add new filetype"></a>
				<?php $char = displayAlphaBar("filetype","extension","",$char,$dss_displayListSize); ?>
				<table class="listing">
					<tr><th>Extension</th><th>MIME Type</th></tr>
<?php

	$where = "";
	if ($char == "#") $where = " WHERE left(extension,1)>='0' AND left(extension,1)<='9'";
	elseif ($char != "all") $where = " WHERE left(extension,1)='$char'";
	$filetypeQuery = db_query("SELECT id,extension,mimeType FROM filetype $where ORDER BY extension");

	while ($filetypeResult = db_fetch($filetypeQuery)) {
		
?>
					<tr>
						<td><a href="filetype_edit.php?filetypeId=<?php echo $filetypeResult["id"]; ?>"><?php echo $filetypeResult["extension"]; ?></a></td>
						<td><?php echo $filetypeResult["mimeType"]; ?></td>
					</tr>		
<?php

	}
		
?>
				</table>	
<?php

	require "resources/footer.php";
	
?>