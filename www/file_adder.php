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

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }   
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }
        
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'File is empty');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }
        
        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
            return array('success'=>true);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
        
    }    
}

$filename = $_REQUEST['qqfile'];

if (!file_exists($dss_fileshare.$_REQUEST["projectId"]."/$filename")) {

	db_connect('dam');
	
	/* See if this an acceptable file type. */

	$fileparts = explode(".",$filename);
	$filetype = strtoupper($fileparts[sizeof($fileparts)-1]);
	$filetypeQuery = db_query("SELECT id,mimeType FROM filetype WHERE extension=".escapeQuote($filetype));
	
	if (db_numrows($filetypeQuery) == 1) {
		
		// max file size of 2 gigabytes
		$sizeLimit = 2 * 1024 * 1024 * 1024;
		
		/* We have already limited by file type, so allow all file types. */
		
		$uploader = new qqFileUploader(array(), $sizeLimit);
		$result = $uploader->handleUpload($dss_fileshare.$_REQUEST["projectId"]."/");
		
		// if successful, add this item to the database
		
		if ($result['success']) {
	
			if ($filename != "metadata.txt") {
						
				/* If this is an image type file, see if there is IPTC metadata we can harvest. */
				
				$filetypeResult = db_fetch($filetypeQuery);
				
				if (strstr($filetypeResult["mimeType"],"image")) {

					$size = @getimagesize($dss_fileshare.$_REQUEST["projectId"]."/$filename", $info);

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
				db_query("UPDATE item SET projectId=".$_REQUEST["projectId"].
					",filetype=".escapeQuote($filetype).
					",filesize=".filesize($dss_fileshare.$_REQUEST["projectId"]."/$filename").
					",filename=".escapeQuote($filename).
					",expirationDate=$expirationDate".
					",checksum=".escapeQuote(md5_file($dss_fileshare.$_REQUEST["projectId"]."/$filename")).
					",addedByUserId=$dss_userId".
					",addedDate=".date("U").
					",lastUpdatedByUserId=$dss_userId".
					",lastUpdatedDate=".date("U").
					",title=".escapeQuote($title).
					",description=".escapeQuote($description).
					",creator=".escapeQuote($creator).
					",creationDate=".escapeQuote($creationDate).
					",location=".escapeQuote($location).
					" WHERE id=$itemId");
			
			}
				
		}

	} else $result = array('error'=>'Unacceptable file type.');
	
} else $result = array('error'=>'File already exists.');

// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);