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
	db_connect("dss");
	
	$projectQuery = db_query("select id from project where createdByUserId=1 and name>'08' and name<'10' order by name");
	
	while ($projectResult = db_fetch($projectQuery)) {
	
		echo $projectResult["id"]."\n";
		db_query("delete from item where addedByUserId=1 and projectId=".$projectResult["id"]);
		db_query("delete from project where createdByUserId=1 and id=".$projectResult["id"]);
		//exec("rm /data/files/".strval($projectResult["id"])."/*");
		//exec("rmdir /data/files/".strval($projectResult["id"]));
		
	}
	
?>