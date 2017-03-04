<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

/** @global CMain $APPLICATION */
/** @var Local\Main\TimHalls $component */

$hall = $component->hall;
$event = $component->event;

debugmessage($event);
?>


    <div class="engBox engContent">
        <div class='event-page-detail'>
            <div class='eventpage-img' style="background:url(<?=$event['PREVIEW_PICTURE']?>);"></div>
            <div class='eventpage-descr'>
                <div class='eventpage-info'>
                    <div class='eventpage-ttl'><?=$event['NAME']?></div>
                    <form class='ep-form'>
                        <select>
                            <option disabled>25 января вт 20:00</option>
                            <option value="25 января вт 20:00">27 января вт 20:00</option>
                            <option value="25 января вт 20:00">28 января вт 20:00</option>
                        </select>
                        <div class='eventpage-place'>кз. им. Ф.И. Шаляпина</div>
                        <div class='eventpage-price'>3500 - 5000 руб.</div>
                        <input class='btn' type="submit" value="Купить билет">
                    </form>
                    <div class="age-rule">12+</div>
                    <div class="hall-type">E</div>
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
?>