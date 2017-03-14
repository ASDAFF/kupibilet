<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$banners = \Local\Main\Banner::getBySectionCode('right');

foreach ($banners as $item) {
	?>
	<div class="elBanner"><?

		if ($item['HREF'])
		{
			?>
			<a href="<?= $item['HREF'] ?>"><img src="<?= $item['PREVIEW_PICTURE'] ?>"/></a><?
		}
		else
		{
			?>
			<img src="<?= $item['PREVIEW_PICTURE'] ?>"/><?
		}
        ?>
    </div><?
}
