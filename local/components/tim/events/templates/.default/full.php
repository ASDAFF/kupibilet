<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @var Local\Main\TimEvents $component */

$filter = $component->filter;
$products = $component->products['ITEMS'];

?>
<div id="catalog-wrap">

<div id="filters-panel">
	<input type="hidden" name="q" value="<?= $component->searchQuery ?>">
	<input type="hidden" name="catalog_path" value="<?= $filter['CATALOG_PATH'] ?>">
	<input type="hidden" name="separator" value="<?= $filter['SEPARATOR'] ?>"><?

	$closed = array();

	$i = 0;
	foreach ($filter['GROUPS'] as $group)
	{
		$style = $closed[$i] ? ' style="display:none;"' : '';
		$class = $closed[$i] ? ' closed' : '';
		?>
		<div class="filter-group<?= $class ?>">
			<h3><?= $group['NAME'] ?><s></s></h3><?

			if ($group['TYPE'] == 'price')
			{
				$from = $group['FROM'] ? $group['FROM'] : $group['MIN'];
				$to = $group['TO'] ? $group['TO'] : $group['MAX'];
				?>
				<div class="price-group"<?= $style ?> data-min="<?= $group['MIN'] ?>" data-max="<?= $group['MAX'] ?>">
					<div class="inputs">
						<div class="l">от <input type="text" class="from" value="<?= $from ?>"/></div>
						<div class="r">до <input type="text" class="to" value="<?= $to ?>" /></div>
					</div>
				</div><?
			}
			elseif ($group['TYPE'] == 'date')
			{
				$min = ConvertTimeStamp($group['MIN']);
				$max = ConvertTimeStamp($group['MAX']);
				$from = $group['FROM'] ? $group['FROM'] : $min;
				$to = $group['TO'] ? $group['TO'] : $max;
				?>
				<div class="date-group"<?= $style ?> data-min="<?= $min ?>" data-max="<?= $max ?>">
					<div class="inputs">
						<div class="l">от <input type="text" class="from" value="<?= $from ?>"/></div>
						<div class="r">до <input type="text" class="to" value="<?= $to ?>" /></div>
					</div>
				</div><?
			}
			else
			{
				?>
				<div<?= $style ?>>
					<ul><?

						foreach ($group['ITEMS'] as $code => $item)
						{
							$style = $item['ALL_CNT'] ? '' : ' style="display:none;"';
							$class = '';
							if (!$item['CNT'] && $item['CHECKED'])
								$class = ' class="checked disabled"';
							elseif ($item['CHECKED'])
								$class = ' class="checked"';
							elseif (!$item['CNT'])
								$class = ' class="disabled"';
							$checked = $item['CHECKED'] ? ' checked' : '';
							$disabled = $item['CNT'] ? '' : ' disabled';

							?>
							<li<?= $class ?><?= $style ?>>
								<b></b><label>
									<input type="checkbox" name="<?= $code ?>"<?= $checked ?><?= $disabled ?> />
									<?= $item['NAME'] ?> (<i><?= $item['CNT'] ?></i>)
								</label>
							</li><?
						}

						?>
					</ul>
				</div><?
			}
			?>
		</div><?

		$i++;
	}
	?>
</div><?

?>
<div id="catalog-list"><?

	//=========================================================
	include('products.php');
	//=========================================================

	?>
</div>

</div>

<?

foreach ($filter['BC'] as $i => $item)
	$APPLICATION->AddChainItem($item['NAME'], $item['HREF']);

if ($component->navParams['iNumPage'] > 1)
	$component->seo['TITLE'] .= ' - страница ' . $component->navParams['iNumPage'];

if ($component->seo['H1'])
	$APPLICATION->SetTitle($component->seo['H1']);
if ($component->seo['TITLE'])
	$APPLICATION->SetPageProperty('title', $component->seo['TITLE']);
if ($component->seo['DESCRIPTION'])
	$APPLICATION->SetPageProperty('description', $component->seo['DESCRIPTION']);
