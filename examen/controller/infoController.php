<?php
class InfoController
{
    public $infoDao;

    public function __construct()
    {
        $this->infoDao = new GenericDao('./info.log');
    }
    
    public function guardar($caso, $ip)
    {
        $hora = date("d-m-Y_H_i");
        $info = new Info($caso, $hora, $ip);
        $this->infoDao->guardar($info);
    }
}
