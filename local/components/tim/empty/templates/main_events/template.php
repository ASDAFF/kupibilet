<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// TODO: Пока оставим 15 первых попавшихся событий, дальше добавим какую-то нужную сортировку и фильтр
$events = \Local\Main\Event::getByFilter(array(), array(), array('nTopCount' => 15));

$calendar = false;
$now = time();

?>
<div class="engBox engContent">
	<div class="elList">
		<div class="grid-sizer"></div>
		<?

		foreach ($events['ITEMS'] as $item)
		{
			$hall = \Local\Main\Hall::getById($item['HALL']);
			$run = \Local\Main\Run::getClosest($item['RUNS']);

			if ($run)
			{
				$price = $run['MIN_PRICE'];
				if ($run['MIN_PRICE'] != $run['MIN_PRICE'])
					$price .= ' - ' . $run['MAX_PRICE'];
			}

			?>
			<div class="it-item">
				<div class="it-img">
					<a href="<?= $item['DETAIL_PAGE_URL'] ?>">
						<img src="<?= $item['PREVIEW_PICTURE'] ?>" />
					</a>
				</div>
				<div class="it-inf">
					<div class="it-title"><?= $item['NAME'] ?></div><?

					if ($run)
					{
						?>
						<div class="it-date"><i class="engIcon setIcon-date-black"></i><?= $run['DATE'] ?></div>
						<div class="it-map"><i class="engIcon setIcon-map-black"></i><?= $hall['NAME'] ?></div>
						<div class="it-money"><i class="engIcon setIcon-price-black"></i><?= $price ?> руб.</div><?
					}

					?>
				</div>
			</div><?

			if (!$calendar)
			{
				$calendar = true;
				?>
				<div class="it-item">
					<div class="it-date-form">
						<div id="engDate-picter"></div>
					</div>
				</div><?
			}
		}

		if (!$calendar)
		{
			$calendar = true;
			?>
			<div class="it-item">
				<div class="it-date-form">
					<div id="engDate-picter"></div>
				</div>
			</div><?
		}

		?>
	</div>
</div><?
