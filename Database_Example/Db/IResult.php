<?php
namespace Db;

/**
 * Interface IResult
 */
interface IResult
{
	public function getGenerator();
	public function fetchAll();
	public function getNumberRows();
	public function getInsertId();
	public function getAssocRow();
	public function getObject();
	public function getAffectedRows();
}