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
                array(__NAMESPACE__ . '\Handlers', 'afterUserLogout'));
            AddEventHandler('main', 'OnAfterUserLogin',
                array(__NAMESPACE__ . '\Handlers', 'afterUserLogin'));
			AddEventHandler('main', 'OnBuildGlobalMenu',
				array(__NAMESPACE__ . '\Handlers', 'buildGlobalMenu'));
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

	/**
	 * Сбрасываем кеш корзины после логаута пользователя
	 */
	public static function afterUserLogout()
	{
	    Cart::updateSessionCartSummary();
    }

	/**
	 * Сбрасываем кеш корзины после логина пользователя
	 */
    public static function afterUserLogin()
    {
        Cart::updateSessionCartSummary();
    }

	public static function buildGlobalMenu(&$adminMenu, &$moduleMenu) {
		// Добавляем пункты меню в админку
		$moduleMenu[] = array(
			'parent_menu' => 'global_menu_content',
			'section' => 'chat',
			'sort' => 60,
			'text' => 'Отчеты о показах',
			'title' => 'Информация о проданных билетах',
			'url' => 'info.php',
			'icon' => 'statistic_icon_summary',
			'items_id' => 'info',
		);
	}


}