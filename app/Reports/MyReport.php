<?php
namespace App\Reports;

class MyReport extends \koolreport\KoolReport
{
    use \koolreport\laravel\Friendship;
    // By adding above statement, you have claim the friendship between two frameworks
    // As a result, this report will be able to accessed all databases of Laravel
    // There are no need to define the settings() function anymore
    // while you can do so if you have other datasources rather than those
    // defined in Laravel.

    public function settings()
    {
        return array(
            "dataSources"=>array(
                "ecolesDB"=>array(
                    'host' => 'localhost',
                    'username' => 'root',
                    'password' => 'Azerty123$$123',
                    'dbname' => 'projet_scolarite',
                    'charset' => 'utf8',  
                    'class' => "\koolreport\datasources\MySQLDataSource"  
                ),
            )
        );
    }
    

    function setup()
    {
        // Let say, you have "sale_database" is defined in Laravel's database settings.
        // Now you can use that database without any futher setitngs.
        try {
            $this->src("ecolesDB")
            ->query("SELECT EC.id,nom_ecole, adresse, telephone, COUNT(*) as total_eleves, SUM(pension) as total_revenues FROM ecoles EC,eleves E WHERE EC.id=E.ecole_id GROUP BY EC.id")
            ->pipe($this->dataStore("all_ecoles"));        
            $this->src("ecolesDB")
            ->query("SELECT COUNT(*) as total_eleves, SUM(pension) as total_revenues FROM eleves WHERE YEAR(date_ajout) = YEAR(CURRENT_DATE - INTERVAL 0 MONTH) AND MONTH(date_ajout) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)")
            ->pipe($this->dataStore("totals_this_month"));        
            $this->src("ecolesDB")
            ->query("SELECT COUNT(*) as total_eleves, SUM(pension) as total_revenues FROM eleves WHERE YEAR(date_ajout) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(date_ajout) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)")
            ->pipe($this->dataStore("totals_last_month"));        
           
        } catch (\Throwable $th) {
        }
    }
    function getStats(){
        return $this->dataStore("totals_this_month");
    }
}