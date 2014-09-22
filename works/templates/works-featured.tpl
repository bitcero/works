<{include file="db:works-header.tpl"}>
<h3><{$lang_feats}></h3>
<div class="pw_grid_container">
	<{foreach item=item from=$works}>
		<{include file="db:works-loop-item.tpl"}>
	<{/foreach}>
</div>
<{$navpage}>
