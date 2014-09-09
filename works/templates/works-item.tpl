<{include file="db:pw_header.html"}>
<!-- Detalles del Trabajo -->
<h2 class="pw_title"><{$work.title}><{if $work.mark}> <span class="pw_featured">[<{$lang_mark}>]<{/if}></h2>
<div id="pw-image">
	<img src="<{$work.image}>" alt="<{$work.title}>" />
</div>
<div id="pw-details">
	<div class="pw_description">
		<h3><{$lang_desc}></h3>
		<{$work.desc}>
	</div>
	<div class="pw_data">
		<table cellspacing="0" border="0">
			<tr class="<{cycle values="even,odd"}>">
				<td><strong><{$lang_catego}></strong></td>
				<td><a href="<{$work.category.link}>"><{$work.category.name}></a></td>
			</tr>
			<{if $work.client}>
			<tr class="<{cycle values="even,odd"}>">
				<td><strong><{$lang_client}></strong></td>
				<td><{$work.client}></td>
			</tr>
			<{/if}>
			<tr class="<{cycle values="even,odd"}>">
				<td><strong><{$lang_start}></strong></td>
				<td><{$work.start}></td>
			</tr>
			<{if $work.period}>
			<tr class="<{cycle values="even,odd"}>">
				<td><strong><{$lang_period}></strong></td>
				<td><{$work.period}></td>
			</tr>
			<{/if}>
			<{if $work.cost}>
			<tr class="<{cycle values="even,odd"}>">
				<td><strong><{$lang_cost}></strong></td>
				<td><{$work.cost}></td>
			</tr>
			<{/if}>
			<{if $work.url}>
			<tr class="<{cycle values="even,odd"}>">
				<td><strong><{$lang_site}></strong></td>
				<td><a href="<{$work.url}>" target="_blank"><{$work.site}></a></td>
			</tr>
			<{/if}>
			<tr class="<{cycle values="even,odd"}>">
				<td><strong><{$lang_rating}></strong></td>
				<td><{$work.rating}></td>
			</tr>
			<tr class="<{cycle values="even,odd"}>">
				<td><strong><{$lang_views}></strong></td>
				<td><{$work.views}></td>
			</tr>
		</table>
	</div>
</div>
<{if $work.comment}>
<div id="pw-customer-comment">
	"<{$work.comment}>"
	<span>&#8212; <{$work.client}></span>
</div>
<{/if}>

<{if $images}>
<div id="pw-work-images">
	<h3><{$lang_images}></h3>
<{foreach item=img from=$images}>
	<a rel="work-<{$work.id}>" style="width: <{$widthimg}>px;" href="<{$img.link_image}>" title="<{$img.title}>"><img src="<{$img.image}>" /><span><{$img.title}></span></a>
<{/foreach}>
</div>
<{/if}>

<!-- //Fin de Detalles del Trabajo -->

<!-- Otros Trabajos -->
<{if $other_works}>
<h2 class="pwWorks"><{$lang_others}></h2>
<div class="pw_grid_container">
    <{foreach item=feat from=$other_works}>
    <div class="<{cycle values="even,odd"}> pw_works_grid">
        <a href="<{$feat.link}>"><img src="<{$feat.image}>" alt="<{$feat.title}>" /></a>
        <div>        
        <h3><a href="<{$feat.link}>"><{$feat.title}></a></h3>
        <span class="desc"><{$feat.desc}></span>    
        <span class="info"><strong><{$lang_catego}></strong> <a href="<{$feat.linkcat}>"><{$feat.catego}></a> |
        <strong><{$lang_date}></strong> <{$feat.created}> |
        <{if $feat.client}><strong><{$lang_client}></strong> <{$feat.client}><{/if}></span>
        </div>
    </div>
    <{/foreach}>
</div>
<{/if}>
<!-- //Fin de otros trabajos -->

<!-- Start Comments -->
<a name="comments"></a>
<{include file="db:rmc_comments_display.html"}>
<{include file="db:rmc_comments_form.html"}>
<!-- /End comments -->
