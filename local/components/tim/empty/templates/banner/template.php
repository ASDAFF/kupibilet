<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// TODO: Пока оставим 15 первых попавшихся событий, дальше добавим какую-то нужную сортировку и фильтр
$banners = \Local\Main\Banner::getAll(array(), array(), array('nTopCount' => 15));


?>
		<?

		foreach ($banners as $item)
		{
			debugmessage($item);
			$events = \Local\Main\Event::getById($item['EVENT']);

			$hall = \Local\Main\Hall::getById($events['HALL']);
			$run = \Local\Main\Run::getClosest($events['RUNS']);

			$price = $events['PRICE'];
			if ($events['PRICE'] != $events['PRICE_TO'])
				$price .= ' - ' . $events['PRICE_TO'];

			?>
			<div class="elSlider">
				<div class="elSlider-item">
					<img src="<?= $item['PREVIEW_PICTURE'] ?>" />
					<div class="it-inf">
						<div class="it-title"><?=$item['NAME']?></div>
						<div class="it-data"><i class="engIcon setIcon-date-white"></i><?= $run['DATE'] ?></div>
						<div class="it-map"><i class="engIcon setIcon-map-white"></i><?=$hall['NAME']?></div>
						<div class="it-money"><i class="engIcon setIcon-price-white"></i><?=$price?> руб.</div>
						<div class="it-btn">
							<a href="<?=$events['DETAIL_PAGE_URL']?>" class="cssBorderRadius">Купить билет</a>
						</div>
					</div>
				</div>
			</div>
			<?
		}
?>
	</div><?
