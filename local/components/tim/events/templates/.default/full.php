<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @var Local\Main\TimEvents $component */

$filter = $component->filter;
$products = $component->products['ITEMS'];

?>

<div id="catalog-wrap">
    <div class="engBox engContent">
        <div class="engBox">
            <div id="catalog-list">
                <?
                //=========================================================
                include('products.php');
                //=========================================================
                ?>
            </div>
        </div>
        <div class="engBox-right">
            <div class="elList">
            <div id="filters-panel" class="it-item setColor-white">

                <input type="hidden" name="q" value="<?= $component->searchQuery ?>">
                <input type="hidden" name="catalog_path" value="<?= $filter['CATALOG_PATH'] ?>">
                <input type="hidden" name="separator" value="<?= $filter['SEPARATOR'] ?>">

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

                <div class="it-date-form">
                    <div id="engDate-picter"></div>
                </div>
                <?
                $closed = array();
                $i = 0;
                ?>
                <div class="engCheck">
                <li>
                    <input type="checkbox" class="radio" id="checkbox" />
                    <label for="checkbox">Я переключаю чекбокс</label>
                </li>
                    <li>
                        <input type="checkbox" class="radio" id="checkbox2" />
                        <label for="checkbox2">Я переключаю чекбокс</label>
                    </li>
                </div>


                <? foreach ($filter['GROUPS'] as $group) {
                    $style = $closed[$i] ? ' style="display:none;"' : '';
                    $class = $closed[$i] ? ' closed' : '';
                    ?>

                    <div class="filter-group<?= $class ?>">
                        <? /*<h3><?= $group['NAME'] ?><s></s></h3>*/ ?>

                        <? if ($group['TYPE'] == 'price') { ?>
                            <?
                            $from = $group['FROM'] ? $group['FROM'] : $group['MIN'];
                            $to = $group['TO'] ? $group['TO'] : $group['MAX'];
                            ?>

                            <div class="price-group it-filter-price"<?= $style ?> data-min="<?= $group['MIN'] ?>"
                                 data-max="<?= $group['MAX'] ?>">
                                <div class="it-filter-price-title">Стоимость</div>
                                <div class="it-filter-price-pole-input">
                                    <div class="engBox-6">от <input id="it-filter-price-pole-from" type="text" class="from" value="<?= $from ?>"/></div>
                                    <div class="engBox-6">до <input id="it-filter-price-pole-to" type="text" class="to" value="<?= $to ?>"/></div>
                                </div>
                            </div>
                            <div class="it-filter-money">
                                <script>
                                    $(function () {
                                        var max = <?= $to ?>,
                                            min = <?= $from ?>;
                                        $("#slider-range").slider({
                                            range: true,
                                            min: min,
                                            max: max,
                                            values: [ <?= $from ?>, <?= $to ?>],
                                            slide: function (event, ui) {
                                                $("#slider-range-value1 b").text(ui.values[0]);
                                                //$("#slider-range-value1").css("left", ((ui.values[0] * 100)) / max + '%');

                                                $("#slider-range-value2 b").text(ui.values[1]);
                                                //$("#slider-range-value2").css("left", ((ui.values[1] * 100) / max) + '%');
                                                $("#slider-range-value1").css("left", $(".it-filter-money #slider-range span:first").css("left"));
                                                $("#slider-range-value2").css("left", $(".it-filter-money #slider-range span:last").css("left"));

                                                $("#it-filter-price-pole-from").val(ui.values[0]);
                                                $("#it-filter-price-pole-to").val(ui.values[1]);
                                            }
                                        });
                                    });
                                </script>

                                <div id="slider-range">
                                    <i class="slider-range-value" id="slider-range-value1"
                                       style="left: 0%"><b><?= $from ?></b></i>
                                    <i class="slider-range-value" id="slider-range-value2"
                                       style="left: 100%"><b><?= $to ?></b></i>
                                </div>
                            </div>
                        <? } elseif ($group['TYPE'] == 'date') { ?>
                            <?
                            $min = ConvertTimeStamp($group['MIN']);
                            $max = ConvertTimeStamp($group['MAX']);
                            $from = $group['FROM'] ? $group['FROM'] : $min;
                            $to = $group['TO'] ? $group['TO'] : $max;
                            ?>
                            <div class="date-group"<?= $style ?> data-min="<?= $min ?>" data-max="<?= $max ?>">
                                <div class="inputs ">
                                    <div class="l">от <input name="startDate" type="text" class="from" value="<?= $from ?>"/></div>
                                    <div class="r">до <input name="endDate" type="text" class="to" value="<?= $to ?>"/></div>
                                </div>
                            </div>
                        <? } else { ?>
                            <div<?= $style ?>>
                                <ul>
                                    <? foreach ($group['ITEMS'] as $code => $item) { ?>
                                        <?
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
                                    <? } ?>
                                </ul>
                            </div>
                        <? } ?>
                    </div>

                    <? $i++; ?>
                <? } ?>

            </div>
            </div>
        </div>

    </div>
</div>

<?
foreach ($filter['BC'] as $i => $item)
    $APPLICATION->AddChainItem($item['NAME'], $item['HREF']);

if ($component->navParams['iNumPage'] > 1)
    $component->seo['TITLE'] .= ' - страница ' . $component->navParams['iNumPage'];

if ($component->seo['H1'])
    $APPLICATION->SetTitle($component->seo['H1']);
if ($component->seo['TITLE'])
    $APPLICATION->SetPageProperty('title', $component->seo['TITLE']);
if ($component->seo['DESCRIPTION'])
    $APPLICATION->SetPageProperty('description', $component->seo['DESCRIPTION']);
?>
