{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if empty($bPsVersion1606)}
<div class="modal-dialog">
	<div class="modal-content">
{/if}
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h2 class="modal-title" id="modalFrenchOrderReference">{l s='Cron job report' mod='gsnippetsreviews'}: {$sShopName|escape:'htmlall':'UTF-8'}</h2>
			{if !empty($aCronReport)}<span style="float: right;"><strong>{$aCronReport.date|date_format:"%d/%m/%Y %H:%M:%S"}</strong></span>{/if}
		</div>
		<div class="modal-body">
			{if !empty($aErrors)}
				{include file="`$sErrorInclude`" bModal=true}
			{else}
				{if !empty($aCronReport.sent)}
					<div class="alert alert-success">
						<h3>{l s='Total of sent reminders' mod='gsnippetsreviews'} : {$aCronReport.sent.count|intval}</h3>
						<h4>{l s='All the reminders are deleted from your database once they\'ve been sent by e-mail' mod='gsnippetsreviews'}</h4>
						<ul>
							{foreach from=$aCronReport.sent.cbk name=cbk key=iKey item=aCbk}
								<li>{l s='Reminder ID' mod='gsnippetsreviews'} : {$aCbk.cbkId|escape:'htmlall':'UTF-8'}, {l s='Order ID' mod='gsnippetsreviews'} : {$aCbk.orderId|intval}, {l s='Customer ID' mod='gsnippetsreviews'} : {$aCbk.custId|intval}</li>
							{/foreach}
						</ul>
					</div>
				{/if}
				{if !empty($aCronReport.reviewed.cbk)}
					<div class="alert alert-info">
						<h3>{l s='Total of products already reviewed' mod='gsnippetsreviews'} : {$aCronReport.reviewed.count|intval}</h3>
						<h4>{l s='These reminders weren\'t been sent because all products from each order have already been reviewed' mod='gsnippetsreviews'}</h4>
						<ul>
							{foreach from=$aCronReport.reviewed.cbk name=cbk key=iKey item=aCbk}
								<li>{l s='Reminder ID' mod='gsnippetsreviews'} : {$aCbk.cbkId|escape:'htmlall':'UTF-8'}, {l s='Order ID' mod='gsnippetsreviews'} : {$aCbk.orderId|intval}, {l s='Customer ID' mod='gsnippetsreviews'} : {$aCbk.custId|intval}</li>
							{/foreach}
						</ul>
					</div>
				{/if}
				{if !empty($aCronReport.mailerror.cbk)}
					<div class="alert alert-danger">
						<h3>{l s='Reminders sent in error' mod='gsnippetsreviews'} : {$aCronReport.mailerror.count|intval}</h3>
						<h4>{l s='These reminders weren\'t been sent because Prestashop\'s mail function returned an error. In order to send them again via your CRON job, the module has increased the order\'s adding date of one day more' mod='gsnippetsreviews'}</h4>
						<ul>
							{foreach from=$aCronReport.mailerror.cbk name=cbk key=iKey item=aCbk}
								<li>{l s='Reminder ID' mod='gsnippetsreviews'} : {$aCbk.cbkId|escape:'htmlall':'UTF-8'}, {l s='Order ID' mod='gsnippetsreviews'} : {$aCbk.orderId|intval}, {l s='Customer ID' mod='gsnippetsreviews'} : {$aCbk.custId|intval}</li>
							{/foreach}
						</ul>
					</div>
				{/if}
				{if !empty($aCronReport.order.cbk)}
					<div class="alert alert-warning">
						<h3>{l s='Total of orders without the matching status' mod='gsnippetsreviews'} : {$aCronReport.order.count|intval}</h3>
						<h4>{l s='These reminders weren\'t been sent because the order\'s status doesn\'t fit to your selected statuses from your module\'s configuration. In order to process them again and try to send them via your CRON job, the module has increased the order\'s adding date of one day more' mod='gsnippetsreviews'}</h4>
						<ul>
							{foreach from=$aCronReport.order.cbk name=cbk key=iKey item=aCbk}
								<li>{l s='Reminder ID' mod='gsnippetsreviews'} : {$aCbk.cbkId|escape:'htmlall':'UTF-8'}, {l s='Order ID' mod='gsnippetsreviews'} : {$aCbk.orderId|intval}, {l s='Customer ID' mod='gsnippetsreviews'} : {$aCbk.custId|intval}</li>
							{/foreach}
						</ul>
					</div>
				{/if}
			{/if}
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='gsnippetsreviews'}</button>
		</div>
{if empty($bPsVersion1606)}
	</div>
</div>
{/if}