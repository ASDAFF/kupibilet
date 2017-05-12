<?php

namespace Local\System;

use Dompdf\Dompdf;
use Local\Sale\Cart;

class Pdf
{

	public static function create($orderId, $items)
	{
		$filePath = $_SERVER['DOCUMENT_ROOT'] . '/_pdf/order-' . $orderId . '.pdf';
		if (file_exists($filePath))
			return $filePath;

		$orderItems = [
			'ITEMS' => $items,
		];
		$order = Cart::getOrderById($orderId);

		ob_start();
		require $_SERVER['DOCUMENT_ROOT'] . '/local/components/tim/empty/templates/order/print.php';
		$html = ob_get_clean();

		file_put_contents($filePath . '.html', $html);

		include $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';
		$mpdf = new Dompdf();
		$mpdf->loadHtml($html);
		$mpdf->setPaper('A4', 'portrait');
		$mpdf->render();
		$data = $mpdf->output();

		if (file_put_contents($filePath, $data))
			return $filePath;

		return false;
	}

}
