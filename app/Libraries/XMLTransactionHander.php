<?php

namespace App\Libraries;

class XMLTransactionHander 
{
	public $URL;
	public $XMLRequest;
	public $XMLResponseRaw;
	public $XPath;
	public $errno;

	function curlRequest() 
    {
		// Configure headers, etc for request
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->URL);
		curl_setopt($ch, CURLOPT_TIMEOUT, 180);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->XMLRequest);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$httpHeader = array("Content-Type: text/xml; charset=UTF-8", "Content-Encoding: UTF-8");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
		// Execute request, store response and HTTP response code
		$data=curl_exec($ch);
		$this->errno = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close($ch);
		return($data);
	}

	function executeRequest($URL, $request) 
    {
		$this->URL = $URL;
		$this->XMLRequest = $request;
		$this->getFeed();
		
		// M.EDIT for monitoring responses. can be deleted.
		//$filename='/home/hizlisayfalar/domainlog/log_'.date('Y-m-d_H-i-s').'.txt';
	    //file_put_contents($filename, $this->XMLResponseRaw);
		// Process response XML if a successful HTTP response returned
		if( $this->errno == 200 ) 
        {
			return simplexml_load_string($this->XMLResponseRaw);
		} 
        else 
        {
			return NULL;
		}
	}

	function getFeed() 
    {
		$rawData = $this->curlRequest();
		if ($rawData != -1) 
        {
			$this->XMLResponseRaw=$rawData;
		}
	}
}