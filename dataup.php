<?php
	/*
	*File：
	*Author：
	 
	
	*/	
header("Content-Type:text/html;charset=UTF-8");
$msgar=array();
$post=[
  'filename',
  'data',
  'type',
  'numb',
  'add'
  ];
  foreach ($post as $k=>$v){
   
    if ($_POST[$v]){
    $postdata[$v]=$_POST[$v];
  }
  }

    if (!$postdata['filename'] || !$postdata['type']){
      echo "缺少必要参数";
      exit;
    }else{
      $postdata['filename']=$postdata['filename'].'.txt';   
      if ($postdata['data']==''){
        $postdata['data']=' ';
      }
      if (!file_exists($postdata['filename'])){
       if ($postdata['numb']){
         $postdata['data']=$postdata['data']."\r\n";
       }
       $myfile= fopen($postdata['filename'], "a+") or die("Unable to open file!");
       fwrite($myfile,$postdata['data']);fclose($myfile);
       $newadd=true;
     }
      $msgar['code']='1';
    }

     
   switch ($postdata['type']) {
	  case 'get':
        $myfile = fopen($postdata['filename'], "r") or die("Unable to open file!");
       if ($postdata['numb']){          
             $size= 10244;               
         while (false !== ($char = fgets($myfile,$size))) {       
              $mun++;
            if ($postdata['numb']=='all'){
             $msgar['data'][]=str_replace(array("\r\n", "\r", "\n"), "",$char);
            }else{ 
              if ($mun==$postdata['numb']){
             $msgar['data']=str_replace(array("\r\n", "\r", "\n"), "",$char);
              $msgar['msg']="获取第{$mun}行成功";
                break;
            }
            }
         }   
       }else{
         $msgar['data']= str_replace(array("\r\n", "\r", "\n"), "",fread($myfile,filesize($postdata['filename'])));
        $msgar['msg']="获取成功";
       }
       fclose($myfile);
     break;
     case 'set': 
             if ($postdata['numb']){ 
               $myfile = fopen($postdata['filename'], "r") or die("Unable to open file!");
             $size= 10244;               
         while (false !== ($char = fgets($myfile,$size))) {       
              $mun++;
              if ($mun==$postdata['numb']){
             $msgar['data']=$msgar['data'].$postdata['data']."\r\n";
              $msgar['msg']="修改第{$mun}行成功";
              $success=true;  
            }else{
             $msgar['data']=$msgar['data'].$char;
              }
         } 
          if (!$success){
          
            if ($mun<1){
              /*
              $msgar['msg']="新增第1行成功";
              $msgar['data']=$postdata['data']."\r\n";
            */
              $msgar['msg']="修改行数超出上限";
             $msgar['code']='0';
            }elseif(!$newadd){
              $mun++;
              $msgar['msg']="新增第{$mun}行成功";
              $msgar['data']=$msgar['data'].$postdata['data']."\r\n";
            
            }

          }
          fclose($myfile); 
             $myfile = fopen($postdata['filename'], "w") or die("Unable to open file!");
     fwrite($myfile,$msgar['data']);fclose($myfile);    
       $msgar['data']='';
             }else{
    $myfile = fopen($postdata['filename'], "w") or die("Unable to open file!");
     fwrite($myfile,$postdata['data']);fclose($myfile);   
     $msgar['msg']="修改成功";
       } 
     
       break;
     case 'add': 
     $myfile = fopen($postdata['filename'], "a+") or die("Unable to open file!");
     fwrite($myfile,$postdata['data']);fclose($myfile);
     $msgar['msg']="追加成功";
     break;
     case 'del': 
                    if ($postdata['numb']){ 
               $myfile = fopen($postdata['filename'], "r") or die("Unable to open file!");
             $size= 10244;               
         while (false !== ($char = fgets($myfile,$size))) {       
              $mun++;
              if ($mun==$postdata['numb']){
            // $msgar['data']=$msgar['data'].$postdata['data']."\r\n";
              $msgar['msg']="删除第{$mun}行成功";
              $success=true;  
            }else{
             $msgar['data']=$msgar['data'].$char;
              }
         } 
          if (!$success){
              $msgar['msg']="删除行数超出上限";
             $msgar['code']='0';
          }
          fclose($myfile); 
             $myfile = fopen($postdata['filename'], "w") or die("Unable to open file!");
     fwrite($myfile,$msgar['data']);fclose($myfile);    
       $msgar['data']='';
                    }else{
      unlink($postdata['filename']);
     $msgar['msg']="删除成功";        
         }

       
     break;
     default:
		$msgar['msg']="通信成功";
	 break;
   }
   echo json_encode($msgar,JSON_UNESCAPED_UNICODE);     

        
        