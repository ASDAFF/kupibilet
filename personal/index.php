<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел");
?>
<div class="engBox">
    <div class="elFormAuth">
        <div class="container bx_page">

            <div class="col-md-16 m_ct">
                <h1 class="m_toph1">Личный кабинет</h1>
            </div>

	        <p>В личном кабинете Вы можете проверить текущее состояние корзины и ход выполнения Ваших заказов</p><?

	        /*?>
            <p>
                <h2>Личная информация</h2>
                <a href="profile/">Изменить регистрационные данные</a>
	        </p><?*/

	        ?>
            <p>
                <h2>Заказы</h2>
                <a href="cart/">Посмотреть содержимое корзины</a><br/>
                <a href="order/history/">Посмотреть историю заказов</a><br/>
            </p>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
