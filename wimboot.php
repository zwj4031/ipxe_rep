<?php
#you can use mklink /j link c:\xxx\target to create a junction in your hosting folder
#will accept an absolute path or relative path as long as / is used instead of \
#ex : c:/_quickpe or _pxe/quickpe
echo "#!ipxe\n";
echo "set boot-url http://\${next-server}\n";
#echo "show platform\n";
echo ":start\necho Boot menu\nmenu Selection\n";
$directory = new RecursiveDirectoryIterator($argv[1]);
$display = Array ( 'wim' );
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
		#use the below if you want to attach a drive to install windows
		#echo "sanhook --drive 0x86 iscsi:\${next-server}::::iqn-1991-05.com.microsoft:target1/n";
		echo "kernel \${boot-url}/wimboot\n";
		#use the below if you want to launch windows install via script rather than manually
		#if the below do not exist, it is fine? seems not->bac cpio magic
		#echo "initrd \${boot-url}/winpeshl.ini winpeshl.ini ||\n";
		#echo "initrd \${boot-url}/install.bat install.bat ||\n";
		echo "iseq \${platform} pcbios && initrd \${boot-url}/BOOTMGR.EXE bootmgr.exe ||\n";		
		echo "iseq \${platform} efi    && initrd \${boot-url}/bootx64.efi bootx64.efi ||\n";				
		#echo "initrd \${boot-url}/BOOTMGR.EXE bootmgr.exe\n";				
		echo "iseq \${platform} pcbios && initrd \${boot-url}/BOOT/BCD bcd ||\n";		
		echo "iseq \${platform} efi    && initrd \${boot-url}/EFI/MICROSOFT/BOOT/BCD bcd ||\n";	
		#echo "initrd \${boot-url}/BOOT/BCD bcd\n";		
		echo "initrd \${boot-url}/BOOT/BOOT.SDI boot.sdi\n";	
		echo "initrd \${boot-url}/".$filename." boot.wim\n";
		echo "boot\n";
}
		$count += 1;
}
echo ":back\nchain menu2.ipxe\n";
?>