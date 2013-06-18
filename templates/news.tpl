{if $newsItem}
	<h2>{$newsItem->title}</h2>

	<div class="newsItem">
		<p class="author">{$newsItem->username} ({@$newsItem->time|time})</p>
		<div class="text">{@$newsItem->text}</div>
	</div>

	{pages print=true assign=pagesOutput link=$newsItem->getURL()|concat:'?pageNo=%d':SID_ARG_2ND_NOT_ENCODED}

	{if $newsItem->enableComments}
		{if $comments|count}
			<h3>{lang}moxeo.comment.comments{/lang}</h3>

			<ul class="comments">
				{cycle name='commentsCycle' values='even,odd' reset=true print=false advance=false}
				{foreach from=$comments item=commentObj}
					<li class="comment {cycle name='commentsCycle'}">
						<p class="author">{$commentObj->username} ({@$commentObj->time|time})</p>
						<p class="text">{@$commentObj->getFormattedComment()}</p>
					</li>
				{/foreach}
			</ul>
		{/if}

		{@$pagesOutput}

		<h3>{lang}moxeo.comment.add{/lang}</h3>
		{@$commentForm}
	{/if}
{else}
	{pages print=true assign=pagesOutput link=$contentItem->getURL()|concat:'?pageNo=%d':SID_ARG_2ND_NOT_ENCODED}

	<ul class="newsItems">
		{foreach from=$newsItems item=newsItem}
			<li class="newsItem">
				<h3><a href="{$newsItem->getURL()}{@SID_ARG_1ST}">{$newsItem->title}</a></h3>
				<p class="author">{$newsItem->username} ({@$newsItem->time|time})</p>
				<div class="text">{@$newsItem->teaser}</div>
			</li>
		{/foreach}
	</ul>

	{@$pagesOutput}
{/if}
