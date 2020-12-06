<?php
use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\koolphp\Card;
?>
<html>
    <head>
    <title>Rapports</title>
    </head>
    <body>
        <?php
        $totals_this_month=$this->dataStore("totals_this_month")->first(function($row){ return True;});
        $totals_last_month=$this->dataStore("totals_last_month")->first(function($row){ return True;});
        ?>
            
        <!-- <h4> Ce mois-ci</h4>
        <div class="row">
            <div class="col-sm-6">
                <?php 
                Card::create([
                        "title"=>"Nouvels eleves",
                        "value"=>$totals_this_month["total_eleves"],
                        "baseValue"=>$totals_last_month["total_eleves"]
                        
                        ]);
                        ?>
                
            </div>
            <div class="col-sm-6">
                <?php 
                Card::create([
                    "title"=>"Revenues",
                    "value"=>$totals_this_month["total_revenues"],
                        "baseValue"=>$totals_last_month["total_revenues"]
                        
                        ]);
                        ?>
                
            </div>
           
        </div> -->
        <?php
        
        Table::create([
            "dataSource"=>$this->dataStore("all_ecoles"),
            "columns"=>array("nom_ecole","adresse","telephone","total_eleves","total_revenues")
        ]);

        ?>
    </body>
</html>