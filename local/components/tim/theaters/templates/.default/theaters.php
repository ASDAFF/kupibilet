<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

/** @global CMain $APPLICATION */
/** @var $arParams */

$items = \Local\Main\Theater::getAll();
foreach ($items['ITEMS'] as $item)
{
	?>
	<p><a href="<?= $item['DETAIL_PAGE_URL'] ?>"><?= $item['NAME'] ?></a></p><?
}

$APPLICATION->SetTitle("Театры");