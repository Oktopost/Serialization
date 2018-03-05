<?php
namespace Serialization\Json\Serializers;


use Serialization\Json\Plain\PlainNarrowSerializer;


class NarrowLiteSerializer extends TypedSerializer
{
	public function __construct()
	{
		parent::__construct('NarrowLiteSerializer', new PlainNarrowSerializer());
	}
}