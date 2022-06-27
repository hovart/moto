<?php
class Mail extends MailCore
{

	public static function Send($id_lang, $template, $subject, $template_vars, $to,
								$to_name = null, $from = null, $from_name = null, $file_attachment = null, $mode_smtp = null, $template_path = _PS_MAIL_DIR_, $die = false, $id_shop = null)
	{
		$configuration = Configuration::getMultiple(array(
			'PS_SHOP_EMAIL',
			'PS_MAIL_METHOD',
			'PS_MAIL_SERVER',
			'PS_MAIL_USER',
			'PS_MAIL_PASSWD',
			'PS_SHOP_NAME',
			'PS_MAIL_SMTP_ENCRYPTION',
			'PS_MAIL_SMTP_PORT',
			'PS_MAIL_METHOD',
			'PS_MAIL_TYPE'
		), null, null, $id_shop);

		// Returns immediatly if emails are deactivated
		if ($configuration['PS_MAIL_METHOD'] == 3)
			return true;

		$theme_path = _PS_THEME_DIR_;

		// Get the path of theme by id_shop if exist
		if (is_numeric($id_shop) && $id_shop)
		{
			$shop = new Shop((int)$id_shop);
			$theme_name = $shop->getTheme();

			if (_THEME_NAME_ != $theme_name)
				$theme_path = _PS_ROOT_DIR_.'/themes/'.$theme_name.'/';
		}

		if (!isset($configuration['PS_MAIL_SMTP_ENCRYPTION']))
			$configuration['PS_MAIL_SMTP_ENCRYPTION'] = 'off';
		if (!isset($configuration['PS_MAIL_SMTP_PORT']))
			$configuration['PS_MAIL_SMTP_PORT'] = 'default';

		// Sending an e-mail can be of vital importance for the merchant, when his password is lost for example, so we must not die but do our best to send the e-mail
		if (!isset($from) || !Validate::isEmail($from))
			$from = $configuration['PS_SHOP_EMAIL'];
		if (!Validate::isEmail($from))
			$from = null;

		// $from_name is not that important, no need to die if it is not valid
		if (!isset($from_name) || !Validate::isMailName($from_name))
			$from_name = $configuration['PS_SHOP_NAME'];
		if (!Validate::isMailName($from_name))
			$from_name = null;

		// It would be difficult to send an e-mail if the e-mail is not valid, so this time we can die if there is a problem
		if (!is_array($to) && !Validate::isEmail($to))
		{
			Tools::dieOrLog(Tools::displayError('Error: parameter "to" is corrupted'), $die);
			return false;
		}

		if (!is_array($template_vars))
			$template_vars = array();

		// Do not crash for this error, that may be a complicated customer name
		if (is_string($to_name) && !empty($to_name) && !Validate::isMailName($to_name))
			$to_name = null;

		if (!Validate::isTplName($template))
		{
			Tools::dieOrLog(Tools::displayError('Error: invalid e-mail template'), $die);
			return false;
		}

		if (!Validate::isMailSubject($subject))
		{
			Tools::dieOrLog(Tools::displayError('Error: invalid e-mail subject'), $die);
			return false;
		}

		/* Construct multiple recipients list if needed */
		if (is_array($to) && isset($to))
		{
			$to_list = new Swift_RecipientList();
			foreach ($to as $key => $addr)
			{
				$to_name = null;
				$addr = trim($addr);
				if (!Validate::isEmail($addr))
				{
					Tools::dieOrLog(Tools::displayError('Error: invalid e-mail address'), $die);
					return false;
				}
				if (is_array($to_name))
				{
					if ($to_name && is_array($to_name) && Validate::isGenericName($to_name[$key]))
						$to_name = $to_name[$key];
				}
				if ($to_name == null)
					$to_name = $addr;
				/* Encode accentuated chars */
				$to_list->addTo($addr, '=?UTF-8?B?'.base64_encode($to_name).'?=');
			}
			$to_plugin = $to[0];
			$to = $to_list;
		} else {
			/* Simple recipient, one address */
			$to_plugin = $to;
			if ($to_name == null)
				$to_name = $to;
			$to = new Swift_Address($to, '=?UTF-8?B?'.base64_encode($to_name).'?=');
		}
		try {
			/* Connect with the appropriate configuration */
			if ($configuration['PS_MAIL_METHOD'] == 2)
			{
				if (empty($configuration['PS_MAIL_SERVER']) || empty($configuration['PS_MAIL_SMTP_PORT']))
				{
					Tools::dieOrLog(Tools::displayError('Error: invalid SMTP server or SMTP port'), $die);
					return false;
				}
				$connection = new Swift_Connection_SMTP($configuration['PS_MAIL_SERVER'], $configuration['PS_MAIL_SMTP_PORT'],
					($configuration['PS_MAIL_SMTP_ENCRYPTION'] == 'ssl') ? Swift_Connection_SMTP::ENC_SSL :
						(($configuration['PS_MAIL_SMTP_ENCRYPTION'] == 'tls') ? Swift_Connection_SMTP::ENC_TLS : Swift_Connection_SMTP::ENC_OFF));
				$connection->setTimeout(4);
				if (!$connection)
					return false;
				if (!empty($configuration['PS_MAIL_USER']))
					$connection->setUsername($configuration['PS_MAIL_USER']);
				if (!empty($configuration['PS_MAIL_PASSWD']))
					$connection->setPassword($configuration['PS_MAIL_PASSWD']);
			}
			else
				$connection = new Swift_Connection_NativeMail();

			if (!$connection)
				return false;
			$swift = new Swift($connection, Configuration::get('PS_MAIL_DOMAIN', null, null, $id_shop));
			/* Get templates content */
			$iso = Language::getIsoById((int)$id_lang);
			if (!$iso)
			{
				Tools::dieOrLog(Tools::displayError('Error - No ISO code for email'), $die);
				return false;
			}
			$template = $iso.'/'.$template;

			$module_name = false;
			$override_mail = false;

			// get templatePath
			if (preg_match('#'.__PS_BASE_URI__.'modules/#', str_replace(DIRECTORY_SEPARATOR, '/', $template_path)) && preg_match('#modules/([a-z0-9_-]+)/#ui', str_replace(DIRECTORY_SEPARATOR, '/',$template_path), $res))
				$module_name = $res[1];

			if ($module_name !== false && (file_exists($theme_path.'modules/'.$module_name.'/mails/'.$template.'.txt') ||
					file_exists($theme_path.'modules/'.$module_name.'/mails/'.$template.'.html')))
				$template_path = $theme_path.'modules/'.$module_name.'/mails/';
			elseif (file_exists($theme_path.'mails/'.$template.'.txt') || file_exists($theme_path.'mails/'.$template.'.html'))
			{
				$template_path = $theme_path.'mails/';
				$override_mail  = true;
			}
			if (!file_exists($template_path.$template.'.txt') && ($configuration['PS_MAIL_TYPE'] == Mail::TYPE_BOTH || $configuration['PS_MAIL_TYPE'] == Mail::TYPE_TEXT))
			{
				Tools::dieOrLog(Tools::displayError('Error - The following e-mail template is missing:').' '.$template_path.$template.'.txt', $die);
				return false;
			}
			else if (!file_exists($template_path.$template.'.html') && ($configuration['PS_MAIL_TYPE'] == Mail::TYPE_BOTH || $configuration['PS_MAIL_TYPE'] == Mail::TYPE_HTML))
			{
				Tools::dieOrLog(Tools::displayError('Error - The following e-mail template is missing:').' '.$template_path.$template.'.html', $die);
				return false;
			}
			$template_html = file_get_contents($template_path.$template.'.html');
			$template_txt = strip_tags(html_entity_decode(file_get_contents($template_path.$template.'.txt'), null, 'utf-8'));

			if ($override_mail && file_exists($template_path.$iso.'/lang.php'))
				include_once($template_path.$iso.'/lang.php');
			else if ($module_name && file_exists($theme_path.'mails/'.$iso.'/lang.php'))
				include_once($theme_path.'mails/'.$iso.'/lang.php');
			else
				include_once(_PS_MAIL_DIR_.$iso.'/lang.php');

			/* Create mail and attach differents parts */
			$message = new Swift_Message('['.Configuration::get('PS_SHOP_NAME', null, null, $id_shop).'] '.$subject);

			/* Set Message-ID - getmypid() is blocked on some hosting */
			$message->setId(Mail::generateId());

			$message->headers->setEncoding('Q');

			$language = Language::getLanguage($id_lang);
			if(Configuration::get('PS_LOGO_MAIL_LINK_'.$language['language_code'].'-'.$id_shop) && Shop::IsFeatureActive() && file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO_MAIL_LINK_'.$language['language_code'].'-'.$id_shop)))
				$logo = _PS_IMG_DIR_.Configuration::get('PS_LOGO_MAIL_LINK_'.$language['language_code'].'-'.$id_shop);
			elseif(Configuration::get('PS_LOGO_MAIL_LINK_'.$language['language_code']) && Shop::IsFeatureActive() == false && file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO_MAIL_LINK_'.$language['language_code'])))
				$logo = _PS_IMG_DIR_.Configuration::get('PS_LOGO_MAIL_LINK_'.$language['language_code']);
			elseif (Configuration::get('PS_LOGO_MAIL') !== false && file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO_MAIL', null, null, $id_shop)))
				$logo = _PS_IMG_DIR_.Configuration::get('PS_LOGO_MAIL', null, null, $id_shop);
			else
			{
				if (file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO', null, null, $id_shop)))
					$logo = _PS_IMG_DIR_.Configuration::get('PS_LOGO', null, null, $id_shop);
				else
					$template_vars['{shop_logo}'] = '';
			}

			/* don't attach the logo as */
			if (isset($logo))
				$template_vars['{shop_logo}'] = $message->attach(new Swift_Message_EmbeddedFile(new Swift_File($logo), null, ImageManager::getMimeTypeByExtension($logo)));

			$template_vars['{shop_name}'] = Tools::safeOutput(Configuration::get('PS_SHOP_NAME', null, null, $id_shop));
			$template_vars['{shop_url}'] = Context::getContext()->link->getPageLink('index', true, Context::getContext()->language->id);
			$template_vars['{my_account_url}'] = Context::getContext()->link->getPageLink('my-account', true, Context::getContext()->language->id);
			$template_vars['{guest_tracking_url}'] = Context::getContext()->link->getPageLink('guest-tracking', true, Context::getContext()->language->id);
			$template_vars['{history_url}'] = Context::getContext()->link->getPageLink('history', true, Context::getContext()->language->id);
			$template_vars['{color}'] = Tools::safeOutput(Configuration::get('PS_MAIL_COLOR', null, null, $id_shop));
			$swift->attachPlugin(new Swift_Plugin_Decorator(array($to_plugin => $template_vars)), 'decorator');
			if ($configuration['PS_MAIL_TYPE'] == Mail::TYPE_BOTH || $configuration['PS_MAIL_TYPE'] == Mail::TYPE_TEXT)
				$message->attach(new Swift_Message_Part($template_txt, 'text/plain', '8bit', 'utf-8'));
			if ($configuration['PS_MAIL_TYPE'] == Mail::TYPE_BOTH || $configuration['PS_MAIL_TYPE'] == Mail::TYPE_HTML)
				$message->attach(new Swift_Message_Part($template_html, 'text/html', '8bit', 'utf-8'));
			if ($file_attachment && !empty($file_attachment))
			{
				// Multiple attachments?
				if (!is_array(current($file_attachment)))
					$file_attachment = array($file_attachment);

				foreach ($file_attachment as $attachment)
					if (isset($attachment['content']) && isset($attachment['name']) && isset($attachment['mime']))
						$message->attach(new Swift_Message_Attachment($attachment['content'], $attachment['name'], $attachment['mime']));
			}
			/* Send mail */
			$send = $swift->send($message, $to, new Swift_Address($from, $from_name));
			$swift->disconnect();
			return $send;
		}
		catch (Swift_Exception $e) {
			return false;
		}
	}
}
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 07/01/14
 * Time: 17:04
 */ 