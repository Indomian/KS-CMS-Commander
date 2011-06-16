<?php
/**
 * \file renameFiles.php
 * Файл предназначен для генерации страницы переименовывания файлов
 * Файл проекта CMS-local.
 * 
 * Создан 23.12.2008
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
/*Обработка сортировки*/
$arSortFields=array('name','ext','size');
$sOrderField=(in_array($_REQUEST['order'],$arSortFields))?$_REQUEST['order']:'id';
if($_REQUEST['dir']=='asc'):$sOrderDir='asc';$sNewDir='desc';else: $sOrderDir='desc';$sNewDir='asc';endif;
$arOrder=array(
	$sOrderField=>$sOrderDir,
);

/*Отработка постраничной навигации через сессии*/
if(!array_key_exists('count',$_GET))$_GET['count']=$_SESSION['count'];else$_SESSION['count']=$_GET['count'];
if(!array_key_exists('p',$_GET))$_GET['p']=$_SESSION['p'];else$_SESSION['p']=$_GET['p'];

$obPages=new CPageNavigation($Commander,false,$_GET['count']);
$Commander->SetSort($sOrderField,$sOrderDir);
$_SESSION['cur_path']=$path;
if($_POST['action']=='doRename')
{
	$ok=array();
	$wrong=array();
	try
	{
		if(count($_POST['rename'])>0)
		{
			$path=$Commander->cur_path;
			foreach($_POST['rename'] as $file=>$newfile)
			{
			    if(!file_exists(ROOT_DIR.$path.$newfile))
			    {
				if($Commander->RenameFile($path.$file,$newfile))
				{
					$ok[]=array('text'=>"Файл <b>$file</b> успешно переименован в <b>$newfile</b></br>",'color'=>'#00aa00');
				}
				else
				{
					$wrong[]=array('text'=>"Не удалось переименовать файл <b>$file</b> в <b>$newfile</b></br>",'color'=>'#FF0000');
				}
			    }
			    else
			    {
				$wrong[]=array('text'=>"Не удалось переименовать файл <b>$file</b> файл <b>$newfile</b> уже существует</br>",'color'=>'#FF0000');
			    }
			}
		}
	}
	catch (CError $e)
	{
		if($e->error==145) $_SESSION['cur_path']='/uploads/';
		if($mode=='ajax')
		{
			echo $e;
			die();
		}
		else
		{
			$smarty->assign('last_error',$e);
		}
	}
	$smarty->assign('messages',array_merge($ok,$wrong));
	if($mode=='ajax')
	{
		echo json_encode(array_merge($ok,$wrong));
		die();
	}
	$page='_list';
}
else
{
	try
	{
		$totalFiles=$Commander->Count(array('path'=>$path));
		$list=$Commander->GetList(array($sOrderField=>$sOrderDir),array('path'=>$path),$obPages->GetLimits($totalFiles));
		if(($_POST['action']=='rename')&&(count($_POST['selected'])>0))
		{
			$arResult=array();
			$mylist=$list;
			foreach($mylist as $file=>$filedata)
			{
				if(in_array($file,$_POST['selected']))
				{
					$arResult[$file]=$filedata;
					$arResult[$file]['editName']=1;
					unset($list[$file]);
				}
			}
			$list=array_merge($arResult,$list);
		}
		//Формируем массив статистических данных
		$statistics=Array(
			"cur_path"=>$path,
			"object"=>$_REQUEST['CKS_object']
		);
		
		$size = disk_free_space(ROOT_DIR);
		$a = array("Байт", "КБайт", "МБайт", "ГБайт");
		$pos = 0;while($size >= 1024){$size /= 1024;$pos++;}
		$statistics['total_free_space'] = number_format($size,2,',',' ')." ".$a[$pos]; 
		$size = disk_total_space(ROOT_DIR);
		$pos = 0;while($size >= 1024){$size /= 1024;$pos++;}
		$statistics['total_disk_space'] = number_format($size,2,',',' ')." ".$a[$pos]; 
		$size = disk_total_space(ROOT_DIR)-disk_free_space(ROOT_DIR);
		$pos = 0;while($size >= 1024){$size /= 1024;$pos++;}
		$statistics['total_occup_space'] = number_format($size,2,',',' ')." ".$a[$pos];
		//Подготавливаем вывод
		$smarty->assign('statistics',$statistics);
		$smarty->assign('pages',$obPages->GetPages($totalFiles));
		$smarty->assign('num_visible',$obPages->GetVisible());
		$smarty->assign('order',Array('newdir'=>$sNewDir,'curdir'=>$sOrderDir,'field'=>$sOrderField));
		$smarty->assign('list',$list);
	}
	catch (CError $e)
	{
		if($e->error==145) $_SESSION['cur_path']='/uploads/';
		if($mode=='ajax')
		{
			echo $e;
			die();
		}
		else
		{
			$smarty->assign('last_error',$e);
		}
	}
	if($mode=='ajax')
	{
		$smarty->display('admin/kscommander_renamelist.tpl');
		echo "[#DEVIDER#]";
		$smarty->display('admin/kscommander_submitrename.tpl');
		die();
	}
	$page='_renamelist';
}
?>
