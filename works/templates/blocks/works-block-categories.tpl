<ul class="list-unstyled works-categories-block">
    <{foreach item=cat from=$block.categories}>
        <li<{if $cat.level>0}> style="padding-left: <{$cat.level*10}>px;"<{/if}>><span class="fa fa-folder"></span> <a href="<{$cat.link}>"><{$cat.name}></a></li>
    <{/foreach}>
</ul>