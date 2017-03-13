<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

/** @var array $arParams */
/** @var array $arResult */
/** @var array $products */
/** @global CMain $APPLICATION */
/** @var Local\Main\TimEvents $component */

if ($filter['CUR_FILTERS']) { ?>
    <div id="current-filters">
        <? foreach ($filter['CUR_FILTERS'] as $item) {
            ?><span>
        <a href="<?= $item['HREF'] ?>">
                <svg class="engSvg cssTextColor-red" xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
                <path d="M21.736,19.64l-2.098,2.096c-0.383,0.386-1.011,0.386-1.396,0l-5.241-5.239L7.76,21.735 c-0.385,0.386-1.014,0.386-1.397-0.002L4.264,19.64c-0.385-0.386-0.385-1.011,0-1.398L9.505,13l-5.24-5.24 c-0.384-0.387-0.384-1.016,0-1.398l2.098-2.097c0.384-0.388,1.013-0.388,1.397,0L13,9.506l5.242-5.241 c0.386-0.388,1.014-0.388,1.396,0l2.098,2.094c0.386,0.386,0.386,1.015,0.001,1.401L16.496,13l5.24,5.241 C22.121,18.629,22.121,19.254,21.736,19.64z"></path>
            </svg>
        </a>
            <?= $item['NAME'] ?></span><?
        } ?>
    </div>
<? } ?>


<div id="events">
    <? if (count($products) <= 0) { ?>
        <p class="empty">Не найдено ни одного подходящего мероприятия. Попробуйте отключить какой-нибудь фильтр</p>
    <? } ?>

        <div class="elList">
            <div class="grid-sizer"></div>
            <? $i = 0; ?>
            <? foreach ($products as $id => $item) {
                $i++;
	            $hall = \Local\Main\Hall::getById($item['PRODUCT']['HALL']);
	            $run = \Local\Main\Run::getClosest($item['RUNS']);
	            $price = $item['PRICE'];
	            if ($item['PRICE'] != $item['PRICE_TO'])
		            $price .= ' - ' . $item['PRICE_TO'];
	            ?>
                <div class="it-item <? if ($i % 3 == 0) echo 'set-2'; ?>">
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
                        <div class="it-money">
                            <i class="engIcon setIcon-price-black"></i>
                            <?= $price ?> руб.
                        </div>
                    </div>
                </div>
            <? } ?>
        </div>




    <?
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
            <a href="<?= $href ?>" data-page="<?= ($iCur - 1) ?>">
                <i class="engIcon setIcon-left-red"></i>
            </a>
            </li><?
        } else {
            ?>
            <li class="prev">
               <i class="engIcon setIcon-left"></i>
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
            <a href="<?= $href ?>" data-page="<?= ($iCur + 1) ?>">
                <i class="engIcon setIcon-right-red"></i>
            </a>
            </li><?
        } else {
            ?>
            <li class="next">
                <i class="engIcon setIcon-right"></i>
            </li><?
        }

        ?>
        </ul><?

    }
    ?>

    <div class="seo-text"><?/*
        // Описание выводим только на первой странице.
        if ($component->navParams['iNumPage'] == 1) {
            echo $component->seo['TEXT'];
        } */?>
    </div>