<div class="row">
	<div class="col-md-12">
		{if $warning neq false}
			{$warning}
		{/if}
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		{l s="In order to send automatic reminders, you need to set up a cron job, which is a process that allows to schedule regular tasks." mod='cartabandonmentpro'}<br>
		{l s='To do this, you need to click on "ACTIVATE AUTOMATIC REMINDER" button.' mod='cartabandonmentpro'}<br>
		{l s="Then, reminders will be automatically configurated on the Cron job module from your PrestaShop Back Office." mod='cartabandonmentpro'}<br><br>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		{if $warning eq false}
			<form method="POST" action="">
		{/if}
			<input type="hidden" name="cartabandonment_conf" value="1">
			<input type="hidden" name="activateCronJob" value="{if $cronActivated eq 0}1{else}0{/if}">
			<button class="btn btn-teal btn-block btn-{if $cronActivated eq 0 and $warning eq false}primary{else}danger{/if}" type="submit">
				{if $warning eq false}
					{if $cronActivated eq 0}
						{l s='Activate automatic reminder' mod='cartabandonmentpro'}
					{else}
						{l s='Deactivate automatic reminder' mod='cartabandonmentpro'}
					{/if}
				{else}
					{l s='Cron jobs module not found' mod='cartabandonmentpro'}
				{/if}
			</button>
		{if $warning eq false}
			</form>
		{/if}
	</div>
</div>
<br>
<div class="row">
	{l s='Or you can send manual reminders, to do so, you should enter the following urls in your browser.' mod='cartabandonmentpro'}<br>
	<div style="margin-left: 20px;margin-bottom: 10px;">
		{l s='First reminder:' mod='cartabandonmentpro'} {$url}modules/cartabandonmentpro/send.php?id_shop={$id_shop}&token={$token_send}&wich_remind=1<br>
		{l s='Second reminder:' mod='cartabandonmentpro'} {$url}modules/cartabandonmentpro/send.php?id_shop={$id_shop}&token={$token_send}&wich_remind=2<br>
		{l s='Third reminder:' mod='cartabandonmentpro'} {$url}modules/cartabandonmentpro/send.php?id_shop={$id_shop}&token={$token_send}&wich_remind=3<br>
	</div>
	{l s="If you prefer configurate your own cron job on your server, you can also set yourself a cron's task on your server" mod='cartabandonmentpro'}<br>
	<div style="margin-left: 20px;margin-bottom: 10px;">
		{l s='First reminder:' mod='cartabandonmentpro'} 0	*	*	*	* {$dirname}/send.sh {$id_shop} {$token_send} 1<br>
		{l s='Second reminder:' mod='cartabandonmentpro'} 0	*	*	*	* {$dirname}/send.sh {$id_shop} {$token_send} 2<br>
		{l s='Third reminder:' mod='cartabandonmentpro'} 0	*	*	*	* {$dirname}/send.sh {$id_shop} {$token_send} 3<br>
	</div>
	Ubuntu cron documentation: <a href="http://doc.ubuntu-fr.org/cron" target="_blank">http://doc.ubuntu-fr.org/cron</a>
</div>