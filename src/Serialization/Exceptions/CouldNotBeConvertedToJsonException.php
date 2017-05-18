<?php
namespace Serialization\Exceptions;


class CouldNotBeConvertedToJsonException extends \Exception
{
	public function __construct()
	{
		parent::__construct(
			'Could not convert data object to json: ' . json_last_error_msg(), 
			json_last_error(), 
			null);
	}
}