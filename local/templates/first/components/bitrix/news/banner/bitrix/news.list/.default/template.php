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
$this->setFrameMode(true);
?>
<?//<div class="news-list">?>
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>

	<?
	$arItem["BANNER_IMG"] = CFile::GetPath($arItem["PROPERTIES"]["BANNER_IMG"]["VALUE"]);
    ?>
	
	<?if($arItem["PROPERTIES"]["IN_BANNER"]["VALUE"] == 1) :?>
		<div class="elSlider-item">
	        <img src="<?=$arItem['BANNER_IMG']?>">
	        <div class="it-inf">
	            <div class="it-title"><?=$arItem["NAME"];?></div>
	            <div class="it-data"><i class="engIcon setIcon-date-white"></i><?=$arItem["PROPERTIES"]["DATE"]["VALUE"][0]?></div>
	            <div class="it-map"><i class="engIcon setIcon-map-white"></i><?=$arItem["PROPERTIES"]["LOCATION"]["VALUE"]?></div>
	            <div class="it-money"><i class="engIcon setIcon-price-white"></i><?=$arItem["PROPERTIES"]["PRICE"]["VALUE"]?><?= !empty($arItem["PROPERTIES"]["PRICE_TO"]["VALUE"]) ? " - ".$arItem["PROPERTIES"]["PRICE_TO"]["VALUE"] : "" ?> руб.</div>
	            <div class="it-btn">
	                <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="cssBorderRadius">Купить билет</a>
	            </div>
	        </div>
	    </div>
	<?endif;?>
<?endforeach;?>
