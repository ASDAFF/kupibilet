<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

/** @var array $arParams */
/** @var array $arResult */
/** @var array $products */
/** @global CMain $APPLICATION */
/** @var Local\Main\TimEvents $component */

if ($filter['CUR_FILTERS']) {
    ?>
    <div id="current-filters"><?

    foreach ($filter['CUR_FILTERS'] as $item) {
        ?><span><a href="<?= $item['HREF'] ?>">x</a><?= $item['NAME'] ?></span><?
    }

    ?>
    </div><?
}

?>

    <div id="events" class="elList">
        <? if (count($products) <= 0) { ?>
            <p class="empty">Не найдено ни одного подходящего мероприятия. Попробуйте отключить какой-нибудь фильтр</p>
        <? } ?>

        <div class="grid-sizer"></div>
        <? foreach ($products as $id => $item) { ?>
            <div class="item">
                <? //debugmessage($item);?>
                <div class="it-item">
                    <div class="it-img">
                        <a href="<?= $item['DETAIL_PAGE_URL'] ?>">
                            <img src="<?= $item['PREVIEW_PICTURE'] ?>">
                        </a>
                    </div>
                    <div class="it-inf">
                        <?= var_dump($item['RUNS']); ?>
                        <div class="it-title"><?= $item['NAME'] ?></div>
                        <div class="it-date"><i class="engIcon setIcon-date-black"></i>20.02.2016</div>
                        <div class="it-map"><i class="engIcon setIcon-map-black"></i>КЗ им. Ф.И. Шаляпина</div>
                        <div class="it-money"><i class="engIcon setIcon-price-black"></i><?=$item['PRICE']?> руб.</div>
                    </div>
                </div>
            </div>
        <? } ?>
    </div><?

//
// Постраничка
//
$iCur = $component->products['NAV']['PAGE'];
$iEnd = ceil($component->products['NAV']['COUNT'] / $component::PAGE_SIZE);

if ($iEnd > 1) {
    $iStart = $iCur - 2;
    $iFinish = $iCur + 2;
    if ($iStart < 1) {
        $iFinish -= $iStart - 1;
        $iStart = 1;
    }
    if ($iFinish > $iEnd) {
        $iStart -= $iFinish - $iEnd;
        if ($iStart < 1) {
            $iStart = 1;
        }
        $iFinish = $iEnd;
    }

    $url = $component->filter['URL'];
    if (strpos($url, '?') !== false)
        $urlPage = $url . '&page='; else
        $urlPage = $url . '?page=';

    ?>
    <ul class="pagination"><?

    if ($iCur > 1) {
        if ($iCur == 2)
            $href = $url; else
            $href = $urlPage . ($iCur - 1);
        ?>
        <li class="prev">
        <a href="<?= $href ?>" data-page="<?= ($iCur - 1) ?>"></a>
        </li><?
    } else {
        ?>
        <li class="prev">
            <span></span>
        </li><?
    }
    if ($iStart > 1) {
        $href = $url;
        ?>
        <li>
        <a href="<?= $href ?>" data-page="1">1</a>
        </li><?

        if ($iStart > 2) {
            ?>
            <li>
                <span>...</span>
            </li><?
        }
    }
    for ($i = $iStart; $i <= $iFinish; $i++) {
        if ($i == $iCur) {
            ?>
            <li>
            <span class="active"><?= $i ?></span>
            </li><?
        } else {
            if ($i == 1)
                $href = $url; else
                $href = $urlPage . $i;
            ?>
            <li>
            <a href="<?= $href ?>" data-page="<?= $i ?>"><?= $i ?></a>
            </li><?
        }
    }
    if ($iFinish < $iEnd) {
        if ($iFinish < $iEnd - 1) {
            ?>
            <li>
                <span>...</span>
            </li><?
        }

        $href = $urlPage . $iEnd;
        ?>
        <li>
        <a href="<?= $href ?>" data-page="<?= $iEnd ?>"><?= $iEnd ?></a>
        </li><?
    }
    if ($iCur < $iEnd) {
        $href = $urlPage . ($iCur + 1);
        ?>
        <li class="next">
        <a href="<?= $href ?>" data-page="<?= ($iCur + 1) ?>"></a>
        </li><?
    } else {
        ?>
        <li class="next">
            <span></span>
        </li><?
    }

    ?>
    </ul><?

}

?>
    <div class="seo-text"><?
// Описание выводим только на первой странице.
if ($component->navParams['iNumPage'] == 1) {
    echo $component->seo['TEXT'];
}
?>
    </div><?