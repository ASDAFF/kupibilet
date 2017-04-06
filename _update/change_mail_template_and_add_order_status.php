<?php

$arStatuses = array();
$dbRes = CSaleStatus::GetList();
while ($arItem = $dbRes->Fetch()) {
    $arStatuses[$arItem['ID']] = true;
}
if (!$arStatuses['RS']) {
    $arStatusFields = array(
        'ID' => 'RS',
        'SORT' => 1,
        'LANG' => array(
            array(
                'LID' => 'ru',
                'NAME' => 'Забронирован',
                'DESCRIPTION' => 'Забронирован на 24 часа',
            ),
            array(
                'LID' => 'en',
                'NAME' => 'Reserved',
                'DESCRIPTION' => 'Reserved for 24 hours',
            ),
        ),
    );
    if (CSaleStatus::Add($arStatusFields)) {
        echo "Добавлен статус заказа \"RS\" - Зарезирвирован \n";
    }
}
else {
    echo "Статус заказа \"X\" уже существует \n";
}

$templateTable = 'b_event_message';
$dbItems = $DB->Query("UPDATE $templateTable SET SUBJECT = '#SITE_NAME#: Заказ N#ORDER_ID# оплачен' WHERE ID = 43");
if($dbItems){
    echo "Изменен шаблон \"[PAY_ORDER] Заказ оплачен\" \n";
}