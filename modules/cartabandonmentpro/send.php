<?php
include_once('../../config/config.inc.php');
$dir = str_replace('\\', '/', dirname(__FILE__));

if (is_dir($dir.'/../../themes/'._THEME_NAME_.'/modules/cartabandonmentpro'))
{
    // rmdir($dir.'/../../themes/'._THEME_NAME_.'/modules/cartabandonmentpro');
	deleteDirectory($dir.'/../../themes/'._THEME_NAME_.'/modules/cartabandonmentpro');
}
$id_shop = Tools::getValue('id_shop');
if(!$id_shop){
	$id_shop = Tools::getValue('amp;id_shop');
	if(!$id_shop)
		$id_shop = $argv[1];
	if(!$id_shop)
	{
		echo 'No shop ...';die;
	}
}

$token = Tools::getValue('token');
$token_bdd = Configuration::get('CARTABAND_TOKEN', null, null, $id_shop);

if(!$token)
	$token = Tools::getValue('amp;token');

if(!$token)
	$token = $argv[2];

if(strlen($token_bdd) > 0 && isset($token_bdd) && isset($token) && $token_bdd == $token)
{
	require_once dirname(__FILE__).'/controllers/ReminderController.class.php';
	require_once dirname(__FILE__).'/controllers/TemplateController.class.php';
	require_once dirname(__FILE__).'/classes/Template.class.php';
	include_once('controllers/DiscountsController.class.php');
	$wich_remind = Tools::getValue('wich_remind');

	if(!$wich_remind){
		$wich_remind = Tools::getValue('amp;wich_remind');
		if(!$wich_remind)
			$wich_remind = $argv[3];
		if(!$wich_remind)
		{
			echo 'No remind number ...';die;
		}
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
		if(!isset(Context::getContext()->cart))
			Context::getContext()->cart = new Cart($cart);
		$iso = Language::getIsoById($cart['id_lang']);
		$id_lang = $cart['id_lang'];

		if (!isset($templates[$cart['id_shop']][$cart['id_lang']][$wich_remind]))
		{
			$id_lang = Configuration::get('PS_LANG_DEFAULT');
			$iso = Language::getIsoById($id_lang);
		}

		$content = Tools::file_get_contents('mails/' . $iso . '/' . $templates[$cart['id_shop']][$id_lang][$wich_remind]['id'] . '.html');
		$content = Template::editBeforeSending($content, $cart['id_cart'], $id_lang, $wich_remind, $id_shop);

		if(!$content) continue;

		$discounts = DiscountsController::getDiscounts($wich_remind);
		
		$cart2 = new Cart($cart['id_cart']);
		if(!isset($context->currency->id))
			$context->currency = new Currency($cart2->id_currency, null, $id_shop);

		$id_address = Address::getFirstCustomerAddressId($cart['id_customer']);
		if($cart2->id_address_delivery != $id_address) {
			$cart2->id_address_delivery = $id_address;
			$cart2->id_address_invoice = $id_address;
			$cart2->save();
		}

		$total_cart = $cart2->getOrderTotal();
		$i = 0;
		$disc = false;
		$disc_valid = false;
		$type = false;
		$min = false;
		$max = false;
		$value = false;

		if(is_array($discounts) && count($discounts) > 0)
		{
			foreach($discounts as $discount)
			{
				if($total_cart >= $discount['min_amount'])
				{
					$disc = $i;
					$disc_valid = $discount['valid_value'];
					$type = $discount['type'];
					$min = $discount['min_amount'];
					$max = $discount['max_amount'];
					$value = $discount['value'];
				}
				$i++;
			}

			if($value > 0 || $type == 'shipping')
			{
				$voucher = DiscountsController::createDiscount($cart['id_customer'], $value, $cart['id_cart'], $disc_valid, $type, $min, $max);
				$content = Template::editDiscount($voucher, $content, $id_lang);
			}
			else
				$content = str_replace('%DISCOUNT_TXT%', "", $content);
		}
		else
			$content = str_replace('%DISCOUNT_TXT%', "", $content);

		$title	 = Template::editTitleBeforeSending($templates[$cart['id_shop']][$id_lang][$wich_remind]['name'], $cart['id_cart'], $id_lang);

		$fp = fopen('mails/' . $iso . '/send.html', 'w+');
		fwrite($fp, $content);
		fclose($fp);
		$fp = fopen('mails/' . $iso . '/send.txt', 'w+');
		fwrite($fp, $content);
		fclose($fp);

		$mail = Mail::Send($id_lang, 'send', $title, array(), $cart['email'], null, null, null, null, null, dirname(__FILE__) . '/mails/');

                unlink('mails/' . $iso . '/send.html');
                unlink('mails/' . $iso . '/send.txt');
                
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
}
else{
	echo 'hack ...';die;
}


function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}