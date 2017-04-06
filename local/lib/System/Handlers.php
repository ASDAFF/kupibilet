<?
namespace Local\System;
use Local\Main\Event;
use Local\Main\UserTypeQuotas;
use Local\Main\UserTypeScheme;
use Local\Sale\Cart;

/**
 * Class Handlers Обработчики событий
 * @package Local\Utils
 */
class Handlers
{
	/**
	 * Добавление обработчиков
	 */
	public static function addEventHandlers() {
		static $added = false;
		if (!$added) {
			$added = true;
			AddEventHandler('iblock', 'OnBeforeIBlockElementDelete',
				array(__NAMESPACE__ . '\Handlers', 'beforeIBlockElementDelete'));
			AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate',
				array(__NAMESPACE__ . '\Handlers', 'beforeIBlockElementUpdate'));
			AddEventHandler('iblock', 'OnAfterIBlockAdd',
				array(__NAMESPACE__ . '\Handlers', 'afterIBlockUpdate'));
			AddEventHandler('iblock', 'OnAfterIBlockUpdate',
				array(__NAMESPACE__ . '\Handlers', 'afterIBlockUpdate'));
			AddEventHandler('iblock', 'OnIBlockDelete',
				array(__NAMESPACE__ . '\Handlers', 'afterIBlockUpdate'));
			AddEventHandler('iblock', 'OnIBlockPropertyBuildList',
				array(__NAMESPACE__ . '\Handlers', 'addYesNo'));
			AddEventHandler('iblock', 'OnIBlockPropertyBuildList',
				array(__NAMESPACE__ . '\Handlers', 'addScheme'));
			AddEventHandler('iblock', 'OnIBlockPropertyBuildList',
				array(__NAMESPACE__ . '\Handlers', 'addQuotas'));
			AddEventHandler('main', 'OnProlog',
				array(__NAMESPACE__ . '\Handlers', 'prolog'));
			AddEventHandler('search', 'BeforeIndex',
				array(__NAMESPACE__ . '\Handlers', 'beforeSearchIndex'));
			AddEventHandler('sale', 'OnBasketDelete',
				array(__NAMESPACE__ . '\Handlers', 'basketDelete'));
			AddEventHandler('main', 'OnBeforeUserRegister',
				array(__NAMESPACE__ . '\Handlers', 'beforeUserRegister'));
            AddEventHandler('main', 'OnAfterUserLogout',
                array(__NAMESPACE__ . '\Handlers', 'OnAfterUserLogout'));
            AddEventHandler('main', 'OnAfterUserLogin',
                array(__NAMESPACE__ . '\Handlers', 'OnAfterUserLogin'));
		}
	}

	/**
	 * Добавление пользовательских свойств
	 * @return array
	 */
	public static function addYesNo() {
		return UserTypeNYesNo::GetUserTypeDescription();
	}
	public static function addScheme() {
		return UserTypeScheme::GetUserTypeDescription();
	}
	public static function addQuotas() {
		return UserTypeQuotas::GetUserTypeDescription();
	}

	/**
	 * Обработчик события перед удалением элемента, с возможностью отмены удаления
	 * @param $id
	 * @return bool
	 */
	public static function beforeIBlockElementDelete($id)
	{


		return true;
	}

	/**
	 * Обработчик события перед изменением элемента с возможностью отмены изменений
	 * @param $arFields
	 * @return bool
	 */
	public static function beforeIBlockElementUpdate(&$arFields)
	{


		return true;
	}

	/**
	 * обработчик на редактирование ИБ для сброса кеша
	 */
	public static function afterIBlockUpdate() {
		Utils::getAllIBlocks(true);
	}

	/**
	 * Перед выводом визуальной части
	 */
	public static function prolog() {

	}

	/**
	 * Формируем поисковый контент
	 * @param $arFields
	 * @return mixed
	 */
	public static function beforeSearchIndex($arFields)
	{
		if ($arFields['MODULE_ID'] == 'iblock' && $arFields['PARAM2'] == Event::IBLOCK_ID)
			$arFields = Event::beforeSearchIndex($arFields);

		return $arFields;
	}

	/**
	 * После удаления товара из корзины
	 * @param $arFields
	 * @return mixed
	 */
	public static function basketDelete($arFields)
	{
		Cart::updateSessionCartSummary();

		return $arFields;
	}

	/**
	 * Логин копируем из email
	 * @param $arFields
	 */
	public static function  beforeUserRegister(&$arFields)
	{
		$arFields['LOGIN'] = $arFields['EMAIL'];
	}

	public static function OnAfterUserLogout(){
	    Cart::updateSessionCartSummary();
    }

    public static function OnAfterUserLogin(){
        Cart::updateSessionCartSummary();
    }


}