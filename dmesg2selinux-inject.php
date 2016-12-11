<?php

error_reporting(0);
$input_lines = file_get_contents("dmesg.txt");
$ct_array = Array("u","r","object_r","s0");

// [   16.088293]  (0)[218:logd.auditd]type=1400 audit(1262308261.310:64): avc: denied { read write } for pid=240 comm="mediaserver" name="mmcblk0p2" dev="tmpfs" ino=258 
// scontext=u:r:mediaserver:s0 tcontext=u:object_r:nvram_device:s0 tclass=blk_file permissive=1
// sepolicy-inject -s surfaceflinger -t rootfs -c file -p execute -P ./sepolicy

preg_match_all("/.+avc: denied \{(.*)\}.*comm=\"(.*)\".*scontext=(.*)\stcontext=(.*)\stclass=(.*)\spermissive=\d+/i", $input_lines, $output_array);

$sepolicy = Array();

for ($i=0; $i<count($output_array[1]); $i++) {

	$rules = $output_array[1][$i];
	$rules = explode(" ",$rules);
	$rules = array_diff($rules, array(''));
	$rules = implode(",",$rules);

	$comm = trim($output_array[2][$i]);
	// var_dump($comm);
	$comm = explode(":",$comm);
	$comm = array_diff($comm, $ct_array);
	$comm = implode("",$comm);
	
	$source = trim($output_array[3][$i]);
	// var_dump($source);
	$source = explode(":",$source);
	//$source = array_diff($source, $ct_array);
	//$source = implode("",$source);
	$source = $source[2];

	$target = trim($output_array[4][$i]);
	// var_dump($target);
	$target = explode(":",$target);
	$target = array_diff($target, $ct_array);
	$target = implode("",$target);

	
	$context = trim($output_array[5][$i]);

	// echo "sepolicy-inject -s ".$source." -t ".$target." -c ".$context." -p ".$rules." -P ./sepolicy\n";
	$se_rules = $sepolicy[$source][$target][$context];
	if (isset($se_rules)) {
		   $sepolicy[$source][$target][$context] = implode(",",array_unique(array_merge(explode(",",$se_rules),explode(",",$rules)))); 	
		}
		else {
			$sepolicy[$source][$target][$context] = $rules; }
		
}

// var_dump($sepolicy);
foreach($sepolicy as $source => $targets) {
	foreach($targets as $target => $contexts) {
		foreach ($contexts as $context => $rules) {
				echo "sepolicy-inject -s ".$source." -t ".$target." -c ".$context." -p ".$rules." -P ./sepolicy\n";

		}
	}
}
                           	
?>