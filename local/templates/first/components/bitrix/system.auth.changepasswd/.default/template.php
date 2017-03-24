<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

?>
<div class="engBox">
	<div class="elFormAuth"><?

	ShowMessage($arParams["~AUTH_RESULT"]);

	?>
	<form method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform"><?
		if (strlen($arResult["BACKURL"]) > 0) {
			?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><?
		}
		?>
		<input type="hidden" name="AUTH_FORM" value="Y">
		<input type="hidden" name="TYPE" value="CHANGE_PWD">
		<table class="data-table bx-changepass-table">
			<thead>
			<tr>
				<td colspan="2"><b>Смена пароля</b></td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><span class="starrequired">*</span>Email:</td>
				<td><input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" class="bx-auth-input" /></td>
			</tr>
			<tr>
				<td><span class="starrequired">*</span>Контрольная строка:</td>
				<td><input type="text" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" class="bx-auth-input" /></td>
			</tr>
			<tr>
				<td><span class="starrequired">*</span>Новый пароль:</td>
				<td><input type="password" name="USER_PASSWORD" maxlength="50" value="<?=$arResult["USER_PASSWORD"]?>" class="bx-auth-input" autocomplete="off" /></td>
			</tr>
			<tr>
				<td><span class="starrequired">*</span>Подтверждение пароля:</td>
				<td><input type="password" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" class="bx-auth-input" autocomplete="off" /></td>
			</tr>
			</tbody>
			<tfoot>
			<tr>
				<td></td>
				<td><input type="submit" name="change_pwd" value="Изменить пароль" /></td>
			</tr>
			</tfoot>
		</table>

		<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
		<p><span class="starrequired">*</span>Обязательные поля</p>
		<p>
			<a href="<?=$arResult["AUTH_AUTH_URL"]?>"><b>Авторизация</b></a>
		</p>

	</form>
	</div>
</div>