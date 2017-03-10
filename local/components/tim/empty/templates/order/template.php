<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$assets = \Bitrix\Main\Page\Asset::getInstance();
$assets->addJs('/js/cart.js');

$id = $_REQUEST['id'];
$order = \Local\Sale\Cart::getOrderById($id);
if (!$order)
	return;

$orderItems = \Local\Sale\Cart::getOrderItems($order['ID']);

/** @var array $arParams */
if ($arParams['PAGE'] == 'complete')
	include ('complete.php');
elseif ($arParams['PAGE'] == 'pay')
	include ('pay.php');
elseif ($arParams['PAGE'] == 'success')
	include ('success.php');
elseif ($arParams['PAGE'] == 'print')
	include ('print.php');

