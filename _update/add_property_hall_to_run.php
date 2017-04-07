<?php

$iIbId = 4; // Показы

$oCIBlockProperty = new CIBlockProperty();

$sCode = 'HALL';
$sName = 'Зал';
$rsProps = CIBlockProperty::GetList([], [
    'IBLOCK_ID' => $iIbId,
    'CODE' => $sCode,
]);
if ($arProp = $rsProps->Fetch()) {
    ?><p>Свойство "<?= $sName ?>" уже существует</p><?
} else {
    $arFields = [
        'MULTIPLE' => 'N',
        'ACTIVE' => 'Y',
        'NAME' => $sName,
        'CODE' => $sCode,
        'IBLOCK_ID' => $iIbId,
        'PROPERTY_TYPE' => 'E',
        'LINK_IBLOCK_ID' => 2,
        'SORT' => 200,
    ];

    $iPropId = $oCIBlockProperty->Add($arFields);
    if ($iPropId) {
        ?><p>Добавлено свойство "<?= $sName ?>"</p><?
    } else {
        ?><p>Ошибка добавления свойства "<?= $sName ?>"</p><?
    }
}