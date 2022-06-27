<?php
if (!defined('_PS_VERSION_'))
	exit;

class domPromoReq extends dompromo {	
    
	/* Retourne l'ID du magasin courant. */
	public function getIDMagasin() {
		$IDMag = (int) Context::getContext()->shop->id;
		return $IDMag;
	}
	
	/* Liste les ID produit d'une catégorie. */
	public function ProduitFromCategorie($id, $id_supplier = false) {
		return Db::getInstance()->ExecuteS('
				SELECT p.`id_product`
				FROM `'._DB_PREFIX_.'product` p
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON p.`id_product` = cp.`id_product`
				WHERE cp.`id_category` = '.(int)($id).' AND p.`active` = 1
				'.($id_supplier ? 'AND p.id_supplier = '.(int)($id_supplier) : '')); 
	}
	
	/* Liste les ID produit d'un fournisseur. */
	public function ProduitFromFournisseur($id) {
		return Db::getInstance()->ExecuteS('
				SELECT p.`id_product` FROM `'._DB_PREFIX_.'product` p			
				WHERE  p.`active` = 1 AND p.`id_supplier` = '.(int)($id)); 
	}
	
	/* Liste des domPromos (Vente flash, Prix coutant et destockage) en cours sur produit. */
	public function selectDompromoProduct($id_product) {
		return Db::getInstance()->ExecuteS('
				SELECT id, datedebut, datefin, vfreduction, oldreduction,	typesale
				FROM `'._DB_PREFIX_.'dompromo`
				WHERE `id_venteflash` = '.$id_product);
	}
	
	/* Liste des Promos (Promotion et Solde) en cours sur produit. */
	public function selectSpecificPriceProduct($id_product) {
		return Db::getInstance()->ExecuteS('
				SELECT sp.`id_specific_price`, sp.`from`, sp.`to`
				FROM `'._DB_PREFIX_.'specific_price` sp
				WHERE sp.`from_quantity` <= 1
				AND sp.`id_product` = '.(int)($id_product).' AND (`id_shop`='.(int)(domPromoReq::getIDMagasin()).' OR `id_shop`=0)');
	}
	
	/* On vérifie qu'il n'existe pas de vente à prix dégressif sur le produit. */
	public function isVenteDegressif($idProduit, $debut, $fin) {
	 $nbVente = Db::getInstance()->getValue('
    		SELECT count(id_specific_price) as nbVente FROM `'._DB_PREFIX_.'specific_price`
        WHERE `id_product` = '.(int)($idProduit).'
        AND (`id_shop` = '.(domPromoReq::getIDMagasin()).' OR `id_shop` = 0)
        AND `from_quantity` > 1 
        AND (`from` = \'0000-00-00 00:00:00\' OR `to` = \'0000-00-00 00:00:00\'
        		OR (\''.$debut.'\' > `from` AND \''.$debut.'\' < `to`) 
        		OR (\''.$fin.'\' > `from` AND \''.$fin.'\' < `to`)
        		OR (\''.$debut.'\' < `from` AND \''.$fin.'\' > `to`))
        ');
    if ((int)($nbVente) > 0) return TRUE; else return FALSE;
 	}    
 	
	/* Ajout ou mise à jour d'une vente promotionnelle d'un produit. */
	public function add_vente($id, $debut, $fin, $reduction, $typesale, $typesg) {
		if($typesale == 4 || $typesale == 5) {
			// Solde ou promo.
			$solde = 'on_sale="0"';
			if($typesale == 5)
				$solde = 'on_sale="1"';

			// Ajout ou MaJ de la table "specific_price"
			domPromoReq::addmaj_specific_price((int)($id), $debut, $fin, $reduction, 1);

			$venteflash = Db::getInstance()->Execute('
							UPDATE `'._DB_PREFIX_.'product` SET '.$solde.' WHERE `id_product` = '.(int)($id));
			$venteflash = Db::getInstance()->Execute('
							DELETE FROM `'._DB_PREFIX_.'dompromo` WHERE `id_venteflash` = '.(int)($id));	
		} else {
			// Vente flash, prix coutant ou destockage.
			$venteflash = Db::getInstance()->Execute('
							UPDATE `'._DB_PREFIX_.'product` SET on_sale="0" WHERE `id_product` = '.(int)($id));
			 		
			// Ajout ou MaJ de la table "dompromo"
			domPromoReq::addmaj_dompromo((int)($id), $debut, $fin, $reduction, $typesale, $typesg);
					
			// Ajout ou MaJ de la table "specific_price"
			domPromoReq::addmaj_specific_price((int)($id), $debut, $fin, $reduction,0);
		}
	}
	
	/* Ajout ou Mise à jour d'une vente de type Flash, Prix coutant ou Déstockage. */
	public function maj_vf_pc_destock($valeur, $from, $to, $chreduction, $idconfig, $reduction) {
		$venteflash = Db::getInstance()->Execute('
			UPDATE `'._DB_PREFIX_.'dompromo`
			SET `datedebut` ="'.$from.'",`datefin` ="'.$to.'" '.$chreduction.',`id_config`='.(int)($idconfig).'
			WHERE `id_venteflash` = '.$valeur);
		// On vérifie si on doit mettre à jour la table ps_specific_price
		$verif = Db::getInstance()->getValue('SELECT count(id_specific_price) FROM `'._DB_PREFIX_.'specific_price` WHERE  `id_product` = '.$valeur.' 
			AND (`id_shop`='.(int)(domPromoReq::getIDMagasin()).' OR `id_shop`=0) AND `from_quantity`<=1');
		if ($verif > 0) {
			$update = Db::getInstance()->Execute('
				UPDATE `'._DB_PREFIX_.'specific_price`
				SET `reduction`='.($reduction/100).', `from_quantity` = 0, `from`="'.$from.'", `to`="'.$to.'", `reduction_type`="percentage", `price`="-1" 
				WHERE id_product='.(int)($valeur).' 
				AND (`id_shop`='.(int)(domPromoReq::getIDMagasin()).' OR `id_shop`=0)
				AND `from_quantity`<=1');
		} else {
			// On vérifie si la date de début est attente (Si oui, creation dans ps_specific_price)
			if(strtotime($from) < strtotime(date("Y-m-d H:i:s")) AND strtotime($to) > strtotime(date("Y-m-d H:i:s"))) {
				$sql = Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'product` SET on_sale=0 WHERE id_product='.$valeur);
				$insert = Db::getInstance()->Execute('
					INSERT INTO `'._DB_PREFIX_.'specific_price`
					(`id_product`,`id_shop`,`from_quantity`,`reduction`, `from`, `to`, `reduction_type`, `price`)
					VALUES ('.$valeur.','.(int)(domPromoReq::getIDMagasin()).',0,'.($reduction/100).', "'.$from.'", "'.$to.'", "percentage", "-1")');
			}
		}
	}
	
	/* Suppression d'une vente flash, prix coutant, ou destockage. */
	public function del_vf_pc_destock($suppression) {
		foreach($suppression as $chave => $value) {
			$delete = Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'dompromo` WHERE `id_venteflash` =  '.$value);
			$delete = Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'specific_price` WHERE `id_product` =  '.$value.' 
																						AND (`id_shop`='.(int)(domPromoReq::getIDMagasin()).' OR `id_shop`=0) AND `from_quantity`<=1');
		}
	}
	
	/* Supression d'une promo ou d'un solde. */
	public function del_promo_solde($suppression) {
		foreach($suppression as $chave => $value) {                
			$deleted = Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'product` SET on_sale=0, date_upd=NOW() WHERE id_product='.$value);
			$delete = Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'specific_price` WHERE `id_product` =  '.$value.' 
																						AND (`id_shop`='.(int)(domPromoReq::getIDMagasin()).' OR `id_shop`=0) AND `from_quantity`<=1');                
		}
	}
	
	/* Procédure "démarrer" une vente de type Flash, Prix coutant ou Déstockage. */
	public function demarrerVenteVF($value) {
		$sql = domPromoReq::selectDompromoProduct($value);
		foreach ($sql AS $aux) {
			// Ajout ou MaJ de la table "specific_price"
			domPromoReq::addmaj_specific_price($value, date("Y-m-d H:i:s"), $aux['datefin'], $aux['vfreduction'],0);
					
			$update = Db::getInstance()->Execute('
				UPDATE `'._DB_PREFIX_.'dompromo` SET `datedebut` ="'.date("Y-m-d H:i:s").'" WHERE `id_venteflash` = '.$value);
		}
	}
	
	/* Procédure "démarrer" une vente Solde ou Promo. */
	public function demarrerVentePR_SL($value) {
		$sql = Db::getInstance()->Execute('
					UPDATE `'._DB_PREFIX_.'specific_price`SET `from` = "'.date("Y-m-d H:i:s").'" 
					WHERE `id_product`='.$value.' 
					AND (`id_shop`='.(int)(domPromoReq::getIDMagasin()).' OR `id_shop`=0) AND `from_quantity`<=1');
	}
	
	/* Démarre immédiatement une vente différée de type Flash, Prix coutant ou Déstockage. */
	public function startNowVF($id) {
		$sql = domPromoReq::selectDompromoProduct($id);
		foreach ($sql AS $aux) {
			if (domPromoReq::isVenteDegressif($id, date("Y-m-d H:i:s"), $aux['datefin']) == true) {
				return FALSE;
			} else {
				domPromoReq::demarrerVenteVF($id);
				return TRUE;
			}
		}
		return TRUE;
	}
	
	/* Démarre immédiatement une vente différée de type Promotion ou Solde. */
	public function startNowSL($id) {
		$sql = domPromoReq::selectSpecificPriceProduct($id);
		foreach ($sql AS $aux) {
			if (domPromoReq::isVenteDegressif($id, date("Y-m-d H:i:s"), $aux['to']) == true) {
				return FALSE;
			} else {
				domPromoReq::demarrerVentePR_SL($id);
				return TRUE;
			}
		}
		return TRUE;
	}
	
	/* Procédure d'arrêt d'une vente de type Flash, Prix coutant ou Déstockage. */
	public function stopVente($value) {
		$sql = Db::getInstance()->Execute('
			UPDATE `'._DB_PREFIX_.'dompromo`
			SET datedebut="'.date("Y-m-d H:i:s").'", datefin="'.date("Y-m-d H:i:s").'"
			WHERE id_venteflash='.(int)($value));
		$update = Db::getInstance()->Execute('
			UPDATE `'._DB_PREFIX_.'specific_price`
			SET `reduction`=0, `from`=NOW(), `to`=NOW(), `reduction_type`="percentage"
			WHERE id_product='.(int)($value).'
			AND (`id_shop`='.(int)(domPromoReq::getIDMagasin()).' OR `id_shop`=0) 
			AND `from_quantity`<=1');
	}
	
	
	/* Ajoute ou met à jour une vente promotionnelle dans la table "dompromo" */
	private function addmaj_dompromo($id, $debut, $fin, $reduction, $typesale, $typesg) {	
		$sql  = Db::getInstance()->getValue('
					SELECT id FROM `'._DB_PREFIX_.'dompromo` WHERE `id_venteflash` = '.(int)($id));
		if ($sql) {			
			$venteflash = Db::getInstance()->Execute('
					UPDATE `'._DB_PREFIX_.'dompromo`
					SET `datedebut` ="'.$debut.'",`datefin` ="'.$fin.'",`vfreduction` ="'.$reduction.'",`typesale`="'.$typesale.'",`id_config`="'.$typesg.'"
					WHERE `id_venteflash` = '.(int)($id));				 
		} else {			 
			$query =  Db::getInstance()->Execute('
					INSERT INTO  `'._DB_PREFIX_.'dompromo`
					(`id_venteflash`, `datedebut`,`datefin`,`vfreduction`, `typesale`,`id_config`)
					VALUES ('.(int)($id).',"'.$debut.'","'.$fin.'","'.$reduction.'", "'.$typesale.'","'.$typesg.'" )');
		}
	}
	
	/* Ajoute ou met à jour une vente dans la table "specific_price" */
	private function addmaj_specific_price($id, $debut, $fin, $reduction, $quantity) {
		$quantity = intval($quantity);
		$sql  = Db::getInstance()->getValue('
				SELECT id_specific_price FROM `'._DB_PREFIX_.'specific_price` WHERE `id_product` = '.(int)($id).' 
				AND (`id_shop`='.(int)(domPromoReq::getIDMagasin()).' OR `id_shop`=0) AND `from_quantity`<=1');
		if ($sql) {
			$venteflash = Db::getInstance()->Execute('
					UPDATE `'._DB_PREFIX_.'specific_price`
					SET `from_quantity`='.$quantity.',`from` ="'.$debut.'",`to` ="'.$fin.'",`reduction` ="'.($reduction/100).'", reduction_type = "percentage", `price` = "-1" 
					WHERE `id_product` = '.(int)($id).' 
					AND (`id_shop`='.(int)(domPromoReq::getIDMagasin()).' OR `id_shop`=0) AND `from_quantity`<=1');
		} else {
			$venteflash = Db::getInstance()->Execute('
					INSERT INTO `'._DB_PREFIX_.'specific_price`
					(`id_product`,`id_shop`,`from_quantity`,`reduction`, `from`, `to`, `reduction_type`, `price`)
					VALUES ('.(int)($id).','.(int)(domPromoReq::getIDMagasin()).','.$quantity.','.($reduction/100).', "'.$debut.'", "'.$fin.'", "percentage", "-1")');
		}
	}
	
}
?>
