<?php
namespace App\Reports;
class Configs 
{
    public static $PHP_MYADMIN_USERNAME=env("DB_USERNAME","root");
    public static $PHP_MYADMIN_PASSWORD=env("DB_USERNAME","");
    public static $PHP_MYADMIN_DBNAME=env("DB_DATABASE","projet_scolarite");
    
}
?>