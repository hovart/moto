{*
*  
* 	Copyright (c) 2014 Okom3pom.com
*	Module CountDown for Prestashop
*
*	Released under the GNU General Public License
*
*	Author Okom3pom.com -> Thomas Roux
*	Version 1.0.0 - 06/08/2014
* 
*}
	
{if $end_date}

<div class="okom_countdown-product-messagep clearfix">
	{$okom_countdown_product_message1|escape:'UTF-8'}
	<div id="countdown-product"></div>
	{$okom_countdown_product_message2|escape:'UTF-8'}
</div>


		{assign var=date value=$end_date}


{assign var="date_vf" value="-"|explode:$date}
{assign var="day" value=" "|explode:$date_vf[2]}
{assign var="hms" value=":"|explode:$day[1]} 


<script type="text/javascript">

$(function(){

	// Attention les mois commencent à 0 !
	var ts = new Date({$date_vf[0]} ,{$date_vf[1]-1} , {$day[0]}, {$hms[0]}, {$hms[1]} , 00 );
	var newYear = true;
	
	if((new Date()) > ts){
		// The new year is here! Count towards something else.
		// Notice the *1000 at the end - time must be in milliseconds
		ts = (new Date()).getTime() + 10*24*60*60*1000;
		newYear = false;
	}
		
	$('#countdown-product').countdownp({
		timestamp	: ts,
		callback	: function(days, hours, minutes, seconds){
			var message = "";

			message += days + " jour" + ( days == 1 ? '':'s' ) + ", ";
			message += hours + " heure" + ( hours==1 ? '':'s' ) + ", ";
			message += minutes + " minute" + ( minutes==1 ? '':'s' ) + " et ";
			message += seconds + " seconde" + ( seconds==1 ? '':'s' );			

		}
	});
	
});
</script>

{/if}

