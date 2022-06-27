<h3>{l s="Frequently Asked Questions" mod="cartabandonmentpro"}</h3>
<div class="faq items">
	<ul id="basics" class="faq-items">
		<li class="faq-item">
			<span class="faq-trigger">{l s="Can I send reminders to guests?" mod="cartabandonmentpro"}</span>
			<span class="expand pull-right">+</span>
			<div class="faq-content">
				{l s="As guests did not entered their email, you can't send reminders to them." mod="cartabandonmentpro"}
			</div> 
		</li>
		<li class="faq-item">
			<span class="faq-trigger">Discounts tags are not replaced in my templates?</span>
			<span class="expand pull-right">+</span>
			<div class="faq-content">
				<p>
					%DISCOUNT_VALUE%, %DISCOUNT_VALID_DAY%, %DISCOUNT_VALID_MONTH%, %DISCOUNT_VALID_YEAR% {l s="and" mod="cartabandonmentpro"} %DISCOUNT_CODE%
					{l s="can't be used directly in the template." mod="cartabandonmentpro"}
				</p>
				<p>
					{l s="In order to display discount text in your template you need to do the following:" mod="cartabandonmentpro"}
					<ul>
						<li>{l s="Configure a text for discounts in discount tab using the tags." mod="cartabandonmentpro"}</li>
						<li>{l s="Configure a text for free shipping in discount tab using the tags." mod="cartabandonmentpro"}</li>
						<li>{l s="Place %DISCOUNT_TXT% in your template body where you want the discount text to be." mod="cartabandonmentpro"}</li>
					</ul>
				</p>
			</div> 
		</li>
		<li class="faq-item">
			<span class="faq-trigger">Mails are not sent?</span>
			<span class="expand pull-right">+</span>
			<div class="faq-content">
				<p>
					{l s="If mails are not sent, please check the following:" mod="cartabandonmentpro"}
				</p>
				<p>
					<ul>
						<li>
							{l s="Try to send a test mail in advanced parameters > Emails." mod="cartabandonmentpro"}
							<br>
							{l s="If it doesn't work, it means that your mail server is not configured. You need to configure it or contact your host." mod="cartabandonmentpro"}
						</li>
						<li>{l s="Check your spam folder." mod="cartabandonmentpro"}</li>
					</ul>
				</p>
				<p>
					{l s="If it still doesn't work, please send us a ticket" mod="cartabandonmentpro"} 
					<a href="https://addons.prestashop.com/en/contact-us?id_product=16535" target="_blank">{l s="here" mod="cartabandonmentpro"}</a>.
				</p>
			</div> 
		</li>
	</ul>
</div>