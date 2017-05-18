<?php
namespace Serialization;
/** @var $this \Skeleton\Base\IBoneConstructor */


use Serialization\Base;
use Serialization\Encoder\Meta;
use Serialization\Encoder\EncodersContainer;


$this->set(Base\ISerialization::class,				Serialization::class);
$this->set(Base\ISerializationContainer::class,		SerializationContainer::class);
$this->set(Base\Encoder\IMeta::class, 				Meta::class);
$this->set(Base\Encoder\IEncodersContainer::class,	EncodersContainer::class);
