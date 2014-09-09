<{include file="db:pw_header.html"}>
<h2 class="pwWorks"><{$lang_recents}></h2>
<span class="infoNavPage"><{$lang_showing}></span>
<div class="navPage">
	<{$recentNavPage}>
</div>
<div class="pw_grid_container">
	<{foreach item=item from=$recents}>
		<{include file="db:pw_witem.html"}>
	<{/foreach}>
</div>
<{$navpage}>