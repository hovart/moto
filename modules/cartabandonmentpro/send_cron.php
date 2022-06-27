<?php
include_once('../../config/config.inc.php');

	require_once dirname(__FILE__).'/controllers/ReminderController.class.php';
	require_once dirname(__FILE__).'/controllers/TemplateController.class.php';
	require_once dirname(__FILE__).'/classes/Template.class.php';
	$id_shop = Tools::getValue('id_shop');
	$wich_remind = Tools::getValue('wich_remind');

	if(!$id_shop){
		echo 'No shop ...';die;
	}
	if(!$wich_remind){
		echo 'No remind number ...';die;
	}
	$carts = ReminderController::getAbandonedCart($wich_remind, $id_shop);
	$templates = TemplateController::getActiveTemplate($id_shop);
	if(!$templates)
		die('No active template ...');
	$x = 0;
	$sent = array();
	$first = true;
	$mails = '';
	if(!isset(Context::getContext()->link))
		Context::getContext()->link = new Link();
	foreach($carts as $cart){

		$iso = Language::getIsoById($cart['id_lang']);
		$id_lang = $cart['id_lang'];

		if (!isset($templates[$cart['id_shop']][$cart['id_lang']][$wich_remind]))
		{
			$id_lang = Configuration::get('PS_LANG_DEFAULT');
			$iso = Language::getIsoById($id_lang);
		}

		$content = Tools::file_get_contents('mails/' . $iso . '/' . $templates[$cart['id_shop']][$id_lang][$wich_remind]['id'] . '.html');
		$content = Template::editBeforeSending($content, $cart['id_cart'], $id_lang, $wich_remind);

		if(!$content) continue;

		$title	 = Template::editTitleBeforeSending($templates[$cart['id_shop']][$id_lang][$wich_remind]['name'], $cart['id_cart'], $id_lang);

		$fp = fopen('mails/' . $iso . '/send.html', 'w+');
		fwrite($fp, $content);
		fclose($fp);
		$fp = fopen('mails/' . $iso . '/send.txt', 'w+');
		fwrite($fp, $content);
		fclose($fp);

		$mail = Mail::Send($id_lang, 'send', $title, array(), $cart['email'], null, null, null, null, null, dirname(__FILE__) . '/mails/');

		if($mail){
			if(!$first)
				$mails .= ';';
			$mails .= $cart['email'];
			$first = false;
			$justSent = array('id_customer'=> $cart['id_customer'], 'id_cart'=> $cart['id_cart'], 'firstname' => $cart['firstname'], 'lastname' => $cart['lastname'], 'email' => $cart['email']);
			$sent[] = $justSent;
			Db::getInstance()->Execute("INSERT INTO "._DB_PREFIX_."cartabandonment_remind VALUES (NULL, " . $wich_remind . ", " . $cart['id_cart'] . ", NOW(), 0, 0, 0)");
			$x++;
		}
	}
	unset($justSent, $carts, $content, $title, $templates);
	$str = '<LINK rel=stylesheet type="text/css" href="views/css/bootstrap.min.css">';
	$str .= '<div class="container"><h3>'.$x.' mails have been sent.</h3><br><br>';
	$str .= '<table class="table table-striped"><tr><th>ID CUSTOMER</th><th>ID CART</th><th>FIRSTNAME</th><th>LASTNAME</th><th>EMAIL</th></tr>';
	foreach ($sent as $s)
	{
		$str .= '<tr><td>'.$s['id_customer'].'</td><td>'.$s['id_cart'].'</td><td>'.$s['firstname'].'</td><td>'.$s['lastname'].'</td><td><a href="mailto:'.$s['email'].'">'.$s['email'].'</a></td></tr>';
	}
	$str .= '</table>
			<h4><a href="mailto:'.$mails.'">Send an email to these customers</a></h4>
			</div>';
	echo $str;