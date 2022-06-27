# 1. The Import module based on those used by large accounts (introduction)

PrestaShop XML Importer, is able to support flows containing thousands of entities (products, customers, stock movements, …).

This module can be used by small retailers as well as by large accounts, it is built to be robust, quick and easy to use. 

# 2. Installation

To install the module, it is necessary to use the PrestaShop interface created for this purpose: In the back office under the tab "module", click on “Add a new module”.

A form appears:

 

![image alt text](image_0.png)

Choose the zip archive of the module then click on the button: "Change the module"

A message will inform you that the module is downloaded. 

To search the modules, search "importer" then click on the button “install”.

![image alt text](image_1.png)

In the menu, a new entry will appear: 

![image alt text](image_2.png)

The module is now installed. We just need to activate it now.

**The ****activation can only be done from your processing environment.**

If you have a preprocessing  environment, you can access from an external environment, contact us with the PrestaShop Addons form.

The module will function in a degraded mode from a local environment. This mode is sufficient to carry out tests but recurring tasks cannot be done. 

To activate the module go to "Configuration" in the menu. Fill in the field “Order Reference” with the order number and active the api “smart cron”.

![image alt text](image_3.png)

# 3. Importing flows

The importation of flows can be done three different ways:

* Via the administration panel 
* Via FTP or SSH
* Via a planned task

In the different examples whatever the chosen method, we will use the following flow ([product-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-1.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>20</price>
		<tax>FR Taux réduit (5.5%)</tax>
	</product>
</products>

## 3.1 Importing a flow via the back office

The importation of the back office will be done via the tab PrestaShop XML Importer > Upload

![image alt text](image_4.png)

Download [product-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-1.xml) on your computer and upload it via the form.

If your module has been activated, wait a few minutes, the flow is now listed under the tab PrestaShop XML Importer > Flow.

![image alt text](image_5.png)

If you have not activated the module (in case you have done tests on a local machine for instance, it is necessary to simulate the normal operating mode.

To do this, type the url hereunder in your web browser: [http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=](http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=)

"localhost" and “prestashop” of the url are to be adapted to your configuration. [http://localhost/prestashop/](http://localhost/prestashop/) is the url of your shop’s home page. 

Remember to replace the "products" of the Flows Importer products by the type of flow to import: associations, objects, products or stocks.

The flow now figures in the list under the tab PrestaShop XML Importer > Flow.

In PrestaShop XML Importer > Blocks, two new lines appear:

![image alt text](image_6.png)

The block #2 is now pending execution (result = 0). It will be executed automatically if you have activated the module. 

Otherwise, it will be necessary to execute it manually. To do so, choose (in the combobox on the right) the option "Execute the block" (icon play) :

![image alt text](image_7.png)

The product is now imported.

## 3.2 Importation via FTP or SSH

It is possible to place the flow on the server directly by placing them in the file queue of the module (/modules/advancedimporter/flows/import/queue/).

**Beware, it is necessary in that case to create a file named UPLOADED after having uploaded the document in the same file.** That way, it will prevent the file to be processed before the end of the importation. 

So test it with [the sample file](http://prestashopxmlimporter.madef.fr/flows_en/product-1.xml).

If your module has been activated, wait a few minutes, the flow now figures in the list under the tab PrestaShop XML Importer > Flow.

![image alt text](image_8.png)

If you have not activated the module (in the case when you are making tests on a local machine for instance), it is necessary to simulate the normal operating mode. 


To do so, type in your web browser the following url: [http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=](http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=)

The urls "localhost" and “prestashop” are to be adapted to your configuration. http://localhost/prestashop/ should be the url of your shop’s home page. 

Remember to replace the "products" of Flows Importer: products by the type of flow to import: associations, objects, products or stocks.

The flow is now listed under the tab PrestaShop XML Importer > Flow.

Under PrestaShop XML Importer > Blocks, two new lines appear:

![image alt text](image_9.png)

The block #2 is now pending execution (result = 0). It will automatically be executed if you have activated the module.

Otherwise, it will be necessary to execute it manually. To do so, choose (in the combo box on the right) the option "Execute the block" (icon play):

![image alt text](image_10.png)

The product is now uploaded.



## 3.3 Importation via a planned task

PrestaShop XML Importer is able to manage planned tasks. 

To do this, go to the tab PrestaShop XML Importer > recurring task.

This screenshot lists all the recurring tasks as well as the flow processing tasks.  

This is where can be added one or several importers. 

Click on the button, "Add an importer". 

In the form, fill in the following fields:

* Description : Test importer
* Cron Time : 0 1 * * *
* Url : http://prestashopxmlimporter.madef.fr/example/product01.xml
* Station : 1
* Id shop : 1

The notion of station will not be detailed in this documentation. Please always put the number "1".

The field "cron time", uses the same syntax as the software cron. You may find [more details on wikipedia](http://fr.wikipedia.org/wiki/Crontab#Syntaxe_de_la_table).

In this example, the flow is set to be imported daily at 1 am.

Save the recurring task. 

The flow will be imported at 1 am. To check that this works, it is possible to force things. 

In the new line of the recurring tasks, choose the option "add in the blocks" (the icon “play”).

![image alt text](image_11.png)

Go in the block list: PrestaShop XML Importer > Blocs.

This screenshot lists all the plannified tasks.

Our importer figures in the first line of the list. We can cheat even more by choosing the option "Execute the block". If you have activated the module, this action is not necessary.

Another line appears in the lists of the blocks. If it is executed the same way as before, the product will be imported (this action is not necessary if the module has not been activated). 

# 4. Introduction to the flow XML

The module integrates by default, four types of flow XML : [the flow products](#heading=h.y1tyeapvav9l), [stock](#heading=h.x1jopc4me6iq), [attachment to categories](#heading=h.5vys1ry28lcu) and [object](#heading=h.6grw0n5nkrrt). If you have knowledge on PHP, it is possible to add others thanks to the creation of new importers.

The module can import almost any flow format without coding even one line. To do this, it is necessary to create a [XSLT](#heading=h.9y1gyn3t8mrd) format. 

## 4.1 Flow product

The flow product is the flow the most complete. The major part of this document concerns this flow. Even though other flows are used for the stock management and the attachment to categories, it is possible to use directly the flow product without using this one. 

First, we will show you some basic examples. 

Do not forget that if you have not activated this module, import the flow by typing the url [http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=](http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=) and execute the block of importation of the product as specified in the paragraph 3.1 and 3.2.

Please find an example of the development of the most basic product. Do not forget to change the language to the one used in your shop ([product-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-1.xml)).

<products>
	<product external-reference="demo-1">
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>20</price>
		<tax>FR Taux réduit (5.5%)</tax>
	</product>
</products>

If a product has already been created, consequently any flow using the same external reference, id, ean13 or reference will modify the product instead of creating a new one ([product-2.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-2.xml)).

<products>
	<product>
		<id>12</id>
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>19.99</price>
	</product>
	<product>
		<reference>demo_1</reference>
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
	<price>19.99</price>
	</product>
	<product>
		<ean13>1111111111111</ean13>
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>19.99</price>
	</product>
	<product external-reference="product-demo-1" >
		<name lang="en">Name</name>
	</product>
</products>

It is recommended to use the external reference to be identified by the exterior. 

It is possible to import pictures either via HTTP, or from the server ([product-3.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-3.xml)) :

<products>
	<product external-reference="product-demo-1" >
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<images>
			<url>/modules/advancedimporter/img/media/01.jpg</url> <!-- from local storage -->
			<url>http://prestashopxmlimporter.madef.fr/img/demo.jpg</url> <!-- from a server -->
		</images>
	</product>
</products>

It is possible to define taxes, either by using the wording of the rule, or by using its id ([product-4.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-4.xml)) :

<products>
	<product external-reference="product-demo-1" >
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>19.99</price>
		<tax>FR Taux réduit (5.5%)</tax>
	</product>
	<product external-reference="product-demo-2" >
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>19.99</price>
		<id_tax_rules_group>1</id_tax_rules_group>
	</product>
</products>

**Beware: it is not possible to define directly the tax rate.**

The flow produced can modify the following attributes of a product: ([product-attributes.yaml](http://prestashopxmlimporter.madef.fr/flows_en/product-attributes.yaml)) :

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

In all these examples, were defined an attribute "external-reference". This is not necessary but highly recommended, it enables to make a link between the external environment (the flow XML) and your shop. [Click here for further information on the external references](#heading=h.gq7fqypth5w3).

## 4.2 Flow object

The flow object allows the importation of any type of PrestaShop object. These enable the integration of several types of objects in one same file. 

Don’t forget that if you have not activated the module, in order to upload the flow you must type the url [http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::objects&block=](http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=) and execute the import block of the product as specified in the section 3.1 and 3.2.

Importation of a product and a category ([object-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/object-1.xml)) :


<objects>
	<object type="category" external-reference="demo-1">
		<name lang="en">Category name</name>
		<link_rewrite lang="en">Name</link_rewrite>
	</object>
	<object type="product" external-reference="demo-1">
		<name lang="en">Product name</name>
		<link_rewrite lang="en">Name</link_rewrite>
	</object>
</objects>

You can find all the attributes which can be modified in the file [attributes.yaml](http://prestashopxmlimporter.madef.fr/attributes.yaml).

## 4.3 External Reference 

The external references enable to make a link between the flow (external environment) and your shop. The reference can be used in the flow object and product. It is used with the addition of an attribute **external-reference**. The references are unique for each type of entity. Consequently, it is possible to obtain a category and a product with the same external reference without any clashes (cf the precedent example).

Example of the use of external references ([object-2.xml](http://prestashopxmlimporter.madef.fr/flows_en/object-2.xml)) :


<objects>
	<object type="taxRulesGroup" external-reference="tax-1">
		<name>Tax rule goup name</name>
	</object>
</objects>

In addition to the fact of allowing the identification of each entity with the purpose of updating them, it is also possible to refer to them for some of their attributes. Imagine for instance, that you wish to import a product by specifying the tax via its external reference.

Definition of a tax via the external references ([product-5.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-5.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>20</price>
		<external_reference for="id_tax_rules_group" type="taxRulesGroup">tax-1</external_reference>
	</product>
</products>

Another example, let’s imagine that you wish to create an attribute with values.

Creation of an attribute and values ([object-3.xml](http://prestashopxmlimporter.madef.fr/flows_en/object-3.xml)) :

<objects>
	<object type="feature" external-reference="feature-test">
		<name lang="en">Test feature</name>
	</object>
	<object type="featureValue" external-reference="feature-value-test">
		<value lang="en">Test value</value>
		<external_reference for="id_feature" type="feature">feature-test</external_reference>
	</object>
</objects>

## 4.4 Notion of block

The flow product and object enables the modification or the creation of entities. In some cases, it can be useful to include the flows in the entities. To do this, the tag "block" is used to create for instance a product with a discount (specific price).

Example of a flow product with a 20% discount ([product-6.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-6.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>19.99</price>
		<block>
			<objects>
				<object external-reference="specific-price-1" type="specificPrice">
					<id_product>{{id}}</id_product>
					<id_group>1</id_group>
					<price>0</price>
					<reduction>0.2</reduction>
					<reduction_type>percentage</reduction_type> <!-- or amount -->
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

In order to link the entity to the block, we can use the ID of the product that can be named {{id}}.

Note that in this case, this import needs two different manipulations. The entity is first created after the block is added in the file "queue". Then it is processed. If you have not activated the module, it will be necessary to: 

1. Import the flow product by typing this url [http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=](http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=)

2. Execute the or the blocks

3. Import the flow object by typing this url [http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::objects&block=](http://localhost/prestashop/modules/advancedimporter/demo.php?callback=FlowsImporter::products&block=)

4. Execute the created blocks.

## 4.5 Flow delete

To remove entities, we usea flow of type "delete". It’s similar to the flow “object”. 

Example of removing a customer and a group ([delete-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/delete-1.xml)) :

<delete>
    <object type="customer" external-reference="demo-1" />
    <object type="group" external-reference="demo-1" />
    <object type="customerGroup" external-reference="demo-1" />
</delete>

# 5. Gestion des prix

By default the price of the products are tax exclude. It's poossible to define the price as tax inclide by using the tag price_type ([product-13.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-13.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<categorypath separator="&gt;"><![CDATA[cat1 > cat2 > cat3]]></categorypath> <!-- Add product in category cat1 > cat2 > cat3 -->
		<price_type>ti</price_type> <!-- ti = tax include, te (default) = tax exclude -->
		<price>19.99</price>
	</product>
</products>

It's also possible to define customs fields as tax fields ([product-14.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-14.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<<categorypath separator="&gt;"><![CDATA[cat1 > cat2 > cat3]]></categorypath> <!-- Add product in category cat1 > cat2 > cat3 -->
		<tax_fields>custom_tax</tax_fields>
		<price_type>ti</price_type> <!-- ti = tax include, te (default) = tax exclude -->
		<price>19.99</price>
	</product>
</products>


# 6. Management of categories

There are different ways to create categories. Either via the flow object: The category in this case is not linked to another product (the flow association will be used to link it to a product.) Or, via the flow product: in this case, the category will be linked to the product. 

## 6.1 Creation of categories via the flow object

Creation of a category ([category-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/category-1.xml)) :

<objects>
	<object type="category" external-reference="demo-1">
		<name lang="en">Name of the category</name>
		<link_rewrite lang="en">Name</link_rewrite>
		<id_parent>2</id_parent>
	</object>
	<object type="category" external-reference="demo-2">
		<name lang="en">Name of the category</name>
		<link_rewrite lang="en">Name</link_rewrite>
		<id_parent>2</id_parent>
	</object>
</objects>

Creation of a category with the parent file external reference demo-1 ([category-2.xml](http://prestashopxmlimporter.madef.fr/flows_en/category-2.xml)):

<objects>
	<object type="category" external-reference="demo-3">
		<name lang="en">Name of the category</name>
		<link_rewrite lang="en">Name</link_rewrite>
		<external_reference for="id_parent" type="category">demo-1</external_reference>
	</object>
</objects>

## 6.2 The attachment of products to categories

The flow association enables to link products (identified by their id, code ean13, reference or external reference) to categories named by their id or external reference.

The attachment of a product with the external reference demo-1 to the category demo-1 and demo-2 ([association-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/association-1.xml)) :

<associations>
	<association external-reference="demo-1">
		<mode>replace</mode>
		<category use-external-reference="1">demo-1</category>
		<category use-external-reference="1">demo-2</category>
	</association>
</associations>

The attachment of a product with the external reference demo-1 to the category #1 and #2 ([association-2.xml](http://prestashopxmlimporter.madef.fr/flows_en/association-2.xml)) :

<associations>
	<association external-reference="demo-1">
		<mode>replace</mode>
		<category>1</category>
		<category>2</category>
	</association>
</associations>

The use of the ean13, reference and id ([association-3.xml](http://prestashopxmlimporter.madef.fr/flows_en/association-3.xml)) :

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

The mode enables to either replace all the categories of the product or to add one.

An example of the use of the "addition" mode ([association-4.xml](http://prestashopxmlimporter.madef.fr/flows_en/association-4.xml)) :

<associations>
	<association external-reference="demo-1">
		<mode>add</mode>
		<category use-external-reference="1">demo-3</category>
	</association>
</associations>

## 6.3 Creation of categories in the flow product

The flow product enables to create and attach categories directly. This is possible either by using the tab "categorypath" or by using the blocks.

The first solution is the most simple but also the most limited, as only the name of the category can be personified. If the category exists, then the product is simply attached to this same category. 

Using the categorypath ([product-7.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-7.xml)) :

<products>
	<product external-reference="demo-1" >
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<categorypath separator="&gt;"><![CDATA[cat1 > cat2 > cat3]]></categorypath> <!-- Add product in category cat1 > cat2 > cat3 -->
		<price>19.99</price>
	</product>
</products>

With the use of blocks, it is necessary to create first a category and secondly to make the attachment. Beware, in this case the use of the external reference is mandatory. 

The use of blocks to associate a product to a category ([product-8.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-8.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>19.99</price>
		<block>
			<objects>
				<object type="category" external-reference="demo-1">
					<name lang="en">Name</name>
					<link_rewrite lang="en">Name</link_rewrite>
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

# 7. Stock Management

There are two ways to modify the stocks. Either via the stock flow or the flow object. We will not detail the second method as it is more difficult and can only be used by the sellers which need very advanced functionalities (warehouse management for instance).

## 7.1 Stock flow movement

It is possible to choose the stock movement of the stock definition

Stock Movement ([stock-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/stock-1.xml)) :

<stocks>
	<stock>
		<product use-external-reference="1">product-demo-1</product>
		<mode>delta</mode>
		<quantity>10</quantity>
	</stock>
</stocks>

The stock definition ([stock-2.xml](http://prestashopxmlimporter.madef.fr/flows_en/stock-2.xml)) :

<stocks>
	<stock>
		<product use-external-reference="1">product-demo-1</product>
		<mode>set</mode>
		<quantity>10</quantity>
	</stock>
</stocks>

To modify the stock of a variant, use the tag combination.

Definition of the stock for the variant by using the external reference ([stock-4.xml](http://prestashopxmlimporter.madef.fr/flows_en/stock-4.xml)) :

<stocks>
	<stock>
		<product use-external-reference="1">product-demo-1</product>
		<combination use-external-reference="1">combination-1</combination>
		<mode>set</mode>
		<quantity>10</quantity>
	</stock>
</stocks>

Beware : This flow will show as an error if the variant "combination-1" does not exist (cf 8.2).

## 7.2 Stock movement in the flow product

It is possible to modify the stocks directly from the flow product by using the [blocks](#heading=h.l41or1f7b3ak).

# 8. Management of features

The features of a product can be imported via the flow object or simply from the flow product. 

## 8.1 Via the flow object


Creation of a feature and values ([object-3.xml](http://prestashopxmlimporter.madef.fr/flows_en/object-3.xml)) :

<objects>
	<object type="feature" external-reference="feature-test">
		<name lang="en">Test category</name>
	</object>
	<object type="featureValue" external-reference="feature-value-test">
		<value lang="en">Test value</value>
		<external_reference for="id_feature" type="feature">feature-test</external_reference>
	</object>
</objects>

## 8.2 Via the flux product

There are two ways to create attributes from the flow product, either by using the tag "feature" or by using the blocks. The second method will not be detailed.   

The tag "feature" can be used with values, external references or some ids. In the first case, if the features or the values don’t exist then they will be created.

Creation of features ([product-9.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-9.xml)) :

<products>
	<product external-reference="demo-1" >
		<name lang="en">Name</name>
		<feature external-reference="feature-test" external-reference-value="feature-value-test" /> <!-- Add using external reference (cf "Flow object") -->
		<feature id="3" id-value="16" /> <!-- Add using ids -->
		<feature name="Prise casque" name-value="Jack stéréo"/> <!-- Add using names. If value do not exists, it will be create. -->
		<feature name="Test" name-value="Test Value" custom="1"/> <!-- Add using custom value (not recommended) -->
	</product>
</products>

# 9. Combination and variants management

A variant is an alternative version of the product with different attributes. This can be for instance products with different colours. 

A variant is composed of one or several attributes. Each attribute belongs to a group of attributes. A variant has at its maximum one attribute per group of attributes. 

## 9.1 Creation of attributes and groups of attributes via the flow object

The creation of new attributes is made via the flow object.

Creation of attributes and values of attributes ([attribute-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/attribute-1.xml)) :

<objects>
	<object type="attributeGroup" external-reference="demo-recto-color">
		<is_color_group>1</is_color_group>
		<group_type>color</group_type>
		<name lang="en">Color of the front</name>
		<public_name lang="en">Color of the front</public_name>
	</object>
	<object type="attribute" external-reference="demo-recto-color-gray">
		<external_reference for="id_attribute_group" type="attributeGroup">demo-recto-color</external_reference>
		<color>#AAB2BD</color>
		<name lang="en">Gray</name>
	</object>
	<object type="attribute" external-reference="demo-recto-color-blue">
		<external_reference for="id_attribute_group" type="attributeGroup">demo-recto-color</external_reference>
		<color>#5D9CEC</color>
		<name lang="en">Blue</name>
	</object>
	<object type="attribute" external-reference="demo-recto-color-rouge">
		<external_reference for="id_attribute_group" type="attributeGroup">demo-recto-color</external_reference>
		<color>#E84C3D</color>
		<name lang="en">Red</name>
	</object>
</objects>

## 9.2 Creation of variants from the flow product 

Creation or modification of two variants ([product-10.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-10.xml)) :

<products>
	<product external-reference="product-demo-1" >
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>19.99</price>
		<images>
			<url>/modules/advancedimporter/img/media/01.jpg</url> <!-- from local storage -->
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

## 9.3 Creation of attributes and of variants in the flow product 

In order to create the attributes and their values in the flow product, we use the [blocks](#heading=h.l41or1f7b3ak) ([product-11.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-11.xml)) :

<products>
	<product external-reference="product-demo-1">
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>19.99</price>
		<images>
			<url>/modules/advancedimporter/img/media/01.jpg</url> <!-- from local storage -->
		</images>
		<block>
			<objects>
				<object type="attributeGroup" external-reference="demo-recto-color">
					<is_color_group>1</is_color_group>
					<group_type>color</group_type>
					<name lang="en">Color of the front</name>
					<public_name lang="en">Color of the front</public_name>
				</object>
				<object type="attribute" external-reference="demo-recto-color-gray">
					<external_reference for="id_attribute_group" type="attributeGroup">demo-recto-color</external_reference>
					<color>#AAB2BD</color>
					<name lang="en">Gray</name>
				</object>
				<object type="attribute" external-reference="demo-recto-color-blue">
					<external_reference for="id_attribute_group" type="attributeGroup">demo-recto-color</external_reference>
					<color>#5D9CEC</color>
					<name lang="en">Blue</name>
				</object>
				<object type="attribute" external-reference="demo-recto-color-red">
					<external_reference for="id_attribute_group" type="attributeGroup">demo-recto-color</external_reference>
					<color>#E84C3D</color>
					<name lang="en">Red</name>
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

# 10. Management of specific prices 

The specific prices can be created by using the flow object ([specialprice-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/specialprice-1.xml)) :

<objects>
	<object type="specificPrice" external-reference="demo-1">
		<external_reference for="id_product" type="product">demo-1</external_reference>
		<id_group>1</id_group>
		<price>0</price>
		<reduction>0.2</reduction>
		<reduction_type>percentage</reduction_type> <!-- or amount -->
		<from_quantity>1</from_quantity>
		<id_customer>0</id_customer>
		<id_shop>1</id_shop>
		<id_country>0</id_country>
		<id_currency>0</id_currency>
		<from>0000-00-00</from>
		<to>0000-00-00</to>
	</object>
</objects>

Example of a flow product with a 20% discount ([product-12.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-12.xml)) :

<products>
	<product external-reference="demo-1">
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>19.99</price>
		<block>
			<objects>
				<object type="specificPrice">
					<id_product>{{id}}</id_product>
					<id_group>1</id_group>
					<price>0</price>
					<reduction>0.2</reduction>
					<reduction_type>percentage</reduction_type> <!-- or amount -->
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

# 11. Importing customers

Importing customer can be do with the flow object.

Example of creating a customer ([customer-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/customer-1.xml)) :

<objects>
	<object type="customer" external-reference="demo-1">
		<lastname>Lastname</lastname>
		<firstname>Firstname</firstname>
		<email>test@domain.tld</email>
		<passwd modifier="Tools::encrypt">myPassword</passwd>
	</object>
</objects>

An other example of creating a customer plus a group and adding customer to the group ([customer-2.xml](http://prestashopxmlimporter.madef.fr/flows_en/customer-2.xml)) :

<objects>
	<object type="customer" external-reference="demo-1">
		<lastname>Lastname</lastname>
		<firstname>Firstname</firstname>
		<email>test@domain.tld</email>
		<passwd modifier="Tools::encrypt">myPassword</passwd>
		<block>
			<objects>
				<object type="group" external-reference="demo-1">
					<name lang="en">My Group</name>
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

# 12. Multishop

PrestaShop XML Importer is conciliable with the multishop.

For this, it is only necessary to specify the shop or the shops in the flows.

Addition of a product in the shops 1 and 3 ([multishop-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/multishop-1.xml)) :

<products>
	<product external-reference="demo-3">
		<shop>1</shop>
		<shop>3</shop>
		<reference>ref01</reference>
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>19.99</price>
	</product>
</products>

**Beware, if the external reference is not specified, the product will be created twice (once by shop).**

Addition of a product in the shops 1 and 2. then the modification of the title is needed in the shop 1 ([multishop-2.xml](http://prestashopxmlimporter.madef.fr/flows_en/multishop-2.xml)) :

<products>
	<product external-reference="demo-4">
		<shop>1</shop>
		<shop>2</shop>
		<name lang="en">Name</name>
		<description lang="en">Product description</description>
		<price>19.99</price>
	</product>
	<product external-reference="demo-4">
		<shop>2</shop>
		<reference>ref01</reference>
		<name lang="en">New name</name>
	</product>
</products>

# 12. The CSV flows

The importation of the flows CSV is carried out the same way as the flow xml. The CSV flow needs to be converted to a XML flow to be uploaded. To do this, it is necessary to add a recurring task. 

Let’s take for instance the following flow:

Reference Externe,Reference,Nom,Description,Categorie parente,Sous catégorie,Prix,Stock,Ean13,Image

ref-1,ref-1,"Produit 1","Description 1","Baladeur","Ipod",150, 12,8412457989784,[http](http://prestashopxmlimporter.madef.fr/img/demo.jpg)[://prestashopxmlimporter.madef.fr/img/demo.jpg](http://prestashopxmlimporter.madef.fr/img/demo.jpg)

ref-2,ref-2,"Produit 2","Description 2","Baladeur","Accessoire",20, 5,8412457989785,[http](http://prestashopxmlimporter.madef.fr/img/demo.jpg)[://prestashopxmlimporter.madef.fr/img/demo.jpg](http://prestashopxmlimporter.madef.fr/img/demo.jpg)

In PrestaShop XML Importer > recurring task, creation of a new "recurring task".

Fill-in the form hereunder with the following data :

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
    <td>Block</td>
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
    <td>Id shop</td>
    <td>1</td>
  </tr>
</table>


The field block, is a json. The following settings are expected:

<table>
  <tr>
    <td>Attribute</td>
    <td>Type</td>
    <td>Description</td>
  </tr>
  <tr>
    <td>filepath</td>
    <td>string</td>
    <td>Format of the file name. For instance product*.csv for the file beginning by product and ending by .csv</td>
  </tr>
  <tr>
    <td>roottag</td>
    <td>string</td>
    <td>Name of the based tag. For instance: products, objects, ...</td>
  </tr>
  <tr>
    <td>ignoreFirstLine</td>
    <td>int</td>
    <td>Ignore the first CSV line</td>
  </tr>
  <tr>
    <td>delimiter</td>
    <td>char</td>
    <td>Character of separation of columns</td>
  </tr>
  <tr>
    <td>enclosure</td>
    <td>char</td>
    <td>Character of limitation of character string</td>
  </tr>
  <tr>
    <td>encoding</td>
    <td>string</td>
    <td>Encoding of the CSV file</td>
  </tr>
  <tr>
    <td>escape</td>
    <td>char</td>
    <td>Character: escape</td>
  </tr>
  <tr>
    <td>template</td>
    <td>string</td>
    <td>Template repeated for each line of the CSV. {{0}} will be replaced by a value of the first column, {{1}} by the value of the second, ... </td>
  </tr>
</table>


# 14. Adapting your XML to the format of the module (XSLT)

The XSLT enables a conversion of any document of any format to a different format in particular the one supported by the module.

## 14.1 Creation via the online tool

To faciliate the spelling of the XSLT, a free online tool exists: [http://prestashopxmlimporter.madef.fr/xslt.php](http://prestashopxmlimporter.madef.fr/xslt.php)

This tool enables the creation of the XSLT for the flow product without any particular knowledge. Therefore, it is necessary to have a minimum knowledge on the XML format and on the features of the PrestaShop products.

## 14.2 Examples of use of the XSLT

 Example with two specified prices and stocks ([xslt-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/xslt-1.xml)) :

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
						<name lang="en"><xsl:value-of select="./s_modele" /></name>
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

# 15. Abnormalities

If the module does not work correctly, please check the following scenarios

**The cron seems to work correctly but no block is created**

Please check the version of your PHP. The module requires at least the version PHP 5.3. Of course, more recent versions can also be used.

**Blocks are created but never executed**

This issue occurs when the file "lock" cannot be deleted. to correct this, delete the file module/advancedimporter/lock/1.lock and keep sure that everyone can edit in the file module/advancedimporter/lock.

**Blocks regarding products are processed although not to a term **

This case can occur when the execution of the blocks ends unexpectedly or when the error cannot be interpreted by the module. In this case, go to the first example of the flow product ([product-1.xml](http://prestashopxmlimporter.madef.fr/flows_en/product-1.xml)). Please replace the language by the one used by default by your shop.

 

# 15. Download

The module is available on the [website addons of PrestaShop](http://addons.prestashop.com/fr/edition-rapide-modules-prestashop/7951-massive-customizable-xml-importer.html).

