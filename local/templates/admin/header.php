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
	$assets->addJs('/js/scheme.js');
	$assets->addCss('/css/style.css');
	$assets->addCss('/css/scheme.css');

	$APPLICATION->ShowHead();

	?>
</head>
<body><?


