<?php

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

if ($_REQUEST['action'] == 'set_city')
{
	$cityId = $_REQUEST['id'];
	\Local\Main\City::selectCity($cityId);
	$data = \Local\Main\Event::getDataByFilter(array('CITY' => $cityId));
	$events = \Local\Main\Event::getByFilter(
		array('DATE' => 'asc'),
		$data['IDS'],
		false
	);
	$eventsCount = count($events['ITEMS']);
	$eventsText = \Local\System\Utils::cardinalNumberRus($eventsCount, ' мероприятий', ' мероприятие', ' мероприятия');
	$eventsText = $eventsCount . $eventsText;
	$html = '';
	
    $html .= "<div class='grid-sizer'></div>";

	foreach ($events['ITEMS'] as $item)
	{
		$hall = \Local\Main\Hall::getById($item['HALL']);
		$run = \Local\Main\Run::getClosest($item['RUNS']);

		$price = $item['PRICE'];
		if ($item['PRICE'] != $item['PRICE_TO'])
			$price .= ' - ' . $item['PRICE_TO'];

        $html .= "<div class='it-item'>";
        $html .= "<div class='it-img'>";
        $html .= "<a class='engAnm' href='{$item['DETAIL_PAGE_URL']}'
               style='background-image: url({$item['PREVIEW_PICTURE']['src']});filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='{$item['PREVIEW_PICTURE']['src']}');'>
                <img src='{$item['PREVIEW_PICTURE']['src']}'
                     width='{$item['PREVIEW_PICTURE']['width']}'
                     height='{$item['PREVIEW_PICTURE']['height']}'>
            </a>
        </div>";
        $html .= "<div class='it-inf'>
            <div class='it-title'>{$item['NAME']}</div>";

			if ($run)
			{
				$href = $item['DETAIL_PAGE_URL'] . $run['FURL'];
				$html .= "<div class='it-date'><i class='engIcon setIcon-date-black'></i>{$run['DATE_S']}</div>
            <a class='engBtn-kyp' href='$href'>Купить билет</a>";
			}

            $html .= "<div class='it-map'><i class='engIcon setIcon-map-black'></i>{$hall['NAME']}</div>
            <div class='it-money'><i class='engIcon setIcon-price-black'></i>$price руб.</div>
        </div>
        </div>";
	}

	header('Content-Type: application/json');
	echo json_encode(['COUNT' => $eventsText, 'HTML' => $html]);

}

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');