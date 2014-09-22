<{include file="db:works-header.tpl"}>
<h3><{$lang_works}></h3>
<div class="pw_grid_container">
    <{foreach item=item from=$works}>
        <{include file="db:works-loop-item.tpl"}>
    <{/foreach}>
</div>
<{$navpage}>
