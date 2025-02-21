<?php
$bot_token = '7318472062:AAHU7Pi5Q0IEPP4Q9K85x9zMPtL7vKfWQpg';
$chatid = '-1002224132681';
$db_host = '127.0.0.1';
$db_username = 'hasaninc_irooniam';
$db_password = '(DVPGChQZ^mG';
$db_name = 'hasaninc_irooniam';
$file = dirname(__FILE__) . "/{$db_name}-".date("Ymd-His").".sql";
exec("mysqldump --user={$db_username} --password='{$db_password}' --host={$db_host} $db_name --result-file={$file} 2>&1", $output);
print_r($output);
if(filesize($file) > 100){
    gzcompressfile($file);
    $cfile = new CURLFile($file.'.gz');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot{$bot_token}/sendDocument");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ["chat_id" => $chatid, "document" => $cfile]);
    $result = curl_exec($ch);
    curl_close($ch);
    echo $result;
    unlink($file);
    unlink($file.'.gz');
}
function gzcompressfile($source,$level=false){
    $dest=$source.'.gz';
    $mode='wb'.$level;
    $error=false;
    if($fp_out=gzopen($dest,$mode)){
        if($fp_in=fopen($source,'rb')){
            while(!feof($fp_in))
                gzwrite($fp_out,fread($fp_in,1024*512));
            fclose($fp_in);
            }
          else $error=true;
        gzclose($fp_out);
    }
      else $error=true;
    if($error) return false;
      else return $dest;
}
?>