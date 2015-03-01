<?php

class cRestClient {

	private $url;
	private $headers = array(
		'Content-Type' => 'text/html',

		'User-Agent' => 'Simple PHP Rest Client',
	);
	private $method = 'GET';
	private $basicAuth;
	private $payload;
	private $timeout = 30;
	private $verbose = false;
	private $showHeaders = false;
	private $error = array();

	function __construct($username = null, $password = null, $headers = array()) {

		if (!empty($username)) {
			$this->setBasicAuth($username, $password);
		}

		foreach ($headers as $name => $value) {
			$this->addHeader($name, $value);
		}

	}

	public function go() {
		return $this->_doRequest();
	}

	public function get($url = null) {
		$this->setUrl($url);
		$this->setMethod('GET');
		return $this->_doRequest();
	}

	public function post($url = null, $payload = null) {
		$this->setUrl($url);
		$this->setPayload($payload);
		$this->setMethod('POST');
		return $this->_doRequest();
	}

	public function put($url = null, $payload = null) {
		$this->setUrl($url);
		$this->setPayload($payload);
		$this->setMethod('PUT');
		return $this->_doRequest();
	}

	public function delete($url = null) {
		$this->setUrl($url);
		$this->setMethod('DELETE');
		return $this->_doRequest();
	}

	public function setUrl($url = null) {
		$this->url = $url;
	}

	public function setTimeout($timeout = null) {
		if(!empty($timeout)) {
			$this->timeout = $timeout;
		}
	}

	public function setBasicAuth($username = null, $password = null) {
		if (empty($username)) {
			throw new Exception('Username cannot be blank.');
		}
		$this->basicAuth = "$username:$password";
	}

	public function setMethod($method = null) {
		$this->method = strtoupper($method);
	}

	public function setPayload($payload = null) {
		$this->payload = $payload;
	}

	public function setVerbose($verbose = false) {
		$this->verbose = $verbose;
	}

	public function setShowHeaders($show = false) {
		$this->showHeaders = $show;
	}

	public function addHeader($name = null, $value = null) {
		if (empty($name)) {
			throw new Exception('Header name cannot be blank.');
		}

		array_push($this->headers, "$name: $value");
	}

	public function getError() {
		return $this->error;
	}

	private function setError($http_code, $body) {
		$this->error['http_code'] = $http_code;
		$this->error['body'] = $body;
	}

	private function _doRequest() {

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $this->url);
		curl_setopt($c, CURLOPT_HEADER, $this->showHeaders);

		if ($this->method == 'POST' || $this->method == 'PUT') {
			curl_setopt($c, CURLOPT_CUSTOMREQUEST, $this->method);
			curl_setopt($c, CURLOPT_POSTFIELDS, $this->payload);
		} elseif ($this->method == 'DELETE') {
			curl_setopt($c, CURLOPT_CUSTOMREQUEST, $this->method);
		}

		curl_setopt($c, CURLOPT_USERPWD, $this->basicAuth);
		curl_setopt($c, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_VERBOSE, $this->verbose);

		curl_setopt($c, CURLOPT_HTTPHEADER, $this->headers);

		$result = curl_exec($c);
		$info = curl_getinfo($c);
		curl_close($c);

		if (preg_match("/^(20)[0-1]/", $info['http_code'])) {
			return $result;
		} else {
			$this->setError($info['http_code'], $result);
			return false;
		}
	}
}
