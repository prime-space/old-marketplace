<?php
	class cron{
		public $stop;
		private $cronId;
		private $time;
		function __construct($name, $interval){
			global $bd;
			
			//exit();
			
			$this->time = time();
			$interval = round($interval - $interval * 0.2);
			
			$this->cronId = $bd->write("
				INSERT INTO cron
				(
					name
				)
				VALUES(
					'".$name."'
				)
			");
			
			$bd->read("
				SELECT id
				FROM cron
				WHERE
					name = '".$name."'
					AND(
						timestamp > ADDDATE(CURRENT_TIMESTAMP, INTERVAL -".$interval." SECOND)
						OR(
							status IS NULL AND
							timestamp > ADDDATE(CURRENT_TIMESTAMP, INTERVAL -5 MINUTE)
						)
					)
				LIMIT 2
			");
			if($bd->rows > 1){
				//Отвергаем, если найден дубликат за текущий интервал - 20% или за последние 5 минут не исполненный.
				$this->stop = true;
				echo 'stop';
				$bd->write("
					UPDATE cron
					SET status = 0
					WHERE id = ".$this->cronId."
					LIMIT 1
				");
				return;
			}
			
		}
		function end(){
			global $bd;
			
			$time = time() - $this->time;
			
			$bd->write("
				UPDATE cron
				SET
					status = 1,
					time = ".$time."
				WHERE id = ".$this->cronId."
				LIMIT 1
			");
		}
		function clearLogs(){
			global $bd;
			$bd->write("
				DELETE FROM cron
				WHERE `timestamp` < ADDDATE(CURRENT_TIMESTAMP, INTERVAL -2 DAY)
			");
		}
	}
?>