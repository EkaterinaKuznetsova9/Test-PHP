<?php

class Figure // класс ФИГУРЫ
    {
        protected $type = '';
        public function getArea() {}
        public function getType()
        {
            if ($this->type == '') return 'Не определен';
            else return $this->type;
        }
    }
	
	class Circle extends Figure  //дочерний класс круги
    {
        private $radius;
        function __construct($r = 0)
        {
            $this->type = '';
            $r = floatval($r);
            if ($r > 0)
            {
                $this->type = 'Circle';
                $this->radius = $r;   
            }  
			                
        } 
        public function getArea()
        {
            if (!$this->type) return '0';
			return M_PI * $this->radius * $this->radius;
        }
        
           
    }    
	
	class Rectangle extends Figure //дочерний класс прямоугольники
    {
        private $st1, $st2;
        function __construct($st1 = 0, $st2=0)
        {
            $this->type = '';
            $st1 = floatval($st1); $st2 = floatval($st2);
            if (($st1 > 0)&&($st2>0))
            {
                $this->type = 'Rectangle';
                $this->st1 = $st1;
				$this->st2 = $st2;
            }
        }
        public function getArea()
        {
            if (!$this->type) return '0';
            return $this->st1 * $this->st2;
        }
            
    }
	
	class Pyramid extends Figure //дочерний класс пирамиды
	/* у нас будут они исключительно правильные, иначе не получится вывести некую общую ф-лу для нахождения площади пирамиды.
	Но, вообще, важно, если в основании неправильный треугольник, то нужно задавать все три стороны, а также нужно добавить
	условие на то, что сумма двух любых сторон больше третьей,	иначе площадь = 0. */
    {
        private $num, $a, $apofema; //$num - количество углов/сторон у фигуры в основании, $a - длина стороны, $apofema - длина апофемы
        function __construct($num = 0, $a = 0, $apofema = 0)
        {
            $this->type = '';
            $b = floatval($a); $apofema = floatval($apofema);
            if (($num > 0 ) && ($a > 0) && ($apofema > 0)) 
            {
                                
                    $this->type = 'Pyramid';
                    $this->num = $num;                    
                    $this->a = $a;  
                    $this->apofema = $apofema;  
                
            }    
        }
        
        public function getArea() //площадь боковой поверхности (через полупериметр и апофему) + площадь основания(через тангенс)
        {
            if (!$this->type) return '0';
            $sBok = $this->h * $this->a * $this->num / 2; // пл-дь боковой пов-ти
			$sOsn = $this->num * pow($this->a, 2)/tan(180/$this->num); // пл-дь основания
            return ($sBok + $sOsn) ; 
        }
        
             
    }
	
// ПОЛУЧЕНИЕ ОБЪЕКТОВ ИЗ ФАЙЛА	
$path = file_get_contents("figures.json");
$json = json_decode($path, true);
for($i=0;$i<count($json);$i++) {
	switch ($json[$i]['type']) {
	    case 'circle':
	        $figure = new Circle($json[$i]['radius']);
			break;
		case 'rectangle':
            $figure = new Rectangle($json[$i]['st1'], $json[$i]['st2']);
			break;
		case 'pyramid':
            $figure = new Pyramid($json[$i]['num'], $json[$i]['a'], $json[$i]['apofema']);
			break;
	}
	$json[$i]['square'] = $figure->getArea();
	echo $json[$i]['square'];
	echo ("-----");
}
