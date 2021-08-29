<?php
// iniciamos con carga de variables de entorno
define('DEBUG', TRUE);

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    ini_set('display_errors',1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors',0);
    ini_set('log_errors', 1);
}

define('ROOT', getcwd());

//acemos la carga automatica de clases

// https://diego.com.es/autoload-en-php#:~:text=El%20autoloading%20permite%20en%20PHP,la%20carga%20autom%C3%A1tica%20de%20clases&text=El%20objetivo%20de%20la%20funci%C3%B3n,el%20archivo%20de%20la%20clase.

//recomiendo usar composer para cargar dependecias
//https://academy.leewayweb.com/que-es-composer/

spl_autoload_register(function ($className) {
    if (file_exists(ROOT . "/app/ctrls/$className.php")) {
        require_once(ROOT . "/app/ctrls/$className.php");
    }else if (file_exists(ROOT . "/app/models/$className.php")) {
        require_once(ROOT . "/app/models/$className.php");
    }
});


//clases bases, esto es un esboso hay que agregar validacion, sanitizacion, modo debug, organizar en carpetas, etc
class View
{
    // recomientod usar un motor de platillas https://twig.symfony.com/, http://mustache.github.io/

    private $path;// ruta al archivo de la vista
    private $name;// nombre de la vista
    private $vars;// variables de la vista
    public function __construct($name = ""){
        $this->path = ROOT."/app/views/";
        $this->name = empty($name) ? "not_view": $name;//si no se indica el nombre renderiza la vista por defecto
        $this->vars = [];
    }
    public function setVars($vars = []){
        $this->vars = $vars;
    }
    public function render($name = ""){
        extract($this->vars);//convirte el array a variariables locales
        $name = empty($name) ? $this->name : $name;//si no se indica el nombre renderiza la vista por defecto
        require_once($this->path."$name.php");
    }
}

class Controller
{

    protected $v;//vista por defecto
    public function __construct($method = "")
    {
        $this->v = new View($method);

    }
}
// recomiendo orm o active record, generar la coneccion a base de datos
// https://j4mie.github.io/idiormandparis/
// https://redbeanphp.com/index.php
class Models
{
    public function __construct($table){
        // hacer la coneciion a la base y por defegto selecinona la taba etc,
    }
    public function getData(){ // simula optenet los datos
        return [
            ['id' => '1','Nombre' => 'Leon Cesar El Hani Zacares',  'Sexo' => 'Masculino', 'Edad' => '55', 'Fecha de Nac' => '6 de Septiembre de 1966', 'Oficio' => 'Estilista',                       'Domicilio' => 'Boulevard Connor No. 423', 'Código Postal' => '72939', 'Edo./Prov.' => 'Darién',  'País' => 'Panama', 'Correo' => 'edleoncesar3@yopmail.com Check Email',  'Contraseña' => 'd12718e4d',  'Teléfono' => '+507(858)-7332221', 'Núm. de TDC' => '5866712327102283'],
            ['id' => '2','Nombre' => 'Prudencio Montorio Berro',    'Sexo' => 'Masculino', 'Edad' => '38', 'Fecha de Nac' => '7 de Febrero de 1983',    'Oficio' => 'Tecnico en Medios Audiovisuales', 'Domicilio' => 'Privada Agapito No. 301',  'Código Postal' => '23278', 'Edo./Prov.' => 'Darién',  'País' => 'Panama', 'Correo' => 'bxprudencio23@yopmail.com Check Email', 'Contraseña' => 'x361115b1x', 'Teléfono' => '+507(545)-1771363', 'Núm. de TDC' => '4537731731986356'],
            ['id' => '3','Nombre' => 'Ernesto Ismael Trejo Santos', 'Sexo' => 'Masculino', 'Edad' => '44', 'Fecha de Nac' => '11 de Marzo de 1977',     'Oficio' => 'Biólogo',                         'Domicilio' => 'Calle Mato Groso No. 781', 'Código Postal' => '72276', 'Edo./Prov.' => 'Herrera', 'País' => 'Panama', 'Correo' => 'elsantos11@yopmail.com Check Email',    'Contraseña' => 'l76900e4l',  'Teléfono' => '+507(030)-9223867', 'Núm. de TDC' => '344899322966680'],
        ];
    }
}
// este es un router basico  solo para sitios pequeños o prueva de consepto, usar una libreria
// https://www.php.net/manual/es/class.yaf-router.php

$routes=[
    // la ruta esta definida por una exprecion regular como esta
    '/^GET\/$/i'=>
        [
            'controller' => 'Main',
            'method' => 'index'
        ],
    '/^GET\/url_amigable$/i'=>
        [
            'controller' => 'Main',
            'method' => 'otherMethod'
        ],
];

if (!key_exists("url", $_GET)) {
    $_GET['url'] = "";
}

$url = "{$_SERVER['REQUEST_METHOD']}/{$_GET['url']}";

foreach ($routes as $route => $destination) {
    if (preg_match($route, $url)) {
        $controller = $destination['controller'];
        $method     = $destination['method'];
        break;
    }
}

if (isset($controller)) {
    if (class_exists($controller) and method_exists($controller, $method)) {
        $dispatch = new $controller($method);// por defecto la vista se llama igual que accion
        call_user_func([$dispatch, $method]);
    } else {
        require_once('./404.php');
    }
} else {
    require_once('./404.php');
}
