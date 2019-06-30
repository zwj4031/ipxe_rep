<?php
#you can use mklink /j link c:\xxx\target to create a junction in your hosting folder
#will accept an absolute path or relative path as long as / is used instead of \
#ex : c:/_quickpe or _pxe/quickpe
echo "#!ipxe\n";
echo "set boot-url http://\${next-server}\n";
#echo "show platform\n";
echo ":start\necho Boot menu\nmenu Selection\n";
$directory = new RecursiveDirectoryIterator($argv[1]);
$display = Array ( 'iso' ); #could be Array ( 'iso', 'raw' ) 
$count = 0;
foreach(new RecursiveIteratorIterator($directory) as $file)
{
    if (in_array(strtolower(array_pop(explode('.', $file))), $display))
        echo "item ". $count . " " . array_pop(explode("/", $file))  . "\n";
        $count += 1;
}
echo "item back back\n";
echo "choose os && goto \${os}\n";
$count = 0;
foreach(new RecursiveIteratorIterator($directory) as $file)
{
    if (in_array(strtolower(array_pop(explode('.', $file))), $display)){
		$filename= array_pop(explode("/", $file));
		$filename = ltrim($filename, chr(92)); 			
		echo ":". $count."\n";
		#memdisk, memdisk iso, or memdisk raw ?
		echo "kernel \${boot-url}/memdisk iso\n";
		echo "initrd \${boot-url}/".$filename."\n";
		echo "boot\n";
}
		$count += 1;
}
echo ":back\nchain menu2.ipxe\n";
?>