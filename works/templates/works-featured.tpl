<{include file="db:pw_header.html"}>
<h2 class="pwWorks"><{$lang_feats}></h2>
<span class="infoNavPage"><{$lang_showing}></span>
<div class="navPage">
	<{$featuredNavPage}>
</div>
<div class="pw_grid_container">
	<{foreach item=item from=$featureds}>
		<{include file="db:pw_witem.html"}>
	<{/foreach}>
</div>
<{$navpage}>
