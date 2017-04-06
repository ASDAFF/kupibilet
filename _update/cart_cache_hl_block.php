<?
\Bitrix\Main\Loader::includeModule('highloadblock');

//
// Добавление HL-блока для хранения кеша запросов к чекауту
//

$hlId = 0;
$hlData = array(
	'NAME' => 'CartCache',
	'TABLE_NAME' => 'cart_cache',
);
$rsBlock = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
	'filter' => array(
		'TABLE_NAME' => $hlData['TABLE_NAME'],
	),
    'select' => array(
	    'ID',
    ),
));
if ($hlBlock = $rsBlock->fetch())
{
	$hlId = $hlBlock['ID'];
	echo "HL-блок \"" . $hlData['NAME'] . "\" уже существует\n";
}
else
{
	$res = \Bitrix\Highloadblock\HighloadBlockTable::add($hlData);
	if ($res->isSuccess())
	{
		echo "HL-блок \"" . $hlData['NAME'] . "\" успешно добавлен\n";
		$hlId = $res->getId();
	}
	else
		echo "Ошибка добавления HL-блока \"" . $hlData['NAME'] . "\"\n";
}

if ($hlId)
{
	$userTypeEntity = new CUserTypeEntity();
	$entityId = 'HLBLOCK_' . $hlId;

	$fieldsData = array(
		array(
			'ENTITY_ID' => $entityId,
			'FIELD_NAME' => 'UF_FUSER_ID',
			'USER_TYPE_ID' => 'integer',
		),
	);

	$rsEntities = $userTypeEntity->GetList(array(), array(
		'ENTITY_ID' => $entityId,
	));
	$exist = array();
	while ($item = $rsEntities->Fetch())
		$exist[$item['FIELD_NAME']] = $item['ID'];

	foreach ($fieldsData as $fieldData)
	{
		if ($exist[$fieldData['FIELD_NAME']])
			echo "Поле \"" . $fieldData['FIELD_NAME'] . "\" уже существует\n";
		else
		{
			$userTypeId = $userTypeEntity->Add($fieldData);
			if ($userTypeId)
				echo "Поле \"" . $fieldData['FIELD_NAME'] . "\" успешно добавлено\n";
			else
				echo "Ошибка добавления поля \"" . $fieldData['FIELD_NAME'] . "\"\n";
		}
	}

	$tableName = $hlData['TABLE_NAME'];

	/** @global \CDatabase $DB */
	$rsIndexes = $DB->Query('SHOW INDEX FROM ' . $tableName);
	$columns = array();
	while ($item = $rsIndexes->Fetch())
		$columns[$item['Column_name']] = true;

	$fields = array(
		'UF_FUSER_ID',
	);
	foreach ($fields as $field)
	{
		if (!$columns[$field])
		{
			$DB->Query('ALTER TABLE ' . $tableName . ' ADD INDEX (' . $field . ')');
			echo "Добавлен индекс в таблице \"" . $tableName . "\" по полю \"" . $field . "\"\n";
		}
		else
			echo "Индекс в таблице \"" . $tableName . "\" по полю \"" . $field . "\" уже существует\n";
	}
}