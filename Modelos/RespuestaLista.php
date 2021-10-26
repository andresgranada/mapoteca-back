<?php

class RespuestaLista {
    protected $lista;
    protected $mensaje;

    public function __construct(array $lista){
        $this->lista = $lista;
    }

    public function getLista(){
        echo $this->lista;
        return $this->lista;
    }

    public function __toString()
    {
        return (object) array('lista' => getMensaje(), 'mensaje' => 'Respuesta con exito');
    }
}