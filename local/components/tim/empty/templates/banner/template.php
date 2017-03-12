<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$banners = \Local\Main\Banner::getAll(array(), array(), array());

foreach ($banners as $item) {

	if ($item['EVENT'])
	{
		$event = \Local\Main\Event::getById($item['EVENT']);
		$hall = \Local\Main\Hall::getById($event['PRODUCT']['HALL']);
		$run = \Local\Main\Run::getClosest($event['RUNS']);

		$price = $event['PRODUCT']['PRICE'];
		if ($event['PRODUCT']['PRICE'] != $event['PRODUCT']['PRICE_TO'])
			$price .= ' - ' . $event['PRODUCT']['PRICE_TO'];
		$href = $event['DETAIL_PAGE_URL'] . $run['FURL'];
	}

	?>
	<div class="elSlider-item"><?

		if ($item['HREF'])
		{
			?>
			<a href="<?= $item['HREF'] ?>"><img src="<?= $item['PREVIEW_PICTURE'] ?>"/></a><?
		}
		else
		{
			?>
			<img src="<?= $item['PREVIEW_PICTURE'] ?>"/><?
		}

        if (!empty($event))
        {
	        ?>
		    <div class="it-inf">
		        <div class="it-title"><?= $event['NAME'] ?></div><?

		        if (!empty($run))
		        {
			        ?>
                    <div class="it-data"><i class="engIcon setIcon-date-white"></i><?= $run['DATE'] ?></div><?
		        }
		        if (!empty($hall))
		        {
			        ?>
			        <div class="it-map"><i class="engIcon setIcon-map-white"></i><?= $hall['NAME'] ?></div><?
		        }
		        if (!empty($run))
		        {
			        ?>
                    <div class="it-money"><i class="engIcon setIcon-price-white"></i><?= $price ?> руб.</div>
                    <div class="it-btn">
                        <a href="<?= $href ?>" class="cssBorderRadius">Купить билет</a>
                    </div><?
		        }
			    ?>
	        </div><?
	    }
        ?>
    </div>
    <?
}
