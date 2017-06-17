<{foreach item=work from=$block.works}>
	<blockquote>
        <p><{$work.comment}></p>
        <footer>
            <{$work.lang_cite}>
        </footer>
	</blockquote>
<{/foreach}>

