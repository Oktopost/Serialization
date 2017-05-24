<?php
namespace Serialization;
/** @var $this \Skeleton\Base\IBoneConstructor */


use Serialization\Base;
use Serialization\Json\JsonSerializersContainer;
use Serialization\Serializers\JsonSerializer;


$this->set(Base\IJsonSerializer::class,				        JsonSerializer::class);
$this->set(Base\Json\IJsonSerializersContainer::class,		JsonSerializersContainer::class);