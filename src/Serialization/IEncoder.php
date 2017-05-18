<?php
namespace Serialization;


interface IEncoder
{
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function encode($data);

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function decode($data);
}