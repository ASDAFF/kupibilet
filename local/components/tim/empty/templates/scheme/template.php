<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$hall = \Local\Main\Hall::getById($_REQUEST['ID'], true);
if (!$hall)
	return;

$assets = \Bitrix\Main\Page\Asset::getInstance();
$assets->addCss('/css/scheme.css', true);
$assets->addJs('/js/scheme.js');

$scheme = $hall['SCHEME'] ? $hall['SCHEME'] : '{}';
$href = '/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=2&type=main&ID=' . $hall['ID'] .
	'&lang=ru&find_section_section=0&WF=Y';

?>
<div id="options-panel">
	<div class="theater-info">
		<h3>Схема мест театра: <?= $hall['NAME'] ?></h3>
		<a href="<?= $href ?>">Вернуться к форме редактирования элемента</a>
	</div>
	<div>
		<p>Зона</p>
		<input type="text" name="title" value="" style="width:200px;" />
	</div>
	<div>
		<p>Ряд</p>
		<input type="text" name="row" value="" />
	</div>
	<div>
		<p>Место</p>
		<input type="text" name="num" value="" />
	</div>
	<div>
		<p>X</p>
		<input type="text" name="x" value="" />
	</div>
	<div>
		<p>Y</p>
		<input type="text" name="y" value="" />
	</div>
	<div>
		<p>Поворот</p>
		<input type="text" name="rotate" value="" />
	</div>
	<div>
		<input class="btn new" type="button" value="Добавить" />
	</div>
	<div>
		<input class="btn del disabled" type="button" value="Удалить" />
	</div>
	<div>
		<p>Включить попап</p>
		<input type="checkbox" name="popup" />
	</div>
	<div>
		<input class="btn save disabled" type="button" value="Сохранить" />
	</div>
</div>
<div id="options-panel-margin">
</div>
<div class="engBox">
	<div class="elZal" id="elZal" data-id="<?= $hall['ID'] ?>">
		<div class="elZal-box" id="elZal-box">

		</div>
	</div>
</div>

<div id="popup-hidden">
	<div class="elZal-inf">
		<div class="engRow">
			<div class="engBox-12 cssText-center elZal-inf-title"></div>
		</div>
		<div class="engRow">
			<div class="engBox-6">Ряд</div>
			<div class="engBox-6 cssText-right elZal-inf-set"></div>
		</div>
		<div class="engRow">
			<div class="engBox-6">Место</div>
			<div class="engBox-6 cssText-right elZal-inf-number"></div>
		</div>
	</div>
</div>

<script>
	var ZalArray = <?= $scheme ?>;
</script>