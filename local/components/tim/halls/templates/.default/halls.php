<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

/** @global CMain $APPLICATION */
/** @var $arParams */

$items = \Local\Main\Hall::getAll();

// ---------------------------------------------
// вот сюда верстку залов и фильтр по городам

$for_columns = ceil(count($items["ITEMS"]) / 3);
$i = 0;
$first = ""; ?>
    <div class="engBox engContent">
        <form class="city-select">
            <div class="city-select-ttl">Концертные залы</div>
            <select>
                <option value="Выберите город">Выберите город</option>
                <option value="Пятигорск">Пятигорск</option>
                <option value="Есентуки">Есентуки</option>
            </select>
        </form>
        <div class="hall-list">

            <? foreach ($items['ITEMS'] as $item) { ?>

                <?
                $letter = mb_substr($item['NAME'], 0, 1);
                $i++;
                if ($i == 1): ?>
                    <div class="col-4">
                <? endif; ?>
                <? if ($first == "" || $first != $letter):
                $first = $letter; ?>
                <div class="hall-it">
                <div class="letter"><?= $first ?></div>
                <?
                $first = "";
            endif; ?>
                <div class="hall-it-name"><a style="color: #525252;"
                                             href="<?= $item["DETAIL_PAGE_URL"] ?>"><?= $item['NAME'] ?></a></div>
                <?
            if ($first == "" || $first != $letter):
                $first = $letter; ?>
                </div>
            <? endif;
                ?>
                <? if ($i == $for_columns || $i == $for_columns * 2): ?>
                    </div>
                    <div class="col-4">
                <? endif; ?>


                <? if ($i == count($items['ITEMS'])): ?>
                    </div>
                <? endif; ?>
                <?
            } ?>

        </div>
    </div>
<?
// ---------------------------------------------

$APPLICATION->SetTitle("Концертные залы");