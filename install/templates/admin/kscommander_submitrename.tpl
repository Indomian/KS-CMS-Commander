<div class="manage">
<input type="button" onclick="doRename()" value="Переименовать" class="refresh"/>
<input type="button" value="Отмена" onclick="if(document.ksPath) return LoadFiles(document.ksPath); else return LoadFiles('{$statics.cur_path}');"/>
</div>