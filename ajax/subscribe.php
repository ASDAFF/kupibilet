<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$result = array(
	'ERROR' => '',
    'ID' => 0,
);

$email = $_REQUEST['email'];
if (!$email)
	$result['ERROR'] = 'empty_email';
else
{
	$ex = \Local\Sale\Subscribe::isEmail($email);
	if ($ex)
		$result['ERROR'] = 'ex';
	else
	{
		$id = \Local\Sale\Subscribe::addEmail($email);
		$result['ID'] = intval($id);
	}
}

header('Content-Type: application/json');
echo json_encode($result);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");