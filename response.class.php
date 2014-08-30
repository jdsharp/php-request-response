<?php

/* 
 * Request Response Wrapper Class (https://github.com/jdsharp/php-request-response)
 * Copyright (c) 2014, Jonathan Sharp (http://jdsharp.com)
 * All rights reserved.
 * 
 * Licensed under the MIT license.
 */
class RequestResponse implements JsonSerializable {
	const SUCCESS   = 'success';
	const ERROR     = 'error';
		
	private $status = 'undefined';
	
	// The payload
	public $data    = null;
	// Additional meta-data about the error
	public $state   = null;

	public function __construct($status, $data = null, $state = null) {
		$this->status = $status;

		if ( $data !== null ) {
			$this->data = $data;
		}
		
		if ( $state !== null ) {
			$this->state = $state;
		}
	}

	public function success()
	{
		return $this->status == self::SUCCESS;
	}

	public function error()
	{
		return $this->status == self::ERROR;
	}

	public function jsonSerialize() {
		$obj = new stdClass;
		$obj->status = $this->status;
		$obj->data   = $this->data;
		$obj->state  = $this->state;
		return $obj;
	}
}

class SuccessResponse extends RequestResponse {
	public function __construct($data = null) {
		parent::__construct(self::SUCCESS, $data);
	}
}
class ErrorResponse extends RequestResponse {
	public function __construct($data = null) {
		parent::__construct(self::ERROR, $data);
	}
}

