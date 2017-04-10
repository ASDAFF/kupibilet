<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

/** @global CMain $APPLICATION */
/** @var Local\Main\TimHalls $component */

$eventHall = $component->hall;
$event = $component->event;

$item = $event['PRODUCT'];
$price = $item['PRICE'];
if ($item['PRICE'] != $item['PRICE_TO'])
	$price .= ' - ' . $item['PRICE_TO'];

?>
<div class="engBox engContent">
    <div class='event-page-detail'>
        <div class='eventpage-img' style="background:url(<?=$event['PREVIEW_PICTURE']?>);"></div>
        <div class='eventpage-descr'>
            <div class='eventpage-info'>
                <div class='eventpage-ttl'><?=$event['NAME']?></div>
                <form class='ep-form'>
                    <ul><?
                        foreach ($event['RUNS'] as $run)
                        {
	                        $href = $event['DETAIL_PAGE_URL'] . $run['FURL'];
	                        $hall = \Local\Main\Hall::getById($run['HALL']);
	                        if (!$hall)
		                        $hall = $eventHall;
	                        if (!$hall)
		                        continue;

	                        ?>
                            <li value="<?=$run['ID']?>">
                                <?=$run['DATE_S']?>
                                <span class="run_hall">
                                    <a href="<?= $hall['DETAIL_PAGE_URL'] ?>"><?= $hall['NAME'] ?></a>
                                </span>
                                <a class="engBtn-kyp" href="<?= $href ?>">Купить билет</a>
                            </li><?
                        }
                        ?>
                    </ul>
                    <!--<div class='eventpage-place'>
                        <i class="engIcon setIcon-map-black"></i>
                        <?/*=$hall['NAME']*/?></div>-->
                    <div class='eventpage-price'>
                        <i class="engIcon setIcon-price-black"></i>
                        <?=$price?> руб.</div>
                    <?if($event['PRODUCT']['E_TICKET'] != 0){?>
                        <input class='btn' type="submit" value="Купить билет">
                    <?}?>
                </form>
                <?if(!empty($event['PRODUCT']['AGE'])){?>
                    <div class="age-rule"><?=$event['PRODUCT']['AGE']?></div>
                <?}?>
                <?if($event['PRODUCT']['E_TICKET'] != 0){?>
                <div class="hall-type">E</div>
                <?}?>
            </div>
            <div class='eventpage-txt'>
                <?=$event['DETAIL_TEXT']?>
            </div>
        </div>
    </div>
</div>

<script>
    $('.elList').masonry({
        // options...
        itemSelector: '.it-item',
        columnWidth: 395
    });
    $(".ep-form select").selectmenu();

</script>

<?
\Local\Main\Event::viewedCounters($event['ID']);
$APPLICATION->SetTitle($event['NAME']);
$APPLICATION->SetPageProperty('title', $event['NAME']);
?>