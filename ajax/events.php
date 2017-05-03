<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if ($_REQUEST['action'] == 'set_city')
{
	$cityId = $_REQUEST['id'];
	\Local\Main\City::selectCity($cityId);

	$eventsIds = \Local\Main\Event::getByCityId($cityId);
	$events = \Local\Main\Event::getByFilter(
		['DATE' => 'asc'],
		$eventsIds,
		false
	);

	?>
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
            <a class="engAnm" href="<?= $item['DETAIL_PAGE_URL'] ?>"
               style="background-image: url(<?= $item['PREVIEW_PICTURE']['src'] ?>);filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?= $item['PREVIEW_PICTURE']['src'] ?>');">
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
	}

}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");