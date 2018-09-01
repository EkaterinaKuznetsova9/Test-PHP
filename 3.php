 <?php
 class Figure
    {
        protected $type = '';
        public function getArea() {}
        public function getType()
        {
            if ($this->type == '') return 'Не определен'; //можно добавить, что площадь = 0
            else return $this->type;
        }
    }
    
	class Circle extends Figure
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
            if (!$this->type) return '';
            return M_PI * $this->radius * $this->radius;
        }
        
           
    }    
	
    class Rectangle extends Figure
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
            if (!$this->type) return '';
            return $this->st1 * $this->st2;
        }
            
    }
    
    
    
    class Pyramid extends Figure //у нас будут исключительно правильные пирамиды
    {
        private $num, $a, $h;
        function __construct($num = 0, $b = 0, $h = 0)
        {
            $this->type = '';
            $b = floatval($a); $h = floatval($h);
            if (($num > 0 ) && ($a > 0) && ($h > 0)) 
            {
                $p = ($a * $num) / 2; // полупериметр
                
                    $this->type = 'Pyramid';
                    $this->num = $num;                    
                    $this->a = $a;  
                    $this->h = $h;  
                
            }    
        }
        
        public function getArea() //площадь боковой поверхности (через периметр и апофему)
        {
            if (!$this->type) return '';
            $p = ($this->a * $this->num) / 2; // полупериметр
            return ($p * $this->h); // S = p*h
        }
        
             
    }



    $x = ''; // массив
	//$f = [{"type":"circle","radius":8},{"type":"rectangle","st1":4,"st2":5},{"type":"Pyramid","num":3,"b":4,"h":6},{"type":"rectangle","st1":6,"st2":8}, {"type":"circle","radius":7}];
	$f = file('file.txt');// чтение объектов из файла;
    foreach ($f as $val)
    {
        $who = explode(',', trim($val)); 
        $who[] = 0; $who[] = 0; $who[] = 0; 
        switch ($who[0]) 
        {
            case 'circle':
                $x[] = new Circle($who[1]);
                break;
            case 'rectangle':
                $x[] = new Rectangle($who[1]);
                break;
            case 'pyramid':
                $x[] = new Pyramidwho[1], $who[2], $who[3]);
                break;                
        }
    }
    if ($x)    
    {
        foreach ($x as $v)
        {
            echo $v->getType(). ' , Площадь=', $v->getArea();
        }
    }    
?>
