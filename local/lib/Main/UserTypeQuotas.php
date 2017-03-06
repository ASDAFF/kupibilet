<?

namespace Local\Main;

/**
 * Юзертайп "Квоты" - (базовый тип - S)
 * Class UserTypeQuotas
 * @package Local\Main
 */
class UserTypeQuotas
{
	public static function GetUserTypeDescription()
	{
		return array(
			'PROPERTY_TYPE' => 'S',
			'USER_TYPE' => 'Quotas',
			'DESCRIPTION' => 'Квоты',
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
		return '(Квоты)';
	}

	public static function GetPropertyFieldHtml($arProperty, $arValue, $arHTMLControlName)
	{
		$sReturn = '';
		if ($_REQUEST['ID'])
			$sReturn .= '<a href="/admin/quotas.php?ID=' . $_REQUEST['ID'] . '">Редактировать в конструкторе</a><br />';
		else
			$sReturn .= 'Для редактирования квот создайте элемент (кнопка "Применить")';
		$sReturn .= '<textarea name="' . $arHTMLControlName['VALUE'] . '" style="width:95%;height:150px;margin-top:5px;">' . $arValue['VALUE'] .
			'</textarea>';
		return $sReturn;
		$sReturn .= '<input type="hidden" name="' . $arHTMLControlName['VALUE'] . '" value=\'' . $arValue['VALUE'] .
			'\' />';
		return $sReturn;
	}
}
