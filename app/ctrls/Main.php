<?php

class Main extends Controller
{
	public function __construct($method)
    {
        parent::__construct($method);
    }
    public function index()
    {
    	$title = "Lista de usuarios";
    	$model = new MainModel("tabla_principal");//aqui se supone que hace la conecion y seleciona la tabla, esto seria como active record https://blog.nearsoftjobs.com/que-es-active-record-y-como-funciona-cbfc7910541f
    	// $model = new Model("tabla_principal");//aqui es otra alternativa, esto seria mas como ORM http://www.tuprogramacion.com/glosario/que-es-un-orm/
    	$data = $model->getData();

    	$this->v->setVars(compact('title','data'));//hago esta para no crear el array
        //$this->v->setVars(['title' => $title, 'data' => $data]); me refiero a esto
    	$this->v->render();
        //$this->v->render("onter_name");o llamar una vista que no se llame como el metodo
    }
    public function otherMethod()
    {
    	echo "otro ejemplo de metodo controlador";
    }
}