<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */

use Voronkovich\SberbankAcquiring\Client;
use Voronkovich\SberbankAcquiring\OrderStatus;
use Voronkovich\SberbankAcquiring\Exception\ActionException;

$assets = \Bitrix\Main\Page\Asset::getInstance();
$assets->addJs('/js/cart.js');

$id = $_REQUEST['id'];
$order = \Local\Sale\Cart::getOrderById($id);
if (!$order)
	return;

$orderItems = \Local\Sale\Cart::getOrderItems($order['ID']);

if ($arParams['PAGE'] == 'print')
{
	if ($order['STATUS_ID'] == 'F')
		include ('print.php');
	else
		return;
}
else
{
	if ($order['STATUS_ID'] == 'F')
	{
		include('success.php');
	}
	elseif ($order['STATUS_ID'] == 'O')
	{
		include('overdue.php');
	}
	else
	{
		if ($arParams['PAGE'] == 'complete')
		{
			include('complete.php');
		}
		elseif ($arParams['PAGE'] == 'pay')
		{
			if ($order['XML_ID'])
			{
				try
				{
					$client = new Client(array(
						'userName' => 'kupibilet-api',
						'password' => 'C~opKB*Q@h',
						//'apiUri' => Client::API_URI_TEST,
					));
					$result = $client->getOrderStatus($order['XML_ID']);
					if (OrderStatus::isCreated($result['OrderStatus']))
					{
						header('Location: ' . $order['ADDITIONAL_INFO']);
					}
					elseif (OrderStatus::isDeposited($result['OrderStatus']))
					{
						\Local\Sale\Cart::setOrderPayed($order['ID'], $orderItems['ITEMS']);
						include('success.php');
					}
				}
				catch (ActionException $e)
				{
					include('cancel.php');
				}
				catch (\Exception $e)
				{
					LocalRedirect('/personal/order/payment/error.php');
				}
			}
			else
			{
				try
				{
					$client = new Client(array(
						'userName' => 'kupibilet-api',
						'password' => 'C~opKB*Q@h',
						//'apiUri' => Client::API_URI_TEST,
					));

					$host = $_SERVER['HTTP_HOST'];
					$orderId = $order['ID'];
					$orderAmount = $order['PRICE'] * 100;
					$returnUrl = 'http://' . $host . '/personal/order/payment/success/' . $order['ID'] . '/';
					$params = array();
					$params['failUrl'] = 'http://' . $host . '/personal/order/payment/error.php';

					$result = array();
					$result = $client->registerOrder($orderId, $orderAmount, $returnUrl, $params);

					$paymentOrderId = $result['orderId'];
					$paymentFormUrl = $result['formUrl'];

					if ($paymentOrderId)
					{
						\Local\Sale\Cart::prolongReserve($orderItems['ITEMS']);
						\Local\Sale\Cart::setSbOrderId($order['ID'], $paymentOrderId, $paymentFormUrl);
						header('Location: ' . $paymentFormUrl);
					}
					else
						LocalRedirect('/personal/order/payment/error.php');
				}
				catch (ActionException $e)
				{
					include('cancel.php');
				}
				catch (\Exception $e)
				{
					LocalRedirect('/personal/order/payment/error.php');
				}
			}
		}
		elseif ($arParams['PAGE'] == 'success')
		{
			try
			{
				$client = new Client(array(
					'userName' => 'kupibilet-api',
					'password' => 'C~opKB*Q@h',
					//'apiUri' => Client::API_URI_TEST,
				));

				$sbOrderId = $_REQUEST['orderId'];
				$ok = false;
				if ($sbOrderId)
				{
					$result = $client->getOrderStatus($sbOrderId);
					if (OrderStatus::isDeposited($result['OrderStatus']))
					{
						\Local\Sale\Cart::setOrderPayed($order['ID'], $orderItems['ITEMS']);
						$ok = true;
					}
				}

				if ($ok)
					include('success.php');
				else
					LocalRedirect('/personal/order/payment/error.php');
			}
			catch (ActionException $e)
			{
				include('cancel.php');
			}
			catch (\Exception $e)
			{
				LocalRedirect('/personal/order/payment/error.php');
			}
		}
	}

}