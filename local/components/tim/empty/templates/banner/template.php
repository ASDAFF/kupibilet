<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$banners = \Local\Main\Banner::getAll(array(), array(), array());

?>
<?

foreach ($banners as $item) {

    $events = \Local\Main\Event::getById($item['EVENT']);
    $hall = \Local\Main\Hall::getById($events['PRODUCT']['HALL']);
    $run = \Local\Main\Run::getClosest($events['RUNS']);

    $price = $events['PRODUCT']['PRICE'];
    if ($events['PRODUCT']['PRICE'] != $events['PRODUCT']['PRICE_TO'])
        $price .= ' - ' . $events['PRODUCT']['PRICE_TO'];
    ?>

        <div class="elSlider-item">
            <img src="<?= $item['PREVIEW_PICTURE'] ?>"/>
            <div class="it-inf">
                <div class="it-title"><?= $item['NAME'] ?></div>
                <div class="it-data"><i class="engIcon setIcon-date-white"></i><?= $run['DATE'] ?></div>
                <div class="it-map"><i class="engIcon setIcon-map-white"></i><?= $hall['NAME'] ?></div>
                <div class="it-money"><i class="engIcon setIcon-price-white"></i><?= $price ?> руб.</div>
                <div class="it-btn">
                    <a href="<?= $events['DETAIL_PAGE_URL'] ?>" class="cssBorderRadius">Купить билет</a>
                </div>
            </div>
        </div>
    <?
}
?>
