<?
// подключим все необходимые файлы:
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php"); // первый общий пролог
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/sale/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/sale/include.php");

// получим права доступа текущего пользователя на модуль
$POST_RIGHT = $APPLICATION->GetGroupRight("sale");
// если нет прав - отправим к форме авторизации с сообщением об ошибке
if ($POST_RIGHT == "D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$sTableID = "tbl_info"; // ID таблицы
$oSort = new CAdminSorting($sTableID, "ID", "desc"); // объект сортировки
$lAdmin = new CAdminList($sTableID, $oSort); // основной объект списка


$runs = \Local\Main\Run::getAll();
$runs_filter = [];
$arFilter = [];
$res = [];


if ($_REQUEST['find_id'])
{

	$reserved = \Local\Sale\Reserve::getByFilter(['UF_RUN' => $_REQUEST['find_id'], 'UF_PAYED' => 1]);
	$baskets = new CSaleBasket();
	$order = new CSaleOrder();
	$carts = $baskets->GetList([], ['=ID' => $reserved]);

	$cartIds = [];

	while ($cart = $carts->Fetch())
	{
		$cartIds[] = $cart['ID'];
		$arOrder = $order->GetByID($cart['ORDER_ID']);
		$cart['DELIVERY'] = ($arOrder['PRICE_DELIVERY']) ? 'Да' : 'Нет';
		$res[$cart['ID']] = $cart;
	}

	if ($res)
	{
		$items = $baskets->GetPropsList([], ['@BASKET_ID' => $cartIds]);
		while ($item = $items->Fetch())
			$res[$item['BASKET_ID']][$item['CODE']] = $item['VALUE'];
	}
}

foreach ($runs as $run)
{
	foreach ($run as $elem)
	{
		$runs_filter['REFERENCE'][] = $elem['NAME'] . " - " . date('d.m.Y', $elem['TS']);
		$runs_filter['REFERENCE_ID'][] = $elem['ID'];
	}
}


// ******************************************************************** //
//                           ФИЛЬТР                                     //
// ******************************************************************** //

// *********************** CheckFilter ******************************** //
// проверку значений фильтра для удобства вынесем в отдельную функцию
function CheckFilter()
{
	global $FilterArr, $lAdmin;
	foreach ($FilterArr as $f) global $$f;

	// В данном случае проверять нечего.
	// В общем случае нужно проверять значения переменных $find_имя
	// и в случае возниконовения ошибки передавать ее обработчику
	// посредством $lAdmin->AddFilterError('текст_ошибки').

	return count($lAdmin->arFilterErrors) == 0; // если ошибки есть, вернем false;
}

// *********************** /CheckFilter ******************************* //

// опишем элементы фильтра
$FilterArr = [
	"find_id",
];


// инициализируем фильтр
$lAdmin->InitFilter($FilterArr);


// ******************************************************************** //
//                ВЫБОРКА ЭЛЕМЕНТОВ СПИСКА                              //
// ******************************************************************** //
// выберем список рассылок

// преобразуем список в экземпляр класса CAdminResult
$rsData = new CAdminResult($res, $sTableID);

// аналогично CDBResult инициализируем постраничную навигацию.
$rsData->NavStart();

// отправим вывод переключателя страниц в основной объект $lAdmin
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("rub_nav")));

// ******************************************************************** //
//                ПОДГОТОВКА СПИСКА К ВЫВОДУ                            //
// ******************************************************************** //

$lAdmin->AddHeaders([
	[
		"id" => "ID",
		"content" => "Номер билета",
		"sort" => "id",
		"default" => true,
	],
	[
		"id" => "DATE_INSERT",
		"content" => "Дата продажи билета",
		"sort" => "date_insert",
		"default" => true,
	],
	[
		"id" => "ORDER_ID",
		"content" => "ID заказа",
		"sort" => "order_id",
		"default" => true,
	],
	[
		"id" => "SECTOR",
		"content" => "Сектор",
		"sort" => "sector",
		"default" => true,
	],
	[
		"id" => "ROW",
		"content" => "Ряд",
		"sort" => "row",
		"default" => true,
	],
	[
		"id" => "NUM",
		"content" => "Место",
		"sort" => "num",
		"default" => true,
	],
	[
		"id" => "SECRET",
		"content" => 'Электронный номер билета',
		"sort" => "auto",
		"default" => true,
	],
	[
		"id" => "PRICE",
		"content" => 'Цена билета, руб.',
		"sort" => "price",
		"default" => true,
	],
    [
		"id" => "DELIVERY",
		"content" => 'Доставка',
		"sort" => "delivery",
		"default" => true,
	],
]);

while ($arRes = $rsData->NavNext(true, "f_"))
{
	// создаем строку. результат - экземпляр класса CAdminListRow
	$row =& $lAdmin->AddRow($f_ID, $arRes);

	$row->AddViewField("PRICE", number_format($arRes['PRICE'], 0, '', ' '));

	if (intval($arRes['ORDER_ID']))
	{
		$url = '/bitrix/admin/sale_order_view.php?ID=' . intval($arRes['ORDER_ID']) . '&filter=Y&set_filter=Y&lang=ru';
		$row->AddViewField("ORDER_ID", '<a href="' . $url . '">' . $arRes['ORDER_ID'] . '</a>');
	}
}

$lAdmin->AddAdminContextMenu();

// ******************************************************************** //
//                ВЫВОД                                                 //
// ******************************************************************** //

// альтернативный вывод
$lAdmin->CheckListMode();

// не забудем разделить подготовку данных и вывод
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$APPLICATION->SetTitle('Отчеты о показах');

// ******************************************************************** //
//                ВЫВОД ФИЛЬТРА                                         //
// ******************************************************************** //

// создадим объект фильтра
$oFilter = new CAdminFilter(
	$sTableID . "_filter",
	[
		"ID",
	]
);
?>
    <form name="find_form" method="get" action="<? echo $APPLICATION->GetCurPage(); ?>">
		<? $oFilter->Begin(); ?>
        <tr>
            <td><b>Показ:</b></td>
            <td>
				<?

				echo SelectBoxFromArray("find_id", $runs_filter, $_REQUEST['find_id']);
				?>
            </td>
        </tr>

		<?
		$oFilter->Buttons(["table_id" => $sTableID, "url" => $APPLICATION->GetCurPage(), "form" => "find_form"]);
		$oFilter->End();
		?>
    </form>

<?
// выведем таблицу списка элементов
$lAdmin->DisplayList();


// завершение страницы
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>