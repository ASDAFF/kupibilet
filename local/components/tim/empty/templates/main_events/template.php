<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// TODO: Пока оставим 15 первых попавшихся событий, дальше добавим какую-то нужную сортировку и фильтр
$events = \Local\Main\Event::getByFilter(array(), array(), array('nTopCount' => 15));

?>
<div class="engBox engContent">
	<div class="elList">
		<div class="grid-sizer"></div>
		<div class="it-item"><?

			foreach ($events['ITEMS'] as $item)
			{
				$hall = \Local\Main\Hall::getById($item['HALL']);

				?>
				<div class="it-img">
					<a href="<?= $item['DETAIL_PAGE_URL'] ?>">
						<img src="<?= $item['PREVIEW_PICTURE'] ?>" />
					</a>
				</div>
				<div class="it-inf">
					<div class="it-title"><?= $item['NAME'] ?></div>
					<div class="it-date"><i class="engIcon setIcon-date-black"></i>(Дата)</div>
					<div class="it-map"><i class="engIcon setIcon-map-black"></i><?= $hall['NAME'] ?></div>
					<div class="it-money"><i class="engIcon setIcon-price-black"></i>3500 - 5000 руб.</div>
				</div><?
			}

			?>

		</div>
	</div>
</div><?
