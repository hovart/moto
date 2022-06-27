<div class="panel-heading">
	<i class="fa fa-cogs"></i>
	5 - {l s='Relances' mod='cartabandonmentpro'}
</div>
<div class="panel-body">
	<div class="row">
		<div class="col-lg-5">
			{l s='Do you want to remind customers that didn\'t subscribe to the newsletter?' mod='cartabandonmentpro'} *
		</div>
		<div class="col-lg-2">			
			<span class="switch prestashop-switch fixed-width-lg">
				<input onclick="setNewsletter('{$token}', {$id_shop}, 1);" type="radio" name="active" id="newsletter_on" value="1" {if $newsletter eq 1}checked="checked"{/if}>
				<label for="newsletter_on" class="radioCheck">
					Oui
				</label>
				<input onclick="setNewsletter('{$token}', {$id_shop}, 0);" type="radio" name="active" id="newsletter_off" value="0" {if $newsletter eq 0}checked="checked"{/if}>
				<label for="newsletter_off" class="radioCheck">
					Non
				</label>
				<a class="slide-button btn"></a>
			</span>
		</div>
	</div>
	{if $iso_lang eq 'fr'}
		<div class="row">
			<div class="col-xs-12">
				</i><small><a href="http://www.cnil.fr/fileadmin/documents/Marketing/Commerce_et_Donnees_Personnelles.pdf" target="_blank">* Voir plus d'informations sur les opt-in</a></small>
			</div>
		</div>
	{/if}
	{include file="../conf/cron.tpl"}
	<div class="panel-footer">
		<button type="submit" name="submitCron" id="submitCron" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s="Save" mod="cartabandonmentpro"}</button>
	</div>
</div>