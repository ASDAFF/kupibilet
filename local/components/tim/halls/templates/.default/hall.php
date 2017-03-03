<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
/** @global CMain $APPLICATION */
/** @var Local\Main\TimHalls $component */
$hall = $component->hall;
$events = \Local\Main\Event::getByHall($hall['ID']);
?>

<? //debugmessage($hall); ?>
    <div class="engBox engContent">
        <div class='hallpage-detail'>
            <div class='hallpage-img'><img src='<?=CFile::GetPath($hall["PICTURE"])?>'></div>
            <div class='hallpage-descr'>
                <div class='hallpage-ttl'><?=$hall['NAME']?></div>
                <div class='hallpage-place'><?=$hall['NAME']?></div>
                <div class='hallpage-txt'>
                    <?=$hall['DETAIL_TEXT']?>
                </div>
            </div>
        </div>
        <div class='up-events'>
            <div class='up-events-ttl'>Предстоящие мероприятия</div>
            <div class='up-events-list'>
                <? foreach ($events as $event): ?>
                    <? //debugmessage($event); ?>
                    <? /*<p><a href="<?= $event['DETAIL_PAGE_URL'] ?>"><?= $event['NAME'] ?></a></p>*/ ?>
                    <div class='up-events-item'>
                        <div class='up-img' style='background:url(<?=CFile::GetPath($event["PREVIEW_PICTURE"])?>);'></div>
                        <div class='up-info'>
                            <div class='up-title'><?= $event['NAME'] ?></div>
                            <div class='up-date'><?= $event['DATE_SHOW'][0] ?></div>
                            <div class='up-place'><?= $event['HALL_NAME'] ?></div>
                            <div class='up-price'><?= $event['PRICE'] ?>-<?= $event['PRICE_TO'] ?> руб.</div>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
    </div>
    <script>
        $('.elList').masonry({
            // options...
            itemSelector: '.it-item',
            columnWidth: 395
        });
    </script>

<? $APPLICATION->SetTitle($item['NAME']); ?>