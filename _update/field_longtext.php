<?
/** @global \CDatabase $DB */
$tableName = 'b_iblock_element_prop_s2';
$dbItems = $DB->Query('SHOW COLUMNS FROM ' . $tableName);
$arExColumns = array();
while($item = $dbItems->Fetch()) {
	if ($item['Field'] == 'PROPERTY_23')
	{
		if ($arItem['Type'] == 'text')
		{
			$dbItems = $DB->Query('ALTER TABLE ' . $tableName . ' CHANGE PROPERTY_23 PROPERTY_23 longtext NULL');
			echo "Изменен тип поля PROPERTY_23 на longtext.\n";
		}
	}
}

echo "\n";

