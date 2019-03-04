<ul class="list-unstyled works-categories-block">
    <{foreach item=cat from=$block.categos}>
        <li><span class="fa fa-folder"></span> <a href="<{$cat.link}>"><{$cat.name}></a></li>
    <{/foreach}>
</ul>