<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */



?>
<div class="engBox">
    <div class="elFormAuth">
        <?ShowMessage($arParams["~AUTH_RESULT"]);?>
        <form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>"><?
	if (strlen($arResult["BACKURL"]) > 0)
	{
		?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><?
	}

	?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">
	<p>Если вы забыли пароль, введите E-Mail.<br />
		Контрольная строка для смены пароля, а также ваши регистрационные данные, будут высланы вам по E-Mail.</p>

	<table class="data-table bx-forgotpass-table">
		<thead>
			<tr>
				<td colspan="2"><b>Выслать контрольную строку</b></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>E-Mail</td>
				<td>
					<input type="text" name="USER_EMAIL" maxlength="255" />
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2">
					<input type="submit" name="send_account_info" value="Выслать" />
				</td>
			</tr>
		</tfoot>
	</table>
	<p>
	<a href="<?=$arResult["AUTH_AUTH_URL"]?>"><b>Авторизация</b></a>
	</p>
</form>
    </div>
</div>