<?php
class LogController
{
    public $logDao;

    public function __construct()
    {
        $this->logDao = new GenericDao('./info.log'); 
    }

    function logear($request) 
    {
        //var_dump($request->getAttribute('ip_address'));
        $ip = $request->getServerParam('REMOTE_ADDR');
        $path = $request->getRequestTarget();
        $method = $request->getMethod();
        $date = getDate();

        $log = new Log($ip, $path, $method, $date); 
        $this->logDao->guardar($log);
    }

}
