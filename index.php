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
function selectVillageForm($mysqlObj)    {
    //Form presents villages in order of most recently modified by default.
    writePageTitle("Village Selection");
    try {
        $vId = 0; $vName = null; $vDesc = null; $vPop = 0; $vAge = 0;
        $vMod = 0; $vTerrain = 0;
        $query = '
            SELECT villId, villName, villDescription, villPopulation, villAge, 
                villLastModified, terrId
            FROM Villages
            ORDER BY villLastModified DESC';
        $stmt = $mysqlObj->prepare($query);
        $stmt->bind_result($vId, $vName, $vDesc, $vPop, $vAge, $vMod, 
            $vTerrain);
        if(!$stmt->execute())
            throw new Exception('unable to execute mysqlObj statement');
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
            <form action=? method=\"post\">";
    if (!$Burn) {
        echo "
                <div class=\"villageSelection\">";
    }
    else {
        echo "
                <div class=\"villageSelection redBackground\">";
    }
        echo "
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

function selectVillageBurnForm($mysqlObj)    {
    writePageTitle("Confirm?", "Once burned, it cannot be un-burned");
    try {
        $vId = $_POST['f_Village_Burn'];
        $vName = null; $vDesc = null; $vPop = 0; $vAge = 0;
        $vMod = 0; $vTerrain = 0;
        $query = '
            SELECT villName, villDescription, villPopulation, villAge,
                villLastModified, terrId
            FROM Villages
            WHERE villId = ' . $vId;
        $stmt = $mysqlObj->prepare($query);
        $stmt->bind_result($vName, $vDesc, $vPop, $vAge, $vMod, 
            $vTerrain);
        if(!$stmt->execute())
            throw new Exception('unable to execute mysqlObj statement');
        //Begin writing display for each village
        while ($stmt->fetch())  {
            writeVillageSelection($vId, $vName, $vAge, $vMod, $vDesc, true);
        }
    } catch(Exception $e)  {
        echo "<p><em>ERROR: " . $e->getMessage() . "</em></p>\n";
    }
}

/**
 * function selectVillageBurnConfirm($mysqlObj)
 * attempts to delete village from database,
 * writes result to bannerMessage, and continues with
 * village selection form.
 */
function villageBurnConfirm($mysqlObj)   {
    $response = null;
    try {
        $vId = $_POST['f_ConfirmBurn'];
        $sqlstmt = '
            DELETE FROM Villages
            WHERE villId = ' . $vId;
        if(!$mysqlObj->query($sqlstmt))
            throw new Exception('unable to execute mysqlObj statement');
        $response = "Village " . $vId . " successfully burned.";
    } catch(Exception $e)  {
        $response = "SQL Error: " . $e->getMessage();
    }
    return $response;
}

function villageCopy($mysqlObj) {
    /**$response = null;
    try {
        $vId = $_POST['f_Village_Copy'];
        //TODO: Create mechanism for creating Village Object from SQL request.
    } catch(Exception $e)  {
        $response = "SQL Error: " . $e->getMessage();
    }*/
    return "Copying not yet implemented!";
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

function getVillageList($mysqlObj)   {

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

//f_Village holds village id of currently viewed village.
if(isset($_POST['f_Village']))  {
    if(isset($_POST['f_Goblins']))  {
        //Show list of goblin citizens
        villageGoblinsForm($mysqlObj);
    }
    else if(isset($_POST['f_Economy'])) {
        villageEconomyForm($mysqlObj);
    }
    else if(isset($_POST['f_Grounds'])) {
        //Show Grounds screen???
        villageGroundsForm($mysqlObj);
    }
    else if(isset($_POST['f_Martial'])) {
        //Show martial screen for f_Village
        villageMartialForm($mysqlObj);
    }
    else    {
        //Show overview for f_Village
        villageOverviewForm($mysqlObj);
    }
}
else    {
    if(isset($_POST['f_Village_Copy'])) {
        //f_Village_Copy holds village id of village to copy.
        writeBannerMessage(villageCopy($mysqlObj));
        selectVillageForm($mysqlObj); //Main
    }
    else if(isset($_POST['f_Village_Burn'])) {
        //f_Village_Burn holds village id of village to confirm deletion.
        selectVillageBurnForm($mysqlObj);
    }
    else if(isset($_POST['f_ConfirmBurn'])) {
        //f_ConfirmBurn holds village id of village to delete.
        writeBannerMessage(villageBurnConfirm($mysqlObj));
        selectVillageForm($mysqlObj);
    }
    else    {
        selectVillageForm($mysqlObj); //Main
    }
}

if(isset($mysqlObj)) $mysqlObj->close();
writeFooters();
?>
