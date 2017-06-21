<?php
namespace Serialization\Base\Json;


interface IJsonSerializerDataConstructor extends IJsonDataConstructor
{
	public function __construct(IJsonSerializersContainer $container);
}
