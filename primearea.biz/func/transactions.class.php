<?php
	class transactions {
		private $db;
		private $executedId;
		private $log;
		function __construct(){
			global $db;
			
			$this->db = $db;
		}
		public function create($parameters){
			bcscale(2);
			
			if(//Insufficiently founds
				$parameters['type'] === 0 &&
				!$this->checkFounds($parameters['user_id'], $parameters['amount'])
			)return false;
			
			$executing = $parameters['executing'] ? "'".$parameters['executing']."'" : 'NULL';
			
			$this->db->query("
				INSERT INTO transactions
				(user_id, type, method, method_id, currency, amount, executing)
				VALUES(
					".$parameters['user_id'].",
					".$parameters['type'].",
					'".$parameters['method']."',
					".$parameters['method_id'].",
					".$parameters['currency'].",
					".$parameters['amount'].",
					".$executing."
				)
			");
			
			$transaction_id = $this->db->insert_id();
			
			return $transaction_id;
		}
		public function updateMethodId($transactionId, $methodId){
			$this->db->query("
				UPDATE transactions
				SET method_id = ".$methodId."
				WHERE id = ".$transactionId."
				LIMIT 1
			");
		}
		public function execute(){
			$transactions_request = $this->db->query("
				SELECT id, user_id, amount
				FROM transactions
				WHERE
					executed IS NULL AND
					(
						executing IS NULL OR
						executing < CURRENT_TIMESTAMP
					)
				ORDER BY executing
				LIMIT 15
			");
			
			while($transaction = $this->db->get_row($transactions_request)){
				$this->log = 'user_id:'.$transaction['user_id'].'; amount: '.$transaction['amount'].';';
				$control = $this->calcControl($transaction['user_id'], $transaction['amount']);
				
				$this->db->query("
					UPDATE user
					SET wmrbal = ".$control."
					WHERE id = ".$transaction['user_id']."
					LIMIT 1
				");
				
				
				$executeId = $this->getExecutedId();
				
				$this->db->query("
					UPDATE transactions
					SET
						executed = CURRENT_TIMESTAMP,
						control = ".$control.",
						executed_id = ".$executeId."
					WHERE id = ".$transaction['id']."
					LIMIT 1
				");
				
				$this->log .= ' executeId: '.$executeId.';';
				
				$logs = new logs();
				$logs->add('transactionExecuting', $transaction['id'], $this->log);
			}
		}
		private function getExecutedId(){
			if($this->executedId)$this->executedId++;
			else{
				$executedId = $this->db->super_query("
					SELECT executed_id
					FROM transactions
					ORDER BY executed_id DESC
					LIMIT 1
				");
				$this->executedId = $executedId ? $executedId['executed_id']+1 : 1;
			}
			
			return $this->executedId;
		}
		private function calcControl($user_id, $amount){
			bcscale(2);
			
			$lastControl = $this->getLastControl($user_id);			
			$control = bcadd($lastControl, $amount);
			
			$this->log .= ' lastControl: '.$lastControl.'; control: '.$control.';';
			
			return $control;
		}
		private function getLastControl($user_id){
			$lastControl = $this->db->super_query("
				SELECT control
				FROM transactions
				WHERE user_id = ".$user_id."
				ORDER BY executed_id DESC
				LIMIT 1
			");
			$lastControl = $lastControl ? $lastControl['control'] : 0;
			
			return $lastControl;
		}
		public function checkFounds($user_id, $amount){
			bcscale(2);
			
			$decreaseSum = $this->db->super_query("
				SELECT SUM(amount) AS value
				FROM transactions
				WHERE
					user_id = ".$user_id." AND
					executed IS NULL AND
					type = 0
			");
			$decreaseSum = $decreaseSum ? $decreaseSum['value'] : 0;
			$decreaseSum = bcadd($decreaseSum, $amount);
			
			$needAmount = bcadd($this->getLastControl($user_id), $decreaseSum);
			
			if(bccomp(0, $needAmount) === 1)return false;
			
			return true;
		}
		public function correcting(){
			$this->db->query("
				SELECT user_id
				FROM transactions
				GROUP BY user_id
			");
			$correctedUsers = array(0);
			while($row = $this->db->get_row())$correctedUsers[] = $row['user_id'];
			
			$balances_request = $this->db->query("
				SELECT id, wmrbal
				FROM user
				WHERE id NOT IN(".implode(',', $correctedUsers).")
			");
			while($balances = $this->db->get_row($balances_request)){
				$this->db->query("
					INSERT INTO transactions
					(user_id, type, method, method_id, currency, amount, control, executed, executed_id)
					VALUES(
						".$balances['id'].",
						1,
						'correction',
						0,
						4,
						0,
						".$balances['wmrbal'].",
						CURRENT_TIMESTAMP,
						".$this->getExecutedId()."
					)
				");
			}
		}
		public function retransferTasks(){
			global $currency;
			
			$timeMoneyRetention = $this->db->super_query("
				SELECT value
				FROM setting
				WHERE ids = 3
				LIMIT 1
			");
			$timeMoneyRetention = $timeMoneyRetention['value'];
			
			$orders_request = $this->db->query("
				SELECT
					o.id, o.user_id, o.curr, o.totalSeller,
					ADDDATE(
						DATE_FORMAT(FROM_UNIXTIME(o.time_money_in), '%Y-%m-%d %h:%i:%s'),
						INTERVAL ".$timeMoneyRetention." HOUR
					) AS executing
				FROM `order` o
				LEFT JOIN transactions t ON t.method = 'sale' AND t.method_id = o.id
				WHERE
					o.time_money_in IS NOT NULL AND
					o.time_money_in <> 0 AND
					t.id IS NULL
				ORDER BY o.id DESC
			");
			
			while($order = $this->db->get_row($orders_request)){
				$amount = $currency->convert($order['totalSeller'], $order['curr'], 4, 0);
				
				$this->create(array(
					'user_id' => $order['user_id'],
					'type' => 1,
					'method' => 'sale',
					'method_id' => $order['id'],
					'currency' => 4,
					'amount' => $amount,
					'executing' => $order['executing']
				));
			}
		}
		public function userTransactions($userId){
			
			$tpl = new tpl('userTransactions');
			
			/*$this->db->query("
				SELECT
					method, amount, control, executing, executed,
					(CASE WHEN executed_id IS NULL THEN 1 ELSE 0 END) AS noExecuted
				FROM transactions
				WHERE user_id = ".$userId."
				ORDER BY noExecuted DESC, (CASE WHEN executed_id IS NULL THEN executing ELSE executed_id END) DESC
			");*/
			$this->db->query("
				SELECT
					method, amount, control, executing, executed,
					(CASE WHEN executed_id IS NULL THEN 1 ELSE 0 END) AS noExecuted
				FROM transactions
				WHERE user_id = ".$userId."
				ORDER BY noExecuted DESC, executed_id DESC
			");
			
			$tpl->fory('TRANSACTIONS');
			while($transaction = $this->db->get_row()){
				if($transaction['executed']){
					$execute = $transaction['executed'];
					$executeColor = 'black';
				}else{
					$execute = $transaction['executing'];
					$executeColor = 'red';
				}
				$balance = $transaction['control'] ? $transaction['control'].' руб.' : '';
				$tpl->foryCycle(array(
					'execute' => $execute,
					'executeColor' => $executeColor,
					'method' => $transaction['method'],
					'amount' => $transaction['amount'],
					'balance' => $balance
				));
			}
			$tpl->foryEnd();
			
			return array('status' => 'ok', 'content' => $tpl->content);
		}
	}
?>