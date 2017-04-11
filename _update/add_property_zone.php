<?php

$iIbId = 2; // Залы

$oCIBlockProperty = new CIBlockProperty();

$sCode = 'ZONE';
$sName = 'Схема зон';
$rsProps = CIBlockProperty::GetList([], [
	'IBLOCK_ID' => $iIbId,
	'CODE' => $sCode,
]);
if ($arProp = $rsProps->Fetch())
{
	echo "Свойство \"$sName\" уже существует\n";
}
else
{
	$arFields = [
		'MULTIPLE' => 'N',
		'ACTIVE' => 'Y',
		'NAME' => $sName,
		'CODE' => $sCode,
		'IBLOCK_ID' => $iIbId,
		'PROPERTY_TYPE' => 'S',
		'USER_TYPE' => 'HTML',
		'SORT' => 600,
	];

	$iPropId = $oCIBlockProperty->Add($arFields);
	if ($iPropId)
	{
		echo "Добавлено свойство \"$sName\"\n";
	}
	else
	{
		echo "Ошибка добавления свойства \"$sName\"\n";
	}
}

echo "\n";