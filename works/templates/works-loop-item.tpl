<div class="<{cycle values="even,odd"}> works-grid">
	<a href="<{$item.link}>"><img src="<{$item.image}>" alt="<{$item.title}>" /></a>
	<div>	
		<h3><a href="<{$item.link}>"><{$item.title}></a> <{if $item.featured}><sup class="works-featured-label"><{$lang_featured}></sup><{/if}></h3>
		<span class="description"><{$item.description}></span>
		<span class="info"><strong><{$lang_catego}></strong> <a href="<{$item.linkcat}>"><{$item.catego}></a> |
		<span><strong><{$lang_date}></strong> <{$item.created}> |
		<{if $item.client}><strong><{$lang_client}></strong> <{$item.client}><{/if}></span>
	</div>
</div>