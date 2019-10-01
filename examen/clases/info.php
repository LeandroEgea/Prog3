<?php
class Info
{
    public $caso;
    public $hora;
    public $ip;

    public function __construct($caso, $hora, $ip)
    {
        $this->caso = $caso;
        $this->hora = $hora;
        $this->ip = $ip;
    }
}
