<?

$em = new \CEventMessage;
$message = "#ORDER_USER#<br />
Ваш заказ номер #ORDER_ID# оплачен.<br />
Проверочный код: #SECRET#<br />
#PRINT#";
$fields = array(
    'MESSAGE' => $message,
);
$res = $em->Update(43, $fields);
echo "Изменен шаблон \"[PAY_ORDER] Заказ оплачен\" ID - 43 \n";

echo "\n";