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

/* General Purpose Utilities. */

/* 

db_connect connects to the database server and selects the proper database.

Arguments:	db is the database to connect to.

Returns:	Nothing.

*/

function db_connect($db) {

	global $dss_mysqlHostname,$dss_mysqlUsername,$dss_mysqlPassword;
	mysql_pconnect($dss_mysqlHostname,$dss_mysqlUsername,$dss_mysqlPassword);
	mysql_select_db($db);
	
}

/* 

db_query queries the database server and returns the results.

Arguments:	query is SQL query string,
			exit is 1 if the query should just be echoed to the screen.

Returns:	Query results.

*/

function db_query($query,$exit=0) {

	if ($exit) {
		
		echo $query;
		exit;
		
	} else {
	
		$rc = @mysql_query($query);
		
		if ($rc) return $rc;
		else {
		
			echo $query;
			exit;
			
		}
		
	}
	
}

/* 

db_fetch returns the next set of results from a db_query.

Arguments:	results is the db_query results variable.

Returns:	Next set of query results as an array or 0 if no further results are present

*/

function db_fetch($results) {

	return mysql_fetch_array($results);
	
}

/* 

db_insert inserts a blank record into the specified table and returns the new id of
that record.

Arguments:	table is the database table name to insert a blank record into.

Returns:	the id value of the inserted record.

*/

function db_insert($table) {

	mysql_query("insert into ".$table." (id) values (0)");
	return mysql_insert_id();
	
}

/* 

db_insert perfomrs the specified insert statement and returns the new id of
that record.

Arguments:	sql is the insert SQL statement.

Returns:	the id value of the inserted record.

*/

function db_insert_data($sql) {

	mysql_query($sql);
	return mysql_insert_id();
	
}

/* 

db_numrows returns the number of rows selected from a query.

Arguments:	result is the db_query result variable.

Returns:	the number of rows in the query result.

*/

function db_numrows($result) {

	return mysql_num_rows($result);
	
}

/*

db_duplicate creates a new row in the specified table using the specified
record ID as the source and returns the new row ID.  The table MUST have
an auto-increment id field.

Arguments:	table is the table name, 
			id is the source record ID.

Returns:	the new record ID or 0 if the duplication failed.

*/

function db_duplicate($table, $id) {

	$fieldQuery = db_query("DESCRIBE $table");
	
	if (db_numrows($fieldQuery) > 1) {
	
		$sourceQuery = db_query("SELECT * FROM $table WHERE id=".escapeValue($id));
		
		if (db_numrows($sourceQuery) == 1) {
	
			$sourceResult = db_fetch($sourceQuery);
			$sql = "UPDATE $table SET ";
			$first = 1;
	
			while ($fieldResult = db_fetch($fieldQuery)) {
		
				if (strtolower($fieldResult["Field"]) != "id") {
				
					if (!$first) $sql .= ",";
					if (substr(strtolower($fieldResult["Type"]),0,7) == "varchar" || strtolower($fieldResult["Type"]) == "text")
						$sql .= $fieldResult["Field"]."=".escapeQuote($sourceResult[$fieldResult["Field"]],0);
					else				
						$sql .= $fieldResult["Field"]."=".escapeValue($sourceResult[$fieldResult["Field"]]);
					$first = 0;
				}
				
			}
			
			//echo $sql;
			//exit;
			$newId = db_insert($table);
			db_query($sql." WHERE id=$newId");	
			return $newId;

		} else return 0;

	} else return 0;
	
}

/* 

escapeQuote escape any single quote characters in order to insert the string into
a database.  Carriage returns are also removed from the string.

Arguments:	input is the string as received from the web browser,
			strip indicates whether to strip HTML tags before escaping string.
				Default is 1 (yes).

Returns:	the escaped string enclosed in single quotes.

*/

function escapeQuote($input, $strip=1) {

	$string = stripslashes(trim($input));
	if ($strip) $string = strip_tags($string);
	$result = "";
	
	for ($i=0;$i<strlen($string);$i++) {

		$char = substr($string,$i,1);
		if ($char == "'") $result .= "''";
		elseif ($char == "\r") $result .= "";
		else $result .= $char;

	}

	return "'".$result."'";

}

/* 

pathName removes any non-alphanumeric characters from the lower-case version
of the string passed in order to prepare the string for use in a file path.

Arguments:	string is the path to be prepared.

Returns:	the string containing only lower-case alphanumeric characters.

*/

function pathName($string) {

	$lower = strtolower($string);
	$result = "";
   
	for ($i=0;$i<strlen($lower);$i++) {
	
		$char = substr($lower,$i,1);
		if ((($char >= "a") && ($char <= "z")) || (($char >= "0") && ($char <= "9"))) $result .= $char;
	
	}
	
	return $result;
	
}

/* 

escapeValue removes any non-numeric characters from a string in order to prepare
it to be inserted into a numeric database field.

Arguments:	value is the string as received from the web browser.

Returns:	the string containing only numeric characters or zero if there were no numberic characters present.

*/

function escapeValue($value) {

   $result = "";
   
   for ($i=0;$i<strlen($value);$i++) {
   
      $char = substr($value,$i,1);
      if ($char == "-") $result .= "-";
      elseif ($char == ".") $result .= ".";
      elseif (($char >= "0") && ($char <= "9")) $result .= $char;
   
   }
   
   if ($result == "") $result = "0";
   
   return $result;

}

/* 

selectOptions writes the option tags for a pull-down field using information from a database table.

Arguments:	table is the database table to get the option list from,
			current is the current value to be selected in the pull-down (it must match a 'value' to do so),
			orderBy is the field to order the results by,
			value is the field to be used as the value of the option,
			label is the field to be displayed to the user,
			where is used to limit the list items selected from the table.

Returns:	Nothing.

*/

function selectOptions($table,$current,$orderby="",$value="value",$label="label",$where="") {

	global $TESTMODE;
	
	$sql = "SELECT $value,$label FROM $table WHERE testmode<=".escapeValue($TESTMODE);

	if ($where != "") $sql .= " AND $where";

	if ($orderby != "") $sql .= " ORDER BY $orderby";

	$query = db_query($sql);
	
	for ($i=0; $result = db_fetch($query,$i); $i++) {
	
		echo "<option value=\"".$result[$value]."\"";
		if ($result[$value] == $current) echo " selected";
		echo ">".$result[$label]."</option>\n";
		
	}

}

/* 

getOption returns the label for the specified value from the specified select table.

Arguments:	table is the database table to get the option from,
			current is the value to look up,
			int indicates whether value is an integer or a string,
			value is the field to be used for specified value,
			label is the field to be returned,
			where is used to limit the items selected from the table.			
					 
Returns:	the label associated with value.

*/

function getOption($table,$current,$int=1,$value="value",$label="label",$where="") {

	if ($int)
		$sql = "SELECT $label FROM $table WHERE $value=".escapeValue($current); 
	else
		$sql = "SELECT $label FROM $table WHERE $value=".escapeQuote($current); 
		
	if ($where != "") $sql .= " AND $where";

	$query = db_query($sql);

	if (db_numrows($query) == 1) {
	
		$result = db_fetch($query);
		return $result[$label];
		
	} else return "";
	
}

/* 

selectNumbers writes the option tags for a pull-down field using the range of numbers provided.

Arguments:	start is the first value to be displayed,
			end is the last value to be displayed,
			current is the current value to be selected in the pull-down (it must match a value between start and end to do so),
			inc is the value to increment the value, negative values decrement the value -- the default value is 1,
			label is an optional label to append to the values displayed.

Returns:	Nothing.

*/

function selectNumbers($start, $end, $current, $inc = 1, $label = "") {

	if ($start < $end) {
		
		for ($i=$start; $i<=$end; $i += $inc) {
	
			echo "<option value=\"".$i."\"";
			if ($i == $current) echo " selected";
			echo ">".$i.$label."</option>\n";
		
		}
		
	} else {
	
		for ($i=$start; $i>=$end; $i -= $inc) {
	
			echo "<option value=\"".$i."\"";
			if ($i == $current) echo " selected";
			echo ">".$i.$label."</option>\n";
		
		}
		
	}

}

/*

metamail is an interface to the PHP mail function which allows mail to be re-directed to
a test person while the system is being tested.

Arguments:	All of the same arguments that the PHP function 'mail' has.

Returns:	Nothing.

*/

function metamail($to,$subject,$body,$headers) {

	global $TESTMODE,$TESTEMAIL;

	if ($TESTMODE) {
	
		$header = "To: $to\r\n$headers\r\n\r\n";
		
		mail($TESTEMAIL,$subject,$header.$body);
	
	} else mail($to,$subject,$body,$headers);
		
}

/*

displayMessages echos an information message and/or an error message that is 
passed in.

Arguments:	infoMessage contains an information message, if one exists,
			errorMessage contains an error message, if one exists,
			required if not empty displays a note about required fields.
					 
Returns:	Nothing.

*/

function displayMessages($infoMessage, $errorMessage, $required = array()) {

	if (trim($infoMessage) != "") {
	
?>
			<p class="infoMessage"><?php echo strip_tags($infoMessage,"<b><u><i>"); ?></p>
<?php

	}
	
	if (trim($errorMessage) != "") {
	
?>
			<p class="errorMessage"><?php echo strip_tags($errorMessage,"<b><u><i>"); ?></p>
<?php

	}
	
	if (sizeof($required) > 0) {

?>
			<p><span class="requiredField">*</span> denotes a required field.</p>
<?php

	}
	
}

/* 

displayAlphaBar displays an alphabetical bar from which the user can select
the first letter to be used in displaying a list.  Only characters for which
there are current items with this first letter will be listed. The pound sign 
(#) is used to represent all digits.

Arguments:	table is the table from which items will be selected,
			field is the field in 'table' to use,
			where is an optional SQL WHERE clause to use in selecting,
			char is the currently selected character. If blank, the
				first character that has an item will be selected,
			max is the maximum number of items to display without 
				displaying the alpha tool bar.
			linkOptions are additional parameters to be passed on the
				toolbar links.
							
Returns:	the currently selected character.

*/

function displayAlphaBar($table,$field,$where,$char,$max=10,$linkOptions="") {

	global $PHP_SELF;
	
	$charArray = array();
	$pageName = $_SERVER["PHP_SELF"];
	
	if ($max == 0) $max = 10;

	$sql = "SELECT LEFT($field,1) AS firstChar FROM $table";
	if (trim($where) != "") $sql .= " $where";
	
	$countQuery = db_query($sql);
	$pageCount = db_numrows($countQuery);
	
	$sql .=	" GROUP BY firstChar ORDER BY firstChar";
	$query = db_query($sql);
	
	if ($pageCount > $max) {
	
		echo "\n<div id=\"alphaBar\">";
	
		while ($result = db_fetch($query))
			if ($result["firstChar"] >= "0" && $result["firstChar"] <= "9") $charArray["number"] = 1;
			else $charArray[strtolower($result["firstChar"])] = 1;
			
		/* If the character passed in is not in the array, blank it out. */
		
		if (strtolower($char) != "all" && !$charArray[$char] && !($charArray["number"] && $char == "#")) $char = "";
		
		while (list($label,$value) = each($charArray)) {
		
			if ($label == "number") {
			
				$label = "#";		
				$linkValue = "%23";
			
			} elseif ($label == '"') {
			
				$label = '"';		
				$linkValue = "%22";
			
			} else $linkValue = $label;
					
			if ($char == "") $char = $label;
			echo "<a href=\"";
			if (!strstr($pageName,"content")) echo "$pageName?char=$linkValue$linkOptions";
			else echo "#";
			echo "\"";
			if ($label == strtolower($char)) echo " class=\"selectedChar\"";
			echo ">$label</a>";
	
		}
		
		echo "<a href=\"";
		if (!strstr($pageName,"content")) echo "$pageName?char=all$linkOptions";
		else echo "#";
		echo "\"";
		if (strtolower($char) == "all") echo " class=\"selectedChar\"";
		echo ">all</a>";
		echo "</div>\n";

	} else $char = "all";
	
	return $char;
	
}

/*

displayPageBar displays a list of pages number from which a user can select.
If the number of items to display exceeds the maximum number for a page, the
page bar is displayed.  If 25 or fewer pages are required, all page numbers are
displayed.  Otherwise, the first seven and last seven pages are displayed with
intervening pages included in between.

Arguments:	numItems is the number of items that need to be displayed,
			max is the maximum number of items to display per page,
			currentPage is the current page of items being displayed.
			
Returns:	Nothing.
	 
*/

function displayPageBar($numItems, $max=10, $currentPage=0) {

	global $PHP_SELF;

	if ($numItems > $max) {
	
		$numPages = ceil($numItems / $max);
		echo "\n<div id='pageBar'>";		
		echo "&nbsp;Page:&nbsp;";
		$nextPage = $currentPage + 1;
		if ($nextPage >= $numPages) $nextPage = 0;
		echo "<a href='$PHP_SELF?currentPage=$nextPage' title='Display next page'>&gt;&nbsp;</a>";
		
		if ($numPages <= 25) {
		
			for ($i=0; $i<$numPages; $i++) {
		
				echo "<a href='$PHP_SELF?currentPage=$i'";
				if ($currentPage == $i) echo " class='selectedChar'";
				echo ">".intval($i + 1)."&nbsp;</a>";
		
			}

		} else {
		
			for ($i=0; $i<7; $i++) {
		
				echo "<a href='$PHP_SELF?currentPage=$i'";
				if ($currentPage == $i) echo " class='selectedChar'";
				echo ">".intval($i + 1)."&nbsp;</a>";
		
			}
			
			if ($currentPage - 3 <= 7) {
			
				for ($i=7; $i<14; $i++) {
		
					echo "<a href='$PHP_SELF?currentPage=$i'";
					if ($currentPage == $i) echo " class='selectedChar'";
					echo ">".intval($i + 1)."&nbsp;</a>";
		
				}
				
				echo "&hellip;&nbsp;";
			
			} elseif ($currentPage + 3 >= $numPages - 8) {
			
				echo "&hellip;&nbsp;";

				for ($i=$numPages - 14; $i<$numPages - 7; $i++) {
		
					echo "<a href='$PHP_SELF?currentPage=$i'";
					if ($currentPage == $i) echo " class='selectedChar'";
					echo ">".intval($i + 1)."&nbsp;</a>";
		
				}
				
			
			} else {
			
				echo "&hellip;&nbsp;";

				for ($i=$currentPage - 3; $i<=$currentPage +3; $i++) {
		
					echo "<a href='$PHP_SELF?currentPage=$i'";
					if ($currentPage == $i) echo " class='selectedChar'";
					echo ">".intval($i + 1)."&nbsp;</a>";
		
				}

				echo "&hellip;&nbsp;";
			
			}
			
			for ($i=$numPages - 7; $i<$numPages; $i++) {
		
				echo "<a href='$PHP_SELF?currentPage=$i'";
				if ($currentPage == $i) echo " class='selectedChar'";
				echo ">".intval($i + 1)."&nbsp;</a>";
		
			}
		
		}
					
		echo "</div>\n";
		
	}
	
}

/*

addRequired automates the creation of the Javascript needed for the "verify" function.

Arguments:	required is an array with the form field name as the key and the descriptive textContent
				to display if the field is not filled in as the value.
				
Returns:	Nothing.

*/

function addRequired($required) {

	while (list($field,$description) = each($required)) {
	
		echo "document.main.$field.description='$description'; document.main.$field.isRequired=true; ";
		
	}
	
}

/*

displayNumberWords allows numbers to be displayed as they would in written English including the proper
surrounding words.

Arguments:	number is the number value,
			preOne is the text to display before the number if its value is 1,
			preOther is the text to display before the number if its value is not 1,
			postOne is the text to display after the number if its value is 1,
			postOther is the text to display after the number if its value is not 1,
			decimalPlaces is the number of decimal places to use when displaying the number as a number.
			
Returns:	The formatted string.

*/

function displayNumberWords($number,$preOne="",$preOther="",$postOne="",$postOther="",$decimalPlaces=0) {

	global $dss_numberWords;
	
	if ($number == 1) return $preOne." ".$dss_numberWords[$number]." ".$postOne;
	elseif ($number < 13) return $preOther." ".$dss_numberWords[$number]." ".$postOther;
	else return $preOther." ".number_format($number,$decimalPlaces)." ".$postOther;
	
}

/*

verify_passsword verifies that the username and password passed to it are correct.

Arguments:	username - of user,
			password - of user.

Returns: True if username and password are correct, false otherwise.

*/

function verify_password($username,$password) {

	/* MODIFY THIS FOR YOUR INSTITUTION! */
	if ($username == 'dssadmin' && password == 'dssadmin') return 1;
	else return 0;

}

/*

fullNameById returns the name of the person with the specified ID number.

Arguments:	userId is the ID number of the person,
			format is the format of the person's name to return:
				0 = first name, space, last name,
				1 = last name, comma space, first name.
				
Returns:	the name in the format requested or blank if not found.

*/

function fullNameById($userId, $format=0) {

	$query = db_query("SELECT firstName,lastName FROM user WHERE id=".escapeValue($userId));

	if (db_numrows($query) == 1) {
	
		$result = db_fetch($query);
		if ($format == 0)
			$name = $result["firstName"]." ".$result["lastName"];
		else
			$name = $result["lastName"].", ".$result["firstName"];
		
	} else $name = "";
	
	return $name;
	
}

/*

filesize_format returns a filesize rounded to the largest units not a fraction.

Arguments:	filesize is the filesize in bytes.

Returns:	the formatted string.

*/

function filesize_format($filesize) {

	if ($filesize >= 1073741824) $string = round($filesize / 1073741824)." GB";
	elseif ($filesize >= 1048576) $string = round($filesize / 1048576)." MB";
	elseif ($filesize >= 1024) $string = round($filesize / 1024)." KB";
	else $string = "$filesize bytes";
	return $string;
	
}

/*

metasync reads the files in a project directory and verifies that each is in the database.
Any files not found in the database is added.  Only files that have acceptable file types are
added -- others are deleted.  If the item is an image file type, certain IPTC metadata is
harvested from the file.  This includes:

	title (iptc['2#005']),
	creationDate (iptc['2#055']),
	creator =(iptc['2#080'])
	location (iptc['2#092']), and
	description (iptc['2#120']).
	
If a file named 'metadata.txt' is present, it is read and the metadata included is applied based
on filename.  Once this file has been proccessed, it is deleted.  See help_ziparchives.php for
details on this process.

Arguments:	projectId is the project ID number which will be the directory name in the filestore.
			commandLineUserId is the ID of the person executing this function from the command line,
				otherwise, dss_userId contains the ID of the person logged in to the web site.

Returns:	the status array:
				success => number of files synced or
				error => the error message.

*/

function metasync($projectId,$commandLineUserId=0) {

	global $dss_fileshare, $dss_userId;
	
	$query = db_query("SELECT id FROM project WHERE id=".escapeValue($projectId));
	
	if (db_numrows($query) == 1) {
	
		/* Get the acceptable file types. */
		
		$query = db_query("SELECT extension,mimeType FROM filetype");
		while ($result = db_fetch($query)) $allowedExtensions[$result["extension"]] = $result["mimeType"];
		
		/* If this is being called from the command line, use the passed in user ID. */
		
		if ($commandLineUserId != 0) $userId = $commandLineUserId;
		else $userId = $dss_userId;

		/* Scan the project directory for files that are not already in this project. */
		
		if ($handle = opendir($dss_fileshare.$projectId)) {
		
			$filesSynced = array();
			
			while (false !== ($filename = readdir($handle))) {
			
				/* Only files are permitted. Remove anything else. */
				
				if (filetype($dss_fileshare.$projectId."/".$filename) == "file") {
					
					/* See if this file has an acceptable file type. */
					
					$fileparts = explode(".",$filename);
					$filetype = strtoupper($fileparts[sizeof($fileparts)-1]);
					
					if (array_key_exists($filetype,$allowedExtensions)) {
				
						if (!in_array($filename,array("metadata.txt"))) {
						
							/* See if this filename is already in this project. */
							
							$query = db_query("SELECT id FROM item WHERE projectId=$projectId AND filename=".escapeQuote($filename));
							
							if (db_numrows($query) == 0) {
												
								$title = "";
								$creationDate = "";
								$creator = "";
								$location = "";
								$description = "";
		
								/* If this is an image type file, see if there is IPTC metadata we can harvest. */
								
								if (strstr($allowedExtensions[$filetype],"image")) {

									$size = @getimagesize($dss_fileshare.$projectId."/$filename", $info);

									if (isset($info['APP13'])) {
					
										$iptc = iptcparse($info['APP13']);
										$title = $iptc['2#005'][0];
										$creationDate = $iptc['2#055'][0];
										$creator = $iptc['2#080'][0];
										$location = $iptc['2#092'][0];
										$description = $iptc['2#120'][0];
						
									}				
				
								}
				
								if (trim($title) == "") $title = $filename;
								$expirationDate = mktime(6,0,0,date("m"),date("d"),date("Y")+1);
								$itemId = db_insert("item");
								db_query("UPDATE item SET projectId=".escapeValue($projectId).
									",filetype=".escapeQuote($filetype).
									",filesize=".filesize($dss_fileshare.$projectId."/$filename").
									",filename=".escapeQuote($filename).
									",expirationDate=$expirationDate".
									",checksum=".escapeQuote(md5_file($dss_fileshare.$projectId."/$filename")).
									",addedByUserId=$userId".
									",addedDate=".date("U").
									",lastUpdatedByUserId=$userId".
									",lastUpdatedDate=".date("U").
									",title=".escapeQuote($title).
									",description=".escapeQuote($description).
									",creator=".escapeQuote($creator).
									",creationDate=".escapeQuote($creationDate).
									",location=".escapeQuote($location).
									" WHERE id=$itemId");
								$filesSynced[$filename] = 1;
					
							}
							
						}
						
					} else unlink($dss_fileshare.$projectId."/".$filename);
					
				} else system("rm -R ".$dss_fileshare.$projectId."/".$filename);
				
			}
		
			closedir($handle);
						
			/* If there is a metadata.txt file, load the metadata into the database. */
			
			if ($handle = @fopen($dss_fileshare.$projectId."/metadata.txt","r")) {
			
				/* The first record describes the field layout (tab-delimited). */
				
				if ($record = fgets($handle,4096)) {
							
					$fieldArray = explode("\t",strtolower(trim($record,"\r\n")));
					
					if (false !== $filenameIndex = array_search("filename",$fieldArray)) {
											
						/* For each filename in the file that is in the database, update the metadata. */
						
						while ($record = fgets($handle,4096)) {
						
							$recordArray = explode("\t",trim($record,"\r\n"));
							$filename = $recordArray[$filenameIndex];
							$query = db_query("SELECT id,addedDate FROM item WHERE projectId=".escapeValue($projectId)." AND filename=".escapeQuote($filename));
							
							if (db_numrows($query) == 1) {
							
								$result = db_fetch($query);
								
								if (false !== $index = array_search("title",$fieldArray)) {

									if (trim($recordArray[$index]) != "") {
									
										db_query("UPDATE item SET title=".escapeQuote($recordArray[$index]).",lastUpdatedByUserId=$userId,lastUpdatedDate=".date("U")." WHERE id=".$result["id"]);	
										$filesSynced[$filename] = 1;
										
									}
									
								}
							
								if (false !== $index = array_search("description",$fieldArray)) {

									if (trim($recordArray[$index]) != "") {

										db_query("UPDATE item SET description=".escapeQuote($recordArray[$index]).",lastUpdatedByUserId=$userId,lastUpdatedDate=".date("U")." WHERE id=".$result["id"]);	
										$filesSynced[$filename] = 1;
									
									}
									
								}
							
								if (false !== $index = array_search("creator",$fieldArray)) {

									if (trim($recordArray[$index]) != "") {

										db_query("UPDATE item SET creator=".escapeQuote($recordArray[$index]).",lastUpdatedByUserId=$userId,lastUpdatedDate=".date("U")." WHERE id=".$result["id"]);	
										$filesSynced[$filename] = 1;
									
									}
									
								}
							
								if (false !== $index = array_search("date",$fieldArray)) {

									if (trim($recordArray[$index]) != "") {

										db_query("UPDATE item SET creationDate=".escapeQuote($recordArray[$index]).",lastUpdatedByUserId=$userId,lastUpdatedDate=".date("U")." WHERE id=".$result["id"]);	
										$filesSynced[$filename] = 1;
									
									}
									
								}
							
								if (false !== $index = array_search("location",$fieldArray)) {

									if (trim($recordArray[$index]) != "") {

										db_query("UPDATE item SET location=".escapeQuote($recordArray[$index]).",lastUpdatedByUserId=$userId,lastUpdatedDate=".date("U")." WHERE id=".$result["id"]);	
										$filesSynced[$filename] = 1;
									
									}
									
								}

								if (false !== $index = array_search("group",$fieldArray)) {

									if (trim($recordArray[$index]) != "") {

										$retentionPeriodQuery = db_query("SELECT retentionPeriod FROM retention WHERE retentionGroup=".escapeQuote($recordArray[$index]));
										
										if (db_numrows($retentionPeriodQuery) == 1) {
										
										$retentionPeriod = $retentionPeriodResult["retentionPeriod"];
										
											if ($retentionPeriod > 0)
												$expirationDate = mktime(6,0,0,date("m",$result["addedDate"]),date("d",$result["addedDate"]),date("Y",$result["addedDate"])+$retentionPeriod);
											else
												$expirationDate = mktime(6,0,0,12,31,2037);
	
											db_query("UPDATE item SET retentionGroup=".escapeQuote($recordArray[$index]).",expirationDate=$expirationDate,lastUpdatedByUserId=$userId,lastUpdatedDate=".date("U")." WHERE id=".$result["id"]);	
											$filesSynced[$filename] = 1;

										}
																			
									}
									
								}
								
							}
							
						}
				
						fclose($handle);

						/* Delete the metadata.txt file so it doesn't get loaded again. */
						
						unlink($dss_fileshare.$projectId."/metadata.txt");
			
						/* Mark the items that have all of their required metadata present. */
						
						db_query("UPDATE item SET metadataCompleted=1 WHERE retentionGroup<>'' AND projectId=$projectId");
					
					} else {
					
						fclose($handle);
					
						/* Delete the metadata.txt file so it doesn't get loaded again. */
						
						unlink($dss_fileshare.$projectId."/metadata.txt");
			
						return array("error"=>"No filename field present in metadata.txt file.");
					
					}
					
				} else {
				
					fclose($handle);
				
					/* Delete the metadata.txt file so it doesn't get loaded again. */
					
					unlink($dss_fileshare.$projectId."/metadata.txt");
			
					return array("error"=>"No records present in metadata.txt file.");
				
				}
			
			}					
			
			return array("success"=>sizeof($filesSynced));
			
		} return array("error"=>"Unable to open project directory");	
	
	} return array("error"=>"Invalid project ID");

}

/* SUPERSTORE FUNCTIONS */

function superstore_save($script,$sessionId,$name,$value) {

	/* Delete any expired records. */
	
	db_query("DELETE FROM superstore WHERE timeout<".date("U"));
	
	/* See if this superstore record already exists. */
	
	$query = db_query("SELECT value FROM superstore WHERE script=".escapeQuote($script)." AND sessionId=".escapeQuote($sessionId)." AND name=".escapeQuote($name));
	
	/* If not add a new record. */
	
	if (db_numrows($query) == 0)
		db_query("INSERT INTO superstore VALUES (".escapeQuote($script).",".escapeQuote($sessionId).",".escapeQuote($name).",".escapeQuote($value).",".intval(date("U")+86400).")");
	else
		db_query("UPDATE superstore SET value=".escapeQuote($value).", timeout=".intval(date("U")+86400)." WHERE script=".escapeQuote($script)." AND sessionId=".escapeQuote($sessionId)." AND name=".escapeQuote($name));
		
}

function superstore_fetch($script,$sessionId,$name) {

	$query = db_query("SELECT value FROM superstore WHERE script=".escapeQuote($script)." AND sessionId=".escapeQuote($sessionId)." AND name=".escapeQuote($name));
	
	if (db_numrows($query) == 1) {
	
		$result = db_fetch($query);
		return $result["value"];
		
	} else return "";

}

/* Always connect to the database. */

db_connect($dss_mysqlDatabaseName);

?>
