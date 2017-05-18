<?php
namespace Serialization\Exceptions;


class InvalidJsonException extends \Exception
{
	public function __construct($json)
	{
		parent::__construct(
			'Could not decode json: "' . json_last_error_msg() . '", from string: ' . $json, 
			json_last_error(), 
			null);
	}
}