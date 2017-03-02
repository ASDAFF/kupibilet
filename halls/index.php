<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Залы");
?>

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
        <?$APPLICATION->IncludeComponent(
            "bitrix:news",
            "halls",
            Array(
                "ADD_ELEMENT_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "Y",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "BROWSER_TITLE" => "-",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "CACHE_TIME" => "36000000",
                "CACHE_TYPE" => "A",
                "CHECK_DATES" => "Y",
                "DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
                "DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
                "DETAIL_DISPLAY_TOP_PAGER" => "N",
                "DETAIL_FIELD_CODE" => array("",""),
                "DETAIL_PAGER_SHOW_ALL" => "Y",
                "DETAIL_PAGER_TEMPLATE" => "",
                "DETAIL_PAGER_TITLE" => "Страница",
                "DETAIL_PROPERTY_CODE" => array("",""),
                "DETAIL_SET_CANONICAL_URL" => "N",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "DISPLAY_TOP_PAGER" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "IBLOCK_ID" => "2",
                "IBLOCK_TYPE" => "main",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                "LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
                "LIST_FIELD_CODE" => array("",""),
                "LIST_PROPERTY_CODE" => array("",""),
                "MESSAGE_404" => "",
                "META_DESCRIPTION" => "-",
                "META_KEYWORDS" => "-",
                "NEWS_COUNT" => "20",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_TEMPLATE" => ".default",
                "PAGER_TITLE" => "Новости",
                "PREVIEW_TRUNCATE_LEN" => "",
                "SEF_MODE" => "N",
                "SET_LAST_MODIFIED" => "N",
                "SET_STATUS_404" => "N",
                "SET_TITLE" => "Y",
                "SHOW_404" => "N",
                "SORT_BY1" => "NAME",
                "SORT_BY2" => "SORT",
                "SORT_ORDER1" => "DESC",
                "SORT_ORDER2" => "ASC",
                "USE_CATEGORIES" => "N",
                "USE_FILTER" => "N",
                "USE_PERMISSIONS" => "N",
                "USE_RATING" => "N",
                "USE_RSS" => "N",
                "USE_SEARCH" => "N",
                "USE_SHARE" => "N",
                "VARIABLE_ALIASES" => Array("ELEMENT_ID"=>"ELEMENT_ID","SECTION_ID"=>"SECTION_ID")
            )
        );?>
        <?/*<div class="col-4">
            <div class="hall-it">
                <div class="letter">A</div>
                <div class="hall-it-name">AZIMUT HOTEL SOCHI</div>
            </div>
            <div class="hall-it">
                <div class="letter">C</div>
                <div class="hall-it-name">Crocus City Hall (Крокус Сити Холл)</div>
            </div>
            <div class="hall-it">
                <div class="letter">L</div>
                <div class="hall-it-name">Live Music Hall</div>
            </div>
            <div class="hall-it">
                <div class="letter">N</div>
                <div class="hall-it-name">NEW WAVE HALL</div>
                <div class="hall-it-name">NEW WAVE HALL</div>
            </div>
            <div class="hall-it">
                <div class="letter">S</div>
                <div class="hall-it-name">Shore House</div>
                <div class="hall-it-name">Shore House</div>
                <div class="hall-it-name">Stereo Hall</div>
            </div>
            <div class="hall-it">
                <div class="letter">V</div>
                <div class="hall-it-name">Vegas City Hall в ТРК «Vegas Крокус Сити»</div>
            </div>
            <div class="hall-it">
                <div class="letter">Y</div>
                <div class="hall-it-name">YOTASPACE</div>
            </div>
            <div class="hall-it">
                <div class="letter">A</div>
                <div class="hall-it-name">Англиканский собор святого Андрея</div>
                <div class="hall-it-name">Артист Концерт Холл</div>
            </div>
            <div class="hall-it">
                <div class="letter">Б</div>
                <div class="hall-it-name">БКЗ КОСМОС</div>
                <div class="hall-it-name">БКЗ "Октябрьский" г. Санкт-Петербург</div>
                <div class="hall-it-name">Большой концертный зал </div>
            </div>
            <div class="hall-it">
                <div class="letter">Г</div>
                <div class="hall-it-name">Государственный Кремлевский Дворец </div>
            </div>
        </div>
        <div class="col-4">
            <div class="hall-it">
                <div class="letter">A</div>
                <div class="hall-it-name">AZIMUT HOTEL SOCHI</div>
            </div>
            <div class="hall-it">
                <div class="letter">C</div>
                <div class="hall-it-name">Crocus City Hall (Крокус Сити Холл)</div>
            </div>
            <div class="hall-it">
                <div class="letter">L</div>
                <div class="hall-it-name">Live Music Hall</div>
            </div>
            <div class="hall-it">
                <div class="letter">N</div>
                <div class="hall-it-name">NEW WAVE HALL</div>
                <div class="hall-it-name">NEW WAVE HALL</div>
            </div>
            <div class="hall-it">
                <div class="letter">S</div>
                <div class="hall-it-name">Shore House</div>
                <div class="hall-it-name">Shore House</div>
                <div class="hall-it-name">Stereo Hall</div>
            </div>
            <div class="hall-it">
                <div class="letter">V</div>
                <div class="hall-it-name">Vegas City Hall в ТРК «Vegas Крокус Сити»</div>
            </div>
            <div class="hall-it">
                <div class="letter">Y</div>
                <div class="hall-it-name">YOTASPACE</div>
            </div>
            <div class="hall-it">
                <div class="letter">A</div>
                <div class="hall-it-name">Англиканский собор святого Андрея</div>
                <div class="hall-it-name">Артист Концерт Холл</div>
            </div>
            <div class="hall-it">
                <div class="letter">Б</div>
                <div class="hall-it-name">БКЗ КОСМОС</div>
                <div class="hall-it-name">БКЗ "Октябрьский" г. Санкт-Петербург</div>
                <div class="hall-it-name">Большой концертный зал </div>
            </div>
            <div class="hall-it">
                <div class="letter">Г</div>
                <div class="hall-it-name">Государственный Кремлевский Дворец </div>
            </div>
        </div>
        <div class="col-4">
            <div class="hall-it">
                <div class="letter">A</div>
                <div class="hall-it-name">AZIMUT HOTEL SOCHI</div>
            </div>
            <div class="hall-it">
                <div class="letter">C</div>
                <div class="hall-it-name">Crocus City Hall (Крокус Сити Холл)</div>
            </div>
            <div class="hall-it">
                <div class="letter">L</div>
                <div class="hall-it-name">Live Music Hall</div>
            </div>
            <div class="hall-it">
                <div class="letter">N</div>
                <div class="hall-it-name">NEW WAVE HALL</div>
                <div class="hall-it-name">NEW WAVE HALL</div>
            </div>
            <div class="hall-it">
                <div class="letter">S</div>
                <div class="hall-it-name">Shore House</div>
                <div class="hall-it-name">Shore House</div>
                <div class="hall-it-name">Stereo Hall</div>
            </div>
            <div class="hall-it">
                <div class="letter">V</div>
                <div class="hall-it-name">Vegas City Hall в ТРК «Vegas Крокус Сити»</div>
            </div>
            <div class="hall-it">
                <div class="letter">Y</div>
                <div class="hall-it-name">YOTASPACE</div>
            </div>
            <div class="hall-it">
                <div class="letter">A</div>
                <div class="hall-it-name">Англиканский собор святого Андрея</div>
                <div class="hall-it-name">Артист Концерт Холл</div>
            </div>
            <div class="hall-it">
                <div class="letter">Б</div>
                <div class="hall-it-name">БКЗ КОСМОС</div>
                <div class="hall-it-name">БКЗ "Октябрьский" г. Санкт-Петербург</div>
                <div class="hall-it-name">Большой концертный зал </div>
            </div>
            <div class="hall-it">
                <div class="letter">Г</div>
                <div class="hall-it-name">Государственный Кремлевский Дворец </div>
            </div>
        </div>*/?>
    </div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>