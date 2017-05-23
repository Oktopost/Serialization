<?php
namespace Serialization\Json\Serializers;


use Serialization\Json\Plain\PlainArraySerializer;


class ArraySerializer extends TypedSerializer
{
	public function __construct()
	{
		parent::__construct('ArraySerializer', new PlainArraySerializer());
	}
}