<?
namespace Components;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

class EmptyProject extends \CBitrixComponent
{
	public function executeComponent()
	{
		$this->IncludeComponentTemplate();
	}

}