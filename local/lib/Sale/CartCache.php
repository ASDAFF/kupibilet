<?
namespace Local\Sale;

use Bitrix\Highloadblock\HighloadBlockTable;

/**
 * Class CartCache Служебный класс для управления кешем корзин
 * @package Local\Sale
 */
class CartCache
{
	/**
	 * ID HL-блока
	 */
	const ENTITY_ID = 3;

	/**
	 * Нужно ли очистить кеш корзины
	 * @param $fuserId
	 * @return int 0 - значит не нужно
	 * @throws \Bitrix\Main\ArgumentException
	 * @throws \Bitrix\Main\SystemException
	 */
	public static function getByFuserId($fuserId)
	{
		$entityInfo = HighloadBlockTable::getById(static::ENTITY_ID)->Fetch();
		$entity = HighloadBlockTable::compileEntity($entityInfo);
		$dataClass = $entity->getDataClass();
		$rsItems = $dataClass::getList(array(
			'filter' => array(
				'UF_FUSER_ID' => $fuserId,
			),
		));
		$return = 0;
		if ($item = $rsItems->Fetch())
			$return = $item['ID'];

		return $return;
	}

	/**
	 * Добавляет информацию о том, что нужно очистить кеш корзины
	 * @param $fuserId
	 * @return int
	 * @throws \Bitrix\Main\SystemException
	 * @throws \Exception
	 */
	public static function add($fuserId)
	{
		$entityInfo = HighloadBlockTable::getById(static::ENTITY_ID)->Fetch();
		$entity = HighloadBlockTable::compileEntity($entityInfo);
		$dataClass = $entity->getDataClass();
		$rsItems = $dataClass::getList(array(
			'filter' => array(
				'UF_FUSER_ID' => $fuserId,
			),
		));
		if ($item = $rsItems->Fetch())
			return false;

		$data = array();
		$data['UF_FUSER_ID'] = $fuserId;
		$result = $dataClass::add($data);
		$id = $result->getId();
		return $id;
	}

	/**
	 * Удаляет элемент (после того, как очистили кеш корзины)
	 * @param $id
	 * @throws \Bitrix\Main\ArgumentException
	 * @throws \Bitrix\Main\SystemException
	 * @throws \Exception
	 */
	public static function delete($id)
	{
		$entityInfo = HighloadBlockTable::getById(static::ENTITY_ID)->Fetch();
		$entity = HighloadBlockTable::compileEntity($entityInfo);
		$dataClass = $entity->getDataClass();
		$dataClass::delete($id);
	}
}
