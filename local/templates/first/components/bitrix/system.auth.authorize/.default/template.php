<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

?>
<div class="engBox">
    <div class="elFormAuth">
        <?
        ShowMessage($arParams["~AUTH_RESULT"]);
        ShowMessage($arResult['ERROR_MESSAGE']);
        ?>
        <div class="bx-auth">
	<div class="bx-auth-note">Авторизация</div>
	<form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="AUTH" /><?

		if (strlen($arResult["BACKURL"]) > 0)
		{
			?>
			<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>" /><?
		}

		foreach ($arResult["POST"] as $key => $value)
		{
			?>
			<input type="hidden" name="<?=$key?>" value="<?=$value?>" /><?
		}

		?>
		<table class="bx-auth-table">
			<tr>
				<td class="bx-auth-label">Email</td>
				<td><input class="bx-auth-input" type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" /></td>
			</tr>
			<tr>
				<td class="bx-auth-label">Пароль</td>
				<td><input class="bx-auth-input" type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off" /></td>
			</tr><?

			if ($arResult["STORE_PASSWORD"] == "Y")
			{
				?>
				<tr>
					<td></td>
					<td><input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" /><label
							for="USER_REMEMBER">&nbsp;Запомнить меня</label></td>
				</tr><?
			}
			?>
			<tr>
				<td></td>
				<td class="authorize-submit-cell"><input type="submit" name="Login" value="Войти" /></td>
			</tr>
		</table>
		<noindex>
			<p>
				<a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow">Забыли пароль?</a>
			</p>
		</noindex><?

		if ($arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y")
		{
			?>
			<noindex>
				<p>
					<a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow">Регистрация</a><br />
					Если вы впервые на сайте, заполните, пожалуйста, регистрационную форму.
				</p>
			</noindex><?
		}

		?>
	</form>
</div>
    </div>
</div>
<?
