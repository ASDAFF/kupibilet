<?

$statuses = array();
$rsStatuses = CSaleStatus::GetList();
while ($item = $rsStatuses->Fetch())
	$statuses[$item['ID']] = true;

if (!$statuses['RS'])
{
    $fields = array(
        'ID' => 'RS',
        'SORT' => 150,
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
    if (CSaleStatus::Add($fields))
        echo "Добавлен статус заказа \"RS\" - Зарезирвирован \n";
}
else
{
    echo "Статус заказа \"RS\" уже существует \n";
}

echo "\n";