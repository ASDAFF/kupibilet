<?php

namespace Local\System;


use Dompdf\Dompdf;
use Local\Sale\Cart;

class Pdf
{

	public static function create($filePath, $orderId, $orderItems)
	{
		$order = Cart::getOrderById($orderId);
		include $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';
		$mpdf = new Dompdf();
		ob_start();
		require $_SERVER['DOCUMENT_ROOT'] . '/local/components/tim/empty/templates/order/print.php';
		$html = ob_get_clean();
		echo $html;
		$mpdf->loadHtml($html);
		$mpdf->setPaper('A4', 'portrait');
		$mpdf->render();
		$data = $mpdf->output();
		if (!file_exists($filePath))
		{
			if (file_put_contents($filePath, $data))
			{
				return $filePath;
			}
		}

		return false;
	}

}

?>