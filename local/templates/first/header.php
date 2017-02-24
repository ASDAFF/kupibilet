<!DOCTYPE html>
<html>
<head><?

	/** @var CMain $APPLICATION */
	/** @var CUser $USER */

	?>
	<title><?$APPLICATION->ShowTitle();?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?

	$assets = \Bitrix\Main\Page\Asset::getInstance();

	$assets->addJs('/js/jquery.js');
	$assets->addJs('/js/events.js');

	$APPLICATION->ShowHead();
	?>
</head>
<body><?

$APPLICATION->ShowPanel();

?>
<div class="navbar">
	<hr />
	header
	<a href="/">Главная</a>
	<a href="/bitrix/admin">(Админка)</a>
	<hr />
</div>
<div><?

	$APPLICATION->IncludeComponent('bitrix:breadcrumb', '', Array());

	?>
	<h1 id="h1"><? $APPLICATION->ShowTitle(false, false); ?></h1>
	<div id="content">

