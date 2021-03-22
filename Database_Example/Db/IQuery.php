<?php
namespace Db;

/**
 * Interface IQuery
 */
interface IQuery
{
	public function getQuery();
	public function getDriver();
}