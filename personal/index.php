<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел");
?>
<div class="container bx_page">

	<div class="col-md-16 m_ct">
		<h1 class="m_toph1">Личный кабинет</h1>
	</div>

	<p>В личном кабинете Вы можете проверить текущее состояние корзины, ход выполнения Ваших заказов, просмотреть или изменить личную информацию, а также подписаться на новости и другие информационные рассылки. </p>
	<p>
		<h2>Личная информация</h2>
		<a href="profile/">Изменить регистрационные данные</a>
	</p>
	<p>
		<h2>Заказы</h2>
		<a href="order/">Ознакомиться с состоянием заказов</a><br/>
		<a href="cart/">Посмотреть содержимое корзины</a><br/>
		<a href="order/">Посмотреть историю заказов</a><br/>
	</p>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
