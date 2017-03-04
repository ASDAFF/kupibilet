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
            ?><span><a href="<?= $item['HREF'] ?>">x</a><?= $item['NAME'] ?></span><?
        } ?>
    </div>
<? } ?>

<div id="events">
    <? if (count($products) <= 0) { ?>
        <p class="empty">Не найдено ни одного подходящего мероприятия. Попробуйте отключить какой-нибудь фильтр</p>
    <? } ?>

    <?
    $m_from = 0;
    $m_to = 0;

    foreach ($filter['GROUPS'] as $group) {
        if ($group['TYPE'] == 'price') {
            $t_from = $group['FROM'] ? $group['FROM'] : $group['MIN'];
            $t_to = $group['TO'] ? $group['TO'] : $group['MAX'];

            if($m_from == 0){
                $m_from = $t_from;
            }else {
                $m_from = $t_from < $m_from ? $t_from : $m_from;
            }
            $m_to = $t_to > $m_to ? $t_to : $m_to;

            echo $t_from." ".$t_to." ".$m_from." ".$m_to;
        }
    }
    ?>

    <div class="engBox engContent">
        <div class="elList">
            <div class="grid-sizer"></div>
            <? $i = 0; ?>
            <? foreach ($products as $id => $item) { ?>
                <? $i++; ?>
                <? //debugmessage($item);?>
                <div class="it-item <? if ($i % 3 == 0) {
                    echo 'set-2';
                } ?>">
                    <div class="it-img">
                        <a href="<?= $item['DETAIL_PAGE_URL'] ?>">
                            <img src="<?= $item['PREVIEW_PICTURE'] ?>">
                        </a>
                    </div>
                    <div class="it-inf">
                        <div class="it-title"><?= $item['NAME'] ?></div>
                        <div class="it-date"><i class="engIcon setIcon-date-black"></i><?= $item['DATE_SHOW'][0] ?>
                        </div>
                        <div class="it-map"><i class="engIcon setIcon-map-black"></i><?= $item['HALL_NAME'] ?></div>
                        <div class="it-money"><i class="engIcon setIcon-price-black"></i><?= $item['PRICE'] ?>
                            -<?= $item['PRICE_TO'] ?> руб.
                        </div>
                    </div>
                </div>

                <? /* отображение фильтра */?>
                <? if ($i % 2 == 0): /*?>
                    <div class="it-item setColor-white">
                        <div class="it-filter">
                            <div class="engRow">
                                <div class="engBox-6">
                                    <div class="it-filter-title">Фильтр</div>
                                </div>
                                <div class="engBox-6">
                                    <a href="" class="it-filter-btn">
                                        <span>Очистить все</span>
                                        <i class="engIcon setIcon-filter-delete"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="q" value="<?= $component->searchQuery ?>">
                        <input type="hidden" name="catalog_path" value="<?= $filter['CATALOG_PATH'] ?>">
                        <input type="hidden" name="separator" value="<?= $filter['SEPARATOR'] ?>">

                        <?
                        $closed = array();
                        $i = 0;
                        ?>

                        <? foreach ($filter['GROUPS'] as $group): ?>
                            <?
                            $style = $closed[$i] ? ' style="display:none;"' : '';
                            $class = $closed[$i] ? ' closed' : '';
                            ?>

                            <? if ($group['TYPE'] == 'price'): ?>
                                <?
                                //$from = $group['FROM'] ? $group['FROM'] : $group['MIN'];
                                //$to = $group['TO'] ? $group['TO'] : $group['MAX'];
                                ?>
                                <? // new  ?>
                                <div class="it-filter-price">
                                    <div class="it-filter-price-title">Стоимость</div>
                                    <div class="engRow">
                                        <div class="engBox-6">
                                            <div class="it-filter-price-pole">
                                                <div class="it-filter-price-pole-title">От:</div>
                                                <div class="it-filter-price-pole-input">
                                                    <input type="text" class="cssBorderRadius">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="engBox-6">
                                            <div class="it-filter-price-pole">
                                                <div class="it-filter-price-pole-title">До:</div>
                                                <div class="it-filter-price-pole-input">
                                                    <input type="text" class="cssBorderRadius">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="it-filter-money">
                                    <script>
                                        $(function () {
                                            var max = <?=$m_to?>,
                                                min = <?=$m_from?>;
                                            $("#slider-range").slider({
                                                range: true,
                                                min: min,
                                                max: max,
                                                values: [<?=$m_from?>, <?=$m_to?>],
                                                slide: function (event, ui) {
                                                    $("#slider-range-value1 b").text(ui.values[0]);
                                                    $("#slider-range-value1").css("left", ((ui.values[0] * 100)) / max + '%');
                                                    $("#slider-range-value2 b").text(ui.values[1]);
                                                    $("#slider-range-value2").css("left", (ui.values[1] * 100) / max + '%');
                                                }
                                            });
                                        });
                                    </script>

                                    <div id="slider-range">
                                        <i class="slider-range-value" id="slider-range-value1"
                                           style="left: 0%"><b><?=$m_from?></b></i>
                                        <i class="slider-range-value" id="slider-range-value2"
                                           style="left: 100%"><b><?=$m_to?></b></i>
                                    </div>
                                </div>

                                <?
                                //<div class="price-group"<?= $style ?> data-min="<?= $group['MIN'] ?>" data-max="<?= $group['MAX'] ?>">
                                 //   <div class="inputs">
                                 //       <div class="l">от <input type="text" class="from" value="<?= $from ?>"/></div>
                                  //      <div class="r">до <input type="text" class="to" value="<?= $to ?>"/></div>
                                 //   </div>
                               // </div>
                                ?>
                            <? elseif ($group['TYPE'] == 'date'): ?>
                                <?
                                $min = ConvertTimeStamp($group['MIN']);
                                $max = ConvertTimeStamp($group['MAX']);
                                $from = $group['FROM'] ? $group['FROM'] : $min;
                                $to = $group['TO'] ? $group['TO'] : $max;
                                ?>
                                <? // new  ?>
                                <div class="it-date-form">
                                    <div id="engDate-picter"></div>
                                </div>

                                <?
                                //<div class="date-group"<?= $style ?> data-min="<?= $min ?>" data-max="<?= $max ?>">
                                //    <div class="inputs">
                                //        <div class="l">от <input type="text" class="from" value="<?= $from ?>"/></div>
                                //        <div class="r">до <input type="text" class="to" value="<?= $to ?>"/></div>
                               //     </div>
                               // </div>
                                ?>
                            <? else: ?>
                                <div<?= $style ?>>
                                    <ul>
                                        <? foreach ($group['ITEMS'] as $code => $item):
                                            $style = $item['ALL_CNT'] ? '' : ' style="display:none;"';
                                            $class = '';
                                            if (!$item['CNT'] && $item['CHECKED'])
                                                $class = ' class="checked disabled"'; elseif ($item['CHECKED'])
                                                $class = ' class="checked"';
                                            elseif (!$item['CNT'])
                                                $class = ' class="disabled"';
                                            $checked = $item['CHECKED'] ? ' checked' : '';
                                            $disabled = $item['CNT'] ? '' : ' disabled';
                                            ?>
                                            <li<?= $class ?><?= $style ?>>
                                                <b></b><label>
                                                    <input type="checkbox"
                                                           name="<?= $code ?>"<?= $checked ?><?= $disabled ?> />
                                                    <?= $item['NAME'] ?> (<i><?= $item['CNT'] ?></i>)
                                                </label>
                                            </li>
                                        <? endforeach; ?>
                                    </ul>
                                </div>
                            <? endif; ?>
                            <? $i++; ?>
                        <? endforeach; ?>
                        <div class="it-filter-btn">
                            <button class="cssBorderRadius">Применить фильтр</button>
                        </div>
                    </div>
                <?*/ endif; ?>
            <? } ?>
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
    } ?>
</div>