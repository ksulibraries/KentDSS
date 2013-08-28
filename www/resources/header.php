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
?>
<html>
	<head>
		<title>pod0 - Digital Storage System</title>
		<link type="text/css" rel="stylesheet" media="screen" href="/resources/standard.css" />
		<script type="text/javascript" src="/resources/formValidation.js"></script>
<?php

	if (isset($dss_javascriptLibraries)) {
	
		foreach ($dss_javascriptLibraries as $libraryURL) {
	
?>
		<script type="text/javascript" src="<?php echo $libraryURL; ?>"></script>
<?php

		}
		
	}
	
?>
	</head>
	<body<?php if (trim($dss_onLoad) != "") echo " onload='$dss_onLoad'"; ?>>
		<div id="container">
			<div id="header">
				<h1>
					<span id="name"><?php echo $dss_systemName; ?></span> <span id="description">Digital Storage System</span>
				</h1>
			</div>
			<div id="navigation">
				<ul>
<?php

	if ($dss_userId > 0) {

?>
					<li><a href="search.php">Search</a></li>
<?php
	
		if ($dss_numWorkgroups > 1 || $dss_accessLevel > 0) {
		
?>
					<li><a href="workgroup_list.php">Workgroups</a></li>
<?php

		}
		
?>
					<li><a href="project_list.php">Projects</a></li>
					<li><a href="account.php">Account</a></li>
					<li><a href="help_index.php">Help</a></li>
<?php
		
		if ($dss_accessLevel > 0) {
	
?>
					<li><a href="admin.php">Admin</a></li>
<?php

		}
		
?>
					<li><a href="logout.php">Logout</a></li>
<?php

	} else {
	
?>
					<li><a href="#">&nbsp;</a></li>
<?php

	}
	
?>
				</ul>
			</div>
			<div id="content">
