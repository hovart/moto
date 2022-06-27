{**
  * Recherche de produits par compatibilité
  *
  * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
  * @copyright Ukoo 2015
  * @license   Ukoo - Tous droits réservés
  *
  * "In Ukoo we trust!"
  *}

<div class="col-lg-5">
	<div class="panel">
		<div class="panel-heading">
			<i class="icon-file"></i> {l s='File' mod='ukoocompat'}
		</div>
		<div class="form-horizontal">
			<div class="row">
				<label class="control-label col-lg-3">{l s='Original filename' mod='ukoocompat'}</label>
				<div class="col-lg-9">
					<p class="form-control-static">
						{if isset($import->original_filename) && !empty($import->original_filename)}
							{$import->original_filename|escape:'htmlall':'UTF-8'}
						{else}
							<em>{l s='Unknow' mod='ukoocompat'}</em>
						{/if}
					</p>
				</div>
			</div>
			<div class="row">
				<label class="control-label col-lg-3">{l s='File path' mod='ukoocompat'}</label>
				<div class="col-lg-9">
					<p class="form-control-static">
						{if isset($import->file) && !empty($import->file) && file_exists('../modules/ukoocompat/import/'|cat:$import->file)}
							/modules/ukoocompat/import/<b>{$import->file|escape:'htmlall':'UTF-8'}</b>
						{else}
							<em>{l s='File not found' mod='ukoocompat'}</em>
						{/if}
					</p>
				</div>
			</div>
			<div class="row">
				<label class="control-label col-lg-3">{l s='File size' mod='ukoocompat'}</label>
				<div class="col-lg-9">
					<p class="form-control-static">
						{if isset($import->file) && !empty($import->file) && file_exists('../modules/ukoocompat/import/'|cat:$import->file)}
							{assign var=size value=filesize('../modules/ukoocompat/import/'|cat:$import->file)}
							{if $size > 1024}
								{math equation='x/1024' x=$size} {l s='Mb' mod='ukoocompat'}
							{else}
								{$size|escape:'htmlall':'UTF-8'} {l s='bytes' mod='ukoocompat'}
							{/if}
						{else}
							<em>-</em>
						{/if}
					</p>
				</div>
			</div>
			<div class="row">
				<label class="control-label col-lg-3"></label>
				<div class="col-lg-9">
					<p class="form-control-static">
						<a href="../modules/ukoocompat/import/{$import->file|escape:'htmlall':'UTF-8'}" class="btn btn-default" target="_blank">
							<i class="icon-download"></i> {l s='Download file' mod='ukoocompat'}
						</a>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-7">
	<div class="panel">
		<div class="panel-heading">
			<i class="icon-refresh"></i> {l s='Analyze' mod='ukoocompat'}
		</div>
	</div>
</div>