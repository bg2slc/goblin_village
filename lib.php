<?php
//Include File
//By Benjamin Garrett
//Contains helper functions

/** ++++++++++++ DOCUMENT WRITING **/
function writeHeaders($Heading, $Village="Goblin Village Manager")
{
    echo "<!doctype html>
<html>
<head>
	<meta charset = \"UTF-8\">
	<title>$Village</title>\n
    <link rel =\"stylesheet\" type = \"text/css\" href=\"style.css\" />
    <link rel=\"icon\" type=\"image/x-icon\" href=\"/images/gobicon.ico\" />
</head>
<body>\n<form action=? method=\"post\">
<div class=\"outer\">
    <div class=\"mainHeader\">
        <h1>$Village</h1>
        <h2>$Heading</h2>
        <p><a href=?>MAIN</a></p>
    </div>
    <div class=\"mainBody\">
    ";
}

function writePageTitle($Title) {
    echo "<h1>$Title</h1>\n";
}

function displayLabel($Label="My Label", $Name="")
{
	if ($Name == "")
        echo "<label>$Label</label>";
    else
        echo "<label for=\"$Name\">$Label</label>";
}

function displayTextBox($InputType, $Name, $Size, $Value=0)
{
    echo "<input type = $InputType name=\"$Name\" id=\"$Name\"
        size = \"$Size\" MaxLength=\"$Size\" value = \"$Value\">\n";
}

function displayImage($FileName, $Alt="Alternate Text Here",
    $Height=100, $Width=100)
{
    echo "<img src=\"$FileName\" alt=\"$Alt\" height=\"$Height\" 
        width=\"$Width\"/>\n";
}

function displayButton($Name, $Text="Button", $Value="")
{
    //echo "<div>";
    if ($Value=="")
        echo "<button type=Submit name=\"$Name\">$Text</button>";
    else
        echo "<button type=Submit name=\"$Name\" value=\"$Value\">$Text" .
        "</button>";
    //echo "</div>";
}

function writeFooters()
{
    echo "
    </div><!-- mainBody -->
</div><!-- outer div -->
</form>
</body>
</html>";
}

/** ++++++++++++ MYSQL **/
function createConnectionObject()
{
    $fh = fopen('auth.txt','r');
    $Host = trim(fgets($fh));
    $UserName = trim(fgets($fh));
    $Password = trim(fgets($fh));
    $Database = trim(fgets($fh));
    $Port = trim(fgets($fh));
    fclose($fh);

    $mysqlObj = new mysqli($Host, $UserName, $Password,$Database,$Port);
    // if the connection and authentication are successful,
    // the error number is 0
    // connect_errno is a public attribute of the mysqli class.
    if ($mysqlObj->connect_errno != 0)
    {
        echo 
        "<p>Connection failed. Unable to open database $Database. Error: "
        . $mysqlObj->connect_error . "</p>";
        // stop executing the php script
        exit;
    }
    return ($mysqlObj);
}

/** ++++++++++++ SRTING MANIP **/
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

?>
