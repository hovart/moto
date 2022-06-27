<?php
/**
 * All-in-one Rewards Module
 *
 * @category  Prestashop
 * @category  Module
 * @author    Yann BONNAILLIE - ByWEB
 * @copyright 2012-2014 Yann BONNAILLIE - ByWEB (http://www.prestaplugins.com)
 * @license   Commercial license see license.txt
 * Support by mail  : contact@prestaplugins.com
 * Support on forum : Patanock
 * Support on Skype : Patanock13
 */

class Allinone_rewardsSponsorshipModuleFrontController extends ModuleFrontController
{
	public $content_only = false;
	public $display_header = true;
	public $display_footer = true;

	public function init()
	{
		if (!Tools::getValue('checksponsor')) {
			$id_template = (int)MyConf::getIdTemplate('sponsorship', $this->context->customer->id);
			if (!MyConf::get('RSPONSORSHIP_ACTIVE', null, $id_template))
				die('This functionality is not available');

			if (!$this->context->customer->isLogged())
				Tools::redirect('index.php?controller=authentication');
			elseif (!RewardsSponsorshipModel::isCustomerAllowed($this->context->customer))
				Tools::redirect('index');
		}

		if (Tools::getValue('popup') || Tools::getValue('provider')) {
			// allow to not add the javascript at the end causing JS issue (presta 1.6)
			$this->controller_type = 'modulefront';
			$this->content_only = true;
			$this->display_header = false;
			$this->display_footer = false;
		}

		parent::init();
	}

	public function setMedia()
	{
		parent::setMedia();
		if (!Tools::getValue('checksponsor')) {
			$this->addJqueryPlugin(array('idTabs'));
		}
	}


	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();

		$id_template = (int)MyConf::getIdTemplate('sponsorship', $this->context->customer->id);
		$popup = Tools::getValue('popup');

		if (Tools::getValue('checksponsor')) {
			$sponsorship = trim(Tools::getValue('sponsorship'));
			$customer_email = trim(Tools::getValue('customer_email'));
			$sponsor = new Customer(RewardsSponsorshipModel::decodeSponsorshipLink($sponsorship));
			if (Validate::isLoadedObject($sponsor) && RewardsSponsorshipModel::isCustomerAllowed($sponsor) && $sponsor->email != $customer_email) {
				die('{"result":"1"}');
			} else {
				$sponsor = new Customer();
				if (Validate::isEmail($sponsorship)) {
					$sponsor=$sponsor->getByEmail($sponsorship);
					if (Validate::isLoadedObject($sponsor) && RewardsSponsorshipModel::isCustomerAllowed($sponsor) && $sponsor->email != $customer_email){
						die('{"result":"1"}');
					}
				}
			}
			die('{"result":"0"}');
		} else {
			// open inviter
			if (Configuration::get('RSPONSORSHIP_OPEN_INVITER')) {
				require_once(dirname(__FILE__).'/../../libraries/OpenInviter/openinviter.php');
				$openInviter=new OpenInviter();
				$openInviterServices=$openInviter->getPlugins();
			}

			// si on submit le formulaire openinviter en ajax pour lister les contacts
			if (Tools::getValue('provider')) {
				$provider = Tools::getValue('provider');
				$login = Tools::getValue('login');
				$password = Tools::getValue('password');
				if (empty($login))
					$error = 'email is missing';
				else if (empty($password))
					$error = 'password is missing';
				if (!isset($error)) {
					$openInviter->startPlugin($provider);
					$internal = $openInviter->getInternalError();
					if ($internal) {
						$error = $internal;
					} else if (!$openInviter->login($login, $password)) {
						$internal = $openInviter->getInternalError();
						$error = ($internal ? $internal : "login failed");
					} else if (false === ($contacts = $openInviter->getMyContacts())) {
						$error = "unable to get contacts";
					}
				}
				// Smarty display
				$this->context->smarty->assign(array(
					'popup' => $popup,
					'error' => isset($error) ? $error : false,
					'getcontact' => true,
					'open_inviter' => $openInviter,
					'open_inviter_contacts' => isset($contacts) ? $contacts : array()
				));
			} else {
				$error = false;

				// get discount value for sponsored (ready to display)
				$discount_gc = $this->module->getDiscountReadyForDisplay((int)MyConf::get('RSPONSORSHIP_DISCOUNT_TYPE_GC', null, $id_template), (int)MyConf::get('RSPONSORSHIP_FREESHIPPING_GC', null, $id_template), (float)MyConf::get('RSPONSORSHIP_VOUCHER_VALUE_GC_'.(int)$this->context->currency->id, null, $id_template));

				// get reward value for sponsor (ready to display)
				$params_s = explode(',', MyConf::get('RSPONSORSHIP_REWARD_TYPE_S', null, $id_template));
				$type_reward_s = (int)$params_s['0'];
				$params_s = explode(',', ($type_reward_s == 2) ? MyConf::get('RSPONSORSHIP_REWARD_VALUE_S_'.(int)$this->context->currency->id, null, $id_template) : MyConf::get('RSPONSORSHIP_REWARD_PERCENTAGE', null, $id_template));
				$reward_allowed_s = (int)MyConf::get('RSPONSORSHIP_REWARD_S', null, $id_template);

				$template = 'sponsorship-invitation-novoucher';
				if ((int)MyConf::get('RSPONSORSHIP_DISCOUNT_GC', null, $id_template) == 1) {
					if ((int)MyConf::get('RSPONSORSHIP_DISCOUNT_TYPE_GC', null, $id_template) != 0)
						$template = 'sponsorship-invitation';
					else
						$template = 'sponsorship-invitation-freeshipping';
				}

				$activeTab = 'sponsor';

				// Mailing invitation to friend sponsor
				$invitation_sent = false;
				$sms_sent = false;
				if (version_compare(_PS_VERSION_, '1.5', '<')) {
					$hook = new Hook();
					$hook_sms = $hook->get('sendsms2Sponsorship');
				} else
					$hook_sms = Hook::getIdByName('sendsms2Sponsorship');
				$sms_active = Module::isEnabled('sendsms2') && Configuration::get('SENDSMS2_ISACTIVE_'.$hook_sms);

				$nbInvitation = 0;
				$code = RewardsSponsorshipModel::getSponsorshipLink();

				if (Tools::getValue('friendsEmail') && sizeof($friendsEmail = Tools::getValue('friendsEmail')) >= 1)
				{
					$activeTab = 'sponsor';

					// on ne garde que ceux qui sont cochés
					if (!is_array(Tools::getValue('friendsLastName'))) {
						$friendsLastName = array();
						$friendsFirstName = array();
					} else {
						$friendsLastName = Tools::getValue('friendsLastName');
						$friendsFirstName = Tools::getValue('friendsFirstName');
					}
					$mails_exists = array();

					// 1ere boucle pour contrôle des erreurs
					foreach ($friendsEmail as $key => $friendEmail)
					{
						$friendEmail = $friendEmail;
						$friendLastName = isset($friendsLastName[$key]) ? $friendsLastName[$key] : '';
						$friendFirstName = isset($friendsFirstName[$key]) ? $friendsFirstName[$key] : '';

						if (empty($friendEmail) && empty($friendLastName) && empty($friendFirstName))
							continue;
						elseif (empty($friendEmail) || !Validate::isEmail($friendEmail))
							$error = 'email invalid';
						elseif (Tools::isSubmit('submitSponsorFriends') && (empty($friendFirstName) || empty($friendLastName) || !Validate::isName($friendLastName) || !Validate::isName($friendFirstName)))
							$error = 'name invalid';
						if ($error)
							break;
					}

					if (!$error) {
						// 2ème boucle pour envoie des invitations
						foreach ($friendsEmail as $key => $friendEmail)
						{
							$friendEmail = $friendEmail;
							$friendLastName = isset($friendsLastName[$key]) ? $friendsLastName[$key] : '';
							$friendFirstName = isset($friendsFirstName[$key]) ? $friendsFirstName[$key] : '';

							if (empty($friendEmail) && empty($friendLastName) && empty($friendFirstName))
								continue;

							if (RewardsSponsorshipModel::isEmailExists($friendEmail) || Customer::customerExists($friendEmail))	{
								$error = 'email exists';
								$mails_exists[] = $friendEmail;
								continue;
							}

							$sponsorship = new RewardsSponsorshipModel();
							$sponsorship->id_sponsor = (int)$this->context->customer->id;
							$sponsorship->firstname = $friendFirstName;
							$sponsorship->lastname = $friendLastName;
							$sponsorship->channel = 1;
							$sponsorship->email = $friendEmail;
							if ($sponsorship->save()) {
								$vars = array(
									'{email}' => $this->context->customer->email,
									'{lastname}' => $this->context->customer->lastname,
									'{firstname}' => $this->context->customer->firstname,
									'{email_friend}' => $friendEmail,
									'{link}' => $this->context->link->getPageLink('index', true, $this->context->language->id, 's='.$sponsorship->getSponsorshipMailLink()),
									'{nb_discount}' => (int)MyConf::get('RSPONSORSHIP_QUANTITY_GC', null, $id_template),
									'{discount}' => $discount_gc);
								$this->module->sendMail((int)$this->context->language->id, $template, $this->module->getL('invitation'), $vars, $friendEmail, $friendFirstName.' '.$friendLastName);
								$invitation_sent = true;
								$nbInvitation++;
								$activeTab = 'pending';
							}
						}
					}
					if ($nbInvitation > 0)
						$_POST = array();
				} else if ($sms_active && Tools::isSubmit('submitSponsorSMS')) {
					$phone = Tools::getValue('phone');
					if (empty($phone) || !Validate::isPhoneNumber($phone))
						$error = 'bad phone';
					else {
						$qry = '
							SELECT count(*)
							FROM `'._DB_PREFIX_.'sendsms_recipient`
							JOIN `'._DB_PREFIX_.'sendsms_campaign` AS sc USING(id_sendsms_campaign)
							WHERE `phone`=\''.$phone.'\'
							AND `event`=\'sendsms2Sponsorship\'
							AND sc.status=3
							AND TO_DAYS(NOW()) - TO_DAYS(sc.date_send) <= 10';
						$result = Db::getInstance()->getValue($qry);
						if ((int)$result > 0)
							$error = 'sms already sent';
						else {
							// envoi du SMS
							$vars = array('phone' => $phone, 'customer' => $this->context->customer, 'code' => $code);
							if (!Hook::exec('sendsms2Sponsorship', $vars))
								$error = 'sms impossible';
							else
								$sms_sent = true;
						}
					}
				}

				if (!$popup) {
					// Mailing revive
					$revive_sent = false;
					$nbRevive = 0;
					if (Tools::isSubmit('revive'))
					{
						$activeTab = 'pending';
						if (Tools::getValue('friendChecked') && sizeof($friendsChecked = Tools::getValue('friendChecked')) >= 1)
						{
							foreach ($friendsChecked as $key => $friendChecked)
							{
								$sponsorship = new RewardsSponsorshipModel((int)$key);
								$vars = array(
									'{email}' => $this->context->customer->email,
									'{lastname}' => $this->context->customer->lastname,
									'{firstname}' => $this->context->customer->firstname,
									'{email_friend}' => $sponsorship->email,
									'{link}' => $this->context->link->getPageLink('index', true, $this->context->language->id, 's='.$sponsorship->getSponsorshipMailLink()),
									'{nb_discount}' => (int)MyConf::get('RSPONSORSHIP_QUANTITY_GC', null, $id_template),
									'{discount}' => $discount_gc
								);
								$sponsorship->save();
								$this->module->sendMail((int)$this->context->language->id, $template, $this->module->getL('invitation'), $vars, $sponsorship->email, $sponsorship->firstname.' '.$sponsorship->lastname);
								$revive_sent = true;
								$nbRevive++;
							}
						}
						else
							$error = 'no revive checked';
					}

					$stats = $this->context->customer->getStats();

					$orderQuantityS = (int)MyConf::get('RSPONSORSHIP_ORDER_QUANTITY_S', null, $id_template);

					$canSendInvitations = false;
					if ((int)($stats['nb_orders']) >= $orderQuantityS)
						$canSendInvitations = true;
				}

				// lien de parrainage
				$link_sponsorship = $this->context->link->getPageLink('index', true, $this->context->language->id, 's='.$code);
				$link_sponsorship_fb = $link_sponsorship . '&c=3';
				$link_sponsorship_twitter = $link_sponsorship . '&c=4';
				$link_sponsorship_google = $link_sponsorship . '&c=5';

				// Smarty display
				$smarty_values = array(
					'text' => !$popup ? MyConf::get('RSPONSORSHIP_ACCOUNT_TXT', $this->context->language->id, $id_template) : (Tools::getValue('scheduled') == 1 ? MyConf::get('RSPONSORSHIP_POPUP_TXT', $this->context->language->id, $id_template) : MyConf::get('RSPONSORSHIP_ORDER_TXT', $this->context->language->id, $id_template)),
					'link_sponsorship' => $link_sponsorship,
					'link_sponsorship_fb' => $link_sponsorship_fb,
					'link_sponsorship_twitter' =>$link_sponsorship_twitter,
					'link_sponsorship_google' => $link_sponsorship_google,
					'email' => $this->context->customer->email,
					'code' => $code,
					'reward_allowed_s' => $reward_allowed_s,
					'template' => $template,
					'nbFriends' => (int)MyConf::get('RSPONSORSHIP_NB_FRIENDS', null, $id_template),
					'error' => $error,
					'invitation_sent' => $invitation_sent,
					'sms_sent' => $sms_sent,
					'nbInvitation' => $nbInvitation,
					'mails_exists' => (isset($mails_exists) ? $mails_exists : array()),
					'open_inviter' => isset($openInviter) ? $openInviter : '',
					'open_inviter_providers' => isset($openInviterServices['email']) ? $openInviterServices['email'] : '',
					'rewards_path' => $this->module->getPathUri(),
					'sms' => $sms_active
				);
				$this->context->smarty->assign($smarty_values);

				// si affichage normal, dans le compte du client
				if (!$popup) {
					$statistics = RewardsSponsorshipModel::getStatistics();
					$multilevel = count($params_s) > 1 || Configuration::get('RSPONSORSHIP_UNLIMITED_LEVELS') || (float)$statistics['indirect_rewards'] > 0;
					$smarty_values = array(
						'activeTab' => $activeTab,
						'orderQuantityS' => $orderQuantityS,
						'canSendInvitations' => $canSendInvitations,
						'pendingFriends' => RewardsSponsorshipModel::getSponsorFriends((int)$this->context->customer->id, 'pending'),
						'revive_sent' => $revive_sent,
						'nbRevive' => $nbRevive,
						'subscribeFriends' => RewardsSponsorshipModel::getSponsorFriends((int)$this->context->customer->id, 'subscribed'),
						'statistics' => $statistics,
						'multilevel' => $multilevel
					);
					$this->context->smarty->assign($smarty_values);
				}
				// si popup
				else {
					$smarty_values = array(
						'canSendInvitations' => true,
						'popup' => true,
						'afterSubmit' => Tools::getValue('conditionsValided')
					);
					$this->context->smarty->assign($smarty_values);
				}
			}
			$this->setTemplate('sponsorship.tpl');
		}
	}
}