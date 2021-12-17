<?php
define('SQLUSR', 'root');
define('SQLPWD', '');
require_once('./db.php');

if(!empty($_REQUEST['addStock'])) {
    // ajout stock
}

if(!empty($_REQUEST['addSale'])) {
    // ajout vente
}

?><!doctype html>
<html>
    <head>
        <title>[ADMIN] Autos Management</title>
        <meta charset="utf8" />
    </head>
    <body>
         <h1>Gestionnaire</h1>

            <fieldset>
                <p> Entrée d'un stock </p>
                <select name="entree d'un stock">
                <?php    foreach ($models as $model) { ?> 
                    
                <?php } ?>
                    <option>- Marque -</option>
                    <option value= ""> ford </option>
           
    

                </select>   
                <select name="entree d'un stock">
                    <option>- Couleur -</option>
                    <input type="number" value= "prix d'achat" placeholder="prix d'achat">
                    <input type="submit" value="Enregistrer" /><br />

                </select>
                </fieldset>   
                


                        <details>
                            <summary>Stocks</summary>
                            <table>
                                <thead>
                                    <tr>
                                        <th> ID </th>
                                        <th> Couleur </th>
                                        <th> Marque </th>
                                        <th> Prix </th>
                                        <th> Date d'entree </th>
                                    </tr>
                                </thead>
                            <tbody>
                              <?php $stocks = getstocks();
                var_dump($stocks);
                foreach ($stocks as $stock ) { ?>
                 <tr>
                     <th>    
                          <?php       echo $stock['id']    ; ?>    </th>  
                     <th>    
                          <?php       echo $stock['model'] ; ?>    </th>                                           
                     <th>    
                          <?php       echo $stock['color'] ; ?>    </th>
                     <th>    
                          <?php       echo $stock['price'] ; ?>    </th>  
                     <th>     
                         <?php       echo $stock['entry'] ; ?>    </th>
                 </tr>              
                <?php } ?>
                
             </tbody>
         </table>

            
                        </details>
                    <fieldset>
                    <select name="entree d'une vente">
                        <option>- Voiture -</option>
                    <input type="number" value= "prix de vente" placeholder="prix de vente">
                    <input type="submit" value= "Enregistrer" /><br />

                    </select>   
                    </fieldset> 
    
                             <details>
                                <summary> Ventes </summary> 

                                <table>
                                    <tbody>

                                    <thead>
                                            <tr>
                                              <th>Date de vente</th>
                                              <th>Couleur</th>
                                              <th>Benefice</th>
                                              </tr>
                                        </thead>
                               <tbody>
                              <?php $sales = getsales();
                var_dump($sales);
                foreach ($sales as $sale ) { ?>
                    <tr> 
                 <th>  
                      <?php   echo $sale['soldDate'] ; ?>    </th>                                           
                 <th>
                        <?php   echo $sale['color']    ; ?>    </th>
                 <th>
                        <?php   echo $sale['soldPrice'] - $sale['price']    ; ?>    </th>  

                 </tr>              
                <?php } ?>
                
             </tbody>
         </table>
                             </details>


<footer title="Deleplace Lucas"> Copyright Lucas deleplace</footer>

    </body>
   

</html>