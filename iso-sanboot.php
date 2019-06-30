<?php
#you can use mklink /j link c:\xxx\target to create a junction in your hosting folder
#will accept an absolute path or relative path as long as / is used instead of \
#ex : c:/_quickpe or _pxe/quickpe
echo "#!ipxe\n";
echo "set boot-url http://\${next-server}\n";
echo ":start\necho Boot menu\nmenu Selection\n";
$directory = new RecursiveDirectoryIterator($argv[1]);
$display = Array ( 'iso' );
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
		#$pos = strpos($file, ':');
		#if ($pos !== false) {
		##absolute path
		#echo ":". $count ."\nsanboot \${boot-url}/". substr($file,3) . "\ngoto start\n";	
		#	} else {
		##relative path
		#echo ":". $count ."\nsanboot \${boot-url}/". substr($file,0) . "\ngoto start\n";		
		#}
		echo ":". $count ."\nsanboot \${boot-url}/".$filename. "\ngoto start\n";		
}
		$count += 1;
}
echo ":back\nchain menu2.ipxe\n";
?>