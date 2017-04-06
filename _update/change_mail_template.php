<?php

$em = new CEventMessage;
$message = "#ORDER_USER#<br />
Ваш заказ номер #ORDER_ID# оплачен.<br />
Проверочный код: #SECRET#<br />
#PRINT#";
$arFields = Array(
    "MESSAGE" => $message,
);
$em->Update(43, $arFields);
echo "Изменен шаблон \"[PAY_ORDER] Заказ оплачен\" ID - 43 \n";