<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @global CMain $APPLICATION */

$selectedCity = \Local\Main\City::getSelected();

$data = \Local\Main\Event::getDataByFilter(array('CITIES' => array($selectedCity => $selectedCity)));

$events = \Local\Main\Event::getByFilter(
	array('DATE' => 'asc'),
	$data['IDS'],
	false
);
$dates = [];

?>
<div class="engBox engContent">
    <div class="engBox-content">
		<div class="elList">
			<div class="grid-sizer"></div><?

			foreach ($events['ITEMS'] as $item)
			{
				$hall = \Local\Main\Hall::getById($item['HALL']);
				$run = \Local\Main\Run::getClosest($item['RUNS']);

				$price = $item['PRICE'];
				if ($item['PRICE'] != $item['PRICE_TO'])
					$price .= ' - ' . $item['PRICE_TO'];

				?>
				<div class="it-item">
					<div class="it-img">
                        <a class="engAnm" href="<?= $item['DETAIL_PAGE_URL'] ?>"  style="background-image: url(<?= $item['PREVIEW_PICTURE']['src'] ?>);filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?= $item['PREVIEW_PICTURE']['src'] ?>');">
                            <img src="<?= $item['PREVIEW_PICTURE']['src'] ?>"
                                 width="<?= $item['PREVIEW_PICTURE']['width'] ?>"
                                 height="<?= $item['PREVIEW_PICTURE']['height'] ?>">
                        </a>
					</div>
					<div class="it-inf">
						<div class="it-title"><?= $item['NAME'] ?></div><?

						if ($run)
						{
							$href = $item['DETAIL_PAGE_URL'] . $run['FURL'];
							?>
							<div class="it-date"><i class="engIcon setIcon-date-black"></i><?= $run['DATE_S'] ?></div>
							<a class="engBtn-kyp" href="<?= $href ?>">Купить билет</a><?
						}

						?>
						<div class="it-map"><i class="engIcon setIcon-map-black"></i><?= $hall['NAME'] ?></div>
						<div class="it-money"><i class="engIcon setIcon-price-black"></i><?= $price ?> руб.</div>
					</div>
				</div><?

				foreach ($item['RUNS'] as $run)
				{
					$date = ConvertTimeStamp($run['TS']);
					$dates[$date] = $date;
				}
			}
			?>
		</div>
    </div>
    <div class="engBox-right">
        <div class="elRight-filter">
            <div class="it-item">
                <div class="it-date-form">
                    <div id="engDate-picter"></div>
                    <script>
	                    var picterDates = <?= json_encode(array_values($dates))?>;
                    </script>
                </div>
            </div>
        </div><?

	    $APPLICATION->IncludeComponent('tim:empty', 'subscribe');

	    $APPLICATION->IncludeComponent('tim:empty', 'right_banners');
	    ?>
    </div>
</div><?
