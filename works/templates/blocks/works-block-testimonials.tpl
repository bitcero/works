<{foreach item=work from=$block.works}>
	<div class="<{cycle values="even,odd"}>" style="font-style: italic;">
		"<{$work.comment}>"
		<span style="display: block; font-style: normal; margin-top: 10px; font-size: 0.9em;"><a href="<{$work.link}>"><{$work.client}></a></span>
	</div>
<{/foreach}>

