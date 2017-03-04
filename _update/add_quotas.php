<?

$oCIBlockProperty = new \CIBlockProperty();
$oCIBlockPropertyEnum = new \CIBlockPropertyEnum;

$code = 'QUOTAS';
$name = 'Квоты мест';
$iblockId = \Local\Main\Run::IBLOCK_ID;

$rsProps = CIBlockProperty::GetList(array(), array(
	'IBLOCK_ID' => $iblockId,
	'CODE' => $code,
));
if ($arProp = $rsProps->Fetch())
	echo "Свойство \"$name\" уже существует\n";
else
{
	$fiealds = array(
		'MULTIPLE' => 'N',
		'ACTIVE' => 'Y',
		'NAME' => $name,
		'CODE' => $code,
		'IBLOCK_ID' => $iblockId,
		'PROPERTY_TYPE' => 'S',
		'USER_TYPE' => 'Quotas',
		'SORT' => 100,
	);

	$propId = $oCIBlockProperty->Add($fiealds);

	if ($propId)
		echo "Добавлено свойство \"$name\"\n";
	else
		echo "Ошибка добавления свойства \"$name\"\n";
}

echo "\n";

