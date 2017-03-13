<?
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<div class="bx-auth"><?

	ShowMessage($arParams["~AUTH_RESULT"]);

	if ($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) &&
		$arParams["AUTH_RESULT"]["TYPE"] === "OK")
	{
		?>
		<p>На указанный в форме e-mail было выслано письмо с информацией о подтверждении регистрации.</p><?
	}
	else
	{
		if ($arResult["USE_EMAIL_CONFIRMATION"] === "Y")
		{
			?><p>На указанный в форме e-mail придет запрос на подтверждение регистрации.</p><?
		}

		?>
		<noindex >
		<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform"><?
			if (strlen($arResult["BACKURL"]) > 0)
			{
				?>
				<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/><?
			}
			?>
			<input type="hidden" name="AUTH_FORM" value="Y"/>
			<input type="hidden" name="TYPE" value="REGISTRATION"/>

			<table class="data-table bx-registration-table">
				<thead>
				<tr>
					<td colspan="2"><b>Регистрация</b></td>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>Имя</td>
					<td><input type="text" name="USER_NAME" maxlength="50" value="<?= $arResult["USER_NAME"] ?>"
					           class="bx-auth-input"/></td>
				</tr>
				<tr>
					<td>Фамилия</td>
					<td><input type="text" name="USER_LAST_NAME" maxlength="50" value="<?= $arResult["USER_LAST_NAME"] ?>"
					           class="bx-auth-input"/></td>
				</tr>
				<tr>
					<td><span class="starrequired">*</span>Пароль</td>
					<td><input type="password" name="USER_PASSWORD" maxlength="50" value="<?= $arResult["USER_PASSWORD"] ?>"
					           class="bx-auth-input" autocomplete="off"/>
					</td>
				</tr>
				<tr>
					<td><span class="starrequired">*</span>Подтверждение пароля</td>
					<td><input type="password" name="USER_CONFIRM_PASSWORD" maxlength="50"
					           value="<?= $arResult["USER_CONFIRM_PASSWORD"] ?>" class="bx-auth-input" autocomplete="off"/></td>
				</tr>
				<tr>
					<td><span class="starrequired">*</span>E-Mail</td>
					<td><input type="text" name="USER_EMAIL" maxlength="255" value="<?= $arResult["USER_EMAIL"] ?>"
					           class="bx-auth-input"/></td>
				</tr>
				</tbody>
				<tfoot>
				<tr>
					<td></td>
					<td><input type="submit" name="Register" value="Зарегистрироваться"/></td>
				</tr>
				</tfoot>
			</table>
			<p><? echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?></p>
			<p><span class="starrequired">*</span> Обязательные поля</p>

			<p>
				<a href="<?= $arResult["AUTH_AUTH_URL"] ?>" rel="nofollow"><b>Авторизация</b></a>
			</p>

		</form>
		</noindex><?
	}
	?>
</div>