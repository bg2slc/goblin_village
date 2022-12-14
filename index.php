<!-- Goblin Village Manager -->
<!-- http://localhost/index.php -->
<?php
/**     --[[PREAMBLE]]--    **/
require_once("lib.php");

$ERRORCHECK = true; //set false for release version
if($ERRORCHECK)    {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}

/**     --[[HELPER FUNCTIONS]]--**/


/**     --[[FORMS]]--**/
function selectVillageForm($mysql)    {

    //Form presents villages in order of most recently modified by default.
    writePageTitle("Village Selection");
    try {
        $stmt = $mysql->prepare("
            SELECT villId, villName, villDesc, villPop, villAge, villLastModified 
            FROM Villages
            ORDER BY villLastModified DESC");
        $stmt->bind_result($vId, $vName, $vDesc, $vPop, $vAge, $vMod);
        $result = $stmt->execute();
        echo "
        <table>
            <tr><th>Name</th><th>Population</th><th>Number of Weeks</th><th>Last Played</th></tr>";
        while ($stmt->fetch())  {

            echo "
            <tr><td>$vName</td><td style=\"text-align:right\">$vPop</td><td style=\"text-align:right\">" . yearsAndWeeks($vAge) . "</td><td>$vMod</td></tr>";
        }
        echo "
        </table>";
    }
    catch(Exception $e)  {
        echo "<p><em>ERROR: " . $e->getMessage() . "</em></p>\n";
    }
}

function yearsAndWeeks($numOfWeeks)    {
    $message = "";
    if($numOfWeeks > 52)    {
        $numOfYears = intdiv($numOfWeeks, 52);
        $numOfWeeks = $numOfWeeks % 52;
        if($numOfYears == 1)
            $message = "1 year, ";
        else
            $message = "" . $numOfYears . " years, ";
    }
    if($numOfWeeks == 1)
        $message = $message . "1 week";
    else
        $message = $message . $numOfWeeks . " weeks";
    return $message;
}

function villageOverviewForm()  {

}

function villageGoblinsForm()   {
}

function villageEconomyForm()   {
}

function villageGroundsForm()   {
}

function villageMartialForm()   {
}

function getVillageList($mysql)   {
        
}

/**     --[[MAIN]]--    **/
date_default_timezone_set('America/Toronto');
$mysqlObj = createConnectionObject();
if(isset($_POST['f_Village']))
    $Village = $_POST['f_Village'];

//HEADER
if(isset($Village))
    writeHeaders("Goblin Village Manager", $Village);
else
    writeHeaders("Goblin Village Manager");

if($ERRORCHECK) {
    //write error stuff here, maybe?
}

//BODY
/*  SELECT  - shows current village selected, and select or create new village.
 *  VILLAGE - Overview of current village, status, alerts, and forecasts.
 *  GOBLINS - Browse village population, add or edit goblins, manage groups
 *  ECONOMY - Overview of current stores, TX history, projections
 *  GROUNDS - Different zones, landmarks, and neighbourhoods.
 *  MARTIAL - Guards, solders, and goblin law enforcement.
 */
if(isset($_POST['f_Village']))  {
    if(isset($_POST['f_Goblins']))  {
        villageGoblinsForm($mysqlObj);
    }
    else if(isset($_POST['f_Economy'])) {
        villageEconomyForm($mysqlObj);
    }
    else if(isset($_POST['f_Grounds'])) {
        villageGroundsForm($mysqlObj);
    }
    else if(isset($_POST['f_Martial'])) {
        villageMartialForm($mysqlObj);
    }
    else    {
        villageOverviewForm($mysqlObj);
    }
}
else    {
    selectVillageForm($mysqlObj); //Main
}

if(isset($mysqlObj)) $mysqlObj->close();
writeFooters();
?>
