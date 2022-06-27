<?php
/**
* 2015 Novansis
*
* NOTICE OF LICENSE
*
* Conditions and limitations:
*
* A. This source code file is copyrighted, you cannot remove any copyright notice from this file.  You agree to prevent any unauthorized copying of this file.  Except as expressly provided herein, Novansis does not grant any express or implied right to you under copyrights, trademarks, or trade secret information.
*
* B. You may NOT:  (i) rent or lease the file to any third party; (ii) assign this file or transfer the file without the express written consent of Novansis; (iii) modify, adapt, or translate the file in whole or in part; or (iv) distribute, sublicense or transfer the source code form of any components of the file and derivatives thereof to any third party.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer versions in the future.
*
*  @author    Novansis <info@novansis.com>
*  @copyright 2015 Novansis SL
*  @license   http://www.novansis.com/
*/

class Search extends SearchCore
{
	protected static function getProductsToIndex($total_languages, $id_product = false, $limit = 50, $weight_array = array())
	{
		$max_possibilities = $total_languages * count(Shop::getShops(true));
		$limit = max($max_possibilities, floor($limit / $max_possibilities) * $max_possibilities);
		$sql = 'SELECT p.id_product, pl.id_lang, pl.id_shop, l.iso_code';
		if (is_array($weight_array))
			foreach ($weight_array as $key => $weight)
				if ((int)$weight)
					switch ($key)
					{
						case 'pname':
							$sql .= ', pl.name pname';
						break;
						case 'reference':
							$sql .= ', p.reference';
						break;
						case 'pa_reference':
							$sql .= ', pa.reference AS pa_reference';
						break;
						case 'supplier_reference':
							$sql .= ', p.supplier_reference';
						break;
						case 'pa_supplier_reference':
							$sql .= ', pa.supplier_reference AS pa_supplier_reference';
						break;
						case 'ean13':
							$sql .= ', p.ean13';
						break;
						case 'pa_ean13':
							$sql .= ', pa.ean13 AS pa_ean13';
						break;
						case 'upc':
							$sql .= ', p.upc';
						break;
						case 'pa_upc':
							$sql .= ', pa.upc AS pa_upc';
						break;
						case 'description_short':
							$sql .= ', pl.description_short';
						break;
						case 'description':
							$sql .= ', pl.description';
						break;
						case 'cname':
							$sql .= ', cl.name cname';
						break;
						case 'mname':
							$sql .= ', m.name mname';
						break;
					}
		$sql .= ' FROM '._DB_PREFIX_.'product p
			LEFT JOIN '._DB_PREFIX_.'product_attribute pa
				ON pa.id_product = p.id_product
			LEFT JOIN '._DB_PREFIX_.'product_lang pl
				ON p.id_product = pl.id_product
			'.Shop::addSqlAssociation('product', 'p', true, null, true).'
			LEFT JOIN '._DB_PREFIX_.'category_lang cl
				ON (cl.id_category = product_shop.id_category_default AND pl.id_lang = cl.id_lang AND cl.id_shop = product_shop.id_shop)
			LEFT JOIN '._DB_PREFIX_.'manufacturer m
				ON m.id_manufacturer = p.id_manufacturer
			LEFT JOIN '._DB_PREFIX_.'lang l
				ON l.id_lang = pl.id_lang
			WHERE product_shop.indexed = 0
			AND product_shop.visibility IN ("both", "search")
			'.($id_product ? 'AND p.id_product = '.(int)$id_product : '').'
			AND product_shop.`active` = 1
			AND pl.`id_shop` = product_shop.`id_shop`
			LIMIT '.(int)$limit;
		return Db::getInstance()->executeS($sql);
	}
	public static function find($id_lang, $expr, $page_number = 1, $page_size = 1, $order_by = 'position',
		$order_way = 'desc', $ajax = false, $use_cookie = true, Context $context = null)
	{
		$result = parent::find($id_lang, $expr, $page_number, $page_size, $order_by, $order_way, $ajax, $use_cookie, $context);
		if (Configuration::get('SEARCH_OPTIMIZER_RECORD') == 1)
		{
			$products = '';
			if (!$context)
				$context = Context::getContext();
			$id_shop = $context->shop->id;
			if ($result === false)
				$products = '';
			elseif ($ajax) 
			{
				if (count($result) > 0)
					foreach ($result as $product)
						$products .= $product['id_product'].',';
			} 
			else
				if (count($result['result']) > 0)
					foreach ($result['result'] as $product)
						$products .= $product['id_product'].',';
			$products = Tools::substr($products, 0, -1);
			$sql = 'INSERT INTO '._DB_PREFIX_.'search_optimizer (id_shop, expression, id_lang, products) VALUES ('.$id_shop.',\''.$expr.'\',\''.$id_lang.'\',\''.$products.'\')';
			Db::getInstance()->execute($sql);
		}
		return $result;
	}
	public static function indexation($full = false, $id_product = false)
	{
		$db = Db::getInstance();
		if ($id_product)
			$full = false;
		if ($full)
		{
			$db->execute('TRUNCATE '._DB_PREFIX_.'search_index');
			$db->execute('TRUNCATE '._DB_PREFIX_.'search_word');
			ObjectModel::updateMultishopTable('Product', array('indexed' => 0));
		}
		$weight_array = array(
			'pname' => Configuration::get('PS_SEARCH_WEIGHT_PNAME'),
			'reference' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
			'ean13' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
			'upc' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
			'description_short' => Configuration::get('PS_SEARCH_WEIGHT_SHORTDESC'),
			'description' => Configuration::get('PS_SEARCH_WEIGHT_DESC'),
			'cname' => Configuration::get('PS_SEARCH_WEIGHT_CNAME'),
			'mname' => Configuration::get('PS_SEARCH_WEIGHT_MNAME'),
			'tags' => Configuration::get('PS_SEARCH_WEIGHT_TAG'),
			'attributes' => Configuration::get('PS_SEARCH_WEIGHT_ATTRIBUTE'),
			'features' => Configuration::get('PS_SEARCH_WEIGHT_FEATURE')
		);
		$count_words = 0;
		$query_array3 = array();
		$word_ids = $db->executeS('
			SELECT id_word, word, id_lang, id_shop
			FROM '._DB_PREFIX_.'search_word', false);
		$word_ids_by_word = array();
		while ($word_id = $db->nextRow($word_ids))
		{
			if (!isset($word_ids_by_word[$word_id['id_shop']][$word_id['id_lang']]))
				$word_ids_by_word[$word_id['id_shop']][$word_id['id_lang']] = array();
			$word_ids_by_word[$word_id['id_shop']][$word_id['id_lang']]['_'.$word_id['word']] = (int)$word_id['id_word'];
		}
		$total_languages = count(Language::getLanguages(false));
		$products = Search::getProductsToIndex($total_languages, $id_product, 50, $weight_array);
		$cproducts = count($products);
		while ($products && $cproducts > 0)
		{
			$products_array = array();
			foreach ($products as $product)
			{
				if ((int)$weight_array['tags'])
					$product['tags'] = Search::getTags($db, (int)$product['id_product'], (int)$product['id_lang']);
				if ((int)$weight_array['attributes'])
					$product['attributes'] = Search::getAttributes($db, (int)$product['id_product'], (int)$product['id_lang']);
				if ((int)$weight_array['features'])
					$product['features'] = Search::getFeatures($db, (int)$product['id_product'], (int)$product['id_lang']);
				$product_array = array();
				foreach ($product as $key => $value)
				{
					if (strncmp($key, 'id_', 3) && isset($weight_array[$key]))
					{
						$words = explode(' ', Search::sanitize($value, (int)$product['id_lang'], true, $product['iso_code']));
						foreach ($words as $word)
							if (!empty($word))
							{
								$word = Tools::substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH);
								$word = Tools::replaceAccentedChars($word);
								if (!isset($product_array[$word]))
									$product_array[$word] = 0;
								$product_array[$word] += $weight_array[$key];
							}
					}
				}
				if (count($product_array))
				{
					$query_array = $query_array2 = array();
					foreach ($product_array as $word => $weight)
						if ($weight && !isset($word_ids_by_word['_'.$word]))
						{
							$query_array[$word] = '('.(int)$product['id_lang'].', '.(int)$product['id_shop'].', \''.pSQL($word).'\')';
							$query_array2[] = '\''.pSQL($word).'\'';
							$word_ids_by_word[$product['id_shop']][$product['id_lang']]['_'.$word] = 0;
						}
					if ($query_array2)
					{
						$existing_words = $db->executeS('
						SELECT DISTINCT word FROM '._DB_PREFIX_.'search_word
							WHERE word IN ('.implode(',', $query_array2).')
						AND id_lang = '.(int)$product['id_lang'].'
						AND id_shop = '.(int)$product['id_shop']);
						foreach ($existing_words as $data)
							unset($query_array[Tools::replaceAccentedChars($data['word'])]);
					}
					if (count($query_array))
					{
						$db->execute('
						INSERT IGNORE INTO '._DB_PREFIX_.'search_word (id_lang, id_shop, word)
						VALUES '.implode(',', $query_array));
					}
					if (count($query_array2))
					{
						$added_words = $db->executeS('
						SELECT sw.id_word, sw.word
						FROM '._DB_PREFIX_.'search_word sw
						WHERE sw.word IN ('.implode(',', $query_array2).')
						AND sw.id_lang = '.(int)$product['id_lang'].'
						AND sw.id_shop = '.(int)$product['id_shop'].'
						LIMIT '.count($query_array2));
						foreach ($added_words as $word_id)
							$word_ids_by_word[$product['id_shop']][$product['id_lang']]['_'.Tools::replaceAccentedChars($word_id['word'])] = (int)$word_id['id_word'];
					}
				}
				foreach ($product_array as $word => $weight)
				{
					if (!$weight)
						continue;
					if (!isset($word_ids_by_word[$product['id_shop']][$product['id_lang']]['_'.$word]))
						continue;
					if (!$word_ids_by_word[$product['id_shop']][$product['id_lang']]['_'.$word])
						continue;
					$query_array3[] = '('.(int)$product['id_product'].','.
						(int)$word_ids_by_word[$product['id_shop']][$product['id_lang']]['_'.$word].','.(int)$weight.')';
					if (++$count_words % 200 == 0)
						Search::saveIndex($query_array3);
				}
				if (!in_array($product['id_product'], $products_array))
					$products_array[] = (int)$product['id_product'];
			}
			Search::setProductsAsIndexed($products_array);
			Search::saveIndex($query_array3);
			$products = Search::getProductsToIndex($total_languages, $id_product, 50, $weight_array);
			$cproducts = count($products);
		}
		return true;
	}
	public static function sanitize($string, $id_lang, $indexation = false, $iso_code = false)
	{
		$string = trim($string);
		if (empty($string))
			return '';
		$string = Tools::strtolower(strip_tags($string));
		$string = html_entity_decode($string, ENT_NOQUOTES, 'utf-8');
		$string = preg_replace('/(['.PREG_CLASS_NUMBERS.']+)['.PREG_CLASS_PUNCTUATION.']+(?=['.PREG_CLASS_NUMBERS.'])/u', '\1', $string);
		$string = preg_replace('/['.PREG_CLASS_SEARCH_EXCLUDE.']+/u', ' ', $string);
		$string = Tools::replaceAccentedChars($string);
		if ($indexation)
			$string = preg_replace('/[._-]+/', ' ', $string);
		else
		{
			$string = preg_replace('/[._]+/', '', $string);
			$string = ltrim(preg_replace('/([^ ])-/', '$1 ', ' '.$string));
			$string = preg_replace('/[._]+/', '', $string);
			$string = preg_replace('/[^\s]-+/', '', $string);
		}
		$blacklist = Tools::strtolower(Configuration::get('PS_SEARCH_BLACKLIST', $id_lang));
		if (!empty($blacklist))
		{
			$bla = explode('|', $blacklist);
			$lim = 2000;
			$cbla = count($bla);
			while ($cbla > 0)
			{
				$blatmp = array_slice($bla, 0, $lim);
				$blstr = implode('|', $blatmp);
				$string = preg_replace('/(?<=\s)('.$blstr.')(?=\s)/Su', '', $string);
				$string = preg_replace('/^('.$blstr.')(?=\s)/Su', '', $string);
				$string = preg_replace('/(?<=\s)('.$blstr.')$/Su', '', $string);
				$string = preg_replace('/^('.$blstr.')$/Su', '', $string);
				$bla = array_splice($bla, $lim);
				$cbla = count($bla);
			}
		}
		if (!$indexation)
		{
			$words = explode(' ', $string);
			$processed_words = array();
			foreach ($words as $word)
			{
				$alias = new Alias(null, $word);
				if (Validate::isLoadedObject($alias))
					$processed_words[] = $alias->search;
				else
					$processed_words[] = $word;
			}
			$string = implode(' ', $processed_words);
		}
		if (in_array($iso_code, array('zh', 'tw', 'ja')) && function_exists('mb_strlen'))
		{
			$symbols = '';
			$letters = '';
			foreach (explode(' ', $string) as $mb_word)
				if (Tools::strlen(Tools::replaceAccentedChars($mb_word)) == mb_strlen(Tools::replaceAccentedChars($mb_word)))
					$letters .= $mb_word.' ';
				else
					$symbols .= $mb_word.' ';
			if (preg_match_all('/./u', $symbols, $matches))
				$symbols = implode(' ', $matches[0]);
			$string = $letters.$symbols;
		}
		elseif ($indexation)
		{
			$minwordlen = (int)Configuration::get('PS_SEARCH_MINWORDLEN');
			if ($minwordlen > 1)
			{
				$minwordlen -= 1;
				$string = preg_replace('/(?<=\s)[^\s]{1,'.$minwordlen.'}(?=\s)/Su', ' ', $string);
				$string = preg_replace('/^[^\s]{1,'.$minwordlen.'}(?=\s)/Su', '', $string);
				$string = preg_replace('/(?<=\s)[^\s]{1,'.$minwordlen.'}$/Su', '', $string);
				$string = preg_replace('/^[^\s]{1,'.$minwordlen.'}$/Su', '', $string);
			}
		}
		$string = trim(preg_replace('/\s+/', ' ', $string));
		return $string;
	}
}