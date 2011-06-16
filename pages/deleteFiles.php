<?php
/**
 * \file deleteFiles.php
 * Файл отвечает за удаление группы выделенных файлов в файловом менеджере.
 * Файл проекта CMS-local.
 * 
 * Создан 22.12.2008
 *
 * \author blade39 <blade39@kolosstudio.ru>
 * \version 1.0
 * \todo
 */
/*Обязательно вставляем во все файлы для защиты от взлома*/ 
if( !defined('KS_ENGINE') ) {die("Hacking attempt!");}

/*Подключаем библеотеку*/
include_once MODULES_DIR.'/kscommander/libs/CKSCommander.php';
$Commander=new CKSCommander();
$path=$Commander->cur_path;
/*Устанавливаем режим работы*/
$mode=($_REQUEST['mode']!='')?$_REQUEST['mode']:'';

if($_POST['action']=='delete')
{
	$ok=array();
	$wrong=array();
	if(!is_array($_POST['selected'])) $_POST['selected']=array($_POST['selected']);
	if(count($_POST['selected'])>0)
	{
		foreach($_POST['selected'] as $file)
		{
			if(preg_match('#^[a-zA-Z_0-9\(\)\[\]\-\.]+$#',$file))
			{
				if($Commander->Delete($path.$file))
				{
					$deleted++;
					$ok[]=array('text'=>"Файл <b>$file</b> успешно удален",'color'=>'#00AA00');
				}
				else
				{
					$wrong[]=array('text'=>"Файл <b>$file</b> удалить не удалось",'color'=>'#FF0000');
				}
			}
			else
			{
				$wrong[]=array('text'=>"Файл <b>$file</b> удалить не удалось",'color'=>'#FF0000');
			}
		}
	}
	$smarty->assign('messages',array_merge($ok,$wrong));
	if($mode=='ajax')
	{
		echo json_encode(array_merge($ok,$wrong));
		die();
	}
}
$page='_message';
?>
