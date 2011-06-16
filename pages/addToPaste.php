<?php
/**
 * \file addToPaste.php
 * Сюда сделать описание файла
 * Файл проекта CMS-local.
 * 
 * Создан 22.12.2008
 *
 * \author blade39
 * \version 
 * \todo
 */
/*Обязательно вставляем во все файлы для защиты от взлома*/ 
if( !defined('KS_ENGINE') ) {die("Hacking attempt!");}

/*Подключаем библеотеку*/
include_once MODULES_DIR.'/kscommander/libs/CKSCommander.php';
$Commander=new CKSCommander();
$path=$Commander->cur_path;
/*Устанавливаем режим работы*/
$mode=($_GET['mode']!='')?$_GET['mode']:'';

if(array_key_exists('action',$_POST))
{
	$ok=array();
	$wrong=array();
	$_SESSION['pasteAction']=$_POST['action'];
	$_SESSION['pasteBuffer']=array();

	if(count($_POST['selected'])>0)
	{
		foreach($_POST['selected'] as $file)
		{
			if(preg_match('#^[a-zA-Z_0-9\(\)\[\]\-\.]+$#',$file))
			{
				$_SESSION['pasteBuffer'][]=$path.$file;
				$inbuffer++;
			}
			else
			{
				$wrong[]=array('text'=>"Файл <b>$file</b> не удалось добавить в буффер объмена",'color'=>'#FF0000');
			}
		}
	}
	$ok[]=array('text'=>"В буффер добавлено <b>$inbuffer</b> файлов",'color'=>'#00AA00');
	$smarty->assign('messages',array_merge($ok,$wrong));
	if($mode=='ajax')
	{
		echo json_encode(array("total"=>$inbuffer,"list"=>array_merge($ok,$wrong)));
		die();
	}
}
$page='_message';
?>
