<div class="right">

	<h2>KS Commander</h2>

	<p>Удаление файла</p>
	<div style="float:center;width:300px;border:1px solid #F00;background-color:#fff0f0;">
	{$message}
	<form action="{get_url _CLEAR=CKS_ACTION}">
	<input type="hidden" name="module" value="kscommander">
	<input type="hidden" name="CKS_path" value="{$statistics.cur_path}">
	<input type="hidden" name="CKS_object" value="{$statistics.object}">
	<input type="hidden" name="CKS_ACTION" value="delete_confirm">
	<input type="submit" value="Удалить">&nbsp;<input type="button" value="Отмена" onclick="document.location='{get_url _CLEAR="CKS_ACTION" CKS_path=$statistics.cur_path}';">
	</form>
	</div>

</div>