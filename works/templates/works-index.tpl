<{include file="db:works-header.tpl"}>
<!-- Trabajos recientes -->
<h2 class="pwWorks"><{$lang_works}></h2>
<div class="pw_grid_container">
    <{foreach item=item from=$works}>
    <{include file="db:works-loop-item.tpl"}>
    <{/foreach}>
</div>
<{$navpage}>
<!-- //Fin de trabajos recientes -->

<{$worksNavPage}>
