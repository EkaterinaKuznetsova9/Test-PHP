<?php

// СТРУКТУРА КЛАССОВ
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
			$sOsn = $this->num * pow($this->a, 2)/(4*tan(M_PI/$this->num)); // пл-дь основания
            return ($sBok + $sOsn) ; 
        }
        
             
    }

// ГЕНЕРАЦИЯ ОБЪЕКТОВ
function GenerateFig($type) {
	global $arr; // для записи объектов в файл
	switch ($type){
		case 1: $radius = rand(1,15) + rand(0,9)/10;
		$figura = new Circle($radius);
		$arr .= '{"type":"circle","radius":'.$radius.'}'; 
		break;
		case 2: $st1 = rand(1,15) + rand(0,9)/10; 
		$st2 = rand(1,15) + rand(0,9)/10;
		$figura = new Rectangle($st1,$st2);
		$arr .= '{"type":"rectangle","st1":'.$st1.',"st2":'.$st2.'}';
		break;
		case 3: $num = rand(3,8); // в основании пирамиды не может быть 2 угла, минимум 3, максимум пусть будет 8
		$a = rand(1,15) + rand(0,9)/10;
		$apofema = rand(1,15) + rand(0,9)/10;
		$figura = new Pyramid($num,$a,$apofema);
		$arr .= '{"type":"pyramid","num":'.$num.',"a":'.$a.',"apofema":'.$apofema.'}';
		break;
	}
	$arr.=',';
}

$kolFig = rand(4,15); // рандомное количество фигур от 4 до 15
for ($i=0;$i<$kolFig;$i++){
	$typeFig = rand(1,3); // задается тип фигуры
	GenerateFig($typeFig);
}

// ЗАПИСЬ В ФАЙЛ
$arr = '['.$arr;
$arr = substr($arr,0,strlen($arr)-1).']';	
$fail = fopen("figurki.json", 'w') or die("Не удалось создать файл");
fwrite($fail, $arr);
fclose($fail);

// ПОЛУЧЕНИЕ ОБЪЕКТОВ ИЗ ФАЙЛА	
$path = file_get_contents("figurki.json");
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
}

// СОРТИРОВКА ОБЪЕКТОВ ПО УБЫВАНИЮ ПЛОЩАДИ ФИГУР
function compare_square($a,$b) {
	if ($a['square'] == $b['square']) {return 0;}
	return ($a['square'] > $b['square']) ? -1:1;
}
usort($json,'compare_square');

// ВЫВОД РЕЗУЛЬТАТА НА ЭКРАН
echo ("<br/><strong> РЕЗУЛЬТАТ СОРТИРОВКИ: </strong><br/><br/>");
for($i=0;$i<count($json);$i++){
	echo ($i+1).". Площадь фигуры: ".$json[$i]['square']."<br/>";
	switch ($json[$i]['type']) {
	    case 'circle':
	        echo "Круг с радиусом = ". $json[$i]['radius']."<br/><br/>";
			break;
		case 'rectangle':
            echo "Прямоугольник со сторонами: ".$json[$i]['st1']." и ".$json[$i]['st2']."<br/><br/>";
			break;
		case 'pyramid':
            echo "Пирамида, в основании правильный ".$json[$i]['num']."-угольник со стороной = ".$json[$i]['a'].", 
			длина апофемы = ".$json[$i]['apofema']."<br/><br/>";
			break;
	}
}
?>
