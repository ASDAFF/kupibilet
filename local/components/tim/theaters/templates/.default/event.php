<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

/** @global CMain $APPLICATION */
/** @var Local\Main\TimTheaters $component */

$theater = $component->theater;
$event = $component->event;

debugmessage($event);

\Local\Main\Event::viewedCounters($event['ID']);
$APPLICATION->SetTitle($event['NAME']);