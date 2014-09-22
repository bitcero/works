<{if $block.options.display == 'list'}>

    <ul class="media-list works-block-items-list">
        <{foreach item=work from=$block.works}>
            <li class="media">
                <{if $work.image!=''}>
                    <a href="<{$work.link}>" class="pull-left">
                        <img alt="<{$work.title}>" class="media-object" src="<{$xoops_url}>/modules/rmcommon/include/resizer.php?src=<{$work.image}>&amp;w=<{$block.options.width}>&amp;h=<{$block.options.height}>">
                    </a>
                <{/if}>
                <div class="media-body">
                    <h5 class="media-heading"><a href="<{$work.link}>"><{$work.title}></a></h5>
                    <{if $work.description}><{$work.description}><{/if}><br>
                    <small class="help-block"><{$work.created}></small>
                </div>
            </li>
        <{/foreach}>
    </ul>

<{else}>

    <div class="row works-block-items-grid">
        <{assign var="cols" value=1}>
        <{foreach item=work from=$block.works}>
            <{if $cols > $block.options.grid}>
                </div>
                <div class="row works-block-items-grid">
                <{assign var="cols" value=1}>
            <{/if}>
            <div class="col-sm-<{$block.options.col}>">
                <div class="thumbnail">
                    <{if $work.image != ''}>
                        <a href="<{$work.link}>">
                            <img src="<{$xoops_url}>/modules/rmcommon/include/resizer.php?src=<{$work.image}>&amp;w=<{$block.options.width}>&amp;h=<{$block.options.height}>" alt="<{$work.title}>">
                        </a>
                        <div class="caption">
                            <h5><a href="<{$work.link}>"><{$work.title}></a></h5>
                            <{if $work.description != ''}>
                                <p><{$work.description}></p>
                            <{/if}>
                            <small class="help-block"><{$work.created}></small>
                        </div>
                    <{/if}>
                </div>
            </div>
            <{assign var="cols" value=$cols+1}>
        <{/foreach}>
    </div>

<{/if}>
