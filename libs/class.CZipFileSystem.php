<?php
/**
 * \file class.CZipFileSystem.php
 * Файл контенйнер для класса class.CZipFileSystem
 * Файл проекта kolos-cms.
 * 
 * Создан 31.08.2009
 *
 * \author blade39 <blade39@kolosstudio.ru>
 * \version 1.0
 * \todo
 */
/*Обязательно вставляем во все файлы для защиты от взлома*/ 
if( !defined('KS_ENGINE') ) {die("Hacking attempt!");}

class CZipFileSystem extends CFileSystem
{
	protected $filename;
	protected $za;
	protected $bOpened=false;
	
	function __construct($zipFile=false)
	{
		if(!$zipFile) $this->filename='';
		$this->za=new ZipArchive();	
	}
	
	function __destruct()
	{
		if($this->bOpened) $this->za->close();
	}
	
	function open($filename=false)
	{
		if(!$filename) $filename=$this->filename;
		if(!$filename) throw new CFileError('File not found');
		$this->bOpened=true;
		$filename=rtrim($filename,'/');
		if($this->za->open($filename)!==true)
		{
			$this->bOpened=false;
		}
		return $this->bOpened;
	} 
	
	function extractDir($path,$file=false)
	{
		global $KS_FS;
		if(!file_exists(dirname($path))) throw new CFileError('Path not found');
		if(!$this->bOpened) return false;
		$files=$this->GetList(false,array('path'=>$file));
		$folder=basename($file);
		$bResult=true;
		foreach($files as $fileName=>$data)
		{
			if(strpos($fileName,$file)!==false)
			{
				$to=dirname($path).'/'.$folder.substr($fileName,strlen($file));
			}
			if(!file_exists(dirname($path).'/'.$folder))
			{
				$KS_FS->makedir(dirname($path).'/'.$folder);
			}
			if($this->filetype($fileName)=='dir')
			{
				if(!file_exists($to))
				{
					$KS_FS->makedir($to);
				}
				$fileName=trim($fileName,'/');
				$this->extractDir($to,$fileName);
			}
			else
			{
				$bResult&=file_put_contents($to,$this->za->getFromName($fileName));
			}
		}
		return $bResult;
	}
	
	function extract($path,$file=false)
	{
		if(!file_exists(dirname($path))) throw new CFileError('Path not found');
		if(!$this->bOpened) return false;
		return $this->za->extractTo(dirname($path),$file);
	}
	
	function addFile($what,$name)
	{
		if(!file_exists($what)) throw new CFileError('File not found');
		if(!$this->bOpened) return false;
		return $this->za->addFile($what,$name);
	}
	
	function Delete($filename)
	{
		if(!$this->bOpened) return false;
		return $this->za->deleteName($filename);
	}
	
	function filetype($file)
	{
		$arFile=$this->za->statName($file);
		if(!is_array($arFile))
		{
			$arFile=$this->za->statName($file.'/');
		}
		if(strrpos($arFile['name'],'/')==(strlen($arFile['name'])-1))
		{
			$type='dir';
		}	    		
		else
		{
			$type='file';
		}
		return $type;
	}
	
	function GetList($arOrder=false,$arFilter=false)
	{
		$list=array();
		for ($i=0; $i<$this->za->numFiles;$i++) 
		{
    		$arResult[]=$this->za->statIndex($i);
		}
		if($arOrder||$arFilter)
		{
			if(count($arResult)>0)
	    	{
	    		foreach($arResult as $arFile)
	    		{
	    			if($arFilter['path']!='')
	    			{
	    				if(dirname($arFile['name'])!=$arFilter['path']) continue;
	    			}
	    			if(strrpos($arFile['name'],'/')==(strlen($arFile['name'])-1))
					{
						$type='dir';
					}	    		
					else
					{
						$type='file';
					}
	    			$list[$arFile['name']]=Array(
						"Type"=>$type,
            			"Name"=>basename($arFile['name']),
            			"Extension"=>substr($arFile['name'],strrpos($arFile['name'],".")),
            			"Size"=>$arFile['size'],
            			"Date"=>$arFile['mdate']);
            		if($list[$arFile['name']]['Size']==0 && $list[$arFile['name']]['Type']=='dir')
            		{
            			$list[$arFile['name']]['Size']=4096;
            		}
	    		}
	    	}
		}
		else
		{
			$list=$arResult;
		}
		return $list;
	}
}
?>
