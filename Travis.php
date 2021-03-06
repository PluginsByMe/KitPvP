<?php
$server = proc_open(PHP_BINARY . " src/pocketmine/PocketMine.php --no-wizard", [
//$server = proc_open("./start.sh --no-wizard", [
	0 => ["pipe", "r"],
	1 => ["pipe", "w"],
	2 => ["pipe", "w"]
], $pipes);
if(!is_resource($server)){
	die('Failed to create process');
}
fwrite($pipes[0], "plugins\nhelp\nKits\nFashion\nstop\n\n");
fclose($pipes[0]);
while(!feof($pipes[1])){
	echo fgets($pipes[1]);
}
fclose($pipes[1]);
fclose($pipes[2]);
echo "\n\nReturn value: ". proc_close($server) ."\n";
if(count(glob("crashdumps/CrashDump*.log")) === 0){
	echo "The KitPvP plugin has no errors and is working well!\nYAY!";
	exit(0);
}else{
	echo "The KitPvP plugin has a syntax error.\nIt will be fixed whenever the developers have a chance.\nPlease be patient and wait for them to fix it.";
	exit(1);
}
