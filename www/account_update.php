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

	$firstName = $_POST["firstName"];
	$lastName = $_POST["lastName"];
	$displayListSize = $_POST["displayListSize"];
	
	if (trim($firstName) == "" || trim($lastName) == "") {
	
		header("Location: /account.php?errorMessage=".rawurlencode("Required fields were not filled in."));
		exit;
		
	}
	
	if (escapeValue($displayListSize) < 10) $displayListSize = 10;
	db_query("UPDATE user SET firstName=".escapeQuote($firstName).
		",lastName=".escapeQuote($lastName).
		",displayListSize=".escapeValue($displayListSize).
		",lastUpdatedByUserId=$dss_userId".
		",lastUpdatedDate=".date("U").
		" WHERE id=$dss_userId");
	db_query("UPDATE session SET displayListSize=".escapeValue($displayListSize)." WHERE id='$dss_sessionCookie'");
		
	header("Location: /account.php?infoMessage=".rawurlencode("Update complete."));

?>