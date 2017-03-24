<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("Смена пароля");
$APPLICATION->SetPageProperty('title', "Смена пароля");

?>
<div class="engBox">
	<div class="elFormAuth">
		<p class="notetext">Вы зарегистрированы и успешно авторизовались.</p>

		<p><a href="/">Вернуться на главную страницу</a></p>
	</div>
</div><?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");