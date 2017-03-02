<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arResult */

ob_start();

$l = count($arResult);
if ($l > 1)
{
	$l--;
	?>
	<div id="bc"><?
		foreach ($arResult as $i => $item)
		{
			if ($i < $l)
			{
				?>
				<a href="<?= $item['LINK'] ?>"><?= $item['TITLE'] ?></a> <span class="divider">/</span><?
			}
			else
			{
				?>
				<span><?= $item['TITLE'] ?></span><?
			}
		}

	?>
	</div><?
}

$strReturn = ob_get_contents();
ob_end_clean();

return $strReturn;
