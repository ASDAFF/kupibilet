<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

/** @global CMain $APPLICATION */
/** @var Local\Main\TimHalls $component */

$hall = $component->hall;

$events = \Local\Main\Event::getByHall($hall['ID']);


debugmessage($hall);


foreach ($events as $event)
{
	?>
	<?
	print_r($hall);?>
	<p><a href="<?= $event['DETAIL_PAGE_URL'] ?>"><?= $event['NAME'] ?></a></p><?
}


$APPLICATION->SetTitle($item['NAME']);