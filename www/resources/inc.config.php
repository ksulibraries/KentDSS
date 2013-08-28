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

	// MySQL settings.
	$dss_mysqlDatabaseName = "dss";
	$dss_mysqlHostname = "localhost";
	$dss_mysqlUsername = "username";
	$dss_mysqlPassword = "password";

	// These are the full hostname of your three storage pods and
	// the label you want to show users.
	$dss_pod0name = "pod0.yourdomain.edu";
	$dss_pod0label = "Pod 0";
	$dss_pod1name = "pod1.yourdomain.edu";
	$dss_pod1label = "Pod 1";
	$dss_pod2name = "pod2.yourdomain.edu";
	$dss_pod2label = "Pod 2";
	
	// The path to your web site document root.
	$dss_docRoot = "/data/www/";
	
	// The path to your file store.
	$dss_fileshare = "/data/files/";
	
	// How you refer to the system.
	$dss_systemName = "pod0";
	
	// The geeks who should get system emails.
	$dss_systemAdministratorEmail = "someone@yourdomain.edu";
	
	// The person responsible for interacting with your users.
	$dss_administratorEmail = "someone@yourdomain.edu";
	$dss_administratorName = "Firstname Lastname";
	$dss_administratorTitle = "University Archivist";
	
	// The maximum number of years that retention periods can be set to (other than 'indefinite').
	$dss_maximumRetentionPeriod = 10;
	
	// The labels that are displayed on the login page.
	$dss_loginUsernameText = "Username";
	$dss_loginPasswordText = "Password";
	
	// A link to a page that describe what users should do if they forget their passwords.
	$dss_loginPasswordHelpURL = "http://www.yourdomain.edu";

?>
