{config_load file=admin.conf section=kscommander}
{strip}
<div class="pages_nav">
		Страница:
		{if $pages.active==1}
		<a>
			<img src="{#images_path#}/icons2/first_disabled.gif" alt="&lt;" height="9" width="8">
		</a> 
		<a>
			<img src="{#images_path#}/icons2/previous_disabled.gif" alt="&lt;" height="9" width="8">
		</a>
		{else} 
		<a href="{get_url _CLEAR="p[0-9]*" i=$pages.index}&p{$pages.index}=1" onclick="ksCommander.page=1;return ksCommander.LoadFiles('{$statistics.cur_path}')">
			<img src="{#images_path#}/icons2/first.gif" alt="&lt;" height="9" width="8">
		</a> 
		<a href="{get_url _CLEAR="p[0-9]*" i=$pages.index}&p{$pages.index}={$pages.active-1}"  onclick="ksCommander.page={$pages.active-1};return ksCommander.LoadFiles('{$statistics.cur_path}')">
			<img src="{#images_path#}/icons2/previous.gif" alt="&lt;" height="9" width="8">
		</a>
		{/if}
		{foreach from=$pages.pages item=oItem key=oKey}
			{if $oKey>=$pages.active-3 and $oKey<=$pages.active + 3}
				{if $oKey==$pages.active}
					<span>{$oKey}</span>
				{else}
					<a href="{get_url _CLEAR="p[0-9]*" i=$pages.index}&p{$pages.index}={$oItem}" onclick="ksCommander.page={$oKey};return ksCommander.LoadFiles('{$statistics.cur_path}')">{$oKey}</a>
				{/if}
			{/if}
		{/foreach}
		{if $pages.active<$pages.num}
		<a href="{get_url _CLEAR="p[0-9]*" i=$pages.index}&p{$pages.index}={$pages.active+1}" 
		onclick="ksCommander.page={$pages.active+1};return ksCommander.LoadFiles('{$statistics.cur_path}')">
			<img src="{#images_path#}/icons2/next.gif" alt="arrow_icon" height="9" width="8">
		</a>
		<a href="{get_url _CLEAR="p[0-9]*" i=$pages.index}&p{$pages.index}={$pages.num}" 
		onclick="ksCommander.page={$pages.num};return ksCommander.LoadFiles('{$statistics.cur_path}')">
			<img src="{#images_path#}/icons2/last.gif" alt="arrow_icon" height="9" width="8">
		</a> 
		{else}
		<a>
			<img src="{#images_path#}/icons2/next_disabled.gif" alt="&gt;" height="9" width="8">
		</a>
		<a>
			<img src="{#images_path#}/icons2/last_disabled.gif" alt="&gt;" height="9" width="8">
		</a>
		{/if}
	</div>
	<div id="selectedCount"></div>
	<div class="pages_qnt">
		<label>
			На страницу:
			<select id="show_num1" onchange="ksCommander.visible=document.getElementById('show_num1').value;ksCommander.LoadFiles();" style="width:100px;">
				<option value="10" {if $num_visible==10}selected{/if}>[10]</option>
				<option value="20" {if $num_visible==20}selected{/if}>[20]</option>
				<option value="50" {if $num_visible==50}selected{/if}>[50]</option>
				<option value="100" {if $num_visible==100}selected{/if}>[100]</option>
				<option value="{$dataList.TOTAL}" {if $num_visible==$pages.TOTAL}selected{/if}>[все]</option>
			</select>
		</label> 
	</div>
{/strip}