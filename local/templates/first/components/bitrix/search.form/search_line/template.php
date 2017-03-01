<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>
<div class="search-form">
<form action="<?=$arResult["FORM_ACTION"]?>" id="elSearch">
	<table border="0" cellspacing="0" cellpadding="2" align="center" style="width: 100%;">
		<tr>
			<td align="center"><?if($arParams["USE_SUGGEST"] === "Y"):?><?$APPLICATION->IncludeComponent(
				"bitrix:search.suggest.input",
				"",
				array(
					"NAME" => "q",
					"VALUE" => "",
					"INPUT_SIZE" => 15,
					"DROPDOWN_SIZE" => 10,
				),
				$component, array("HIDE_ICONS" => "Y")
			);?><?else:?><input id="elSearch-pole" class="cssBorderRadius-left cssLeft" type="text" name="q" value="" size="15" maxlength="50" placeholder="Поиск концертов, мероприятий, исполнителей ..." autocomplete="off" /><?endif;?></td>
		</tr>
		<tr>
			<td align="right"><input name="s" id="elSearch-btn" class="cssBorderRadius-right engBtn cssLeft" type="submit" value="<?=GetMessage("BSF_T_SEARCH_BUTTON");?>" /></td>
		</tr>
	</table>
</form>
</div>