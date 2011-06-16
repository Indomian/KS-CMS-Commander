<div style="width:300px;border:1px solid #a0afa0;background-color:#F0FFF0;padding:4px;">
{foreach from=$messages item=oItem key=oKey}
<font color="{$oItem.color}">{$oItem.text}</font><br/>
{/foreach}
</div>