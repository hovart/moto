# 1. Un module d'import basé sur ceux utilisés par les grands comptes (introduction)

PrestaShop XML Importer, est capable de supporter des flux comportant des milliers d'entités (produits, clients, mouvement de stock, …).

Dédié au petit marchand comme au grand compte, le module est construit pour être robuste, rapide, et simple d’utilisation.

# 2. Installation

Pour installer le module, il faut utiliser l’interface de PrestaShop prévue à cet effet : depuis le back office dans l’onglet "module", cliquez sur “Ajouter un nouveau module”. Un formulaire apparait :

 

![image alt text](image_0.png)

Choisissez l’archive zip du module puis cliquez sur le bouton "Changer le module".

Un message vous informe que le module est téléchargé.

Dans la recherche des modules recherchez "importer" puis cliquez sur le bouton “installer”.

![image alt text](image_1.png)

Dans le menu une nouvelle entrée fait son apparition :

![image alt text](image_2.png)

Le module est maintenant installé. Il nous reste plus qu’à l’activer.

**L’activation ne peut être faite que depuis votre environnement de production.**

Si vous avez un environnement de préproduction accessible depuis l’extérieur contactez-nous via le formulaire de PrestaShop Addons.

Depuis un environnement local, le module fonctionnera en mode dégradé. Ce mode est suffisant pour faire des tests mais ne permet pas d’effectuer des tâches récurrentes.

Pour activer le module aller dans "Configuration" du menu plus haut. Renseigner le champ “Référence de commande” avec le numéro de votre commande et activez l’api “smart cron”.

![image alt text](image_3.png)

# 3. Importer des flux

L’importation de flux peut être effectuée de trois manières différentes :

* Via le panel d'administration
* Via FTP ou SSH
* Via une tâche planifiée

Quel que soit le moyen choisi, dans les différents exemples, nous utiliserons le flux suivant ([product-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-1.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>20</price>
		<tax>FR Taux réduit (5.5%)</tax>
	</product>
</products>

## 3.1 Importation d’un flux via le back office

L’importation du backoffice se fait via l’onglet PrestaShop XML Importer > Upload

![image alt text](image_4.png)

Télécharger [product-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-1.xml) sur votre ordinateur et uploadez-le via le formulaire.

Si votre module a été activé, attendez quelques minutes, le flux est maintenant listé dans l’onglet PrestaShop XML Importer > Flux.

![image alt text](image_5.png)

Si vous n’avez pas activé le module (dans le cas où vous faites des tests sur une machine locale par exemple), il faut simuler le fonctionnement normal.

Pour cela entrez dans votre navigateur l’url suivante : [http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=](http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=)

"localhost" et “prestashop” de l’url sont à adapter en fonction de votre configuration. [http://localhost/prestashop/](http://localhost/prestashop/) étant l’url de la page d’accueil de votre boutique.

Pensez à remplacer le "products" de FlowsImporter::products par le type de flux à importer : associations, objects, products ou stocks.

Le flux est maintenant listé dans l’onglet PrestaShop XML Importer > Flux.

Dans PrestaShop XML Importer > Blocs, deux nouvelles lignes sont apparues :

![image alt text](image_6.png)

Le bloc #2 est maintenant en attente d’exécution (resultat = 0). Il sera exécuté automatiquement si vous avez activé le module.

Dans le cas contraire, il faudra l’exécuter manuellement. Pour cela, choisissez dans les actions (liste déroulante à droite) l’option "Exécuter le bloc" (icone play) :

![image alt text](image_7.png)

Le produit est maintenant importé.

## 3.2 Importation via FTP ou SSH

Il est possible de placer les flux directement sur le serveur en les déposant dans le dossier queue du module (/modules/advancedimporter/flows/import/queue/).

**Attention, il faut dans ce cas, après avoir uploadé le fichier, créer un fichier nommé UPLOADED dans le même dossier.** Cela permet d’éviter que le fichier soit traité avant la fin de l’import.

Testez donc avec [le fichier d’exemple](http://prestashopxmlimporter.madef.fr/flows_fr/product-1.xml).

Si votre module a été activé, attendez quelques minutes, le flux est maintenant listé dans l’onglet PrestaShop XML Importer > Flux.

![image alt text](image_8.png)

Si vous n’avez pas activé le module (dans le cas où vous faites des tests sur une machine locale par exemple), il faut simuler le fonctionnement normal.

Pour cela entrez dans votre navigateur l’url suivante : [http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=](http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=)

"localhost" et “prestashop” de l’url sont à adapter en fonction de votre configuration. http://localhost/prestashop/ est normalement l’url de la page d’accueil de votre boutique.

Pensez à remplacer le "products" de FlowsImporter::products par le type de flux à importer : associations, objects, products ou stocks.

Le flux est maintenant listé dans l’onglet PrestaShop XML Importer > Flux.

Dans PrestaShop XML Importer > Blocs, deux nouvelles lignes sont apparues :

![image alt text](image_9.png)

Le bloc #2 est maintenant en attente d’exécution (resultat = 0). Il sera exécuté automatiquement si vous avez activé le module.

Dans le cas contraire, il faudra l’exécuter manuellement. Pour cela, choisissez dans les actions (liste déroulante à droite) l’option "Exécuter le bloc" (icone play) :

![image alt text](image_10.png)

Le produit est maintenant importé.



## 3.3 Importation via une tâche planifiée

PrestaShop XML Importer est capable de gérer des tâches planifiées. Pour ce faire rendez-vous dans l’onglet PrestaShop XML Importer > Tâche récurrente. Cet écran liste toutes les tâches récurrentes comme les tâches de traitement des flux. C’est ici que l’on peut rajouter un ou plusieurs importeurs.

Cliquez sur le bouton, "Ajouter un téléchargeur". Dans le formulaire, renseignez les champs suivants :

* Description : Test importer
* Cron Time : 0 1 * * *
* Url : http://prestashopxmlimporter.madef.fr/example/product01.xml
* Station : 1
* Id boutique : 1

Nous ne détaillerons pas dans cette documentation la notion de station. Mettez toujours le chiffre "1".

Le champ "cron time", utilise la même syntaxe que le logiciel cron. vous trouverez [plus de détails sur wikipedia](http://fr.wikipedia.org/wiki/Crontab#Syntaxe_de_la_table).

Dans cet exemple, on demande d’importer le flux tous les jours à une heure du matin.

Sauvegardez la tâche récurrente.

Le flux sera importé à une heure du matin. Afin de vérifier que cela fonctionne, il est possible de forcer les choses. Dans la nouvelle ligne de la liste de tâches récurrentes, choisissez dans les actions l’option "ajouter dans les blocs" (l’icone “play”).

![image alt text](image_11.png)

Allez dans la liste des blocs : PrestaShop XML Importer > Blocs.

Cet écran liste toutes les tâches planifiées.

La première ligne de la liste est notre téléchargeur. On peut encore tricher, en choisissant dans les actions l’option "Executer le bloc". Si vous avez activé le module, cette action n’est pas nécessaire.

Une ligne supplémentaire apparaît dans la liste des blocs. Si on l’éxécute de la même manière que précédement, le produit est importé (de même cela n’est nécessaire que si le module n’est pas activé).

# 4. Introduction aux flux XML

Le module intègre par défaut, quatre types de flux XML : [les flux produits](#heading=h.y1tyeapvav9l), [stock](#heading=h.x1jopc4me6iq), [rattachement aux catégories](#heading=h.5vys1ry28lcu) et [objet](#heading=h.6grw0n5nkrrt). Si vous avez des connaissances en PHP, il est possible d'en ajouter d'autres en créant de nouveaux importeurs.

Le module peut importer à peu près n’importe quel format de flux sans nouvelle ligne de code. Pour cela il faudra réaliser des [XSLT](#heading=h.9y1gyn3t8mrd).

## 4.1 Flux produit

Le flux produit est le flux le plus complet. La plus grande partie de cette documentation est dédiée à ce dernier. Même si il existe des flux dédiés à la gestion de stock et d’association à des catégories, il est possible de s’en passer et d’utiliser directement le flux produit. Dans un premier temps, nous allons nous contenter de quelques exemples très basiques.

N’oubliez pas si vous n’avez pas activé le module, d’importer le flux en entrant l’url [http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=](http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=) et d’éxécuter le block d’import du produit comme précisé dans la section 3.1 et 3.2.

Voici l’exemple de création de produit le plus basique. N’oubliez pas de changer la langue par celle utilisée sur votre boutique ([product-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-1.xml)).

<products>
	<product external-reference="demo-1">
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>20</price>
		<tax>FR Taux réduit (5.5%)</tax>
	</product>
</products>

Si un produit a déjà été créé, alors tout flux utilisant la même référence externe, id, ean13 ou référence modifiera le produit au lieu d’en créer un nouveau ([product-2.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-2.xml)).

<products>
	<product>
		<id>12</id>
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>19.99</price>
	</product>
	<product>
		<reference>demo_1</reference>
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
	<price>19.99</price>
	</product>
	<product>
		<ean13>1111111111111</ean13>
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>19.99</price>
	</product>
	<product external-reference="product-demo-1" >
		<name lang="fr">Nom</name>
	</product>
</products>

Il est recommandé d’utiliser la référence externe comme identifiant avec l’extérieur.

Il est possible d’importer des images soit via HTTP, soit depuis le serveur ([product-3.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-3.xml)) :

<products>
	<product external-reference="product-demo-1" >
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<images>
			<url>/modules/advancedimporter/img/media/01.jpg</url> <!-- depuis le disque locale -->
			<url>http://prestashopxmlimporter.madef.fr/img/demo.jpg</url> <!-- depuis un serveur distant -->
		</images>
	</product>
</products>

Il est possible de définir les taxes, soit en utilisant le libellé de la règle, soit en utilisant son id ([product-4.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-4.xml)) :

<products>
	<product external-reference="product-demo-1" >
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>19.99</price>
		<tax>FR Taux réduit (5.5%)</tax>
	</product>
	<product external-reference="product-demo-2" >
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>19.99</price>
		<id_tax_rules_group>1</id_tax_rules_group>
	</product>
</products>

**Attention : il n’est pas possible de définir directement le taux de taxe.**

Le flux produit, peut modifier les attributs suivants d’un produit : ([product-attributes.yaml](http://prestashopxmlimporter.madef.fr/flows_fr/product-attributes.yaml)) :

Product:

* id_shop_default
* id_manufacturer
* id_supplier
* reference
* supplier_reference
* location
* width
* height
* depth
* weight
* quantity_discount
* ean13
* upc
* cache_is_pack
* cache_has_attachments
* is_virtual
* id_category_default
* id_tax_rules_group
* on_sale
* online_only
* ecotax
* minimal_quantity
* price
* wholesale_price
* unity
* unit_price_ratio
* additional_shipping_cost
* customizable
* text_fields
* uploadable_files
* active
* redirect_type
* id_product_redirected
* available_for_order
* available_date
* condition
* show_price
* indexed
* visibility
* cache_default_attribute
* advanced_stock_management
* date_add
* date_upd
* meta_description
* meta_keywords
* meta_title
* link_rewrite
* name
* description
* description_short
* available_now
* available_later

Dans tous ces exemples, on a défini un attribut "external-reference". Ce dernier n’est pas nécessaire, mais est fortement recommandé, il permet de faire le lien entre l'environnement externe (le flux XML) et votre boutique. [En savoir plus sur les références externes](#heading=h.gq7fqypth5w3).

## 4.2 Flux objet

Le flux objet, permet d’importer tout type d’objet PrestaShop. Ces derniers peuvent intégrer dans un même fichier plusieurs types d’objet.

N’oubliez pas si vous n’avez pas activé le module, d’importer le flux en entrant l’url [http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::objects&block=](http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=) et d’éxécuter le block d’import du produit comme précisé dans la section 3.1 et 3.2.

Import d’un produit et d’une catégorie ([object-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/object-1.xml)) :


<objects>
	<object type="category" external-reference="demo-1">
		<name lang="fr">Nom de la catégorie</name>
		<link_rewrite lang="fr">Nom</link_rewrite>
	</object>
	<object type="product" external-reference="demo-1">
		<name lang="fr">Nom du produit</name>
		<link_rewrite lang="fr">Nom</link_rewrite>
	</object>
</objects>

Vous pouvez retrouver tous les attributs modifiables dans le fichier [attributes.yaml](http://prestashopxmlimporter.madef.fr/attributes.yaml).

## 4.3 Référence externe

Les références externes permettent de faire le lien entre le flux (l'environnement externe) et votre boutique. La référence externe peux être utilisée dans les flux objet et produit. Elle s’utilise en ajoutant l’attribut **external-reference**. Les références sont uniques pour chaque type d’entité. Ainsi il est possible d’avoir une catégorie et un produit portant la même référence externe sans conflit (cf exemple précédent).

Exemple d’utilisation des références externes ([object-2.xml](http://prestashopxmlimporter.madef.fr/flows_fr/object-2.xml)) :


<objects>
	<object type="taxRulesGroup" external-reference="tax-1">
		<name>Groupe de règle de taxe</name>
	</object>
</objects>

En plus de permettre d'identifier chaque entitée dans le but de les mettre à jour, il est aussi possible d’en faire référence pour certains attributs. Imaginez par exemple, que vous souhaitez importer un produit en spécifiant la taxe via sa référence externe.

Définition d’une taxe via les références externes ([product-5.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-5.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>20</price>
		<external_reference for="id_tax_rules_group" type="taxRulesGroup">tax-1</external_reference>
	</product>
</products>

Autre exemple, imaginons que vous souhaitiez créer une caractéristique avec des valeurs.

Création d’une caractéristique et de valeurs ([object-3.xml](http://prestashopxmlimporter.madef.fr/flows_fr/object-3.xml)) :

<objects>
	<object type="feature" external-reference="feature-test">
		<name lang="fr">Caractéristique de test</name>
	</object>
	<object type="featureValue" external-reference="feature-value-test">
		<value lang="fr">Valeur de test</value>
		<external_reference for="id_feature" type="feature">feature-test</external_reference>
	</object>
</objects>

## 4.4 Notion de bloc

Les flux produit et objet permettent de modifier ou de créer des entités. Dans certains cas il peut être utile d’inclure des flux dans les entités. Pour cela, on utilise la balise "block". Par exemple pour créer un produit avec une réduction (specific price).

Exemple de flux produit avec une réduction de 20% ([product-6.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-6.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>19.99</price>
		<block>
			<objects>
				<object external-reference="specific-price-1" type="specificPrice">
					<id_product>{{id}}</id_product>
					<id_group>1</id_group>
					<price>0</price>
					<reduction>0.2</reduction>
					<reduction_type>percentage</reduction_type> <!-- ou amount -->
					<from_quantity>1</from_quantity>
					<id_customer>0</id_customer>
					<id_shop>1</id_shop>
					<id_country>0</id_country>
					<id_currency>0</id_currency>
					<from>0000-00-00</from>
					<to>0000-00-00</to>
				</object>
			</objects>
		</block>
	</product>
</products>

Afin de lier l’entité au bloc, on peut utiliser l'identifiant du produit que l’on marque par {{id}}.

Notez que dans ce cas l’import se fait en deux fois. L’entité est d’abord créée après quoi le bloc est ajouté dans le dossier "queue". Le bloc est traité dans un second temps. Si vous n’avez pas activé le module, il faudra :

1. Importer le flux produit en entrant cette url [http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=](http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=)

2. Exécuter le ou les blocs

3. Importer le flux object en entrant cette url [http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::objects&block=](http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=)

4. Exécuter les blocs créés.

## 4.5. Flux de suppression

Pour supprimer des entités on utilise un flux de type "delete". Il fonctionne de façon similaire au flux “object”. Voici un exemple de suppression d’un client ainsi que d’un groupe ([delete-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/delete-1.xml)) :

<delete>
    <object type="customer" external-reference="demo-1" />
    <object type="group" external-reference="demo-1" />
    <object type="customerGroup" external-reference="demo-1" />
</delete>

# 5. Gestion des prix

Par défault les prix du flux produit sont hors taxe. Il est possible de définir les prix TTC en utilisant le tag price_type ([product-13.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-13.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<categorypath separator="&gt;"><![CDATA[baladeur > baladeur vidéo > accessoire]]></categorypath> <!-- Ajout du produit dans la catégorie baladeur > baladeur vidéo > accessoire -->
		<price_type>ti</price_type> <!-- ti = tax include, te (default) = tax exclude -->
		<price>19.99</price>
	</product>
</products>

Il est aussi possible de définir des champs personnalisés supplémentaires à conciderer comme des taxes  ([product-14.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-14.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<categorypath separator="&gt;"><![CDATA[baladeur > baladeur vidéo > accessoire]]></categorypath> <!-- Ajout du produit dans la catégorie baladeur > baladeur vidéo > accessoire -->
		<tax_fields>custom_tax</tax_fields>
		<price_type>ti</price_type> <!-- ti = tax include, te (default) = tax exclude -->
		<price>19.99</price>
	</product>
</products>

# 6. Gestion des catégories

Il existe plusieurs moyens de créer des catégories. Soit via le flux objet : la catégorie n’est alors rattachée à aucun produit (on utilisera le flux association pour la lier à un produit). Soit via le flux produit : dans ce cas la catégorie sera rattachée au produit.

## 6.1 Création de catégories via le flux object

Création d’une catégorie ([category-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/category-1.xml)) :

<objects>
	<object type="category" external-reference="demo-1">
		<name lang="fr">Nom de la catégorie</name>
		<link_rewrite lang="fr">Nom</link_rewrite>
		<id_parent>2</id_parent>
	</object>
	<object type="category" external-reference="demo-2">
		<name lang="fr">Nom de la catégorie</name>
		<link_rewrite lang="fr">Nom</link_rewrite>
		<id_parent>2</id_parent>
	</object>
</objects>

Création d’une catégorie ayant pour parent la référence externe demo-1 ([category-2.xml](http://prestashopxmlimporter.madef.fr/flows_fr/category-2.xml)) :

<objects>
	<object type="category" external-reference="demo-3">
		<name lang="fr">Nom de la catégorie</name>
		<link_rewrite lang="fr">Nom</link_rewrite>
		<external_reference for="id_parent" type="category">demo-1</external_reference>
	</object>
</objects>

## 6.2 Rattachement de produits aux catégories

Le flux association permet de rattacher des produits (identifiés par leur id, code ean13, référence ou référence externe) à des catégories référencées par son id ou référence externe.

Rattachement du produit ayant pour référence externe demo-1 aux catégory demo-1 et demo-2 ([association-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/association-1.xml)) :

<associations>
	<association external-reference="demo-1">
		<mode>replace</mode>
		<category use-external-reference="1">demo-1</category>
		<category use-external-reference="1">demo-2</category>
	</association>
</associations>

Rattachement du produit ayant pour référence externe demo-1 aux catégory #1 et #2 ([association-2.xml](http://prestashopxmlimporter.madef.fr/flows_fr/association-2.xml)) :

<associations>
	<association external-reference="demo-1">
		<mode>replace</mode>
		<category>1</category>
		<category>2</category>
	</association>
</associations>

Utilisation de l’ean13, référence et id ([association-3.xml](http://prestashopxmlimporter.madef.fr/flows_fr/association-3.xml)) :

<associations>
	<association productid="1">
		<mode>replace</mode>
		<category use-external-reference="1">demo-1</category>
		<category use-external-reference="1">demo-2</category>
	</association>
	<association ean13="ean-1">
		<mode>replace</mode>
		<category use-external-reference="1">demo-1</category>
		<category use-external-reference="1">demo-2</category>
	</association>
	<association reference="reference-1">
		<mode>replace</mode>
		<category use-external-reference="1">demo-1</category>
		<category use-external-reference="1">demo-2</category>
	</association>
</associations>

Le mode permet soit de remplacer toutes les catégories du produit, soit d’en rajouter.

Exemple d’utilisation du mode "ajout" ([association-4.xml](http://prestashopxmlimporter.madef.fr/flows_fr/association-4.xml)) :

<associations>
	<association external-reference="demo-1">
		<mode>add</mode>
		<category use-external-reference="1">demo-3</category>
	</association>
</associations>

## 5.3 Création de catégories dans le flux produit

Le flux produit permet de créer et rattacher des catégories directement. Cela est possible soit en utilisant la balise "categorypath", soit en utilisant les blocs.

La première solution est la plus simple, mais elle est plus limitée, car seul le nom de la catégorie est personnalisable. Si la catégorie existe, alors le produit est simplement rattaché à cette dernière.

Utilisation du categorypath ([product-7.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-7.xml)) :

<products>
	<product external-reference="demo-1" >
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<categorypath separator="&gt;"><![CDATA[baladeur > baladeur vidéo > accessoire]]></categorypath> <!-- Ajout du produit dans la catégorie baladeur > baladeur vidéo > accessoire -->
		<price>19.99</price>
	</product>
</products>

En utilisant les blocs, il faut créer dans un premier temps la catégorie et dans un second temps effectuer l’association. Attention, dans ce cas l’utilisation de référence externe est obligatoire.

Utilisation des blocs pour associer un produit à une catégorie ([product-8.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-8.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>19.99</price>
		<block>
			<objects>
				<object type="category" external-reference="demo-1">
					<name lang="fr">Nom</name>
					<link_rewrite lang="fr">Nom</link_rewrite>
					<id_parent>1</id_parent>
					<block>
						<associations>
							<association productid="{{id}}">
								<mode>add</mode>

                                <category>#{#{id}#}#</category>
							</association>
						</associations>
					</block>
				</object>
			</objects>
		</block>
	</product>
</products>

# 7. Gestion des stocks

Il existe deux moyens de modifier les stocks. Soit via le flux stock soit via le flux objet. Nous ne détaillerons pas ce dernier qui est plus complexe et n’est utile que pour les marchands ayant besoin de fonctionnalités très avancées (gestion d’entrepôt par exemple).

## 7.1 Flux mouvement de stock

Il est possible d’effectuer au choix des mouvements de stock ou une définition du stock

Mouvement de stock ([stock-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/stock-1.xml)) :

<stocks>
	<stock>
		<product use-external-reference="1">product-demo-1</product>
		<mode>delta</mode>
		<quantity>10</quantity>
	</stock>
</stocks>

Définition du stock ([stock-2.xml](http://prestashopxmlimporter.madef.fr/flows_fr/stock-2.xml)) :

<stocks>
	<stock>
		<product use-external-reference="1">product-demo-1</product>
		<mode>set</mode>
		<quantity>10</quantity>
	</stock>
</stocks>

Pour modifier le stock d’une déclinaison, on utilise le tag combination.

Définition du stock pour une déclinaison en utilisant une référence externe ([stock-4.xml](http://prestashopxmlimporter.madef.fr/flows_fr/stock-4.xml)) :

<stocks>
	<stock>
		<product use-external-reference="1">product-demo-1</product>
		<combination use-external-reference="1">combination-1</combination>
		<mode>set</mode>
		<quantity>10</quantity>
	</stock>
</stocks>

Attention : ce flux sera en erreur si la déclinaison "combination-1" n’existe pas (cf 8.2).

## 7.2 Mouvement de stock dans le flux produit

Il est possible de modifier les stocks directement depuis le flux produit en utilisant les [blocs](#heading=h.l41or1f7b3ak).

# 8. Gestion des caractéristiques

Les caractéristiques d’un produit peuvent être importées via le flux objet ou plus simplement depuis le flux produit.

## 8.1 Via le flux objet


Création d’une caractéristique et de valeurs ([object-3.xml](http://prestashopxmlimporter.madef.fr/flows_fr/object-3.xml)) :

<objects>
	<object type="feature" external-reference="feature-test">
		<name lang="fr">Caractéristique de test</name>
	</object>
	<object type="featureValue" external-reference="feature-value-test">
		<value lang="fr">Valeur de test</value>
		<external_reference for="id_feature" type="feature">feature-test</external_reference>
	</object>
</objects>

## 8.2 Via le flux produit

Il existe deux moyens de créer des caractéristiques depuis le flux produit, soit en utilisant le tag "feature" soit en utilisant les blocs. Nous ne détaillerons pas cette seconde méthode.

Le tag "feature", peut travailler avec des valeurs, des références externes ou des id. Dans le premier cas, si les caractéristiques ou les valeurs n’existent pas alors elles sont créées.

Création de caractéristiques ([product-9.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-9.xml)) :

<products>
	<product external-reference="demo-1" >
		<name lang="fr">Nom</name>
		<feature external-reference="feature-test" external-reference-value="feature-value-test" /> <!-- Ajout utilisant les références externes (cf "Flux objet") -->
		<feature id="3" id-value="16" /> <!-- Ajout utilisant les IDs -->
		<feature name="Prise casque" name-value="Jack stéréo"/> <!-- Ajout utilisant les noms. Si les valeurs n'existent pas, elles sont alors créées. -->
		<feature name="Test" name-value="Test Value" custom="1"/> <!-- Ajout utilisant une valeur personnalisée (déconseillé) -->
	</product>
</products>

# 9. Gestion des combinaisons ou déclinaisons

Une déclinaison est une version alternative du produit avec des attributs différents. Cela peut être par exemple des produits de différentes couleurs.

Une déclinaison est composée d’un ou plusieurs attributs. Chaque attribut appartient à un groupe d’attributs. Une déclinaison possède au maximum un attribut par groupe d’attributs.

## 9.1 Création d'attributs et de groupe d’attributs via le flux objet

La création de nouveaux attributs se fait via le flux objet.

Création d’attributs et de valeur d’attributs ([attribute-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/attribute-1.xml)) :

<objects>
	<object type="attributeGroup" external-reference="demo-recto-color">
		<is_color_group>1</is_color_group>
		<group_type>color</group_type>
		<name lang="fr">Couleur du recto</name>
		<public_name lang="fr">Couleur du recto</public_name>
	</object>
	<object type="attribute" external-reference="demo-recto-color-gray">
		<external_reference for="id_attribute_group" type="attributeGroup">demo-recto-color</external_reference>
		<color>#AAB2BD</color>
		<name lang="fr">Gris</name>
	</object>
	<object type="attribute" external-reference="demo-recto-color-blue">
		<external_reference for="id_attribute_group" type="attributeGroup">demo-recto-color</external_reference>
		<color>#5D9CEC</color>
		<name lang="fr">Bleu</name>
	</object>
	<object type="attribute" external-reference="demo-recto-color-rouge">
		<external_reference for="id_attribute_group" type="attributeGroup">demo-recto-color</external_reference>
		<color>#E84C3D</color>
		<name lang="fr">Rouge</name>
	</object>
</objects>

## 9.2 Création de déclinaisons depuis le flux produit

Création ou modification de deux déclinaisons ([product-10.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-10.xml)) :

<products>
	<product external-reference="product-demo-1" >
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>19.99</price>
		<images>
			<url>/modules/advancedimporter/img/media/01.jpg</url> <!-- depuis le disque locale -->
		</images>
		<combinations external-reference="combination-1">
			<price>10.5</price>
			<unit_price_impact>0</unit_price_impact>
			<images>1</images>
			<attributes use-external-reference="1">demo-recto-color-blue</attributes>
		</combinations>
		<combinations external-reference="combination-2">
			<price>11.5</price>
			<unit_price_impact>0</unit_price_impact>
			<images>1</images>
			<attributes use-external-reference="1">demo-recto-color-red</attributes>
		</combinations>
	</product>
</products>

## 9.3 Création d’attributs et de déclinaisons dans le flux produit

Afin de créer les attributs et leur valeurs dans le flux produit, on utilise les [blocs](#heading=h.l41or1f7b3ak) ([product-11.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-11.xml)) :

<products>
	<product external-reference="product-demo-1">
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>19.99</price>
		<images>
			<url>/modules/advancedimporter/img/media/01.jpg</url> <!-- depuis le disque locale -->
		</images>
		<block>
			<objects>
				<object type="attributeGroup" external-reference="demo-recto-color">
					<is_color_group>1</is_color_group>
					<group_type>color</group_type>
					<name lang="fr">Couleur du recto</name>
					<public_name lang="fr">Couleur du recto</public_name>
				</object>
				<object type="attribute" external-reference="demo-recto-color-gray">
					<external_reference for="id_attribute_group" type="attributeGroup">demo-recto-color</external_reference>
					<color>#AAB2BD</color>
					<name lang="fr">Gris</name>
				</object>
				<object type="attribute" external-reference="demo-recto-color-blue">
					<external_reference for="id_attribute_group" type="attributeGroup">demo-recto-color</external_reference>
					<color>#5D9CEC</color>
					<name lang="fr">Bleu</name>
				</object>
				<object type="attribute" external-reference="demo-recto-color-red">
					<external_reference for="id_attribute_group" type="attributeGroup">demo-recto-color</external_reference>
					<color>#E84C3D</color>
					<name lang="fr">Rouge</name>
					<block>
						<products>
							<product>
								<id>{{id}}</id>
								<combinations external-reference="combination-1">
									<price>10.5</price>
									<unit_price_impact>0</unit_price_impact>
									<images>1</images>
									<attributes use-external-reference="1">demo-recto-color-blue</attributes>
								</combinations>
								<combinations external-reference="combination-2">
									<price>11.5</price>
									<unit_price_impact>0</unit_price_impact>
									<images>1</images>
									<attributes use-external-reference="1">demo-recto-color-red</attributes>
								</combinations>
							</product>
						</products>
					</block>
				</object>
			</objects>
		</block>
	</product>
</products>

# 10. Gestion des prix spécifiques

Les prix spécifiques peuvent être créés en utilisant le flux objet ([specialprice-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/specialprice-1.xml)) :

<objects>
	<object type="specificPrice" external-reference="demo-1">
		<external_reference for="id_product" type="product">demo-1</external_reference>
		<id_group>1</id_group>
		<price>0</price>
		<reduction>0.2</reduction>
		<reduction_type>percentage</reduction_type> <!-- ou amount -->
		<from_quantity>1</from_quantity>
		<id_customer>0</id_customer>
		<id_shop>1</id_shop>
		<id_country>0</id_country>
		<id_currency>0</id_currency>
		<from>0000-00-00</from>
		<to>0000-00-00</to>
	</object>
</objects>

Exemple de flux produit avec une réduction de 20% ([product-12.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-12.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>19.99</price>
		<block>
			<objects>
				<object type="specificPrice">
					<id_product>{{id}}</id_product>
					<id_group>1</id_group>
					<price>0</price>
					<reduction>0.2</reduction>
					<reduction_type>percentage</reduction_type> <!-- ou amount -->
					<from_quantity>1</from_quantity>
					<id_customer>0</id_customer>
					<id_shop>1</id_shop>
					<id_country>0</id_country>
					<id_currency>0</id_currency>
					<from>0000-00-00</from>
					<to>0000-00-00</to>
				</object>
			</objects>
		</block>
	</product>
</products>

# 10. Importation de clients

L’import de clients ce fait au moyen du flux objet.

Voici un exemple de flux clients ([customer-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/customer-1.xml)) :

<objects>
	<object type="customer" external-reference="demo-1">
		<lastname>Lastname</lastname>
		<firstname>Firstname</firstname>
		<email>test@domain.tld</email>
		<passwd modifier="Tools::encrypt">myPassword</passwd>
	</object>
</objects>

Il est possible de définir les groupes du clients via des blocs. Voici un exemple avec création d’un groupe ([customer-2.xml](http://prestashopxmlimporter.madef.fr/flows_fr/customer-2.xml)) :

<objects>
	<object type="customer" external-reference="demo-1">
		<lastname>Lastname</lastname>
		<firstname>Firstname</firstname>
		<email>test@domain.tld</email>
		<passwd modifier="Tools::encrypt">myPassword</passwd>
		<block>
			<objects>
				<object type="group" external-reference="demo-1">
					<name lang="fr">My Group</name>
					<price_display_method>1</price_display_method> <!-- Taxes incluse -->
					<block>
						<objects>
							<object type="customerGroup" external-reference="demo-1">
								<id_customer>{{id}}</id_customer>
								<id_group>\{\{id\}\}</id_group>
							</object>
						</objects>
					</block>
				</object>
			</objects>
		</block>
	</object>
</objects>

# 12. Multiboutique

PrestaShop XML Importer est compatible avec la multiboutique.

Pour cela, il suffit de préciser la boutique ou les boutiques dans les flux.

Ajout d'un produit dans les boutiques 1 et 3 ([multishop-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/multishop-1.xml)) :

<products>
	<product external-reference="demo-3">
		<shop>1</shop>
		<shop>3</shop>
		<reference>ref01</reference>
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>19.99</price>
	</product>
</products>

**Attention, si la référence externe n’est pas précisée, le produit sera créé deux fois (une fois par boutique).**

Ajout d'un produit dans les boutiques 1 et 2. Puis modification du titre dans la boutique 1 ([multishop-2.xml](http://prestashopxmlimporter.madef.fr/flows_fr/multishop-2.xml)) :

<products>
	<product external-reference="demo-4">
		<shop>1</shop>
		<shop>2</shop>
		<name lang="fr">Nom</name>
		<description lang="fr">Description du produit</description>
		<price>19.99</price>
	</product>
	<product external-reference="demo-4">
		<shop>2</shop>
		<reference>ref01</reference>
		<name lang="fr">Nouveau nom</name>
	</product>
</products>

# 13. Flux CSV

L’import des flux CSV se fait de manière similaire aux flux xml. Les flux CSV ont besoin d’être traduit en flux XML pour être importé. Pour ce faire, il faut ajouter une tâche récurrente.

Prenons l’exemple du flux suivant :

Reference Externe,Reference,Nom,Description,Categorie parente,Sous catégorie,Prix,Stock,Ean13,Image

ref-1,ref-1,"Produit 1","Description 1","Baladeur","Ipod",150, 12,8412457989784,[http](http://prestashopxmlimporter.madef.fr/img/demo.jpg)[://prestashopxmlimporter.madef.fr/img/demo.jpg](http://prestashopxmlimporter.madef.fr/img/demo.jpg)

ref-2,ref-2,"Produit 2","Description 2","Baladeur","Accessoire",20, 5,8412457989785,[http](http://prestashopxmlimporter.madef.fr/img/demo.jpg)[://prestashopxmlimporter.madef.fr/img/demo.jpg](http://prestashopxmlimporter.madef.fr/img/demo.jpg)

Dans PrestaShop XML Importer > Tâche récurrente, créé une nouvelle "Tâche récurrente".

Renseignez le formulaire suivant avec les données suivantes :

<table>
  <tr>
    <td>Description</td>
    <td>Product CSV</td>
  </tr>
  <tr>
    <td>Cron time</td>
    <td>*/5 * * * *</td>
  </tr>
  <tr>
    <td>Callback</td>
    <td>CsvConverter::convert</td>
  </tr>
  <tr>
    <td>Bloc</td>
    <td>{
  "filepath": "product*.csv",
  "roottag": "products",
  "ignoreFirstLine": 1,
  "delimiter": ",",
  "enclosure": "\"",
  "escape": "\\",
  "template":"<product external-reference=\"{{0}}\"><reference><![CDATA[{{1}}]]></reference><name lang=\"fr\"><![CDATA[{{2}}]]></name><description lang=\"fr\"><![CDATA[{{3}}]]></description><categorypath><![CDATA[{{4}}]]>&gt;<![CDATA[{{5}}]]></categorypath><price>{{6}}</price><block><stocks><stock><product>{{id}}</product><mode>set</mode><quantity>{{7}}</quantity></stock></stocks></block><ean13>{{8}}</ean13><images><url>{{9}}</url></images></product>"
}
</td>
  </tr>
  <tr>
    <td>Station</td>
    <td>1</td>
  </tr>
  <tr>
    <td>Id boutique</td>
    <td>1</td>
  </tr>
</table>


Le champ bloc, est un json. Il attend les paramètres suivants :

<table>
  <tr>
    <td>Attribut</td>
    <td>Type</td>
    <td>Description</td>
  </tr>
  <tr>
    <td>filepath</td>
    <td>string</td>
    <td>Format du nom du fichier. Par exemple product*.csv pour le fichier commencant par product et finissant par .csv</td>
  </tr>
  <tr>
    <td>roottag</td>
    <td>string</td>
    <td>Nom du tag de base. Par exemple : products, objects, ...</td>
  </tr>
  <tr>
    <td>ignoreFirstLine</td>
    <td>int</td>
    <td>Ignore la première ligne du CSV</td>
  </tr>
  <tr>
    <td>delimiter</td>
    <td>char</td>
    <td>Caractère de séparation des colonnes </td>
  </tr>
  <tr>
    <td>enclosure</td>
    <td>char</td>
    <td>Caractère de limitations des chaines de caratères</td>
  </tr>
  <tr>
    <td>encoding</td>
    <td>string</td>
    <td>Encodage du fichier CSV</td>
  </tr>
  <tr>
    <td>escape</td>
    <td>char</td>
    <td>Caractère d’échapement</td>
  </tr>
  <tr>
    <td>template</td>
    <td>string</td>
    <td>Template répété pour chaque ligne du CSV. {{0}} sare remplacé par la valeur de la première colonne, {{1}} par la valeur de la seconde, ... </td>
  </tr>
</table>


# 14. Adaptation des XML au format du module (XSLT)

Les XSLT permettent de traduire n’importe quel format de document XML dans un autre format et en particulier au format supporté par le module.

## 14.1 Création via l’outil en ligne

Afin de faciliter l’écriture de XSLT, un outil gratuit en ligne existe : [http://prestashopxmlimporter.madef.fr/xslt.php](http://prestashopxmlimporter.madef.fr/xslt.php)

Cet outil permet de créer des XSLT pour le flux produit sans connaissances particulières. Il est par contre nécessaire de connaitre un minimum le format XML ainsi que les attributs des produits PrestaShop.

## 14.2 Exemples d’utilisation des XSLT

 Exemple avec deux prix spécifiques et stock ([xslt-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/xslt-1.xml)) :

<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet  [

	<!ENTITY nbsp   " ">
	<!ENTITY copy   "©">
	<!ENTITY reg	"®">
	<!ENTITY trade  "™">
	<!ENTITY mdash  "—">
	<!ENTITY ldquo  """>
	<!ENTITY rdquo  """>
	<!ENTITY pound  "£">
	<!ENTITY yen	"¥">
	<!ENTITY euro   "€">

]>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="xml" encoding="utf-8" indent="yes"/>
	<xsl:variable name="cdataStart"><![CDATA[ <![CDATA ]]></xsl:variable>
	<xsl:variable name="cdataEnd"><![CDATA[ ]] ]]></xsl:variable>
	<xsl:template match="/VFPData">
		<xsl:comment> XSLT Generated by http://prestashopxmlimporter.madef.fr/xslt.php the 2014-06-04 10:34 </xsl:comment>
		<products>
			<xsl:for-each select="mercator_stock">
				<product>
					<xsl:if test="./s_modele">
						<name lang="fr"><xsl:value-of select="./s_modele" /></name>
					</xsl:if>
					<xsl:if test="./s_prix_ti">
						<price><xsl:value-of select="./s_prix_ti" /></price>
					</xsl:if>
					<xsl:if test="./s_recupel">
						<recupel><xsl:value-of select="./s_recupel" /></recupel>
					</xsl:if>
					<xsl:if test="./s_auvibel">
						<auvibel><xsl:value-of select="./s_auvibel" /></auvibel>
					</xsl:if>
					<xsl:if test="./s_bebat">
						<bebat><xsl:value-of select="./s_bebat" /></bebat>
					</xsl:if>

					  <xsl:if test="./s_cle1">
						<reference><xsl:value-of select="./s_cle1" /></reference>
					</xsl:if>
					<xsl:if test="./s_cle2">
						<ean13><xsl:value-of select="./s_cle2" /></ean13>
					</xsl:if>
					<xsl:if test="./s_sommeil">
						<active><xsl:value-of select="./s_sommeil" /></active>
					</xsl:if>
					<block>
						<xsl:if test="./s_qdispo">
							<stocks>
								<stock>
								<product>{{id}}</product>
								<mode>set</mode>
								<quantity><xsl:value-of select="./s_qdispo" /></quantity>
								</stock>
							</stocks>
						</xsl:if>		 
						<xsl:if test="./s_tarht_1">
							<objects>
								<object type="specificPrice">
								<xsl:attribute name="external-reference">{{id}}-1</xsl:attribute>
								<id_product>{{id}}</id_product>
								<id_group>4</id_group>
								<id_customer>0</id_customer>
								<id_shop>1</id_shop>
								<id_country>0</id_country>
								<id_currency>1</id_currency>
								<from>0000-00-00</from>
								<to>0000-00-00</to>
								<price><xsl:value-of select="./s_tarht_1" /></price>
								<reduction>0</reduction>
								<reduction_type>amount</reduction_type>
								<from_quantity>1</from_quantity>
								</object>
							</objects>
						</xsl:if>
						<xsl:if test="./s_tarht_2">
							<objects>
								<object type="specificPrice">
								<xsl:attribute name="external-reference">{{id}}-2</xsl:attribute>
								<id_product>{{id}}</id_product>
								<id_group>5</id_group>
								<id_customer>0</id_customer>
								<id_shop>1</id_shop>
								<id_country>0</id_country>
								<id_currency>1</id_currency>
								<from>0000-00-00</from>
								<to>0000-00-00</to>
								<price><xsl:value-of select="./s_tarht_2" /></price>
								<reduction>0</reduction>
								<reduction_type>amount</reduction_type>
								<from_quantity>1</from_quantity>
								</object>
							</objects>
						</xsl:if>
					</block>
				</product>
			</xsl:for-each>
		</products>
	</xsl:template>
</xsl:stylesheet>

# 15. Anomalies

Si le module ne fonctionne pas correctement alors pensez à vérifier les cas suivants.

**Le cron semble fonctionner mais aucun bloc n’est créé**

Pensez à vérifier la version de votre PHP. Le module requière PHP 5.3 et supérieur.

**Des blocs sont créés mais jamais exécutés**

Ce problème arrive lorsque le fichier "lock" ne peut être supprimé. Pour cela, supprimez le fichier module/advancedimporter/lock/1.lock et assurez-vous que tout le monde peut écrire dans le dossier module/advancedimporter/lock.

**Des blocs concernant des produits sont traités mais ne se terminent jamais**

Ce cas peut arriver lorsque l’exécution des blocs se termine de façon inattendue et que l’erreur n’a pu être interprétée par le module. Dans ce cas, passer le premier flux d’exemple produit ([product-1.xml](http://prestashopxmlimporter.madef.fr/flows_fr/product-1.xml)). Pensez bien à remplacer la langue par celle de defaut de votre boutique.

 

# 16. Téléchargement

Le module est disponible sur le [site d'addons de PrestaShop](http://addons.prestashop.com/fr/edition-rapide-modules-prestashop/7951-massive-customizable-xml-importer.html).

