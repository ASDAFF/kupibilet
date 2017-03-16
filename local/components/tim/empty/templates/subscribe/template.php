<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$res = \Local\Sale\Subscribe::isSubscribed();
if ($res)
	return;

$user = \Local\System\User::getCurrentUser();
$email = '';
if ($user['EMAIL'])
	$email = $user['EMAIL'];

?>
<div class="elRight-email">
    <div class="it-inf">
        <div class="it-title">Будьте в курсе предстоящих мероприятий</div>
    </div>
    <div class="it-form">
        <input class="it-form-input cssBorderRadius-left" type="text" name="email" id="subscribe-email"
               placeholder="Введите ваш e-mail" autocomplete="off" value="<?= $email ?>" />
        <button id="subscribe-btn" class="it-form-btn cssBorderRadius-right engBtn">ОК</button>
    </div>
</div>