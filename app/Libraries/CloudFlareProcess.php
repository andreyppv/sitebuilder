<?php

namespace App\Libraries;

/*
Trait: Common curl functionality that can be use for any classes
*/
trait CurlTrait
{
	public function executeRequest($url, $headerA, $method, $data = array())
	{
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headerA,
		));	

		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
		    trigger_error("cURL Error #:" . $err, E_USER_WARNING);
		} else {
		    return $response;
		}
	}
	
}

/**
 * CloudFlareProcess
 * 
 * Wrapper classs to perform certain API functionalities
 * 
 * 
 * 
 */
class CloudFlareProcess {

    # implement the CurlTrait to perform curl functionality
    use CurlTrait;

    /**
    * Set the email of User/Login CloudFlare Account
    * @param string $cfEmail
    */
    private $cfEmail;

    /**
    * Set the API Key of User/Login CloudFlare Account
    * @param string $cfKey
    */
    private $cfKey;

    private $headerA = array();

    /**
    * Set Web API base URL
    */

    protected $api_url = 'https://api.cloudflare.com/client/v4/';

	/**
	 * CloudFlareProcess:constructor
	 * This function will initialize 
	 * 
	 * @param string $cfEmail
	 * @param string $cfKey
	 * @return
	 */
	public function __construct( $cfEmail, $cfKey)
	{
		if( !isset($cfEmail) || !isset($cfKey) ) {
			trigger_error('You must provide authentication information');
			return false;
		}

		$this->cfEmail = $cfEmail;
		$this->cfKey= $cfKey;

		$this->buildHeader();

	}

	/**
	 * CloudFlareProcess:buildHeader
	 * Utility function to build the header content as an array
	 * 
	 * @return array $headerA
	 */
	protected function buildHeader()
	{
		$this->headerA = array(
	        "content-type: application/json",
	        "x-auth-email: {$this->cfEmail}",
	        "x-auth-key: {$this->cfKey}"
	    );

		return $this->headerA;
	}
	
	
	/**
	 * CloudFlareProcess:create_zone
	 * This function create/add a new domain to CloudFlare
	 *  $data represents an array to be passed (will be converted to a JSON format)
	 * @param array $data
	 * @return
	 */
	public function create_zone( $data )
	{
		//Build the URL
		$endpoint = 'zones';
		$url = $this->api_url . $endpoint;
		
		//Run it!
		$responseA = $this->executeRequest($url, $this->headerA, $method = 'POST', json_encode($data));
		
		//Retrieve the JSON repsonse in order to fetch the array
		$responseA = json_decode( $responseA );
		
		return $responseA;
	}
	
	/**
	 * CloudFlareProcess:getID_zone
	 * Given a domain/zone, what is its identitifer ID?
	 * @param array $data (the domain name)
	 * @return zone_id
	 */
	public function getID_zone( $data )
	{
		//Build the URL
		$data = http_build_query( $data ); 
		$endpoint = 'zones/?'.$data;
		$url = $this->api_url.$endpoint;
		
		//Run it!
		$responseA = $this->executeRequest($url, $this->headerA, $method = 'GET' );
		
		//Retrieve the JSON repsonse in order to fetch the array
		$responseA = json_decode( $responseA );
        
		//Domain invalid? Then return false
		$zone_id = isset($responseA->result[0]->id) ? $responseA->result[0]->id : false;
	
		return $zone_id;
	}
	
	/**
	 * CloudFlareProcess:get_dns_records
	 * Given a domain/zone, and DNS type - what are its IDs?
	 * @param array $data (the domain name)
	 * @param array $typeA (DNS type (A, AAA, CNAME, MX)
	 * @return arrays of IDs (identifiers)
	 */
	public function get_dns_records( $data, $typeA )
	{
		$dataA = array(); //local data storing DNS information
		
		//Build the URL
		$zone_id = $this->getID_zone( $data ); #get the ID based on the zone/domain
		
		$type = http_build_query( $typeA ); 
		$endpoint = 'zones/' . $zone_id . '/dns_records/?' . $type;
		$url = $this->api_url . $endpoint;
		
		//Run it!
		$responseA = $this->executeRequest($url, $this->headerA, $method = 'GET' );
		
		//Retrieve the JSON repsonse in order to fetch the array
		$responseA = json_decode( $responseA );
		
		//Did it return anything?
		if(isset($responseA->result_info->count) && $responseA->result_info->count) {
			
			//Store the data into the $datA array
			foreach( $responseA->result AS $data){
				$dataA[] = $data->id;
			}
		}

		return $dataA;
	}
	
	/**
	 * CloudFlareProcess:delete_dns_records
	 * Given a domain/zone, and DNS type - what are its IDs?
	 * @param array $data (the domain name)
	 * @param array $dnsIDA (array of IDs based on DNS A records)
	 * @return 
	 */
	public function delete_dns_records( $data, $dnsIDA )
	{
		//Build the URL
		$zone_id = $this->getID_zone( $data ); #get the ID based on the zone/domain
		
		foreach( $dnsIDA AS $identifier)
        {
			$endpoint = 'zones/' . $zone_id . '/dns_records/' . $identifier;
			$url = $this->api_url . $endpoint;
			
			//Run it!
			$responseA = $this->executeRequest($url, $this->headerA, $method = 'DELETE' );
		}
			
		return true;
	}
	
	/**
	 * CloudFlareProcess:create_dns_records
	 * Given a domain/zone, and DNS type - what are its IDs?
	 * @param array $data (the domain name)
	 * @param JSON str $ipA (host IP to point the A record to)
	 * @return arrays of IDs (identifiers)
	 */
	public function create_dns_records( $data, $ipA )
	{
		//Build the URL
		$zone_id = $this->getID_zone( $data ); #get the ID based on the zone/domain
		
		//$ipA = http_build_query( $ipA ); 
		$endpoint = 'zones/' . $zone_id . '/dns_records/';
		$url = $this->api_url . $endpoint;
		
		//print_r( json_encode( $ipA ) ); exit;
		//Run it!
		$responseA = $this->executeRequest($url, $this->headerA, $method = 'POST', $ipA);
		
		//Retrieve the JSON repsonse in order to fetch the array
		$responseA = json_decode( $responseA );

		return $responseA;
	}
	
	
	/**
	 * CloudFlareProcess:delete_zone
	 * Delete a zone from CloudFlare
	 * @param array $data (the domain name)
	 * @return
	 */
	public function delete_zone( $data )
	{
		//Build the URL
		$zone_id = $this->getID_zone( $data ); #get the ID based on the zone/domain
		
		if(!$zone_id)
        {
			 trigger_error("Identifier for zone not found", E_USER_WARNING); 	
		}
		
		$endpoint = 'zones/' . $zone_id;
		$url = $this->api_url . $endpoint;
		
		//Run it!
		$responseA = $this->executeRequest($url, $this->headerA, $method = 'DELETE');
		
		//Retrieve the JSON repsonse in order to fetch the array
		$responseA = json_decode( $responseA );
		
		//If zone is successfully deleted, return true
		//Else, return the specific error messages
		//(https://www.cloudflare.com/docs/next/#zone-delete-a-zone)
		
		if($responseA->success){
			return true;	
		} else{
			return $responseA->errors;
		}

	}
	
	 /**
	 * CloudFlareProcess:update_zone_ssl
	 * Allow to turn on/off an SSL, or change its settings 
	 * valid values: (off, flexible, full, full_strict)
	 * 
	 * Enable = 'full', Disabled = 'off'
	 *
	 * @param array $data (the domain name)
	 * @return
	 */
	 public function update_zone_ssl( $data, $sslVal = 'off' )
	 {
		//Build the URL
		$zone_id = $this->getID_zone( $data ); #get the ID based on the zone/domain
		
		if(!$zone_id)
        {
			 trigger_error("Identifier for zone not found", E_USER_WARNING); 	
		}
		
		$endpoint = 'zones/' . $zone_id . '/settings/ssl';
		$url = $this->api_url . $endpoint; 
		
		$sslA = array(
			'value' => $sslVal
		);
		
		//Run it!
		$responseA = $this->executeRequest($url, $this->headerA, $method = 'PATCH', json_encode($sslA) );
		
		//Retrieve the JSON repsonse in order to fetch the array
		$responseA = json_decode( $responseA );
		
		return $responseA;

	 }
}
    