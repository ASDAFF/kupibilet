<?
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$APPLICATION->IncludeComponent(
	"bitrix:main.register",
	"",
	Array(
		"USER_PROPERTY_NAME" => "",
		"SEF_MODE" => "N",
		"SHOW_FIELDS" => Array("NAME", "LAST_NAME", "EMAIL", "PERSONAL_PHONE", "PERSONAL_BIRTHDAY"),
		"REQUIRED_FIELDS" => Array("EMAIL"),
		"AUTH" => "Y",
		"USE_BACKURL" => "Y",
		"SUCCESS_PAGE" => $APPLICATION->GetCurPageParam('', array('backurl')),
		"SET_TITLE" => "N",
		"USER_PROPERTY" => Array()
	)
);
