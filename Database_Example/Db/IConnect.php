<?php
namespace Db;

/**
 * Interface IConnect
 */
interface IConnect
{
	public function query($strSQL, array $params);
}