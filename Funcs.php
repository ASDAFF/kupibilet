<?
class Funcs{
	public static $uri=array();
	public static $city='';
	function __construct(){
		if(strpos($_SERVER['REQUEST_URI'],'?')!==false)	$uri=substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'?'));
		else $uri=$_SERVER['REQUEST_URI'];
		self::$uri=explode('/',substr(str_replace('.html','',$uri),1,strlen($uri)));
		if(self::$uri[count(self::$uri)-1]=='')unset(self::$uri[count(self::$uri)-1]);
		if($_COOKIE['city']){
			$_SESSION['city']=json_decode($_COOKIE['city']);
		}
		if($_SERVER['REMOTE_ADDR']=='80.76.231.122'){

		}

		if(!$_SESSION['city']){

			//$_SERVER['REMOTE_ADDR']='185.5.17.18';
			//$_SERVER['REMOTE_ADDR']='94.31.134.61';

			$is_bot = preg_match(
					"~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl)~i",
					$_SERVER['HTTP_USER_AGENT']
			);
			$geo = !$is_bot ? json_decode(file_get_contents('http://api.sypexgeo.net/json/'.$_SERVER['REMOTE_ADDR']), true) : [];
			//print '<pre>';print_r($geo);print '</pre>';
			//$geoData=file_get_contents('http://geo.one-touch.ru?ip='.$_SERVER['REMOTE_ADDR']);
			//$city=substr($geoData,5,strpos($geoData,' -->')-5);
			if(trim($geo['city']['name_ru'])!='') Funcs::$city=$geo['city']['name_ru'];
			else Funcs::$city='Москва';
		}
		//unset($_SESSION['city']);
	}
	function newsDate($date){
		$monthsRus=array('января',"февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
		$data=date('d',strtotime($date)).' '.$monthsRus[date('n',strtotime($date))-1].' '.date('Y',strtotime($date));
		if(date('d.m.Y',strtotime($date))==date('d.m.Y')){
			$data='Сегодня';
		}elseif(date('d.m.Y',strtotime($date))==date('d.m.Y', strtotime('-1 day'))){
			$data='Вчера';
		}elseif(date('d.m.Y',strtotime($date))==date('d.m.Y', strtotime('+1 day'))){
			$data='Завтра';
		}/*elseif(date('Y',strtotime($date))==date('Y')){
			$data=date('d',strtotime($date)).' '.$monthsRus[date('n',strtotime($date))-1];
		}*/
		return $data;
	}
	public static function chti($string, $ch1, $ch2, $ch3){
		if(!is_numeric($string))$string='0';
		$ff=Array('0','1','2','3','4','5','6','7','8','9');
		if(substr($string,-2, 1)==1 AND strlen($string)>1) $ry=array("0 $ch3","1 $ch3","2 $ch3","3 $ch3" ,"4 $ch3","5 $ch3","6 $ch3","7 $ch3","8 $ch3","9 $ch3");
		else $ry=array("0 $ch3","1 $ch1","2 $ch2","3 $ch2","4 $ch2","5 $ch3","6 $ch3","7 $ch3","8 $ch3","9 $ch3");
		$string1=substr($string,0,-1).str_replace($ff, $ry, substr($string,-1,1));
		return $string1;
	}
	public static function redirect($uri){
		if(strpos($uri,'http://')!==false){
			header('Location: '.$uri);
		}else{
			header('Location: http://'.$_SERVER['HTTP_HOST'].$uri);
		}
		die;
	}
	public static function getFG($free,$amp='',$first=false){
		$data=array();
		foreach($_GET as $key=>$item){
			if(!is_array($free) && $key!=$free){
				if(is_array($item)){
					foreach($item as $arr){
						$data[]=$key.'[]='.$arr;
					}
				}else{
					$data[]=$key.'='.$item;
				}
			}
			if(is_array($free) && !in_array($key,$free)){
				if(is_array($item)){
					foreach($item as $arr){
						$data[]=$key.'[]='.$arr;
					}
				}else{
					$data[]=$key.'='.$item;
				}
			}
		}
		$text=implode('&',$data);
		if($text!=''){
			if($first==true){
				return $amp.$text.'&';
			}else{
				return $amp.$text;
			}
		}elseif($amp=='?' && $first==false){
			return '/'.implode('/',Funcs::$uri).'/';
		}else{
			if($first==true){
				return '?';
			}else{
				return '';
			}
		}
	}
	public static function generate_password($number){
		$arr = array('a','b','c','d','e','f',
				'g','h','i','j','k','l',
				'm','n','o','p','r','s',
				't','u','v','x','y','z',
				'A','B','C','D','E','F',
				'G','H','I','J','K','L',
				'M','N','O','P','R','S',
				'T','U','V','X','Y','Z',
				'1','2','3','4','5','6',
				'7','8','9','0');
		$pass = "";
		for($i = 0; $i < $number; $i++){
			$index = rand(0, count($arr) - 1);
			$pass .= $arr[$index];
		}
		return $pass;
	}
}
new Funcs;
?>
