<?php
namespace App\Reports\Prof;
use \koolreport\processes\Group;
use \koolreport\processes\Filter;
use \koolreport\processes\CalculatedColumn;
use \koolreport\processes\Join;


class MyReport extends \koolreport\KoolReport
{
    use \koolreport\laravel\Friendship;
    protected $profId;

    public function __construct($id){
        $this->profId=$id;
        parent::__construct();

    }

    public function settings()
    {
        return array(
            "dataSources"=>array(
                "scolariteDB"=>array(
                    'host' => 'localhost',
                    'username' => Configs::$PHP_MYADMIN_USERNAME,
                    'password' => Configs::$PHP_MYADMIN_USERNAME,
                    'dbname' => 'projet_scolarite',
                    'charset' => 'utf8',  
                    'class' => "\koolreport\datasources\MySQLDataSource"  
                ),
            )
        );
    }
    

    function setup()
    {   
        try{

            // # of eleves per formation
        $eleves_per_formation=$this->src("scolariteDB")
            ->query(
            "SELECT F.id as id,F.Des as NomF, COUNT(*) as Count "
            ."FROM formation as F, professeur_formation as PF, eleve_formation as EF "
            ."WHERE PF.Professeur=$this->profId AND F.id=PF.Formation AND PF.Formation= EF.Formation "
            ."GROUP by F.id, F.Des"
            );

        $eleves_per_formation->pipe($this->dataStore('nbr_eleves_formation'));
            # of eleves per classe
        $this->src("scolariteDB")
            ->query(
           "SELECT C.id as id,C.Des as NomC, COUNT(*) as Count "
            ."FROM classe as C, professeur_classe as PC, eleve_classe as EC  "
           ."WHERE PC.Professeur=$this->profId AND C.id=PC.Classe AND PC.Classe= EC.Classe  "
           ."GROUP by C.id, C.Des"
           ) 
            ->pipe($this->dataStore('nbr_eleves_classe'));
            // Total profits per formation
        $formations_prices=$this->src("scolariteDB")
            ->query(
           "SELECT Formation as id, Prix from catalogue_formation"
           ) ;
           $join = new Join($formations_prices,$eleves_per_formation,array("id"=>"id"));
           
           $join->pipe(new CalculatedColumn(array(
            "Total"=>"{Count}*{Prix}")
            ))
            ->pipe($this->dataStore('revenues_formation'));
        }catch(\Throwable $th){
            dd($th);
            
        }
    }
    function getStats($profId){
        
        try{

                // # of eleves per formation
            $this->src("scolariteDB")
                ->query(
                "SELECT F.id as id,F.Des as NomF, EF.Eleve as EleveId "
                ."FROM formation as F, professeur_formation as PF, eleve_formation as EF "
                ."WHERE F.ID=$profId AND F.id=PF.Professeur AND PF.Formation= EF.Formation "
                )
                // ->pipe(new Filter(array(
                //     array("id","=",$profId),  
                // )))
                ->pipe(new Group(array(
                    "by"=>array("id","NomF"),
                    "count"=> "EleveId"
                )))
                ->pipe($this->dataStore('nbr_eleves_formation'));
                // # of eleves per classe
            $this->src("scolariteDB")
                ->query(
               "SELECT C.id as id,C.Des as NomC, PC.Eleve as EleveId "
                ."FROM classe as C, professeur_classe as PC "
               ." WHERE  C.id=PC.Professeur ")
                ->pipe(new Filter(array(
                    array("id","=",$profId),  
                )))
                ->pipe(new Group(array(
                    "by"=>array("id","NomC"),
                    "count"=> "EleveId"
                )))
                ->pipe($this->dataStore('nbr_eleves_classe'));
        }catch(\Throwable $th){
           
        }
    }
}