<{include file="db:works-header.tpl"}>
<!-- Detalles del Trabajo -->
<h2 class="work-title"><{$work.title}><{if $work.featured}> <sup><small class="label label-danger"><{$lang_featured}></small></sup><{/if}></h2>

<div id="work-image">
    <a href="<{$work.image}>" title="<{$work.title}>" class="work-image-item"><img src="<{$work.image}>" alt="<{$work.title}>" class="img-responsive"></a>
</div>
<{if $work.images}>

    <div class="work-images">
        <{foreach item=image from=$work.images}>
            <a href="<{$image.url}>" class="work-image-item" title="<{$image.title}>" style="background-image: url('<{$xoops_url}>/modules/rmcommon/include/resizer.php?src=<{$image.url}>&amp;h=150&amp;w=150');">
                <span><span class="fa fa-search"></span></span>
            </a>
        <{/foreach}>
    </div>

<{/if}>

<div class="row" id="work-details">

    <div class="col-md-7 col-lg-8">
        <{$work.description}>

        <{if $work.comment}>
        <blockquote class="blockquote-reverse">
            <p><{$work.comment}></p>
            <footer>
                <{if $work.url != ''}>
                    <a href="<{$work.url}>" title="<{$work.web}>"><{$work.customer}></a>
                <{else}>
                    <{$work.customer}>
                <{/if}>
            </footer>
        </blockquote>
        <{/if}>
    </div>

    <div class="col-md-5 col-lg-4">

        <ul class="list-unstyled work-data">
            <li class="title">
                <small><span class="fa fa-folder"></span> <{$lang_categories}></small>
            </li>
            <li>
                <ul class=" item-categories">
                    <{foreach item=category from=$work.categories}>
                        <li>
                            <a href="<{$category->permalink()}>">
                                <{$category->name}>
                            </a>
                        </li>
                    <{/foreach}>
                </ul>
            </li>
            <li class="title">
                <small><span class="fa fa-user"></span> <{$lang_customer}></small>
            </li>
            <li>
                <{$work.customer}>
            </li>
            <{if $work.web!=''}>
                <li class="title">
                    <small><span class="fa fa-link"></span> <{$lang_site}></small>
                </li>
                <li>
                    <a href="<{$work.url}>"><{$work.web}></a>
                </li>
            <{/if}>
            <li class="title">
                <small><span class="fa fa-eye"></span> <{$lang_views}></small>
            </li>
            <li>
                <{$work.views}>
            </li>
        </ul>

    </div>

</div>

<!-- Otros Trabajos -->
<{if $other_works}>
<h4><{$lang_others}></h4>
<div class="row works-related">
    <{foreach item=related from=$other_works}>
        <div class="col-xs-6 col-md-3">
            <a href="<{$related.link}>" class="thumbnail">
                <img src="<{$xoops_url}>/modules/rmcommon/include/resizer.php?src=<{$related.image}>&w=300&h=300" alt="<{$related.title}>">
                <div class="caption">
                    <h6><{$related.title}></h6>
                    <p><{$related.description}></p>
                </div>
            </a>
        </div>
    <{/foreach}>
</div>
<{/if}>
<!-- //Fin de otros trabajos -->

<!-- Start Comments -->
<a name="comments"></a>
<h4><{$lang_comments}></h4>
<{include file="db:rmc-comments-display.html"}>
<{$comments_form}>
<!-- /End comments -->
