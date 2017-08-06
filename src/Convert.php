<?php
	namespace BahaiCalendar\BadiCal;
	
	class Convert extends Base{
		static public function getNawruz($gDate){
			return array_key_exists($gDate->year(), self::$NAWRUZ) 
				? self::$NAWRUZ[$gDate->year()] : 21; 
		}
		
		static public function beforeNawruz ($gDate){
			return $gDate->month() < 3 || ($gDate->month() == 3 && $gDate->day() < self::getNawruz($gDate));
			
			return $gDate->month() < 4 
				&& $gDate->day() < self::getNawruz($gDate);
		}
		
		static public function getNawruzDate($gYear){
			return new \DateTime(
				$gYear.'-'
				.'03-' 
				.self::getNawruz(new Date($gYear, 5, 1)));
		}
		
		static protected function gToDate($gDate){
			return new \DateTime(
				$gDate->year(true).'-' 
				.$gDate->month().'-' 
				.$gDate->day()
			);
		}
		
		static public function getAyyamiha($bYear){
			$gYear = $bYear + 1843;
			
			$this_nr =
				new \DateTime(
					$gYear.'-' 
					.'03-' 
					.self::getNawruz(new Date($gYear, 4, 1))
				);
			
			$next_nr =
				new \DateTime(
					($gYear + 1).'-' 
					.'03-' 
					.self::getNawruz(new Date($gYear, 4, 1))
				);
			
			$int = $this_nr->diff($next_nr);
			return $int->format('%a') - (19 * 19) - 1;
		}
		
		static public function GregorianToBadi($one, $two, $three, $startOf = false){
			if($startOf){
				$three++;
			}
			
			$gDate = new Date($one, $two, $three);
			
			$year = $gDate->year();
			if(self::beforeNawruz($gDate)){
				$year--;
			}
			
			$nr_date = self::getNawruzDate($year);
			$gDatetime = self::gToDate($gDate);
			$int = $nr_date->diff($gDatetime);
			
			$num_days = floor((int)$int->format('%a')) + 1;
			
			$bYear = $year - 1843;
			
			$months = array();
			for($i = 0; $i < 18; $i++){
				$months[] = 19;
			}
			$months[] = self::getAyyamiha($bYear);
			$months[] = 19;
			
			$m = 0;
			while($num_days > $months[$m]){
				$num_days -= $months[$m++];
			}
			
			return new Date($bYear, $m + 1, $num_days, self::BADI);
		}
		
		static public function BadiToGregorian($one, $two, $three, $startsOn = false){
			$bDate = new Date($one, $two, $three);
			
			$nr_year = $bDate->year() + 1843;
			$nr_date = self::getNawruzDate($nr_year);
			
			$num_days = 
				min($bDate->month() - 1, 18)*19 //first 18 months
				+ ($bDate->month() > 19 ? self::getAyyamiha($bDate->year()) : 0)
				+ $bDate->day() //days in month
				- 1
			;
			
			if($startsOn){
				$num_days--;
			}
			
			$date = clone $nr_date;
			if($num_days > 0){
				$date->add(new \DateInterval('P'.$num_days.'DT4H'));//4 hours to deal with DST
			}else if($num_days < 0){
				$num_days++;				
				$date->sub(new \DateInterval('P'.abs($num_days).'DT4H'));//4 hours to deal with DST
			}
			
			return new Date($date->format('Y'), $date->format('n'), $date->format('j'), self::GREGORIAN);
		}
	}