<?
if (!function_exists('DebugMessage')) {
	function DebugMessage($message, $backtrace = false, $title = false, $color = '#008B8B') {
	?><table border="0" cellpadding="5" cellspacing="0" style="border:1px solid <?=$color?>;margin:2px;"><tr><td style="color:<?=$color?>;font-size:11px;font-family:Verdana;"><?
				if(strlen($title)) {
					?><p>[<?=$title?>]</p><?
				}
				if (is_array($message) || is_object($message)) {
					echo '<pre>'; print_r($message); echo '</pre>';
				}
				else {
					var_dump($message);
				}
				$bt = array();
				if($backtrace && function_exists('debug_backtrace'))
				{
					$arBacktrace = debug_backtrace();
					$iterationsCount = min(count($arBacktrace), 18);
					for ($i = 1; $i < $iterationsCount; $i++)
					{
						$s = $arBacktrace[$i]['function'];
						if (strlen($arBacktrace[$i]['class']))
							$s = $arBacktrace[$i]['class'] . '::' . $s;
						$bt[] = $s;
					}
					echo '<pre>';
					print_r($bt);
					echo '</pre>';
				}
				?></td></tr></table><?
	}
}

if(!function_exists('log_array')) {
	function log_array($arFields = array(), $sFileName = 'log.txt', $bBacktrace = true) {
		if($GLOBALS['USER']->isAdmin()) {
			_log_array($arFields, $sFileName, $bBacktrace);
		}
	}
}

if(!function_exists('_log_array')) {
	function _log_array($arFields = array(), $sFileName = 'log.txt', $bBacktrace = true) {
		$sMess = '';
		$sMess .= date('d.m.Y H:i:s')."\n";
		$sMess .= print_r($arFields, true)."\n";
		if($bBacktrace && function_exists('debug_backtrace')) {
			$arBacktrace = debug_backtrace();
			$iterationsCount = min(count($arBacktrace), 18);
			for($i = 1; $i < $iterationsCount; $i++) {
				if(strlen($arBacktrace[$i]['class'])) {
					$sMess .= $arBacktrace[$i]['class'].'::';
				}
				$sMess .= $arBacktrace[$i]['function']." >> ";
			}
		}
		$sMess .= "\n----------\n\n";
		$sFileName = empty($sFileName) ? 'log.txt' : $sFileName;
		$sFile = $_SERVER['DOCUMENT_ROOT'].'/_log/'.$sFileName;
		file_put_contents($sFile, $sMess, FILE_APPEND);
	}
}
