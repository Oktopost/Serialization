<?php
namespace Serialization\Json\Serializers;


use Serialization\Json\Plain\PlainLiteObjectSerializer;


class LiteObjectSerializer extends TypedSerializer
{
	public function __construct()
	{
		parent::__construct('LiteObjectSerializer', new PlainLiteObjectSerializer());
	}
}