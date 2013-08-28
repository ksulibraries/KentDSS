#!/usr/bin/php
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

	require "resources/inc.utilities.php";
	require "resources/inc.config.php";
	set_time_limit(0);
	db_connect("dss");

	/* Get the IMAGE filetypes. */
	
	$filetypes = array();
	$filetypeQuery = db_query("SELECT extension FROM filetype WHERE mimeType LIKE 'IMAGE/%'");
	while ($filetypeResult = db_fetch($filetypeQuery)) $filetypes[] = "'".$filetypeResult["extension"]."'";
	
	/* Get all images that do not have thumbnails. */
		
	$itemQuery = db_query("SELECT id,projectId,filename FROM item WHERE thumbnailCompleted=0 AND filetype IN (".implode(",",$filetypes).")");

	for ($i=1; $itemResult = db_fetch($itemQuery); $i++) {

		/* Create the thumbnail. */
		
		$imageCommand = "/usr/bin/convert ".$dss_fileshare.$itemResult["projectId"]."/".str_replace(" ","\ ",$itemResult["filename"])." -resize 7000@ ".$dss_docRoot."thumbnail/".md5($itemResult["id"]).".jpg 2>/dev/null";
		exec($imageCommand);

		if (file_exists($dss_docRoot."thumbnail/".md5($itemResult["id"]).".jpg")) {
		
			/* Create the peview image if the thumbnail was created. */
		
			$imageCommand = "/usr/bin/convert ".$dss_fileshare.$itemResult["projectId"]."/".str_replace(" ","\ ",$itemResult["filename"])." -resize 480000@ ".$dss_docRoot."preview/".md5($itemResult["id"]).".jpg 2>/dev/null";
			exec($imageCommand);

		} else copy($dss_docRoot."resources/NoThumbnail.jpg",$dss_docRoot."thumbnail/".md5($itemResult["id"]).".jpg");
		
		db_query("UPDATE item SET thumbnailCompleted=1 WHERE id=".$itemResult["id"]);

	}
	        
?>
