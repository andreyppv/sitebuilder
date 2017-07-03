<?php

namespace App\Libraries;

use App\Models\Domain;
use App\Models\Transaction;
use App\Libraries\XMLTransactionHander;
use App\Libraries\CloudFlareProcess;

use CustomerAuth;

class DomainProcess
{
    private $_process_url = 'https://api.rxportalexpress.com/V1.0/';
    private $_process_gui = 'c7c91b62-72b8-4948-83b3-bd27a5262cd3';
    
    private $_cldfr_email = '';
    private $_cldfr_key = '';
    private $_host_ip = '107.180.18.58';
    
    private $_customer = false;
    
    public function __construct()
    {
        $auth = new CustomerAuth();
        $this->_customer = $auth->customer();
    }
    
    /**
     * DomainProcess::valid_email()
     * 
     * @param mixed $email
     * @return
     */
    function valid_email($email)
    {
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            $isValid = false;
        } else {
            $domain    = substr($email, $atIndex + 1);
            $local     = substr($email, 0, $atIndex);
            $localLen  = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64) {
                $isValid = false;
            } else if ($domainLen < 1 || $domainLen > 255) {
                $isValid = false;
            } else if ($local[0] == '.' || $local[$localLen - 1] == '.') {
                $isValid = false;
            } else if (preg_match('/\\.\\./', $local)) {
                $isValid = false;
            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                $isValid = false;
            } else if (preg_match('/\\.\\./', $domain)) {
                $isValid = false;
            } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
                if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local))) {
                    $isValid = false;
                }
            }
            if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
                $isValid = false;
            }
        }
        return $isValid;
    }
    
    /**
     * DomainProcess::valid_domain()
     * 
     * @param mixed $domain
     * @return
     */
    function valid_domain($domain)
    {
        if (preg_match('/^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/', $domain)) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * DomainProcess::setBaseUrl()
     * 
     * This method will set strip out http(s) including the www
     * Example: https://rapid.com bcomes rapid.com
     *
     * @param mixed $domain
     * @return $domain
     */
    function setBaseUrl($domain)
    {    
        $host = parse_url($domain, PHP_URL_HOST);
        $host = preg_replace('/^(www\.)/i', '', $host);
        return $host;
    }
    
    /**
     * DomainProcess::get_ip_address()
     * 
     * @return
     */
    function get_ip_address()
    {
        foreach (array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ) as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }
    
    /**
     * DomainProcess::writeResponseToFile()
     * 
     * @param mixed $res
     * @return void
     */
    function writeResponseToFile($res)
    {
        $filename = storage_path() . '/logs/log_' . date('Y-m-d_H-i-s') . '.txt';
        file_put_contents($filename, $res);
    }
    
    /**
     * DomainProcess::create_addon_domain()
     * 
     * @param mixed $cpanel_host
     * @param mixed $cpanel_username
     * @param mixed $cpanel_password
     * @param mixed $cpanel_skin
     * @param mixed $addon_domain
     * @param mixed $addon_user
     * @param mixed $addon_pass
     * @param mixed $addon_dir
     * @return
     */
    function create_addon_domain($cpanel_host, $cpanel_username, $cpanel_password, $cpanel_skin, $addon_domain, $addon_user, $addon_pass, $addon_dir)
    {
        $url = "https://$cpanel_username:$cpanel_password@$cpanel_host:2083/frontend/$cpanel_skin/addon/doadddomain.html?";
        $url .= "domain=$addon_domain&user=$addon_user&dir=$addon_dir&pass=$addon_pass&pass2=$addon_pass";
        $result = @file_get_contents($url);
        writeResponseToFile($result);
        if ($result === false)
            return false;
        return $result;
    }
    
    /**
     * DomainProcess::remove_ftp_account()
     * 
     * @param mixed $cpanel_host
     * @param mixed $cpanel_username
     * @param mixed $cpanel_password
     * @param mixed $cpanel_skin
     * @param mixed $ftp_account
     * @return
     */
    function remove_ftp_account($cpanel_host, $cpanel_username, $cpanel_password, $cpanel_skin, $ftp_account)
    {
        $url = "https://$cpanel_username:$cpanel_password@$cpanel_host:2083/frontend/$cpanel_skin/ftp/delftp.html?";
        $url .= "user=$ftp_account&destroy=0";
        $result = @file_get_contents($url);
        writeResponseToFile($result);
        if ($result === false)
            return false;
        return $result;
    }
    
    /**
     * DomainProcess::create_email()
     * 
     * @param mixed $cpanel_host
     * @param mixed $cpanel_username
     * @param mixed $cpanel_password
     * @param mixed $cpanel_skin
     * @param mixed $domain
     * @param mixed $email_user
     * @param mixed $email_pass
     * @param mixed $quota
     * @return
     */
    function create_email($cpanel_host, $cpanel_username, $cpanel_password, $cpanel_skin, $domain, $email_user, $email_pass, $quota)
    {
        $url = "https://$cpanel_username:$cpanel_password@$cpanel_host:2083/frontend/$cpanel_skin/mail/doaddpop.html?";
        $url .= "email=$email_user&domain=$domain&password=$email_pass&quota=$quota";
        $result = @file_get_contents($url);
        writeResponseToFile($result);
        if ($result === false)
            return false;
        return $result;
    }
    
    /**
     * DomainProcess::change_email_password()
     * 
     * @param mixed $cpanel_host
     * @param mixed $cpanel_username
     * @param mixed $cpanel_password
     * @param mixed $cpanel_skin
     * @param mixed $domain
     * @param mixed $email_user
     * @param mixed $email_pass
     * @param mixed $quota
     * @return
     */
    function change_email_password($cpanel_host, $cpanel_username, $cpanel_password, $cpanel_skin, $domain, $email_user, $email_pass, $quota)
    {
        $url = "https://$cpanel_username:$cpanel_password@$cpanel_host:2083/frontend/$cpanel_skin/mail/dopasswdpop.html?";
        $url .= "email=$email_user&domain=$domain&password=$email_pass&quota=$quota";
        $result = @file_get_contents($url);
        writeResponseToFile($result);
        if ($result === false)
            return false;
        return $result;
    }
    
    /**
     * DomainProcess::getCsrValues()
     * 
     * @param mixed $country
     * @param mixed $state
     * @param mixed $locality
     * @param mixed $orgname
     * @param mixed $orgunitname
     * @param mixed $commonName
     * @param mixed $email
     * @return
     */
    function getCsrValues($country, $state, $locality, $orgname, $orgunitname, $commonName, $email)
    {
        $dn = array(
            "countryName" => $country,
            "stateOrProvinceName" => $state,
            "localityName" => $locality,
            "organizationName" => $orgname,
            "organizationalUnitName" => $orgunitname,
            "commonName" => $commonName,
            "emailAddress" => $email
        );
        
        // Generate a new private (and public) key pair
        $privkey = openssl_pkey_new();
        
        // Generate a certificate signing request
        $csr    = openssl_csr_new($dn, $privkey);
        $sscert = openssl_csr_sign($csr, null, $privkey, 365);
        openssl_csr_export($csr, $csrout);
        //openssl_x509_export($sscert, $certout) and var_dump($certout);
        //openssl_pkey_export($privkey, $pkeyout, "mypassword") and var_dump($pkeyout);
        
        // Show any errors that occurred here
        //while (($e = openssl_error_string()) !== false) {
        #echo $e . "\n";
        //}
        return $csrout;
    }
    
    /**
     * DomainProcess::delete_email()
     * 
     * @param mixed $cpanel_host
     * @param mixed $cpanel_username
     * @param mixed $cpanel_password
     * @param mixed $cpanel_skin
     * @param mixed $domain
     * @param mixed $email_user
     * @return
     */
    function delete_email($cpanel_host, $cpanel_username, $cpanel_password, $cpanel_skin, $domain, $email_user)
    {
        $url = "https://$cpanel_username:$cpanel_password@$cpanel_host:2083/frontend/$cpanel_skin/mail/realdelpop.html?";
        $url .= "domain=$domain&email=$email_user";
        $result = @file_get_contents($url);
        writeResponseToFile($result);
        if ($result === false)
            return false;
        return $result;
    }
    
    /**
     * DomainProcess::check_domain()
     * 
     * @param mixed $domain
     * @return
     */
    function check_domain($domain)
    {
        list($domain_name, $domain_extension) = explode('.', $domain);
        
        $post_data = '<serviceRequest>';
        $post_data .= '<command>domainCheck</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<sld>' . $domain_name . '</sld>';
        $post_data .= '<extensions>';
        $post_data .= '<extension>' . $domain_extension . '</extension>';
        $post_data .= '</extensions>';
        $post_data .= '<checkOptions>';
        $post_data .= '<checkOption>Price</checkOption>';
        $post_data .= '<checkOption>Claim</checkOption>';
        $post_data .= '</checkOptions>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    /**
     * DomainProcess::buy_domain()
     * 
     * @param mixed $domain
     * @param mixed $years
     * @param mixed $ns_1
     * @param mixed $ns_2
     * @param mixed $email
     * @param mixed $first_name
     * @param mixed $last_name
     * @param mixed $country
     * @param mixed $city
     * @param mixed $zip
     * @param mixed $address
     * @return
     */
    function buy_domain($domain, $years, $email, $first_name, $last_name, $country, $state, $zip, $address, $phoneno)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>domainAdd</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef></clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<userId></userId>';
        $post_data .= '<domainName>' . $domain . '</domainName>';
        $post_data .= '<term>' . $years . '</term>';
        $post_data .= '<contacts>';
        $post_data .= '<contact>';
        $post_data .= '<title></title>';
        $post_data .= '<firstName>' . $first_name . '</firstName>';
        $post_data .= '<lastName>' . $last_name . '</lastName>';
        $post_data .= '<companyName></companyName>';
        $post_data .= '<companyPositionHeld></companyPositionHeld>';
        $post_data .= '<emailAddress>' . $email . '</emailAddress>';
        $post_data .= '<telephoneNumber>+1.' . $phoneno . '</telephoneNumber>';
        $post_data .= '<faxNumber></faxNumber>';
        $post_data .= '<addressLine1>My Address</addressLine1>';
        $post_data .= '<addressLine2> </addressLine2>';
        $post_data .= '<city>City</city>';
        $post_data .= '<province></province>';
        $post_data .= '<state>' . $state . '</state>';
        $post_data .= '<postalCode>' . $zip . '</postalCode>';
        $post_data .= '<countryCode>' . $country . '</countryCode>';
        $post_data .= '<contactType>Registration</contactType>';
        $post_data .= '</contact>';
        $post_data .= '<contact>';
        $post_data .= '<title></title>';
        $post_data .= '<firstName>' . $first_name . '</firstName>';
        $post_data .= '<lastName>' . $last_name . '</lastName>';
        $post_data .= '<companyName></companyName>';
        $post_data .= '<companyPositionHeld></companyPositionHeld>';
        $post_data .= '<emailAddress>' . $email . '</emailAddress>';
        $post_data .= '<telephoneNumber>+1.' . $phoneno . '</telephoneNumber>';
        $post_data .= '<faxNumber></faxNumber>';
        $post_data .= '<addressLine1>My Address</addressLine1>';
        $post_data .= '<addressLine2> </addressLine2>';
        $post_data .= '<city>City</city>';
        $post_data .= '<province></province>';
        $post_data .= '<state>' . $state . '</state>';
        $post_data .= '<postalCode>' . $zip . '</postalCode>';
        $post_data .= '<countryCode>' . $country . '</countryCode>';
        $post_data .= '<contactType>Administration</contactType>';
        $post_data .= '</contact>';
        $post_data .= '</contacts>';
        /*    $post_data .= '<nameservers>';
        $post_data .= '<nameserver>';
        $post_data .= '<nsType>Primary</nsType>';
        $post_data .= '<nsName>dns1.register.com</nsName>';
        $post_data .= '</nameserver>';
        $post_data .= '<nameserver>';
        $post_data .= '<nsType>Secondary</nsType>';
        $post_data .= '<nsName>dns2.register.com</nsName>';
        $post_data .= '</nameserver>';
        $post_data .= '</nameservers>';  */
        $post_data .= '<zones>';
        $post_data .= '<zone>';
        $post_data .= '<zoneType>A</zoneType><zoneKey>@</zoneKey>';
        $post_data .= '<zoneValue>' . $this->_host_ip . '</zoneValue>';
        $post_data .= '</zone>';
        $post_data .= '<zone>';
        $post_data .= '<zoneType>A</zoneType><zoneKey>*</zoneKey>';
        $post_data .= '<zoneValue>' . $this->_host_ip . '</zoneValue>';
        $post_data .= '</zone>';
        $post_data .= '</zones>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    /**
     * DomainProcess::renew_domain()
     * 
     * @param mixed $domain
     * @param mixed $years
     * @param mixed $domain_order_id
     * @return
     */
    function renew_domain($years, $domain_order_id)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>domainRenew</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef></clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<productId>' . $domain_order_id . '</productId>';
        $post_data .= '<term>' . $years . '</term>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    /**
     * DomainProcess::domain_get()
     * 
     * @param mixed $domain
     * @return
     */
    function domain_get($domain)
    {
        list($domain_name, $domain_extension) = explode('.', $domain);
        
        $post_data = '<serviceRequest>';
        $post_data .= '<command>domainGet</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<page>1</page>';
        $post_data .= '<domains>';
        $post_data .= '<domainName>' . $domain . '</domainName>';
        $post_data .= '</domains>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    /**
     * DomainProcess::domainPrivacyAdd()
     * 
     * @param mixed $domain_product_id
     * @return void
     */
    function domainPrivacyAdd($domain_product_id)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>domainPrivacyAdd</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<productId>' . $domain_product_id . '</productId>';
        $post_data .= '<term>1</term>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    /**
     * DomainProcess::domainPrivacyRenew()
     * 
     * @return
     */
    function domainPrivacyRenew($domain_product_id)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>domainPrivacyRenew</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<productId>' . $domain_product_id . '</productId>';
        $post_data .= '<term>1</term>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    /**
     * DomainProcess::domainPrivacySuspend()
     * 
     * @param mixed $dom_priv_productid
     * @param mixed $private
     * @return void
     */
    function domainPrivacySuspend($dom_priv_productid, $private)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>domainPrivacySuspend</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<productId>' . $dom_priv_productid . '</productId>';
        $post_data .= '<private>' . $private . '</private>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    /**
     * DomainProcess::registerLockProcess()
     * 
     * @param mixed $productid
     * @param mixed $reglock
     * @return void
     */
    function registerLockProcess($productid, $reglock)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>domainLock</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<productId>' . $productid . '</productId>';
        $post_data .= '<registrarLock>' . $reglock . '</registrarLock>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    /**
     * DomainProcess::mailAdd()
     * 
     * @param mixed $domain
     * @param mixed $terms
     * @return
     */
    function mailAdd($domain, $terms)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>mailAdd</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<userId></userId>';
        $post_data .= '<term>' . $terms . '</term>';
        $post_data .= '<productType>Email5G</productType>';
        $post_data .= '<domainName>' . $domain . '</domainName>';
        $post_data .= '<updateDNS>true</updateDNS>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        return $response;
    }
    
    /**
     * DomainProcess::mailAutoRenew()
     * 
     * @param mixed $productid
     * @return
     */
    function mailAutoRenew($productid)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>mailAutoRenew</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<productId>' . $productid . '</productId>>';
        $post_data .= '<autoRenew>TRUE</autoRenew>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        return $response;
    }
    
    /**
     * DomainProcess::mailLogin()
     * 
     * @param mixed $mailproductid
     * @return
     */
    function mailLogin($mailproductid)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>mailLogin</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<productId>' . $mailproductid . '</productId>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        return $response;
    }
    
    /**
     * DomainProcess::mailForwardAdd()
     * 
     * @param mixed $domain_prdtid
     * @param mixed $key
     * @param mixed $forwardmail
     * @return
     */
    function mailForwardAdd($domain_prdtid, $key, $forwardmail)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>mailForwardAdd</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<productId>' . $domain_prdtid . '</productId> ';
        $post_data .= '<mailForwardRules>';
        $post_data .= '<mailForwardRule>';
        $post_data .= '<key>' . $key . '</key>';
        $post_data .= '<emailAddress>' . $forwardmail . '</emailAddress>';
        $post_data .= '</mailForwardRule>';
        $post_data .= '</mailForwardRules>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    function addZone($productid)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>zoneAdd</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<productId>' . $productid . '</productId>';
        $post_data .= '<zones><zone>';
        $post_data .= '<zoneType>A</zoneType>';
        $post_data .= '<zoneKey>www</zoneKey>';
        $post_data .= '<zoneValue>104.238.80.182</zoneValue>';
        $post_data .= '</zone></zones>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
    }
    
    function zoneGet($productid)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>zoneGet</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<productId>' . $productid . '</productId>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
    }
    
    function sslCertificateAdd($terms, $producttype)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>certificateAdd</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<userId></userId>';
        $post_data .= '<term>' . $terms . '</term>';
        $post_data .= '<productType>' . $producttype . '</productType>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    function certificateApproverGet($productId, $domainName)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>certificateApproverGet</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<productId>' . $productId . '</productId>';
        $post_data .= '<domainName>' . $domainName . '</domainName>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    function certificateConfigure($productId, $approveremail, $firstname, $lastname, $email, $phoneno, $addressL1, $addressL2, $city, $provience, $state, $postcode, $csr_values)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>certificateConfigure</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<productId>' . $productId . '</productId>';
        $post_data .= '<serverType>Apache_SSL</serverType>';
        $post_data .= '<approverEmailAddress>' . $approveremail . '</approverEmailAddress>';
        $post_data .= '<csr>' . $csr_values . '</csr>';
        $post_data .= '<contact>';
        $post_data .= '<firstName>' . $firstname . '</firstName>';
        $post_data .= '<lastName>' . $lastname . '</lastName>';
        $post_data .= '<emailAddress>' . $email . '</emailAddress>';
        $post_data .= '<telephoneNumber>+1.' . $phoneno . '</telephoneNumber>';
        $post_data .= '<addressLine1>' . $addressL1 . '</addressLine1>';
        $post_data .= '<addressLine2>' . $addressL2 . '</addressLine2>';
        $post_data .= '<city>' . $city . '</city>';
        $post_data .= '<province>' . $provience . '</province>';
        $post_data .= '<state>' . $state . '</state>';
        $post_data .= '<postalCode>' . $postcode . '</postalCode>';
        $post_data .= '<countryCode>US</countryCode>';
        $post_data .= '<contactType>Administration</contactType>';
        $post_data .= '</contact>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    function certificateGet($domainName)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>certificateGet</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<page>1</page>';
        $post_data .= '<domains><domainName>' . $domainName . '</domainName></domains>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    /**
     * DomainProcess::hostAdd()
     * 
     * @param mixed $domainname
     * @return
     */
    function hostAdd($domainname)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>hostingAdd</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<userId></userId>';
        $post_data .= '<term>1</term>';
        $post_data .= '<productType>LINUXHOSTING-ECONOMY</productType>';
        $post_data .= '<domainName>' . $domainname . '</domainName>';
        $post_data .= '<updateDNS>TRUE</updateDNS>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    /**
     * DomainProcess::hostingLogin()
     * 
     * @param mixed $hostingProductid
     * @return
     */
    function hostingLogin($hostingProductid)
    {
        $post_data = '<serviceRequest>';
        $post_data .= '<command>hostingLogin</command>';
        $post_data .= '<client>';
        $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
        $post_data .= '<clientRef> </clientRef>';
        $post_data .= '</client>';
        $post_data .= '<request>';
        $post_data .= '<productId>' . $hostingProductid . '</productId>';
        $post_data .= '</request>';
        $post_data .= '</serviceRequest>';
        
        $xml_handler = new XMLTransactionHander;
        $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
        
        return $response;
    }
    
    /**
     * DomainProcess::domain_modify_dns()
     * 
     * @param mixed $domain
     * @param array $dnsA (domain name servers)
     * @return
     */
    function domain_modify_dns($domain, $dnsA)
    {
        list($domain_name, $domain_extension) = explode('.', $domain);
        
        $response = $this->domain_get($domain);
        
        if ($domain == $response->response->domainGet->domain->domainInfo->domainName) {
            #productId
            $productid = $response->response->domainGet->domain->domainInfo->productId;
            
            $post_data = '<serviceRequest>';
            $post_data .= '<command>domainModify</command>';
            $post_data .= '<client>';
            $post_data .= '<applicationGuid>' . $this->_process_gui . '</applicationGuid>';
            $post_data .= '<clientRef> </clientRef>';
            $post_data .= '</client>';
            $post_data .= '<request>';
            $post_data .= '<productId>' . $productid . '</productId>';
            
            //Build the nameservers
            $post_data .= '<nameservers>';
            foreach ($dnsA AS $nameserver):
                $post_data .= '<nameserver>';
                $post_data .= '<nsType>' . $nameserver['nsType'] . '</nsType>';
                $post_data .= '<nsName>' . $nameserver['nsName'] . '</nsName>';
                $post_data .= '</nameserver>';
            endforeach;
            
            $post_data .= '</nameservers>';
            
            $post_data .= '</request>';
            $post_data .= '</serviceRequest>';
            
            $xml_handler = new XMLTransactionHander;
            $response    = $xml_handler->executeRequest($this->_process_url, $post_data);
            
            return $response;
        } else {
            trigger_error("Domain not found" . $domain, E_USER_WARNING);
        }
    }
    
    /**
    * set cldflr_email and key
    * 
    * @param mixed $email
    * @param mixed $key
    */
    function setCloudFlareInfo($email='', $key='')
    {
        $this->_cldfr_email = $email;
        $this->_cldfr_key = $key;
    }
    
    /**
     * DomainProcess::addNewDomain()
     * 
     * @param mixed $domain
     * @param mixed $domain_id
     * @param mixed $productId
     * @return void
     */
    function addNewDomain($domain, $productId, $years, $txnid, $amount, $paymentMethod)
    {
        if($this->_cldfr_email == '' || $this->_cldfr_key == '') return false;
        
        $response = $this->domain_get($domain);
        if($domain == $response->response->domainGet->domain->domainInfo->domainName)
        {
            $objCloudFlare = new CloudFlareProcess($this->_cldfr_email, $this->_cldfr_key);
            //$objCloudFlare->create_zone( array( 'name' => $domain, 'jump_start' => true ) );
            $objCloudFlare->create_zone( array( 'name' => $domain, 'jump_start' => false ) );
            $objCloudFlare->update_zone_ssl( array( 'name' => $domain ), 'full' );                    

            $dnsA = array(
                array(
                    'nsType' => 'Primary',
                    'nsName' => 'nick.ns.cloudflare.com'
                ),
                array(
                    'nsType' => 'Secondary',
                    'nsName' => 'lucy.ns.cloudflare.com'
                ),
            );

            //Get DNS ID (array)
            $dnsIDA = $objCloudFlare->get_dns_records( array( 'name' => $domain ), array('type' => 'A' ) );
            
            //Delete DNS (A records), if exists
            if($dnsIDA) 
            {
                $objCloudFlare->delete_dns_records( array( 'name' => $domain ), $dnsIDA );
            }
            
            $mx_record = 'inbound.registeredsite.com';
            $host_ip = $this->_host_ip;
            $data1 = array('type' => 'A', 'name' => '*', 'content' => $host_ip);
            $data2 = array('type' => 'A', 'name' => $domain, 'content' => $host_ip, 'proxiable' => true, 'proxied' => true);
            $data3 = array('type' => 'A', 'name' => 'www', 'content' => $host_ip, 'proxiable' => true, 'proxied' => true);
            $data4 = array('type' => 'MX', 'name' => $domain, 'content' => $mx_record, 'priority' => 0);
            
            //Create new A records in CloudFlare pointing to new IP address (A record)
            $response1 = $objCloudFlare->create_dns_records( array( 'name' => $domain ), json_encode($data1));
            $response2 = $objCloudFlare->create_dns_records( array( 'name' => $domain ), json_encode($data2));
            $response3 = $objCloudFlare->create_dns_records( array( 'name' => $domain ), json_encode($data3));
            $response4 = $objCloudFlare->create_dns_records( array( 'name' => $domain ), json_encode($data4));

            $response1 = $this->domain_modify_dns($domain, $dnsA);

            $exp_date = strtotime(date("d-m-Y", strtotime('+' . $years . ' years'))) + 86399;
            //insert new domain to database table
            $newDomain = new Domain;
            $newDomain->name        = $domain;
            $newDomain->customer_id = $this->_customer->id;
            $newDomain->product_id  = $productId;
            $newDomain->status      = $response->response->domainGet->domain->domainInfo->domainStatus->__toString();
            $newDomain->start_date  = time();
            $newDomain->expire_date = $exp_date;
            $newDomain->ssl_option  = 0;
            $newDomain->auto_renew  = $response->response->domainGet->domain->domainInfo->autoRenew->__toString();
            $newDomain->privacy     = $response->response->domainGet->domain->domainInfo->privacy->__toString();
            $newDomain->password    = $response->response->domainGet->domain->domainInfo->password->__toString();
            $newDomain->first_name  = $response->response->domainGet->domain->contacts->contact->firstName->__toString();
            $newDomain->last_name   = $response->response->domainGet->domain->contacts->contact->lastName->__toString();
            $newDomain->email_address = $response->response->domainGet->domain->contacts->contact->emailAddress->__toString();
            $newDomain->address     = $response->response->domainGet->domain->contacts->contact->addressLine1->__toString();
            $newDomain->address2    = $response->response->domainGet->domain->contacts->contact->addressLine2->__toString();
            $newDomain->city        = $response->response->domainGet->domain->contacts->contact->city->__toString();
            $newDomain->state       = $response->response->domainGet->domain->contacts->contact->state->__toString();
            $newDomain->postal_code = $response->response->domainGet->domain->contacts->contact->postalCode->__toString();
            $res_ins = $newDomain->save();

            if($res_ins) 
            {
                //insert transaction to database
                $newTransaction = new Transaction;
                $newTransaction->customer_id    = $this->_customer->id;
                $newTransaction->domain_id      = $newDomain->id;
                $newTransaction->description    = sprintf(trans('msg.domain_purchase_description'), $domain, $amount, $years);
                $newTransaction->terms          = $years." Year";
                $newTransaction->type           = 'Debit';
                $newTransaction->method         = $paymentMethod;
                $newTransaction->amount         = $amount;
                $newTransaction->transaction_id = $txnid;
                $res_ins = $newTransaction->save();

                return $res_ins;
            } 
            
            return false;                   
        }
    }
}