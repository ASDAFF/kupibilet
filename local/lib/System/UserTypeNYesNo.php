<?

namespace Local\System;

/**
 * Юзертайп "Да/Нет" - (базовый тип - N)
 * Class UserTypeNYesNo
 * @package Local\System
 */
class UserTypeNYesNo
{
	public static function GetUserTypeDescription()
	{
		return array(
			'PROPERTY_TYPE' => 'N',
			'USER_TYPE' => 'YesNo',
			'DESCRIPTION' => 'Да/Нет',
			'GetAdminListViewHTML' => array(
				__CLASS__,
				'GetAdminListViewHTML'
			),
			'GetPropertyFieldHtml' => array(
				__CLASS__,
				'GetPropertyFieldHtml'
			),
			'GetAdminFilterHTML' => array(
				__CLASS__,
				'GetAdminFilterHTML'
			),
			'ConvertToDB' => array(
				__CLASS__,
				'ConvertToDB'
			),
			'ConvertFromDB' => array(
				__CLASS__,
				'ConvertFromDB'
			),
		);
	}

	public static function GetAdminListViewHTML($arProperty, $arValue, $strHTMLControlName)
	{
		return intval($arValue['VALUE']) == 1 ? 'Да' : 'Нет';
	}

	public static function GetPropertyFieldHtml($arProperty, $arValue, $arHTMLControlName)
	{
		$sChecked = intval($arValue['VALUE']) == 1 ? ' checked="checked"' : '';
		$sReturn = '';
		$sReturn .= '<input type="hidden" name="' . $arHTMLControlName['VALUE'] . '" value="0" />';
		$sReturn .= '<input' . $sChecked . ' type="checkbox" name="' . $arHTMLControlName['VALUE'] . '" id="' . $arHTMLControlName['VALUE'] . '" value="1" />';
		if ($arProperty['WITH_DESCRIPTION'] == 'Y')
		{
			$sReturn .= '<div><input type="text" size="' . $arProperty['COL_COUNT'] . '" name="' . $arHTMLControlName['DESCRIPTION'] . '" value="' . htmlspecialchars($arValue['DESCRIPTION']) . '" /></div>';
		}
		return $sReturn;
	}

	public static function GetAdminFilterHTML($arProperty, $strHTMLControlName)
	{

		$value = '';
		if (array_key_exists($strHTMLControlName['VALUE'], $_REQUEST))
			$value = intval($_REQUEST[$strHTMLControlName['VALUE']]) == 1 ? 1 : 0;
		elseif (isset($GLOBALS[$strHTMLControlName['VALUE']]))
			$value = intval($GLOBALS[$strHTMLControlName['VALUE']]) == 1 ? 1 : 0;

		$strResult = '<select name="' . htmlspecialcharsbx($strHTMLControlName['VALUE']) . '" id="filter_' . htmlspecialcharsbx($strHTMLControlName['VALUE']) . '">';
		$strResult .= '<option value=""' . ('' === $value ? ' selected="selected"' : '') . '>(любой)</option>';
		$strResult .= '<option value="1"' . (1 === $value ? ' selected="selected"' : '') . '>Да</option>';
		$strResult .= '<option value="0"' . (0 === $value ? ' selected="selected"' : '') . '>Нет</option>';
		$strResult .= '</select>';

		return $strResult;
	}

	public static function ConvertToDB($arProperty, $arValue)
	{
		$arValue['VALUE'] = intval($arValue['VALUE']) == 1 ? 1 : 0;
		return $arValue;
	}

	public static function ConvertFromDB($arProperty, $arValue)
	{
		$arValue['VALUE'] = intval($arValue['VALUE']) == 1 ? 1 : 0;
		return $arValue;
	}
}
