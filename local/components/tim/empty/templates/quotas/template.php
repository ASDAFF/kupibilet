<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$run = \Local\Main\Run::getById($_REQUEST['ID'], true);
if (!$run)
	return;

$event = \Local\Main\Event::getById($run['EVENT']);
if (!$event)
	return;

$hall = \Local\Main\Hall::getById($event['PRODUCT']['HALL']);
if (!$hall)
	return;

$scheme = $hall['SCHEME'] ? $hall['SCHEME'] : '{}';

$assets = \Bitrix\Main\Page\Asset::getInstance();
$assets->addCss('/css/scheme.css');
$assets->addJs('/js/quotas.js');

$quotas = $run['QUOTAS'] ? $run['QUOTAS'] : '{}';
$href = '/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=4&type=main&ID=' . $run['ID'] .
	'&lang=ru&find_section_section=0&WF=Y';

?>
<div id="options-panel">
	<div class="q-groups">
		<table>
			<thead>
				<tr>
					<th>Цена</th>
					<th>Цвет</th>
					<th>ID мест</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input type="text" /></td>
					<td><input type="text" /></td>
					<td><input type="text" readonly /></td>
					<td><input class="btn del" type="button" value="удалить" /></td>
				</tr>
			</tbody>
		</table>
		<input class="btn new" type="button" value="Добавить" />
	</div>
	<div class="theater-info">
		<h3>Квоты: <?= $run['NAME'] ?></h3>
		<a href="<?= $href ?>">Вернуться к форме редактирования элемента</a>
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
	<div class="elZal" id="elZal" data-id="<?= $hall['ID'] ?>" data-run="<?= $run['ID'] ?>">
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
	var QuotasArray = <?= $quotas ?>;
</script>