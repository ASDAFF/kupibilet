<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

/** @global CMain $APPLICATION */
/** @var Local\Main\TimHalls $component */

$theater = $component->theater;

$events = \Local\Main\Event::getByTheater($theater['ID']);

debugmessage($theater);


foreach ($events as $event)
{
	?>
	<p><a href="<?= $event['DETAIL_PAGE_URL'] ?>"><?= $event['NAME'] ?></a></p><?
}


$APPLICATION->SetTitle($item['NAME']);