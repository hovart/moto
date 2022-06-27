/*
*  Michel Dumont | michel-dumont.fr 
*
*  @author Michel Dumont <michel@dumont.ior>
*  @copyright Since 2014
*  @version  1.1.3 - 2015-06-22
*  @license  http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  @prestashop version 1.6
*
*/

/* mdg_hfp 2.6.2
--------------------------------------------------------------------------------------- */

var app	= {
		_path: null,
	
	};

	app.version_compare = function(a, b) {
		var i, l, d;
		a = a.split('.');
		b = b.split('.');
		l = Math.min(a.length, b.length);
	
		for (i=0; i<l; i++) {
			d = parseInt(a[i], 10) - parseInt(b[i], 10);
			if (d !== 0) return d;
		}
		return a.length - b.length;
	}
	
	app.form = {
		
		initialize: function()
		{
			if( app.version_compare('1.6.0.7',_PS_VERSION_) > 0 )
			{
				var fieldset_1 = $('#fieldset_1').children();
				var fieldset_2 = $('#fieldset_2').children();
				
				this.categories_ids = $(fieldset_1[2]);
				this.products_ids   = $(fieldset_1[3]);
				this.unsold_days   	= $(fieldset_1[4]);
				this.restricted_categories_ids = $(fieldset_2[2]);
				this.restricted_products_ids   = $(fieldset_2[3]);
			}
			else if( app.version_compare('1.6.0.9',_PS_VERSION_) > 0 )
			{
				var fieldset_1 = $('#fieldset_1 .form-wrapper').children();
				var fieldset_2 = $('#fieldset_2 .form-wrapper').children();
				
				this.categories_ids = $(fieldset_1[1]);
				this.products_ids   = $(fieldset_1[2]);
				this.unsold_days   	= $(fieldset_1[4]);
				this.restricted_categories_ids = $(fieldset_2[1]);
				this.restricted_products_ids   = $(fieldset_2[2]);
			}
			else
			{
				var fieldset_1 = $('#fieldset_1_1 .form-wrapper').children();
				var fieldset_2 = $('#fieldset_2_2 .form-wrapper').children();
				
				this.categories_ids = $(fieldset_1[1]);
				this.products_ids   = $(fieldset_1[2]);
				this.unsold_days   	= $(fieldset_1[3]);
				this.restricted_categories_ids = $(fieldset_2[1]);
				this.restricted_products_ids   = $(fieldset_2[2]);
			}


			this.render();		
			this.events();
		},
		render: function()
		{
			this.categories_ids.hide();
			this.products_ids.hide();
			this.unsold_days.hide();
			this.restricted_categories_ids.hide();
			this.restricted_products_ids.hide();
		},
		events: function()
		{
			var that = this;
			
			$('select[name=type]').change(function()
			{
				switch($(this).val())
				{
					case '1':
						that.categories_ids.slideDown();
						that.products_ids.slideUp();
						that.unsold_days.slideUp();
						break;
					case '2':
						that.categories_ids.slideUp();
						that.products_ids.slideDown();
						that.unsold_days.slideUp();
						break;
					case '6':
						that.categories_ids.slideUp();
						that.products_ids.slideUp();
						that.unsold_days.slideDown();
						break;
					default:
						that.categories_ids.slideUp();
						that.products_ids.slideUp();
						that.unsold_days.slideUp();
				}
			}).trigger('change');
			
			$('select[name=restrict_type]').change(function()
			{
				switch($(this).val())
				{
					case '2':
						that.restricted_categories_ids.slideDown();
						that.restricted_products_ids.slideUp();
						break;
					case '3':
						that.restricted_categories_ids.slideUp();
						that.restricted_products_ids.slideDown();
						break;
					default:
						that.restricted_categories_ids.slideUp();
						that.restricted_products_ids.slideUp();
				}
			}).trigger('change');
		},
	};
	
	app.list = {
		
		initialize: function()
		{
			this.render();		
			this.events();
		},
		render: function()
		{

		},
		events: function()
		{
			
		},
	};