<table class="outer" cellspacing="0" cellpadding="3">
	<tr class="even" valign="top">
	<{assign var="i" value=1}>
	<{foreach item=work from=$block.works}>
		<{if $i>$block.cols}>
			</tr><tr class="<{cycle values="even,odd"}>" valign="top">
			<{assign var="i" value=1}>
		<{/if}>
		<td>
		<{if $block.showimg}><a href="<{$work.link}>"><img src="<{$work.image}>" /></a><br /><{/if}>
		<a href="<{$work.link}>"><strong><{$work.title}></strong></a><br />
		<{if $block.showdesc}><{$work.desc}><br /><{/if}>
		<{$work.client}><br /> 
		<{$work.cat}><br /> 
		<{$work.created}>
		</td>
		<{assign var="i" value=$i+1}>
	<{/foreach}>
	</tr>
</table>
