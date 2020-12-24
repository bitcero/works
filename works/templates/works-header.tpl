<{if $works_location == 'work-details' && $work.status != 'public'}>
<div class="pw-preview infoMsg">
    <{$lang_preview}>
</div>
<{/if}>
<div id="pw-header">
    <a href="<{$url_home}>"><{$pw_title}></a> |
    <a href="<{$url_recent}>"><{$lang_recentsall}></a> |
    <a href="<{$url_featured}>"><{$lang_featuredall}></a>
</div>
