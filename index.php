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
function writeSelectVillageDropButton($vName) {
    echo "
    <!-- select button for $vName -->
    <div class=\"dropdown\">
        <button class=\"dropbtn\" type=\"button\">Select</button>
        <div class=\"dropdown-content\">
            ";
    displayButton('f_Village', "Open", $vName);
    displayButton('f_Village_Copy', "Copy", $vName);
    displayButton('f_Village_Burn', "Burn", $vName);
    echo "
        </div>
    </div>";
}

function writeBannerMessage($Text)  {
    echo "
    <div class=\"bannerMessage\">$Text</div>";
}

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
            <tr><td>$vName</td><td style=\"text-align:right\">$vPop</td><td style=\"text-align:right\">" . yearsAndWeeks($vAge) . "</td><td>$vMod</td><td>";
            writeSelectVillageDropButton($vName);
            echo "
            </td></tr>";
        }
        echo "
        </table>";
    }
    catch(Exception $e)  {
        echo "<p><em>ERROR: " . $e->getMessage() . "</em></p>\n";
    }
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
if(isset($_POST['f_Village'])) {
    $Village = $_POST['f_Village'];
    writeHeaders("Goblin Village Manager", $Village);
}
else {
    writeHeaders("Goblin Village Manager");
}

//BODY
/*  SELECT  - shows current village selected, and select or create new village.
 *  VILLAGE - Overview of current village, status, alerts, and forecasts.
 *  GOBLINS - Browse village population, add or edit goblins, manage groups
 *  ECONOMY - Overview of current stores, TX history, projections
 *  GROUNDS - Different zones, landmarks, and neighbourhoods.
 *  MARTIAL - Guards, solders, and goblin law enforcement.
 */

if(isset($_POST['f_sqlQuery'])) {
    //TODO try a mysql query here
    //output success or error message here.
    writeBannerMessage($results);
}

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
    if(isset($_POST['f_Village_Copy'])) {
        selectVillageForm($mysqlObj); //Main
    }
    else    {
        selectVillageForm($mysqlObj); //Main
    }
}

if(isset($mysqlObj)) $mysqlObj->close();
writeFooters();
?>
