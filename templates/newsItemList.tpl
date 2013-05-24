{pages print=true assign=pagesOutput link=$contentItem->getURL()|concat:'?pageNo=%d':SID_ARG_2ND_NOT_ENCODED}

<ul class="newsItems">
	{foreach from=$newsItems item=newsItem}
		<li class="newsItem">
			<h3><a href="{$newsItem->getURL()}{@SID_ARG_1ST}">{$newsItem->title}</a></h3>
			<p class="author">{$newsItem->username} ({@$newsItem->time|time})</p>
			<div class="text">{if $themeModule->displayType == 'full'}{@$newsItem->text}{else}{@$newsItem->teaser}{/if}</div>
		</li>
	{/foreach}
</ul>

{@$pagesOutput}