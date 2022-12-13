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
function writeSelectVillageDropButton($vName, $vId) {
    echo "
            <!-- select button for $vName -->
            <div class=\"dropdown\">
            <form action=? method=\"post\">
                <button class=\"dropbtn\" type=\"button\">Select</button>
                <div class=\"dropdown-content\">
                <input type=\"hidden\" name=\"openVillId\" value=\"$vId\">
                    ";
            echo "
                </div>
            </form>
            </div>";
}

/** Writes banner div. First parameter is message to write, second is whether
 * it is an error. If no parameters are passed, dumps the POST data.
 */
function writeBannerMessage($Text = "", $isError = 0)  {
    if ($Text=="") {
        echo "
        <div class=\"bannerMessage\">";
        var_dump($_POST);
        echo "</div>";
    }
    else
    {
        if($isError)    {
            echo "
            <div class=\"bannerMessage redMessage\">$Text</div>";
        }
        else    {
            echo "
            <div class=\"bannerMessage greenMessage\">$Text</div>";
        }
    }
}

/**     --[[FORMS]]--**/
function selectVillageForm($mysql)    {
    //Form presents villages in order of most recently modified by default.
    writePageTitle("Village Selection");
    try {
        $vId = 0; $vName = null; $vDesc = null; $vPop = 0; $vAge = 0;
        $vMod = 0; $vTerrain = 0;
        $query = '
            SELECT villId, villName, villDesc, villPop, villAge, 
                villLastModified, terrainId
            FROM Villages
            ORDER BY villLastModified DESC';
        $stmt = $mysql->prepare($query);
        $stmt->bind_result($vId, $vName, $vDesc, $vPop, $vAge, $vMod, 
            $vTerrain);
        if(!$stmt->execute())
            throw new Exception('unable to execute mysql statement');
        //Begin writing display for each village
        while ($stmt->fetch())  {
            writeVillageSelection($vId, $vName, $vAge, $vMod, $vDesc);
        }
    } catch(Exception $e)  {
        echo "<p><em>ERROR: " . $e->getMessage() . "</em></p>\n";
    }
}

function writeVillageSelection($vId, $vName, $vAge, $vMod, $vDesc, $Burn = false)    {
    echo "
            <form action=? method=\"post\">
            <div class=\"villageSelection\">
            <input type=\"hidden\" name=\"villId\" value=\"$vId\" \>
                <img src=\"images/thumb_fight.png\" height=\"200\" " .
                    "width=\"200\" \>
                <div class=\"villageSelectionBlurb\">
                    <p><em>$vName</em></p>
                    <p>Play Time: " . yearsAndWeeks($vAge) . "</p>
                    <p>Last Modified: $vMod</p>
                    <p>$vDesc</p>
                </div>
                <div class=\"villageSelectionButtons\">";
    if (!$Burn) {
        displayButton('f_Village', "Open", $vId);
        displayButton('f_Village_Copy', "Copy", $vId);
        displayButton('f_Village_Burn', "Burn", $vId);
    }
    else    {
        displaybutton('f_ConfirmBurn', "Confirm", $vId);
        displaybutton('', "Cancel");
    }
    echo "
                </div>
            </div>
            </form>";
}

function selectVillageBurnForm($mysql)    {
    writePageTitle("Confirm?", "Once burned, it cannot be un-burned");
    try {
        $vId = $_POST['f_Village_Burn'];
        $vName = null; $vDesc = null; $vPop = 0; $vAge = 0;
        $vMod = 0; $vTerrain = 0;
        $query = '
            SELECT villName, villDesc, villPop, villAge, villLastModified, terrainId
            FROM Villages
            WHERE villId = ' . $vId;
        $stmt = $mysql->prepare($query);
        $stmt->bind_result($vName, $vDesc, $vPop, $vAge, $vMod, 
            $vTerrain);
        if(!$stmt->execute())
            throw new Exception('unable to execute mysql statement');
        //Begin writing display for each village
        while ($stmt->fetch())  {
            writeVillageSelection($vId, $vName, $vAge, $vMod, $vDesc, true);
        }
    } catch(Exception $e)  {
        echo "<p><em>ERROR: " . $e->getMessage() . "</em></p>\n";
    }
}

function 

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
if($ERRORCHECK) {
    writeBannerMessage();
}
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
    if(isset($_POST['f_Village_Burn'])) {
        selectVillageBurnForm($mysqlObj);
    }
    if(isset($_POST['f_ConfirmBurn'])) {
        
    }
    else    {
        selectVillageForm($mysqlObj); //Main
    }
}

if(isset($mysqlObj)) $mysqlObj->close();
writeFooters();
?>
