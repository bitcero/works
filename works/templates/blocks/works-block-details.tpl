<table class="table table-condensed works-block-details">
    <{if $block.description != ''}>

        <tr>
            <td colspan="2">
                <{$block.description}>
            </td>
        </tr>

    <{/if}>
    <{if $block.categories}>
    <tr>
        <td><strong><span class="fa fa-folder"></span> <{$block.lang.categories}></strong></td>
        <td>
            <ul class="list-unstyled">
                <{foreach item=cat from=$block.categories}>
                    <li><a href="<{$cat->permalink()}>"><{$cat->name}></a></li>
                <{/foreach}>
            </ul>
        </td>
    </tr>
    <{/if}>
    <{if $block.customer != ''}>
    <tr>
        <td><strong><span class="fa fa-user"></span> <{$block.lang.customer}></strong></td>
        <td><{$block.customer}></td>
    </tr>
    <{/if}>
    <{if $block.web != ''}>
    <tr>
        <td><strong><span class="fa fa-link"></span> <{$block.lang.website}></strong></td>
        <td>
            <a href="<{$block.url}>"><{$block.web}></a>
        </td>
    </tr>
    <{/if}>
    <{if $block.views != ''}>
    <tr>
        <td><strong><span class="fa fa-eye"></span> <{$block.lang.hits}></strong></td>
        <td>
            <{$block.views}>
        </td>
    </tr>
    <{/if}>
    <{if $block.created != ''}>
    <tr>
        <td><strong><span class="fa fa-clock-o"></span> <{$block.lang.created}></strong></td>
        <td><{$block.created}></td>
    </tr>
    <{/if}>
    <{if $block.modified != ''}>
    <tr>
        <td><strong><span class="fa fa-clock-o"></span> <{$block.lang.updated}></strong></td>
        <td><{$block.modified}></td>
    </tr>
    <{/if}>
    <{if $block.meta}>

        <{foreach item=meta from=$block.meta}>
            <tr>
                <td><strong><{$meta.caption}></strong></td>
                <td><{$meta.value}></td>
            </tr>
        <{/foreach}>

    <{/if}>
</table>