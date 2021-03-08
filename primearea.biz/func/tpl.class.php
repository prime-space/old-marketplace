<?php
	class tpl{
		public $content;
		public $fory_arr = array('model'=>'','model_tags'=>'','content'=>'');
		public function __construct($v){
			$this->content = file_get_contents(TPL_DIR.$v.'.tpl');
		}
		public function ify($val, $variant = false,$data = false){
			$content = $data ? $data : $this->content;
			preg_match_all('#{{IF:'.$val.'}}(.*?){{ENDIF:'.$val.'}}#is', $content, $a);
			$return['orig'] = $a[0][0];
			preg_match_all('#{{IF:'.$val.'}}(.*?){{ELSE:'.$val.'}}#is', $return['orig'], $b);
			$return['if'] = $b[1][0];
			preg_match_all('#{{ELSE:'.$val.'}}(.*?){{ENDIF:'.$val.'}}#is', $return['orig'], $a);
			$return['else'] = $a[1][0];
			if($data)return str_replace($return['orig'], $return[$variant == 1 ? 'if' : 'else'], $content);
			if($variant == 1)$this->set($return['orig'],$return['if']);
			elseif($variant == 2)$this->set($return['orig'],$return['else']);
			return $return;
		}
		public function set($param, $val = false){
			if(is_array($param))foreach($param as $k => $v)$this->content = str_replace('{'.$k.'}', $v, $this->content);
			else $this->content = str_replace($param, $val, $this->content);
		}
		public function fory($val){
			$this->fory_arr['content'] = '';
			preg_match_all('#{{FOR:'.$val.'}}(.*?){{ENDFOR:'.$val.'}}#is', $this->content, $a);
			$this->fory_arr['model'] = $a[1][0];
			$this->fory_arr['model_tags'] = $a[0][0];
		}
		public function foryCycle($arr){
			$a = $this->fory_arr['model'];
			foreach($arr as $k => $v){
				if(is_array($v))$a = $this->ify($v[0],$v[1],$a);
				else $a = str_replace("{".$k."}", $v, $a);
			}
			$this->fory_arr['content'] .= $a;
		}
		public function foryEnd(){
			$this->set($this->fory_arr['model_tags'], $this->fory_arr['content']);
		}
		public function switchy($val, $case){
			$return = array(	'orig'=>'',
								'case'=>array(),
								'key'=>array());
			preg_match_all('#{{SWITCH:'.$val.'}}(.*?){{ENDSWITCH:'.$val.'}}#is', $this->content, $a);
			$return['orig'] = $a[0][0];
			preg_match_all('#{{CASE:(.*?)}}(.*?){{ENDCASE:(.*?)}}#is', $return['orig'], $b);
			$return['case'] = $b[2];
			$return['key'] = array_flip($b[1]);		
			$this->content = str_replace($return['orig'], $return['case'][$return['key'][$case]], $this->content);
		}
	}
?>