<?
namespace Local\Sale;

use Bitrix\Main\Loader;
use Local\System\User;

/**
 * Class Subscribe Баннер
 * @package Local\Sale
 */
class Subscribe
{
    /**
     * Путь для кеширования
     */
    const CACHE_PATH = 'Local/Sale/Subscribe/';

    const SUBSCRIBE_ID = 1;

	/**
	 * Подписан ли текущий пользователь
	 * @return bool
	 */
    public static function isSubscribed()
    {
	    Loader::IncludeModule('subscribe');

	    $user = User::getCurrentUser();
	    $userId = intval($user['ID']);
	    if (!$userId)
		    return false;

	    $filter = array();
	    if ($user['EMAIL'])
		    $filter['=EMAIL'] = $user['EMAIL'];
	    else
		    $filter['USER_ID'] = $userId;

	    return self::getByFilter($filter);
    }

	/**
	 * Подписан ли email
	 * @param $email
	 * @return bool
	 * @throws \Bitrix\Main\LoaderException
	 */
	public static function isEmail($email)
	{
		Loader::IncludeModule('subscribe');

		$filter = array();
		$filter['=EMAIL'] = $email;

		return self::getByFilter($filter);
	}

	/**
	 * Есть ли подписка по фильтру
	 * @param $filter
	 * @return bool
	 */
	public static function getByFilter($filter)
	{
		$filter['RUBRIC'] = self::SUBSCRIBE_ID;

		$subscr = new \CSubscription;
		$rs = $subscr->GetList(array(), $filter);
		if ($item = $rs->Fetch())
			return true;

		return false;
	}

	/**
	 * Добавляет email к рассылке
	 * @param $email
	 * @return mixed
	 */
	public static function addEmail($email)
	{
		Loader::IncludeModule('subscribe');

		$user = User::getCurrentUser();
		$userId = intval($user['ID']);
		$fields = Array(
			'USER_ID' => $userId,
			'FORMAT' => 'html',
			'EMAIL' => $email,
			'ACTIVE' => 'Y',
			'RUB_ID' => array(self::SUBSCRIBE_ID),
		);

		$subscr = new \CSubscription;
		$ID = $subscr->Add($fields);
		if ($ID > 0)
			$subscr->Authorize($ID);

		return $ID;
	}
}
