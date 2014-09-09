<{include file="db:pw_header.html"}>
<!-- Trabajos recientes -->
<h2 class="pwWorks"><{$lang_works}></h2>
<div class="pw_grid_container">
	<{foreach item=item from=$works}>
	<{include file="db:pw_witem.html"}>
	<{/foreach}>
</div>
<{$navpage}>
<!-- //Fin de trabajos recientes -->

<{$worksNavPage}>
