<?
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

?>
<div class="engBox">
	<div class="elFormAuth">
		<div class="bx-auth"><?

			if (count($arResult["ERRORS"]) > 0)
			{
				foreach ($arResult["ERRORS"] as $key => $error)
					if (intval($key) == 0 && $key !== 0)
						$arResult['ERRORS'][$key] = str_replace("#FIELD_NAME#", "&quot;" . GetMessage("REGISTER_FIELD_" . $key) . "&quot;", $error);

				ShowError(implode('<br />', $arResult['ERRORS']));
			}

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
				<form method="post" action="<?= POST_FORM_ACTION_URI ?>" name="regform" enctype="multipart/form-data"><?
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
							<td><input type="text" name="REGISTER[NAME]" maxlength="50"
							           value="<?= $arResult['VALUES']['NAME'] ?>" class="bx-auth-input"/></td>
						</tr>
						<tr>
							<td>Фамилия</td>
							<td><input type="text" name="REGISTER[LAST_NAME]" maxlength="50"
							           value="<?= $arResult['VALUES']['LAST_NAME'] ?>" class="bx-auth-input"/></td>
						</tr>
						<tr>
							<td><span class="starrequired">*</span>E-Mail</td>
							<td><input type="text" name="REGISTER[EMAIL]" maxlength="255"
							           value="<?= $arResult['VALUES']['EMAIL'] ?>" class="bx-auth-input"/></td>
						</tr>
						<tr>
							<td><span class="starrequired">*</span>Пароль</td>
							<td><input type="password" name="REGISTER[PASSWORD]" maxlength="50" autocomplete="off"
							           value="<?= $arResult['VALUES']['PASSWORD'] ?>" class="bx-auth-input" />
							</td>
						</tr>
						<tr>
							<td><span class="starrequired">*</span>Подтверждение пароля</td>
							<td><input type="password" name="REGISTER[CONFIRM_PASSWORD]" maxlength="50"
							           autocomplete="off"
							           value="<?= $arResult['VALUES']['CONFIRM_PASSWORD'] ?>" class="bx-auth-input" /></td>
						</tr>
						<tr>
							<td>Дата рождения</td>
							<td><input type="text" name="REGISTER[PERSONAL_BIRTHDAY]" maxlength="255"
							           value="<?= $arResult['VALUES']['PERSONAL_BIRTHDAY'] ?>"
							           class="bx-auth-input"/><?
								$APPLICATION->IncludeComponent(
									'bitrix:main.calendar',
									'',
									array(
										'SHOW_INPUT' => 'N',
										'FORM_NAME' => 'regform',
										'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
										'SHOW_TIME' => 'N'
									),
									null,
									array("HIDE_ICONS"=>"Y")
								);

								?>
							</td>
						</tr>
						<tr>
							<td>Телефон</td>
							<td><input type="text" name="REGISTER[PERSONAL_PHONE]" maxlength="255"
							           value="<?= $arResult['VALUES']['PERSONAL_PHONE'] ?>" class="bx-auth-input"/></td>
						</tr>
						</tbody>
						<tfoot>
						<tr>
							<td></td>
							<td><input type="submit" name="register_submit_button" value="Зарегистрироваться"/></td>
						</tr>
						</tfoot>
					</table>
					<p><? echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?></p>
					<p><span class="starrequired">*</span> Обязательные поля</p>

					<p>
						<a href="/login/" rel="nofollow"><b>Авторизация</b></a>
					</p>

				</form>
				</noindex><?
			}
			?>
		</div>
	</div>
</div><?
