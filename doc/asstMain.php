<!-- // http://localhost/asstMain.php -->
<?php
require_once("asstInclude.php");
require_once("clsDeleteSunglassRecord.php");

//ERROR REPORTING - Set to true for error reporting
if(true)    {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}

function displayMainForm()
{
    echo "<h3>Main Form</h3>";
    echo "<p>Welcome to our sunglass brand database</p>";
    echo "<div class=\"buttonBar\">";
    displayButton("f_CreateTable", "Create Table", 
        "button_createTable.png");
    displayButton("f_AddRecord", $Text="Add Record", 
        "button_addRecord.png");
    displayButton("f_DeleteRecord", $Text="Delete A Record", 
        $FileName="button_deleteRecord.png", $Alt="");
    displayButton("f_DisplayData", $Text="Display Data", 
        $FileName="button_displayData.png", $Alt="");
    echo "</div>";
}
function createTableForm($mysqlObj, $TableName)
{
    echo "<h3>Create Table</h3>";

    try {
        echo "<p>Attempting to delete existing table...</p>";
        $stmtObj = $mysqlObj->prepare ("DROP TABLE $TableName;");
        $stmtObj->execute();
        echo "<p>Successfully deleted existing table.</p>";
    } catch (Exception $e) {
        echo "<p>No table to delete</p>";
    }

    $stmtObj = $mysqlObj->prepare ("
    CREATE TABLE $TableName(
        name CHAR(10) PRIMARY KEY,
        manufactured DATE,
        cameraMP INT,
        colour CHAR(15)
    );");
    $sqlResult = $stmtObj->execute();
    if ($sqlResult)
        echo "<p>Table $TableName created.</p>";
    else
        echo "<p>Unable to create or display table $TableName.</p>";

    displayButton("", $Text="Home", 
        "button_home.jpg");
}

function addRecordForm($mysqlObj, $TableName)
{
    echo "<h3>Add Record Form</h3>";
    echo "<div class=\"bordered\">";
    //Form Inputs
    echo "<div class=\"control\">";
    displayLabel("Brand Name:", "f_Brand");
    displayTextBox("text", "f_Brand", 10, "Reeverse");
    echo "</div>\n<div class=\"control\">";
    $currentDate = date('Y-m-d');
    displayLabel("Manufacture Date:", "f_Date");
    echo "<input type=date name=\"f_Date\" value=\"$currentDate\" \>";

    echo "</div>\n<div class=\"control\">";
    displayLabel("Camera Input Resolution:\n");
    echo "<input type=radio id=\"5MP\" name=\"f_CameraMP\" 
        value=\"5\" checked>";
    displayLabel("5MP   ", "5MP");
    echo "\n<input type=radio id=\"10MP\" name=\"f_CameraMP\" 
        value=\"10\">";
    displayLabel("10MP   ", "10MP");
    echo "\n</div>\n<div class=\"control\">";
    displayLabel("Colour:", "f_Colour");
    echo "<input type=\"color\" name=\"f_Colour\" id=\"f_Colour\" />";
    echo "</div>";

    //Save and Home Buttons
    echo "<div class=\"buttonBar\">";
    displayButton("f_Save", "Save Record", "button_createTable.png");
    displayButton("", $Text="Home", "button_home.png");
    echo "</div></div>";
}

function saveRecordtoTableForm($mysqlObj, $TableName)
{
    echo "<h3>Saving Record To Table...</h3>";

    $stmtObj = $mysqlObj->prepare("
    INSERT INTO $TableName (name, manufactured, cameraMP, colour)
    VALUES (?, ?, ?, ?)");
    $stmtObj->bind_param('ssis', $f_Brand, $f_Date, $f_CameraMP,
        $f_Colour);
    
    $f_Brand = $_POST['f_Brand'];
    $f_Date = $_POST['f_Date'];
    $f_CameraMP = $_POST['f_CameraMP'];
    $f_Colour = $_POST['f_Colour'];
    try {
        $stmtObj->execute();
        echo "<p><b>SUCCESS</b> New brand $f_Brand successfully added.</p>";
    } catch (Exception $e){
        echo "<p><b>ERROR</b> Unable to add record for $f_Brand.</p>";
    }
    displayButton("", $Text="Home", "button_home.png");
}
function displayDataForm($mysqlObj, $TableName)
{
    echo "<h3>Display Data</h3>";
    try {
        $stmtObj = $mysqlObj->prepare ("
        SELECT * FROM $TableName ORDER BY name");
        $stmtObj->bind_result($name, $manufacture, $cameraMP, $colour);
        $sqlResult = $stmtObj->execute();
        echo "<table>\n<tr><th>Brand Name</th><th>Manufacture</th>
            <th>Camera's MP
            </th><th>Brand Colour</th></tr>\n";
        while ($stmtObj->fetch()) {
            echo "<tr><td>$name</td><td>$manufacture</td><td>$cameraMP</td>
                <td style=\"background-color:$colour\">$colour</td></tr>\n";
        }
        echo "</table>\n";
    } catch (Exception $e) {
        echo "<b>ERROR</b> Unable to retrieve records";
    }
    displayButton("", $Text="Home", "button_home.png");
}

function deleteRecordForm($mysqlObj, $TableName)
{
    echo "<h3>Delete Record</h3>";
    echo "<div class=\"bordered\"><p>Please enter Brand to delete</p>";
    displayTextBox("text", "f_Brand", 10, "Reeverse");
    displayLabel("Deletion is permanent and cannot be reversed by your system
        admin (without a bribe).");

    echo "<div class=\"buttonBar\">";
    displayButton("f_IssueDelete", "Delete!", "button_DeleteImp.png");
    displayButton("", $Text="Home", "button_home.png");
    echo "</div></div>";
}

//clsDeleteSunglassRecord 
function issueDeleteForm($mysqlObj, $TableName)
{
    echo "<h3>Issuing Delete...</h3>";
    $f_Brand = $_POST['f_Brand'];
    $deleter = new clsDeleteSunglassRecord();
    if($deleter->deleteTheRecord($mysqlObj, $TableName, $f_Brand))
    {
        echo "<p><b>Success</b> Deleting ... Done.</p>";
    }
    else
    {
        echo "<p><b>ERROR</b> failed to delete record</p>";
    }
    displayButton("", $Text="Home", "button_home.png");
}
// main
date_default_timezone_set ('America/Toronto');
$mysqlObj = createConnectionObject();
$TableName = "Sunglasses"; 
// writeHeaders call  
writeHeaders("I Wear My Sunglasses At Night", "Sunglasses At Night");
if (isset($_POST['f_CreateTable']))
  createTableForm($mysqlObj,$TableName);
else if (isset($_POST['f_Save'])) 
    saveRecordtoTableForm($mysqlObj,$TableName);
else if (isset($_POST['f_AddRecord'])) 
    addRecordForm($mysqlObj,$TableName);	   
else if (isset($_POST['f_DeleteRecord']))
    deleteRecordForm($mysqlObj,$TableName);
else if (isset($_POST['f_DisplayData']))
   displayDataForm ($mysqlObj,$TableName);
else if (isset($_POST['f_IssueDelete']))
   issueDeleteForm ($mysqlObj,$TableName);
else displayMainForm();

if (isset($mysqlObj)) $mysqlObj->close();
writeFooters();
?>
