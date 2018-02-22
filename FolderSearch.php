<?php


$massiveOfFolders[0] = ".";
$massiveOfFolders[1] = "..";
$searched = "";
$strongStart = "";
$strongFinish = "";
$newString = "";

if (PHP_SAPI === 'cli') {

    if ($_SERVER['argc'] > 1)
    {
        $searched = $_SERVER['argv'][1];
    }
    $newString = PHP_EOL;
    $strongStart = "\033[" . implode(';', array(31)) . 'm';
    $strongFinish = "\033[0m";
} else {
    if (isset($_GET['searchName'])) {
        $searched = $_GET['searchName'];
    }
    $newString = "<br>";
    $strongStart = "<strong>";
    $strongFinish = "</strong>";
}




recurseve(".",0,$searched);


function recurseve(string $memi = ".",int $number = 0,string $search = "") {

    global $massiveOfFolders;

    try {
        if(chdir($memi) === false ){
            return;
        }
    } catch (Exception $e) {
        return;
    }

    $templateDir = getcwd();
    if(in_array($templateDir,$massiveOfFolders)){
        return;
    }
    $massiveOfFolders[] = $templateDir;
    $files = scandir( ".");
    foreach ($files as $file){
        if($file === "." || $file === "..") {
            continue;
        }
        printPath($templateDir."\\".$file,$search,$number);
        if(substr_count($file,".") === 0) {
            recurseve($file, $number + 1,$search);
        }
    }
    chdir("..");
}

function printPath(string $path,string $sub,int $number = 0):void
{
    global $newString;
    if($sub !== "")
    {
        if(substr_count($path, $sub) === 0) return;

        global $strongStart;
        global $strongFinish;
        echo  strstr($path, $sub, true).
            $strongStart.$sub.$strongFinish.
            substr($path,strrpos($path,$sub) + strlen($sub) ).$newString ;
    }
    else {
        $st = str_repeat("---", $number);
        echo $st.substr($path, strrpos($path, "\\") + 1).$newString;
    }

}

