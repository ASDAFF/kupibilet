<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arResult */
/** @var Local\Main\TimEvents $component */

if ($component->arParams['AJAX'])
	include ('ajax.php');
elseif ($arResult['NOT_FOUND'])
	include ('not_found.php');
else
	include ('full.php');
