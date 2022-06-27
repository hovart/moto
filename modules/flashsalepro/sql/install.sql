CREATE TABLE IF NOT EXISTS PREFIXflashsalespro_items (
		id_flashsalespro_item int(11) NOT NULL AUTO_INCREMENT,
		id_flashsalespro int(11) NOT NULL,
		id_specific_price int(10) NOT NULL,
		discount float(10),
		discount_type VARCHAR(254),
		custom_img_link_flag int(1) NOT NULL,
		custom_img_link VARCHAR(254),
		active int(1) NOT NULL,
		stock_above int(10),
		stock_below int(10),
		PRIMARY KEY  (`id_flashsalespro_item`),
		KEY id_specific_price_index (id_specific_price),
		KEY id_flashsalespro_index (id_flashsalespro)
) ENGINE=ENGINE_DEFAULT DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS PREFIXflashsalespro (
		id_flashsalespro int UNSIGNED NOT NULL AUTO_INCREMENT,
		id_shop int(11),
		id_group_restriction int(10),
		id_currency_restriction int(10),
		id_country_restriction int(10),
		sale_type ENUM('manual', 'timed', 'stock'),
		sale_custom_img_link VARCHAR(254),
		activation_type VARCHAR(254),
		active int(1) NOT NULL,
		date_start DATETIME,
		date_end DATETIME,
		end_date_timestamp int(64),
		bg_color VARCHAR(254),
		text_color VARCHAR(254),
		font VARCHAR(254),
		PRIMARY KEY (id_flashsalespro),
		KEY sale_type_index (sale_type)
	) ENGINE=ENGINE_DEFAULT DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS PREFIXflashsalespro_temp (
		temp_key int UNSIGNED NOT NULL AUTO_INCREMENT,
		id_item int(11),
		item_type VARCHAR(254),
		discount_amount float(10),
		discount_type ENUM('amount', 'percentage'),
		PRIMARY KEY (temp_key)
	) ENGINE=ENGINE_DEFAULT DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS PREFIXflashsalespro_names (
	    id_flashsalespro_name int(11) NOT NULL AUTO_INCREMENT,
	    id_flashsalespro int(11) NOT NULL,
		name VARCHAR(254),
		id_lang int(10),
	    PRIMARY KEY  (`id_flashsalespro_name`),
	    KEY name_lang (name, id_lang)
	) ENGINE=ENGINE_DEFAULT DEFAULT CHARSET=utf8;