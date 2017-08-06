<?php
	namespace BahaiCalendar\BadiCal;
	
	class Date extends Base{
		static function addZero($n){
			return sprintf("%02d", $n);
		}
		
		public $y, $m, $d, $type;
		
		public function __construct($year, $month, $day, $type = self::GREGORIAN){
			$this->y = $year;
			$this->m = $month;
			$this->d = $day;
			$this->type = $type;
		}
		
		public function year($formatted = false){
			return $formatted ? self::addZero($this->y) : $this->y;
		}
		
		public function month($formatted = false){
			return $formatted ? self::addZero($this->m) : $this->m;
		}
		
		public function monthName(){
			switch($this->type){
				case self::GREGORIAN :
					return self::$GREGORIAN_MONTHS[$this->m - 1];
					break;
				case self::BADI :
				default :
					return self::$BADI_MONTHS[$this->m - 1];
					break;
			}
		}
		
		public function day($formatted = false){
			return $formatted ? self::addZero($this->d) : $this->d;
		}
		
		public function gToDate($gDate){
			return new DateTime(
				$gDate->year(), 
				$gDate->month(), 
				$gDate->day()
			);
		}
	}