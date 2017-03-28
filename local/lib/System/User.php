<?
namespace Local\System;

/**
 * Дополнительные методы для работы с пользователем битрикса
 */
class User
{
	/**
	 * @var bool Пользователь
	 */
	private static $user = false;
	/**
	 * @var \CUser
	 */
	private static $u = false;
	/**
	 * @var int
	 */
	private static $uId = false;

	/**
	 * Возвращает ID текущего пользователя
	 * @return bool|null
	 */
	public static function getCurrentUserId()
	{
		if (!self::$uId)
		{
			if ($_SESSION['LOCAL_USER']['ID'])
				self::$uId = $_SESSION['LOCAL_USER']['ID'];
			else
			{
				$u = self::getBitrixUser();
				self::$uId = intval($u->GetID());
			}
		}
		return self::$uId;
	}

	/**
	 * Возвращает объект битрикса
	 * @return \CUser
	 */
	public static function getBitrixUser()
	{
		if (!self::$u)
			self::$u = new \CUser();

		return self::$u;
	}

	/**
	 * Возвращает текущего пользователя
	 * @param bool $update
	 * @return array|bool
	 */
	public static function getCurrentUser($update = false)
	{
		if ($update || self::$user === false)
		{
			$userId = self::getCurrentUserId();
			if ($userId)
			{
				$u = self::getBitrixUser();
				$rs = $u->GetByID($userId);
				$user = $rs->Fetch();
				self::$user = array(
					'ID' => $user['ID'],
					'NAME' => $user['NAME'],
				    'LAST_NAME' => $user['LAST_NAME'],
				    'EMAIL' => $user['EMAIL'],
				    'PHONE' => $user['PERSONAL_PHONE'],
				    'ADDRESS' => $user['PERSONAL_STREET'],
				);
			}
			else
				self::$user = array();
		}

		return self::$user;
	}

	/**
	 * Корректирует имя и фамилию ползователя
	 * @param $name
	 * @param $lastName
	 * @param $phone
	 * @param $address
	 */
	public static function update($name, $lastName, $phone, $address)
	{
		if (self::$user === false)
			return;

		$update = array();
		if ($name && self::$user['NAME'] != $name)
			$update['NAME'] = $name;
		if ($lastName && self::$user['LAST_NAME'] != $lastName)
			$update['LAST_NAME'] = $lastName;
		if ($phone && self::$user['PHONE'] != $phone)
			$update['PERSONAL_PHONE'] = $phone;
		if ($address && self::$user['ADDRESS'] != $address)
			$update['PERSONAL_STREET'] = $address;
		if ($update)
		{
			$u = self::getBitrixUser();
			$u->Update(self::$user['ID'], $update);
		}
	}

	/**
	 * Возвращает пользователя по email
	 * @param $email
	 * @return array
	 */
	public static function getByEmail($email)
	{
		$user = array();

		$u = self::getBitrixUser();
		$rs = $u->GetList($by, $order, array(
			'=EMAIL' => $email,
		));
		if ($item = $rs->Fetch())
		{
			$_SESSION['LOCAL_USER']['ID'] = $item['ID'];
			$user = self::getCurrentUser(true);
		}

		return $user;
	}

	/**
	 * Проверка пользователя перед созданием заказа
	 * @param $name
	 * @param $lastName
	 * @param $email
	 * @param $phone
	 * @param $address
	 * @return array|bool
	 */
	public static function checkOrder($name, $lastName, $email, $phone, $address)
	{
		$name = htmlspecialchars(trim($name));
		$lastName = htmlspecialchars(trim($lastName));
		$email = htmlspecialchars(trim($email));
		$phone = htmlspecialchars(trim($phone));
		$address = htmlspecialchars(trim($address));

		$user = self::getCurrentUser();
		if ($user)
		{
			// Если пользователь авторизован - скорректируем поля профиля
			self::update($name, $lastName, $phone, $address);
		}
		else
		{
			if (!$email)
				return array(
					'MESSAGE' => 'Заполните email',
				);

			// Если не авторизован - пробуем найти по email
			$user = self::getByEmail($email);
			if (!$user)
			{
				// если не найден по email - регистрируем
				$user = self::register($name, $lastName, $email);
				if ($user)
					self::update($name, $lastName, $phone, $address);
			}
		}

		return $user;
	}

	/**
	 * Генерирует пароль
	 * @param int $length
	 * @return string
	 */
	public static function generatePass($length = 8)
	{
		$keychars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789_';
		$return = '';
		$max = strlen($keychars) - 1;
		for ($i = 0; $i < $length; $i++)
			$return .= $keychars{rand(0, $max)};
		return $return;
	}

	/**
	 * Регистрирует пользователя
	 * @param $name
	 * @param $lastName
	 * @param $email
	 * @return array|bool
	 */
	public static function register($name, $lastName, $email)
	{
		$name = htmlspecialchars(trim($name));
		$lastName = htmlspecialchars(trim($lastName));
		$email = htmlspecialchars(trim($email));
		$pass = self::generatePass();
		if (!self::$u)
			self::$u = new \CUser();
		$user = self::$u->Register(
			$email,
			$name,
			$lastName,
			$pass,
			$pass,
			$email
		);

		if ($user['TYPE'] == 'OK' && $user['ID'])
		{
			$_SESSION['LOCAL_USER']['PASS'] = $pass;
			$user = self::getCurrentUser(true);
		}

		return $user;
	}
}
