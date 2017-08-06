<?php
	require_once(__DIR__.DIRECTORY_SEPARATOR.'load.php');
	
	$nl = "\n";
	$tb = "\t";
	$group = $argc > 1 ? $argv[1] : 'Basic';
	
	if(strpos($group, '.json') === false){$group .= '.json';}
	
	$testgroup = json_decode(file_get_contents($group), TRUE);
	$max = 0;
	foreach($testgroup['tests'] as $test){
		$max = max(strlen($test['name']), $max);
	}
	$max += 5;
	
	$title = 'Test Group: '.$testgroup['name'];
	echo $title.$nl
		.str_pad('-', strlen($title), '-').$nl;
	
	$failures = array();
	foreach($testgroup['tests'] as $test){
		echo $tb.str_pad($test['name'], $max, '.');
		switch($test['type']){
			case 'two-way' :
				$dates = array();
				$dates[$test['input']['type']] = 
					array(
						'date' =>
							new \BahaiCalendar\BadiCal\Date(
								$test['input']['year'], 
								$test['input']['month'], 
								$test['input']['date']
							),
						'daytime' => $test['input']['daytime'],
					);
				$dates[$test['output']['type']] = 
					array(
						'date' =>
							new \BahaiCalendar\BadiCal\Date(
							$test['output']['year'], 
							$test['output']['month'], 
							$test['output']['date']
						),
						'daytime' => $test['output']['daytime'],
					);

				$badi = 
					\BahaiCalendar\BadiCal\Convert::GregorianToBadi(
						$dates['Gregorian']['date']->y, 
						$dates['Gregorian']['date']->m, 
						$dates['Gregorian']['date']->d,
						!$dates['Gregorian']['daytime']
					);
				$greg = 
					\BahaiCalendar\BadiCal\Convert::BadiToGregorian(
						$dates['Badi']['date']->y, 
						$dates['Badi']['date']->m, 
						$dates['Badi']['date']->d,
						!$dates['Badi']['daytime']
					);
				
				
				if($dates['Gregorian']['date']->y != $greg->y
					|| $dates['Gregorian']['date']->m != $greg->m
					|| $dates['Gregorian']['date']->d != $greg->d
					|| $dates['Badi']['date']->y != $badi->y
					|| $dates['Badi']['date']->m != $badi->m
					|| $dates['Badi']['date']->d != $badi->d){
					$failures[] = $test['name'];
					echo 'failed'.$nl;
				}else{
					echo 'success'.$nl;
				}
				
				break;
		}
	}
	
	$tests = count($testgroup['tests']);
	$fails = count($failures);
	$successes = $tests - $fails;
	echo $tests. ' tests, ' . $fails. ' failures, ' . $successes. ' successes';
