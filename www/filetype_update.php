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
	
	if (trim($_REQUEST["cancel_button"]) != "") {
	
		header("Location: /filetype_list.php");
		exit;
		
	}
	
	$filetypeId = $_REQUEST["filetypeId"];

	if (trim($_REQUEST["delete_button"]) != "") {
	
		db_query("DELETE FROM filetype WHERE id=".escapeValue($filetypeId));
		header("Location: /filetype_list.php?infoMessage=".rawurlencode('The filetype was deleted.'));
		exit;
		
	}
	
	$infoMessage = "Filetype was updated.";
	
	if (trim($filetypeId) == "") {
	
		/* See if this is a duplicate filetype. */
		
		$query = db_query("SELECT id FROM filetype WHERE extension=".escapeQuote($_REQUEST["extension"]));
		
		if (db_numrows($query) == 0) {
		
			$infoMessage = "Filetype was added.";
			$filetypeId = db_insert("filetype");
		
		} else {
		
			header("Location: filetype_list.php?errorMessage=".rawurlencode("The filetype already exists and was not added."));
			exit;
			
		}
		
	}
	
	/* See if this is a duplicate username. */
	
	$query = db_query("SELECT id FROM filetype WHERE id<>".escapeValue($filetypeId)." AND extension=".escapeQuote($_REQUEST["extension"]));
	
	if (db_numrows($query) == 0) {

		db_query("UPDATE filetype SET extension=".escapeQuote($_REQUEST["extension"]).
			",mimeType=".escapeQuote($_REQUEST["mimeType"]).
			" WHERE id=".escapeValue($filetypeId));
			
	} else {
	
		header("Location: filetype_list.php?errorMessage=".rawurlencode("The filetype already exists and no changes were made."));
		exit;
		
	}

	header("Location: filetype_list.php?infoMessage=".rawurlencode($infoMessage));
	
?>