<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

/** @global CMain $APPLICATION */
/** @var Local\Main\TimHalls $component */

$assets = \Bitrix\Main\Page\Asset::getInstance();
$assets->addJs('/js/run.js');

$hall = $component->hall;
$event = $component->event;
$run = $component->run;

$day = FormatDate('j', $run['TS']);
$mdw = FormatDate('F, l', $run['TS']);
$time = FormatDate('H:i', $run['TS']);

$Quotas = json_decode($run['QUOTAS'], true);
$Zal = json_decode($hall['SCHEME'], true);
foreach ($Quotas as $j => $item)
{
	foreach ($item[2] as $i)
		$Zal[$i][6] = $j;
}

$max = 0;
foreach ($Zal as $itemId => $item)
{
	if ($max < $item[1])
		$max = $item[1];
}
$max += 10;

$cartSummary = \Local\Sale\Cart::getSummary();
$sits = \Local\Sale\Cart::getSitsByRun($run['ID']);
$reserved = \Local\Sale\Reserve::getByRun($run['ID']);

?>
    <div class="engBox">
		<div class="elZal-top">

			<div class="engRow">

				<div class="engBox-8 engPl-12  engMb-12">
					<div class="it-title"><?= $event['NAME'] ?></div>
					<div class="it-inf">
						<div class="it-date engBox-2 engPl-2 engMb-12">
							<b class="engBox-4 engMb-2"><?= $day ?></b>
							<span  class="engBox-4 engMb-9"><?= $mdw ?></span>
						</div>
						<div class="it-time engBox-6 engPl-6 engMb-12">
							<b  class="engBox-4  engMb-4"><?= $time ?></b>
							<span  class="engBox-7 engMb-6"><?= $hall['NAME'] ?></span>
						</div>
						<div class=" engBox-3 engPl-3 engMb-12 engMb-cssText-center">
                            <a href="/personal/cart/" class="it-cart-btn">Оформить заказ</a>
						</div>
					</div>
				</div>

				<div class="engBox-4 engPl-12  engMb-12">
					<div class="it-price">
						<ul class="it-price-top"><?

							foreach ($Quotas as $item)
							{
								?>
								<li>
									<i class="engIconColor" style="background:<?= $item[1] ?>"></i>
									<span> — <?= $item[0] ?> руб.</span>
								</li><?
							}
							?>
						</ul>
						<ul class="it-price-botton">
							<li>
								<i class="engIconColor set-ser"></i><span>Забронированные места</span>
							</li>
							<li>
								<i class="engIconColor set-ser-10"></i><span>Выкупленные места</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div><?

    ?>
    <div class="engBox">
        <div class="elZalScen">
            <div class="it-title"><span>Сцена</span></div><?

            if ($hall['ZONE'])
                echo $hall['ZONE'];

            ?>
        </div>
    </div>
	<div class="engBox">
        <div class="elZalZoom">
                <span id="elZalBtnPlus" class="cssBorderRadius set-100">
                    <svg class="engSvg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                        <path d="M 16 3 C 8.8321429 3 3 8.8321429 3 16 C 3 23.167857 8.8321429 29 16 29 C 23.167857 29 29 23.167857 29 16 C 29 8.8321429 23.167857 3 16 3 z M 16 5 C 22.086977 5 27 9.9130231 27 16 C 27 22.086977 22.086977 27 16 27 C 9.9130231 27 5 22.086977 5 16 C 5 9.9130231 9.9130231 5 16 5 z M 15 10 L 15 15 L 10 15 L 10 17 L 15 17 L 15 22 L 17 22 L 17 17 L 22 17 L 22 15 L 17 15 L 17 10 L 15 10 z"></path>
                    </svg>
                </span>
            <span id="elZalBtnMinus" class="cssBorderRadius set-100">
                    <svg class="engSvg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                        <path d="M 16 3 C 8.8321429 3 3 8.8321429 3 16 C 3 23.167857 8.8321429 29 16 29 C 23.167857 29 29 23.167857 29 16 C 29 8.8321429 23.167857 3 16 3 z M 16 5 C 22.086977 5 27 9.9130231 27 16 C 27 22.086977 22.086977 27 16 27 C 9.9130231 27 5 22.086977 5 16 C 5 9.9130231 9.9130231 5 16 5 z M 10 15 L 10 17 L 22 17 L 22 15 L 10 15 z"></path>
                    </svg>
                </span>
        </div>
		<div class="elZal" id="elZal" data-event="<?= $event['ID'] ?>" data-run="<?= $run['ID'] ?>">
			<div class="elZal-box" id="elZal-box" style="height:<?= $max ?>px;max-height:<?= $max ?>px;"><?

				foreach ($Zal as $itemId => $item)
				{
					$pointClass = '';
					$style = 'left:' . $item[0] . 'px;top:' . $item[1] . 'px;';
					$pointStyle = '';
					if ($item[2])
					{
						$rotate = 'rotate(' . $item[2] . 'deg)';
						$pointStyle .= '-moz-transform:' . $rotate . ';' .
							'-ms-transform:' . $rotate . ';' .
							'-webkit-transform:' . $rotate . ';' .
							'-o-transform:' . $rotate . ';' .
							'transform:' . $rotate . ';';
					}
					if (!$item[6])
					{
						$pointStyle .= 'background-color:#d7d7d7;';
						$pointClass .= ' off';
					}
					elseif ($reserved[$itemId] && !$sits[$itemId])
					{
					    if ($reserved[$itemId] == 'Y')
                        {
							$pointStyle .= 'background-color:#d7d7d7;';
							$pointClass .= ' off';
                        }
                        else
						{
							$pointStyle .= '';
							$pointClass .= ' ser';
						}
					}
					else
					{
						$qItem = $Quotas[$item[6]];
						$pointStyle .= 'background-color:' . $qItem[1] . ';';
						$pointClass .= ' on';
						if ($sits[$itemId])
							$pointClass .= ' order';
					}

					?>
					<div class="elZal-item" id="<?= $itemId ?>" style="<?= $style ?>">
                        <div class="elZal-point<?= $pointClass ?>" style="<?= $pointStyle ?>"><?= $item[5] ?></div>
					</div><?
				}

				?>
			</div>
		</div>
	</div>

	<div class="elZal-inf" style="display:none;">
		<div class="engRow">
			<div class="engBox-12 cssText-center elZal-inf-title"></div>
		</div>
		<div class="engRow">
			<div class="engBox-6">Ряд</div>
			<div class="engBox-6 cssText-right elZal-inf-set"></div>
		</div>
		<div class="engRow">
			<div class="engBox-6">Место</div>
			<div class="engBox-6 cssText-right elZal-inf-number"></div>
		</div>
		<div class="engRow priceRow">
			<div class="engBox-12 cssText-center">Стоимость</div>
			<div class="engBox-12 cssText-center elZal-inf-money"></div>
		</div>
        <div class="engRow btnRow"><?

            /*
            ?>
			<div class="engBox-12 cssText-center">
				<a class="engBtn elZal-inf-btn">Купить</a>
            </div><?*/

            ?>
		</div>
	</div>
	<?

foreach ($Quotas as $j => $item)
{
	foreach ($item[2] as $i)
		$Zal[$i][6] = $item[0];
}

	?>
	<script>
		var ZalArray = <?= json_encode($Zal, JSON_UNESCAPED_UNICODE) ?>;
	</script>
<?

$title = $run['DATE_S'] . ' - ' . $event['NAME'];
$APPLICATION->SetTitle($title);
$APPLICATION->SetPageProperty('title', $title);