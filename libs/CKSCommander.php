<?php

if( !defined('KS_ENGINE') )
{
  die("Hacking attempt!");
}

include_once MODULES_DIR.'/kscommander/libs/class.CZipFileSystem.php';

class CKSCommander extends CBaseList
{
	var $filelist;
	var $cur_path;
	var $sort;
	
	protected $arLists;		/**<массив в котором хранятся списки выбранных файлов*/
	
	function CKSCommander()
	{
		if (array_key_exists('CKS_path',$_REQUEST))
		{
			$this->cur_path=$_REQUEST['CKS_path'];
		}
		elseif(array_key_exists('cur_path',$_SESSION))
		{
			$this->cur_path=html_entity_decode($_SESSION['cur_path']);
		}
		else
		{
			$this->cur_path='/uploads/';
		}

		if((preg_match('#\.\.|\/.\/|\/\/#',$this->cur_path))||($this->cur_path=='/')||(!preg_match('#^\/uploads\/.*$#',$this->cur_path)))
		{
			$this->cur_path='/uploads/';
		}
		/*Класс CKSCommander - работа с файловой системой
		Версия 0.1 пробная переменные настройки класса:*/
		$this->_KS_FILEOPS=Array('txt'=>'edit'
						  ,'html'=>'edit'
						  ,'htm'=>'edit'
						  ,'zip'=>'open'
						  ,'jpg'=>'view'
						  ,'gif'=>'view'
						  ,'jpeg'=>'view'
						  ,'png'=>'view'
						  ,'swf'=>'view'); // Файлы которые система может обработать
		// Режим отображения, 1 - отображать все кроме $_KS_HIDDEN
		//                    2 - отображать только $_KS_SHOW
		$this->_KS_SHOWMODE=2;
		$this->_KS_SHOW=Array('/uploads/','/images','/css/');
		$this->_KS_HIDDEN=Array('.'=>0
						 ,'..'=>0); //Файлы которые необходимо скрывать от пользователя 0 - всегда скрывать, 1 - всегда отображать
		// Набор иконок для файлов
		$this->_KS_ICON_SET=Array("dir"=>"icons2/folder.gif",
		                    "file"=>"icons2/file.gif",
		                    "php"=>"icons2/file.gif",
		                    "txt"=>"icons2/file.gif",
		                    "html"=>"icons2/file.gif",
		                    "htm"=>"icons2/file.gif",
		                    "zip"=>"icons2/zip_folder.gif",
		                    "jpg"=>"icons2/file_image.gif",
		                    "jpeg"=>"icons2/file_image.gif",
		                    "png"=>"icons2/file_image.gif",
		                    "bmp"=>"icons2/file_image.gif",
		       				"gif"=>"icons2/file_image.gif");
		//Файлы запрещенные к загрузке 
		$this->_KS_UPLOAD_RESTRICTED=array(
			'php',
			'exe',
			'php3',
			'php5',
		);
		$this->_KS_CAN_UPLOAD_TO_ROOT=false;
		$this->_RESTRICT=array('/uploads'=>array('canDelete'=>0,'canRename'=>0),'/'=>array('canDelete'=>0,'canRename'=>0));
		$this->arLists=array();
	}

	function GetActionType($file,$type="")
	{
		global $_KS_FILEOPS,$_KS_HIDDEN;
		if ($type=="") $type=filetype($file);
		if ($type=='dir') return "CKS_open";
		if ($type=='zip') return 'CKS_open';
		if ($type=='file')
		{
			$extension=strtolower(substr($file,strrpos($file,".")+1));
			if (array_key_exists($extension,$this->_KS_FILEOPS)) return "CKS_".$this->_KS_FILEOPS[$extension];
		}
		return "";
	}
	
	/**
	 * метод проверяет на валидность указанный путь
	 * @param string path - путь который необходимо проверить
	 * @return boolean - результат проверки true - путь валидный, false - нарушение.
	 */
	function IsValidPath($path)
	{
		if (strlen($path) < 1)
			return false;
		if (preg_match('#\.\.|\/.\/#',$path))
			return false;
		$arPath = explode('/', $path);
		if(!in_array('/'.$arPath[1].'/',$this->_KS_SHOW)) return false;
		return true;
	}

	function GetIcon($file,$type="")
	{
		if ($type=="") $type=filetype($file);
		if ($type=='file')
		{
			$extension=strtolower(substr($file,strrpos($file,".")+1));
			if (array_key_exists($extension,$this->_KS_ICON_SET)) return $extension;
		}
		elseif($type=='dir')
		{
			return 'dir';
		}
		return $type;
	}

	function SetSort($field,$dir)
	{
		$this->sort=Array('field'=>$field,'order'=>$dir);
	}

	function Compare($file1,$file2)
	{
 		$order=($this->sort['order']=='asc')?1:-1;
 		if ($order==0) $order=1;
		if ($file1['Type']==$file2['Type'])
		{
			$cmp=0;
			switch ($this->sort['field'])
			{
			    case 'name':
       				$cmp=strcmp($file1['Name'],$file2['Name']);
       				break;
				case 'ext':
					$cmp=strcmp($file1['Extension'],$file2['Extension']);
                    break;
			    case 'size':
			    	if(($file1['Size']-$file2['Size'])==0) $cmp=0;
			    	else
       				$cmp=intval(($file1['Size']-$file2['Size'])/abs($file1['Size']-$file2['Size']));break;
       			case 'date':
       				if(($file1['Date']-$file2['Date'])==0) $cmp=0;
       				else
       				$cmp=intval(($file1['Date']-$file2['Date'])/abs($file1['Date']-$file2['Date']));break;
       			break;
       			default:
       				$cmp=strcmp($file1['Name'],$file2['Name']);
			}
			return $cmp*$order;
		}
		if ($file1['Type']=='dir') return -1;
		if ($file2['Type']=='dir') return 1;
	}
	
	/**
	 * Метод определяет видимость указанного файла в KSCommander.
	 */
	function IsVisible($path,$file)
	{
		if ($this->_KS_SHOWMODE==1)
		{
			$visible=1;
			if (array_key_exists($file,$this->_KS_HIDDEN))
			{
				$visible=$this->_KS_HIDDEN[$file];
			}
		}
		elseif ($this->_KS_SHOWMODE==2)
		{
			foreach($this->_KS_SHOW as $vis)
			{
				$pos=strpos($path.'/'.$file.'/',$vis);
				if ($pos!==false)
				{
					break;
				}
			}
			if ($pos!==false)
			{
				$visible=1;
				if (array_key_exists($file,$this->_KS_HIDDEN))
				{
					$visible=$this->_KS_HIDDEN[$file];
    			}
			}
			else
			{
				$visible=0;
			}
		}
		return $visible;
	}
	
	/**
	 * Данный метод анализирует путь и определяет является ли данный путь зип папкой
	 */
	function IsZipFolder($path)
	{
		$pos=strpos($path,'.zip/');
		if($pos!==false)
		{
			return $pos+5;
		}
		else
		{
			$extension=substr($path,strrpos($path,'.'),4);
			if($extension=='.zip')
			{
				return true;
			}	
		}
		return false;
	}
	
	function Count($arFilter = false, $fGroup = false)
	{
		$list=Array();
		$path=$arFilter['path'];
		if(array_key_exists('type',$arFilter))
		{
			$sType=$arFilter['type'];
		}
		else
		{
			$sType='';
		}
		$path=str_replace('/../',"",$path);
		$old_path=$path;
		$extension=substr($path,strrpos($path,'.'),4);
		$path=ROOT_DIR.$path;
		$count=0;
		$zipFolder=$this->IsZipFolder($path);
		$zip=new CZipFileSystem();
		if (($old_path!="/")&&($old_path!="/uploads/"))
		{
			$list1['На уровень вверх']=Array('Type'=>'dir',
											'Name'=>'На уровень вверх',
											'Full_path'=>substr($old_path,0,strrpos(chop($old_path,'/'),'/'))."/",
											'Size'=>0,
											'Action_type'=>'CKS_open',
											'Icon'=>$this->_KS_ICON_SET['dir']);
			$count++;
		}
		if (is_dir($path))
		{
    		if ($hDir = @opendir($path))
    		{
        		while (($file = readdir($hDir)) !== false)
	        	{
	        		if ($this->_KS_SHOWMODE==1)
	        		{
	        			$visible=1;
	        			if (array_key_exists($file,$this->_KS_HIDDEN))
	        			{
	        				$visible=$this->_KS_HIDDEN[$file];
	        			}
	        		}
	        		elseif ($this->_KS_SHOWMODE==2)
	        		{
	        			foreach($this->_KS_SHOW as $vis)
	        			{

	        				$pos=strpos($old_path.$file.'/',$vis);

							if ($pos!==false)
							{
								break;
							}
						}
	        			if ($pos!==false)
	        			{
	        				$visible=1;
	        				if (array_key_exists($file,$this->_KS_HIDDEN))
	        				{
	        					$visible=$this->_KS_HIDDEN[$file];
		        			}
	        			}
	        			else
	        			{
	        				$visible=0;
	        			}
	        		}
					$filetype=filetype($path.$file);
					if(substr($file,strrpos($file,"."))=='.zip')
	        		{
	        			$filetype='zip';
	        		}
	        		if($sType!='')
	        		{
	        			if($visible!=0)
	        			{
	        				$visible=intval($filetype==$sType);
	        				if($sType=='dir' && $filetype=='zip')
	        				{ 
	        					$visible=1;
	        				}
	           			}
	        		}
	        		if ($visible)
	        		{
		        		$filepath=$old_path.$file;
	    	    		if ($filetype=='dir') $filepath.='/';
        				$list[$file]=Array("Type"=>$filetype,
            						   "Name"=>$file,
            						   "Full_path"=>$filepath,
            						   "Extension"=>substr($file,strrpos($file,".")),
            						   "Action_type"=>$this->GetActionType($path.$file,$filetype),
            						   "Size"=>filesize($path.$file),
            						   "Date"=>filemtime($path.$file),
            						   "Icon"=>$this->_KS_ICON_SET[$this->GetIcon($path.$file,$filetype)]);
            			if($list[$file]['Extension']=='.zip')
            			{
            				$list[$file]['Full_path'].='/';
            			}
            			$size = $list[$file]["Size"];
						$a = array("b", "kb", "mb", "gb");
						$pos = 0;
						while($size >= 1024)
						{
     						$size /= 1024;
     						$pos++;
						}
						$list[$file]['SizeText'] = round($size,2)." ".$a[$pos]; 
            			$count++;
            		}
            	}
	        }
	        else
	        {
	        	throw new CError('KSCOMMANDER_DIR_OPEN_FAIL',144);
	        }
	        closedir($hDir);
	    }
	    //Проверяем это зипфайл или нет
	    elseif(($zipFolder))
	    {
	    	$zippath=substr($path,$zipFolder,-1);
	    	$zfpath=substr($path,0,$zipFolder);
	    	if($zip->open($zfpath))
	    	{
		    	if($zippath!='')
		    	{
		    		$arFilter['path']=$zippath;
		    	}
		    	else
		    	{
		    		$arFilter['path']='.';
		    	}
		    	if(substr($old_path,-1,1)!='/') $old_path.='/';
		    	$arFiles=$zip->GetList(false,$arFilter);
		    	if(count($arFiles)>0)
		    	{
		    		foreach($arFiles as $arFile)
		    		{
		    			$filepath=$old_path.$arFile['Name'];
		    			if ($arFile['Type']=='dir') $filepath.='/';
		    			$list[$arFile['Name']]=Array(
							"Type"=>$arFile['Type'],
	            			"Name"=>$arFile['Name'],
	            			"Full_path"=>$filepath,
	            			"Extension"=>substr($arFile['Name'],strrpos($arFile['Name'],".")),
	            			"Action_type"=>($arFile['Type']=='dir'?'CKS_open':''),
	            			"Size"=>$arFile['Size'],
	            			"Date"=>$arFile['Date'],
	            			"Icon"=>$this->_KS_ICON_SET[$this->GetIcon($arFile['Name'],$arFile['Type'])]);
	            		$a = array("b", "kb", "mb", "gb");
						$pos = 0;
						$size=$list[$arFile['Name']]['Size'];
						while($size>= 1024)
						{
	     					$size /= 1024;
	     					$pos++;
						}
						$list[$arFile['Name']]['SizeText'] = round($size,2)." ".$a[$pos];
	            		$count++;
		    		}
		    	}
	    	}
	    }
	    else
	    {
	    	throw new CError('KSCOMMANDER_NOT_A_DIR',145);
	    }
	    $this->arLists[$path]=array('data'=>$list,'count'=>$count);
		return $count;
	}
	
	protected function _ParseItem(&$item)
	{
		return true;
	}

	function GetList($arOrder=false,$arFilter=false,$arLimit=false,$arSelect=false,$arGroupBy=false)
	{
		$path=$arFilter['path'];
		$path=str_replace('/../',"",$path);
		$old_path=$path;
		$path=ROOT_DIR.$path;
		if (($old_path!="/")&&($old_path!="/uploads/"))
		{
			$list1['u_p']=Array('Type'=>'dir',
											'Name'=>'На уровень вверх',
											'Full_path'=>substr($old_path,0,strrpos(chop($old_path,'/'),'/'))."/",
											'Size'=>0,
											'Action_type'=>'CKS_open',
											'Icon'=>$this->_KS_ICON_SET['dir']);
		}

		if(!array_key_exists($path,$this->arLists))
		{
			$this->count($arFilter);
		}
		$list=$this->arLists[$path]['data'];
		uasort($list,Array($this,'Compare'));
		$list=array_slice($list,$arLimit[0],$arLimit[1],true);
	    if (($old_path!="/")&&($old_path!="/uploads/"))
	    {
		   	$list=array_merge($list1,$list);
		}
		return $list;
	}

	/**
	 * Метод выполняет загрузку файлов, переданных через $_POST
	 * 
	 * @version 1.1
	 * @since 16.04.2009
	 * 
	 * Изменена структура выходного массива: для каждого файла отдаётся не булевское значение
	 * успешности закачки, а строковый идентификатор
	 */
	function Upload()
	{
		global $USER;
	
		if (!$USER->is_admin())
			return false;
			
		$num_files = $_POST['jNum'];
		if ($num_files <= 0)
			return true;
			
		$result = array();
		if((!$this->IsValidPath($this->cur_path))||(($this->cur_path=='/')&&(!$this->_KS_CAN_UPLOAD_TO_ROOT)))
		{
			$result['Все файлы']='error';
			return $result;
		}
		for ($i = 1; $i < $num_files; $i++)
		{
			/* Имя загружаемого непустого файла */
			$filename = $_POST['CKS_file_name_' . $i];
			
			/* Результаты закачки файла */
			$result[$filename] = "";
			if (($_FILES['CKS_file_'.$i]['size'] > 0))
			{
				/* Полное имя будущего файла на сервере */
				$upload_to = ROOT_DIR . $this->cur_path . Translit($filename, true);
				
				preg_match('#\.([a-z0-9]+)$#',$upload_to,$matches);
				if(in_array($matches[1],$this->_KS_UPLOAD_RESTRICTED))
				{
					$result[$filename]="error";
				}
				else
				{
					/* Случай, когда можно успешно закачать новый файл */
					if (!file_exists($upload_to))
					{
						if (@move_uploaded_file($_FILES['CKS_file_'.$i]['tmp_name'], $upload_to))
							$result[$filename] = "ok";
						else
							$result[$filename] = "error"; 		
					}
					else
						$result[$filename] = "doesn't exist";
				}
			}
			else
			{
				if (array_key_exists('CKS_file_name_' . $i, $_POST) && ($filename != ""))
					$result[$filename] = "empty";
			}
		}
		return $result;
	}

	function Del()
	{
		global $USER;
		if (!$USER->is_admin()) return;
		$sourcename=$_REQUEST['CKS_object'];
		if(!$this->IsValidPath($sourcename)) return false;
		$type=filetype(ROOT_DIR.$_REQUEST['CKS_object']);
		if ($type=='file')
		{
			$result=@unlink(ROOT_DIR.$_REQUEST['CKS_object']);
		}
		elseif($type=='dir')
		{
			$result=@rmdir(ROOT_DIR.$_REQUEST['CKS_object']);
		}
		return $result;
	}
	
	/**
	 * Метод удаляет каталоги и файлы
	 * 
	 * @version 1.1
	 * @since 16.04.2009
	 * 
	 * Добавлена возможность удаления непустых каталогов
	 */
	function Delete($sourcename)
	{
		global $USER;

		/* Проверка прав пользователя на удаление */
		if (!$USER->is_admin())
			return false;

		if(!$this->IsValidPath($sourcename)) return false;
		/* Определение типа удаляемого объекта */
		if(strpos($sourcename,'.zip/')>0)
		{
			//Обработка извлечения файла из архива
			$zipFile=substr($sourcename,0,strpos($sourcename,'.zip/')+4);
			$unzipFile=substr($sourcename,strpos($sourcename,'.zip/')+5);
			$obZip=new CZipFileSystem();
			if($obZip->open(ROOT_DIR.$zipFile))
			{
				$result=$obZip->Delete($unzipFile);
			}
		}
		else
		{
			$type = filetype(ROOT_DIR . $sourcename);
			/* Проверка типа удаляемого объекта */
			if ($type == 'file')
				$result = @unlink(ROOT_DIR . $sourcename);
			elseif (($type == 'dir')&&(!(isset($this->_RESTRICT[$sourcename]['canDelete']))||$this->_RESTRICT[$sourcename]['canDelete']))
			{
				/* Каталог можно удалить только в случае, если он пустой,
				 * поэтому предварительно из него нужно удалить всё содержимое */
				$dh = opendir(ROOT_DIR . $sourcename);
				while ($object_in_dir = readdir($dh))
				{
					if ($object_in_dir !== '.' && $object_in_dir !== '..')
					{
						/* Определяем имя очередного объекта в удаляемом каталоге */
						$full_object_name = $sourcename . "/" . $object_in_dir;
						
						/* Рекурсивно удаляем объект */
						$this->Delete($full_object_name);
					}
				}
				closedir($dh);
				$result = @rmdir(ROOT_DIR . $sourcename);
			}
			else
			{
				return false;
			}
		}
		return $result;
	}
	
	function CopyFile($from,$to)
	{
		global $USER,$KS_FS;
		if (!$USER->is_admin()) return;
		if(!$this->IsValidPath($from)) return false;
		if(!$this->IsValidPath($to)) return false;
		if(strpos($from,'.zip/')>0)
		{
			//echo $from;
			//Обработка извлечения файла из архива
			$zipFile=substr($from,0,strpos($from,'.zip/')+4);
			$unzipFile=substr($from,strpos($from,'.zip/')+5);
			$obZip=new CZipFileSystem();
			if($obZip->open(ROOT_DIR.$zipFile))
			{
				if($obZip->filetype($unzipFile)=='dir')
				{
					$result=$obZip->extractDir(ROOT_DIR.$to,$unzipFile);
				}
				else
				{
					$result=$obZip->extract(ROOT_DIR.$to,$unzipFile);
				}
			}
			else
				throw new CFileError("Can't open zip file");
		}
		elseif(strpos($to,'.zip/')>0)
		{
			//Обработка добавления файла в архив
			$zipFile=substr($to,0,strpos($to,'.zip/')+4);
			$obZip=new CZipFileSystem();
			if($obZip->open(ROOT_DIR.$zipFile))
				$obZip->addFile(ROOT_DIR.$from,basename($from));
			else
				throw new CFileError("Can't open zip file");
		}
		else
		{
			$type=filetype(ROOT_DIR.$from);
			if ($type=='file')
			{
				$result=copy(ROOT_DIR.$from,ROOT_DIR.$to);
			}
			elseif($type=='dir')
			{
				$result=$KS_FS->dircopy(ROOT_DIR.$from,ROOT_DIR.$to);
			}
		}
		return $result;
	}
	
	function RenameFile($from,$to)
	{
		global $USER,$KS_FS;
		if (!$USER->is_admin()) return;
		preg_match('#\.([a-z0-9]+)$#',$to,$matches);
		if(in_array($matches[1],$this->_KS_UPLOAD_RESTRICTED))
		{
			return false;
		}
		if(!(isset($this->_RESTRICT[$from]['canRename']))||$this->_RESTRICT[$from]['canRename'])
		$result=rename(ROOT_DIR.$from,ROOT_DIR.dirname($from).'/'.$to);
		else
		$result=false;
		return $result;
	}

	/**
	 * Метод создаёт новую директорию по указанному пути
	 * 
	 * @version 1.1
	 * @since 13.05.2009
	 * 
	 * Добавлена проверка имени создаваемой директории
	 * 
	 * @param string @dirname Имя создаваемой директории
	 * @return bool
	 */
	function Newdir($dirname = "")
	{
		global $USER;
		
		/* Проверка прав доступа пользователя */
		if (!$USER->is_admin())
			return false;
			
		/* Проверка правильности пути, где будем создавать директорию */
		if (!$this->IsValidPath($this->cur_path))
			return false;

		/* Проверка возможности создания директории в корне */			
		if (($this->cur_path=='/') && (!$this->_KS_CAN_UPLOAD_TO_ROOT))
			return false;
			
		/* Проверка имени директории */
		if (!preg_match("#^[A-Za-z0-9_\-]+$#", $dirname))
			return false;
		
		$res = @mkdir(ROOT_DIR . $this->cur_path . $dirname);
		return $res;
	}
}
?>
