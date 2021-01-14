<?php
namespace App\Reports\Admin;
use \koolreport\processes\Group;
use \koolreport\processes\Filter;
use \koolreport\processes\CalculatedColumn;
use \koolreport\processes\Join;


class MyReport extends \koolreport\KoolReport
{
    use \koolreport\laravel\Friendship;
    protected $profId;

    public function __construct(){
        parent::__construct();

    }

    public function settings()
    {
        return array(
            "dataSources"=>array(
                "scolariteDB"=>array(
                    'host' => 'localhost',
                    'username' => 'root',
                    'password' => '',
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

            // # of profs per formation
        $nbr_parents=$this->src("scolariteDB")
            ->query(
            "SELECT  COUNT(*) as Count "
            ."FROM parent"
        );
        $nbr_parents->pipe($this->dataStore('nbr_parents'));

            // # of profs per formation
        $profs_per_formation=$this->src("scolariteDB")
            ->query(
            "SELECT F.id as id,F.Des as NomF, COUNT(*) as Count "
            ."FROM formation as F, professeur_formation as PF "
            ."WHERE F.id=PF.Formation "
            ."GROUP by F.id, F.Des"
        );
        $profs_per_formation->pipe($this->dataStore('nbr_prof_formation'));
            // # of eleves per formation
        $eleves_per_formation=$this->src("scolariteDB")
            ->query(
            "SELECT F.id as id,F.Des as NomF, COUNT(*) as Count "
            ."FROM formation as F, eleve_formation as EF "
            ."WHERE F.id=EF.Formation "
            ."GROUP by F.id, F.Des"
            );

        $eleves_per_formation->pipe($this->dataStore('nbr_eleves_formation'));
            # of eleves per classe
        $this->src("scolariteDB")
            ->query(
           "SELECT C.id as id,C.Des as NomC, COUNT(*) as Count "
            ."FROM classe as C, eleve_classe as EC  "
           ."WHERE C.id=EC.Classe  "
           ."GROUP by C.id, C.Des"
           ) 
            ->pipe($this->dataStore('nbr_eleves_classe'));
            # of observations per month
        $this->src("scolariteDB")
            ->query(
           "SELECT MONTH(Date) as Mois, COUNT(*) as Count "
            ."FROM observation  "
            ."WHERE YEAR(Date)=YEAR(CURRENT_DATE)  "
           ."GROUP by MONTH(Date)"
           ) 
            ->pipe($this->dataStore('nbr_observ_month'));
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