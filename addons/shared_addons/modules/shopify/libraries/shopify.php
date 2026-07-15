<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shopify {

    public $shop_domain;
    private $token;
    private $api_key;
    private $secret;
    private $ci;
    private $last_response_headers = null;

    //public function __construct($shop_domain, $token, $api_key, $secret) {
    public function init($params = array()) {
        $this->name = "ShopifyClient";
        $this->shop_domain = $params['shop_domain'];
        $this->token = $params['token'];
        $this->api_key = $params['api_key'];
        $this->secret = $params['secret'];
    }

    // Get the URL required to request authorization
    public function getAuthorizeUrl($scope, $redirect_url = '') {
        $url = "https://{$this->shop_domain}/admin/oauth/authorize?client_id={$this->api_key}&scope=" . urlencode($scope);
        if ($redirect_url != '') {
            $url .= "&redirect_uri=" . urlencode($redirect_url);
        }
        return $url;
    }

    // Once the User has authorized the app, call this with the code to get the access token
    public function getAccessToken($code) {
        // POST to  POST https://SHOP_NAME.myshopify.com/admin/oauth/access_token
        $url = "https://{$this->shop_domain}/admin/oauth/access_token";
        $payload = "client_id={$this->api_key}&client_secret={$this->secret}&code=$code";
        $response = $this->curlHttpApiRequest('POST', $url, '', $payload, array());
        $response = json_decode($response, true);
        if (isset($response['access_token'])) {
            return $response['access_token'];
        } else {
            print_r($response);
        }
        return false;
    }

    public function callsMade() {
        return $this->shopApiCallLimitParam(0);
    }

    public function callLimit() {
        return $this->shopApiCallLimitParam(1);
    }

    public function callsLeft() {
        return $this->callLimit() - $this->callsMade();
    }

    public function call($method, $path, $params = array()) {
       /* $baseurl = "https://{$this->shop_domain}/";

        $url = $baseurl . ltrim($path, '/');
        $query = in_array($method, array('GET', 'DELETE')) ? $params : array();
        $payload = in_array($method, array('POST', 'PUT')) ? stripslashes(json_encode($params)) : array();
        $request_headers = in_array($method, array('POST', 'PUT')) ? array("Content-Type: application/json; charset=UTF-8","Expect:") : array();

        // add auth headers
        $request_headers[] = 'X-Shopify-Access-Token: ' . $this->token; */
		
		$baseurl = "https://{$this->shop_domain}/";
		$url = $baseurl . ltrim($path, '/');
		
		// GraphQL compatibility: Ensure method is POST and payload includes query and variables
		if ($path === "graphql.json") {
			if (!isset($params['query'])) {
				throw new Exception('GraphQL query is required.');
			}
			$payload = json_encode([
				'query' => $params['query'],
				'variables' => isset($params['variables']) ? $params['variables'] : null
			]);
			$request_headers = [
				"Content-Type: application/json; charset=utf-8",
				'Expect:',
				'X-Shopify-Access-Token: ' . $this->token,
				'x-shopify-api-version: 2024-10'
			];
		} else {
			// Default behavior for REST
			$query = in_array($method, ['GET', 'DELETE']) ? $params : [];
			$payload = in_array($method, ['POST', 'PUT']) ? stripslashes(json_encode($params)) : [];
			$request_headers = in_array($method, array('POST', 'PUT')) ? array("Content-Type: application/json; charset=UTF-8","Expect:") : array();
			// add auth headers
			$request_headers[] = 'X-Shopify-Access-Token: ' . $this->token;
			$request_headers[] = 'x-shopify-api-version: 2024-01';
		}
        
        $response = $this->curlHttpApiRequest($method, $url, $query, $payload, $request_headers);
        $response = json_decode($response, true);

        if (isset($response['errors']) or ( $this->last_response_headers['http_status_code'] >= 400)) {
            $data = array(
                'method' => $method,
                'path' => $path,
                'params' => $params,
                'response_headers' => $this->last_response_headers,
                'response' => $response
            );
            $data['status_response'] = 'error';
            $data['status_code'] = $this->last_response_headers['http_status_code'];
            return $data;
            //throw new ShopifyApiException($method, $path, $params, $this->last_response_headers, $response);
        }
        /* if(is_array($response) and (count($response) > 0)){ 
          $return =array_shift($response);
          }else{ */
        $return = $response;
        //}
        $return['status_response'] = 'success';
        $return['status_code'] = $this->last_response_headers['http_status_code'];
        return $return;
    }
    private function curlHttpApiRequest($method, $url, $query = '', $payload = '', $request_headers = array()) {
        $url = $this->curlAppendQuery($url, $query);
        $ch = curl_init($url);
        $this->curlSetopts($ch, $method, $payload, $request_headers);
        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($errno)
            throw new ShopifyCurlException($error, $errno);
        list($message_headers, $message_body) = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
        $this->last_response_headers = $this->curlParseHeaders($message_headers);

        return $message_body;
    }
	/*New code added by Priyanka for Cursor based pagination- product update*/
	public function call1($method, $path, $params = array()) {
        /* $baseurl = "https://{$this->shop_domain}/";

        $url = $baseurl . ltrim($path, '/');
        $query = in_array($method, array('GET', 'DELETE')) ? $params : array();
        
        $payload = in_array($method, array('POST', 'PUT')) ? stripslashes(json_encode($params)) : array();
        $request_headers = in_array($method, array('POST', 'PUT')) ? array("Content-Type: application/json; charset=UTF-8","Expect:") : array();

        // add auth headers
        $request_headers[] = 'X-Shopify-Access-Token: ' . $this->token; */
		$baseurl = "https://{$this->shop_domain}/";
		$url = $baseurl . ltrim($path, '/');
		
		// GraphQL compatibility: Ensure method is POST and payload includes query and variables
		if ($path === "graphql.json") {
			if (!isset($params['query'])) {
				throw new Exception('GraphQL query is required.');
			}
			$payload = json_encode([
				'query' => $params['query'],
				'variables' => isset($params['variables']) ? $params['variables'] : null
			]);
			$request_headers = [
				"Content-Type: application/json; charset=utf-8",
				'Expect:',
				'X-Shopify-Access-Token: ' . $this->token,
				'x-shopify-api-version: 2024-10'
			];
		} else {
			// Default behavior for REST
			$query = in_array($method, ['GET', 'DELETE']) ? $params : [];
			$payload = in_array($method, ['POST', 'PUT']) ? stripslashes(json_encode($params)) : [];
			$request_headers = in_array($method, array('POST', 'PUT')) ? array("Content-Type: application/json; charset=UTF-8","Expect:") : array();
			// add auth headers
			$request_headers[] = 'X-Shopify-Access-Token: ' . $this->token;
			$request_headers[] = 'x-shopify-api-version: 2024-01';
		}
        
        $response = $this->curlHttpApiRequest1($method, $url, $query, $payload, $request_headers);
         return $response;
          
           
        
    }
    private function curlHttpApiRequest1($method, $url, $query = '', $payload = '', $request_headers = array()) {
        $url = $this->curlAppendQuery($url, $query);
		
        $ch = curl_init($url);
        $this->curlSetopts($ch, $method, $payload, $request_headers);
        $response = curl_exec($ch);

		
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($errno)
            throw new ShopifyCurlException($error, $errno);
        list($message_headers, $message_body) = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
		//var_dump($message_headers);
        $this->last_response_headers = $this->curlParseHeaders($message_headers);
	
        //return $message_body;
		$responseHeaders =  $this->curlParseHeaders($message_headers);  
		// return $responseHeaders;
		   $tokenType = 'next';
    if(array_key_exists('link',$responseHeaders)){
        $link = $responseHeaders['link'];
        $tokenType  = strpos($link,'rel="next') !== false ? "next" : "previous";
        $tobeReplace = ["<",">",'rel="next"',";",'rel="previous"'];
        $tobeReplaceWith = ["","","",""];
		$op1 = str_replace($tobeReplace,$tobeReplaceWith,$link);
		$op2 = parse_str($op1);
		//echo $page_info."<br>";
		
       // $op = parse_str(parse_url(str_replace($tobeReplace,$tobeReplaceWith,$link),PHP_URL_QUERY));
        $pageToken = trim($page_info);
		
    }
		$responseBody = json_decode($message_body,true);
    $r['resource'] =  (is_array($responseBody) && count($responseBody) > 0) ? array_shift($responseBody) : $responseBody;
    $r[$tokenType]['page_token'] = isset($pageToken) ? $pageToken : null;
    
        return $r;
	}
     private function curlHttpApiRequest_backup($method, $url, $query = '', $payload = '', $request_headers = array()) {
        $url = $this->curlAppendQuery($url, $query);
        
        $ch = curl_init($url);
        $this->curlSetopts($ch, $method, $payload, $request_headers);
        $response = curl_exec($ch);

        
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($errno)
            throw new ShopifyCurlException($error, $errno);
        list($message_headers, $message_body) = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);        
        $this->last_response_headers = $this->curlParseHeaders($message_headers);            
        $responseHeaders =  $this->curlParseHeaders($message_headers);           
           $tokenType = 'next';
    if(array_key_exists('Link',$responseHeaders)){
        $link = $responseHeaders['Link'][0];
        $tokenType  = strpos($link,'rel="next') !== false ? "next" : "previous";
        $tobeReplace = ["<",">",'rel="next"',";",'rel="previous"'];
        $tobeReplaceWith = ["","","",""];
        parse_str(parse_url(str_replace($tobeReplace,$tobeReplaceWith,$link),PHP_URL_QUERY),$op);
        $pageToken = trim($op['page_info']);
    }
    
    $responseBody = json_decode($message_body,true);
    $r['resource'] =  (is_array($responseBody) && count($responseBody) > 0) ? array_shift($responseBody) : $responseBody;
    $r[$tokenType]['page_token'] = isset($pageToken) ? $pageToken : null;
    
        return $r;
         
        
    }
	
	
	/*New code added by Priyanka for Cursor based pagination- product update*/
    
    

    private function curlAppendQuery($url, $query) {
        if (empty($query))
            return $url;
        if (is_array($query))
            return "$url?" . http_build_query($query);
        else
            return "$url?$query";
    }

    private function curlSetopts($ch, $method, $payload, $request_headers) {
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, 'HAC');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if (!empty($request_headers)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        }

        if ($method != 'GET' && !empty($payload)) {
            if (is_array($payload))
                $payload = json_encode($payload);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }
    }

    private function curlParseHeaders($message_headers) {
        $header_lines = preg_split("/\r\n|\n|\r/", $message_headers);
        $headers = array();
        list(, $headers['http_status_code'], $headers['http_status_message']) = explode(' ', trim(array_shift($header_lines)), 3);
        foreach ($header_lines as $header_line) {
            list($name, $value) = explode(':', $header_line, 2);
            $name = strtolower($name);
            $headers[$name] = trim($value);
        }

        return $headers;
    }

    private function shopApiCallLimitParam($index) {
        if ($this->last_response_headers == null) {
            throw new Exception('Cannot be called before an API call.');
        }
        $params = explode('/', $this->last_response_headers['http_x_shopify_shop_api_call_limit']);
        return (int) $params[$index];
    }

}

class ShopifyCurlException extends Exception {

    protected $error;
    protected $errorno;

    function __construct($error, $errorno) {
        $this->error = $error;
        $this->errorno = $errorno;
    }

    function getResponse() {
        return $this->errorno . ':' . $this->error;
    }

}

class ShopifyApiException extends Exception {

    protected $method;
    protected $path;
    protected $params;
    protected $response_headers;
    protected $response;
    protected $ci;

    function __construct($method, $path, $params, $response_headers, $response) {
        $this->ci = & get_instance();
        $this->method = $method;
        $this->path = $path;
        $this->params = $params;
        $this->response_headers = $response_headers;
        $this->response = $response;

        $data = array(
            'method' => $method,
            'path' => $path,
            'params' => $params,
            'response_headers' => $response_headers,
            'response' => $response
        );
        //parent::__construct($response_headers['http_status_message'], $response_headers['http_status_code']);
        $this->ci->load->view('shopify/error', array('data' => $data));
    }

    function getMethod() {
        return $this->method;
    }

    function getPath() {
        return $this->path;
    }

    function getParams() {
        return $this->params;
    }

    function getResponseHeaders() {
        return $this->response_headers;
    }

    function getResponse() {
        return $this->response;
    }

}

?>