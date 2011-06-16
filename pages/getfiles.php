<?php
/**
 * \file getfiles.php
 * Файл отвечает за формирование списка файлов и папок в указанной папке
 * может возвращать результат как аякс запрос так и как модульный запрос
 * Файл проекта CMS-local.
 * 
 * Создан 20.12.2008
 *
 * \author blade39 <blade39@kolosstudio.ru>
 * \version 1.0
 * \todo
 */
/*Обязательно вставляем во все файлы для защиты от взлома*/ 
if( !defined('KS_ENGINE') ) {die("Hacking attempt!");}

/*Подключаем библеотеку*/
include_once MODULES_DIR.'/kscommander/libs/CKSCommander.php';
include_once MODULES_DIR.'/main/libs/json.php';
try
{
$Commander=new CKSCommander();

if(array_key_exists('path',$_GET))
{
	$path=$_GET['path'];
}
else
{
	$path=$_SESSION['cur_path'];
}
/*Устанавливаем режим работы*/
$mode = ($_GET['mode'] != '') ? $_GET['mode'] : '';

/*Обработка сортировки*/
$arSortFields=array('name','ext','size','date');
$sOrderField=(in_array($_REQUEST['order'],$arSortFields))?$_REQUEST['order']:'id';
if($_REQUEST['dir']=='asc'):$sOrderDir='asc';$sNewDir='desc';else: $sOrderDir='desc';$sNewDir='asc';endif;
$arOrder=array(
	$sOrderField=>$sOrderDir,
);

/* Подсчитываем общее число файлов в текущем каталоге */
$totalFiles = $Commander->Count(array('path' => $path));

if(!array_key_exists('count',$_GET))
	$_GET['count'] = $_SESSION['count'];
else
{
	if (!$_GET['count'])
		$_GET['count'] = $totalFiles;
	$_SESSION['count'] = $_GET['count'];
}
if(!array_key_exists('p',$_GET))$_GET['p']=$_SESSION['p'];else$_SESSION['p']=$_GET['p'];

$obPages=new CPageNavigation($Commander,false,$_GET['count']);
$Commander->SetSort($sOrderField,$sOrderDir);
$_SESSION['cur_path']=$path;

	$list=$Commander->GetList(array($sOrderField=>$sOrderDir),array('path'=>$path),$obPages->GetLimits($totalFiles));
	$_SESSION['p']=$obPages->iCurrent+1;
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
		$arResult=array("path"=>$path,"error"=>$e);
		echo json_encode($arResult);
		die();
	}
	else
	{
		$smarty->assign('last_error',$e);
	}
}

if ($mode == 'ajax')
{
	if ($_GET['isAjax']==1)
	{
		$smarty->assign('view_url',"onclick=\"FileBrowserDialogue.mySubmit(");
		$smarty->assign('view_url_end',");return false;\"");
	}
	$data['list']=array_values($list);
	$data['pages']=$obPages->GetPages($totalFiles);
	$data['num_visible']=$obPages->GetVisible();
	if($_GET['isAjax']==1)
	{
		$data['view_url']="FileBrowserDialogue.mySubmit";
	}
	echo json_encode($data);
//	$smarty->display('admin/kscommander_list.tpl');
//	echo "[#DEVIDER#]";
//	$smarty->display('admin/kscommander_pages.tpl');
	die();
}
$page='_list';
?>
