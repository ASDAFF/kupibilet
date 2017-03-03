<?

namespace Local\Main;

/**
 * Юзертайп "Да/Нет" - (базовый тип - N)
 * Class UserTypeScheme
 * @package Local\Main
 */
class UserTypeScheme
{
	public static function GetUserTypeDescription()
	{
		return array(
			'PROPERTY_TYPE' => 'S',
			'USER_TYPE' => 'Scheme',
			'DESCRIPTION' => 'Схема зала',
			'GetAdminListViewHTML' => array(
				__CLASS__,
				'GetAdminListViewHTML'
			),
			'GetPropertyFieldHtml' => array(
				__CLASS__,
				'GetPropertyFieldHtml'
			),
		);
	}

	public static function GetAdminListViewHTML($arProperty, $arValue, $strHTMLControlName)
	{
		return '(Схема)';
	}

	public static function GetPropertyFieldHtml($arProperty, $arValue, $arHTMLControlName)
	{
		$sReturn = '';
		if ($_REQUEST['ID'])
			$sReturn .= '<a href="/admin/scheme.php?ID=' . $_REQUEST['ID'] . '">Редактировать в конструкторе</a><br />';
		else
			$sReturn .= 'Для редактирования схемы создайте элемент (кнопка "Применить")';
		$sReturn .= '<input type="hidden" name="' . $arHTMLControlName['VALUE'] . '" value=\'' . $arValue['VALUE'] .
			'\' />';
		return $sReturn;
	}
}
