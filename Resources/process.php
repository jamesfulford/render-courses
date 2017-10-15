<!DOCTYPE html>
<!-- Resources/process.php-->
<?php
    include "get_xml.php"; // gets $xml and $daterun according to current xml file
    date_default_timezone_set("EST");
    $month = intval(date_create_from_format("M d y", $daterun)->format("n"));
    $next_semester = "";
    if($month >= 2 and $month < 6){
        $next_semester = "Summer";
    } elseif ($month >= 6 and $month < 10) {
        $next_semester = "Fall";
    } else {
        $next_semester = "Spring";
    }

?>
<html id="root" lastrun="<?php echo $daterun; ?>">
    <head>
        <title> MCC Course Offering <?php echo $next_semester; ?> Semester</title>
        <link rel="shortcut icon" href="Resources/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="Classes.css"/>
        <!-- Copyright 2016 James Fulford
        For Manchester Community College
        Senior Capstone Seminar -->
    </head>
    <div class="header">
        <h1> MCC Course Offering </h1>
        <h2> <?php echo $next_semester; ?> Semester </h2>
    </div>
    <body>
        <div class="selector"><!-- Since form is not submitting information, onsubmit shouldn't do anything. -->
            <div id="department-selector-div" class="filter">
                <form onchange="filter()" onsubmit="return false;">
                    <select id="department-selector" name="dept">
                        <option value="" selected="selected">All Classes</option>
                        <?php // Populating other choices
                            $departments = array();
                            foreach($xml->rec as $class){
                                $dept = trim((string)$class->Sort_Key);
                                if(!in_array($dept, $departments)) {
                                    $departments[] = $dept;
                                    echo "<option value=\"$dept\">" . $dept . "</option>";
                                }
                            }
                        ?>
                    </select>
                </form>
            </div>
            <div id="search-bar-div" class="filter">
                <input id="search-bar" type="search" name="search" onkeyup="filter()" placeholder="Search Classes" />
            </div>
            <div id="full-classes-div" class="filter">
                <form onchange="filter()" onsubmit="return false">
                    <input type="checkbox" name="full" id="full-classes" checked="checked"><label for="full-classes">Full Classes?</label>
                </form>
            </div>
            <div id="format-div" class="filter">
                <form onchange="filter()" onsubmit="return false">
                    <input type="radio" name="format" class="format-radio" id="online" value="online" /><label id="online-label" for="online">Online</label><br/>
                    <input type="radio" name="format" class="format-radio" id="either" value="both" checked="checked"/><label id="either-label" for="either">Either</label><br/>
                    <input type="radio" name="format" class="format-radio" id="in-class" value="inclass" /><label id="in-class-label" for="in-class">In Class</label>
                </form>
            </div>

        </div> <!-- End Selector Section -->
        <script type="text/javascript">
        function filter() {
            var dept = document.getElementById("department-selector").value;
            var e = document.getElementsByName("format");
            var format;
            for(var i = 0; i < e.length; i++){
                if(e[i].checked){
                    format = e[i].value;
                    break;
                }
            }
            var show_full = document.getElementsByName("full")[0].checked;
            var searchtext = document.getElementsByName("search")[0].value.trim()
            // this should just iterate through the TRs and hide those that don't pass all the criteria.
            var entries = document.getElementsByTagName("TR");
            for (var i = 1 ; i < entries.length ; i++){ // skip the headers
                // department selector
                var code = entries[i].children[1].innerHTML;
                if( !code.includes(dept) && dept.length !== 0 ){
                    // if not right department, dept isn't "", and not the header of the table,
                    entries[i].setAttribute("style", "display:none;");
                    continue;
                }
                // online/inclass selector
                var isonline = entries[i].className === "online"
                if ( format.includes("inclass") && isonline ){
                    entries[i].setAttribute("style", "display:none;");
                    continue;
                } else if ( format.includes("online") && !isonline ){
                    entries[i].setAttribute("style", "display:none;");
                    continue;
                }
                // full class selector
                var isfull = entries[i].getAttribute("data-percent_filled") == 100
                if (!show_full && isfull) {
                    entries[i].setAttribute("style", "display:none;");
                    continue;
                }
                // search selector
                if (!entries[i].children[2].innerHTML.toLowerCase().includes(searchtext.toLowerCase())) {
                    if (!entries[i].children[1].innerHTML.toLowerCase().includes(searchtext.toLowerCase())) {
                        if (!entries[i].children[0].innerHTML.toLowerCase().includes(searchtext.toLowerCase())) {
                            // hide if it isn't in CRN, Code, or Title
                            entries[i].setAttribute("style", "display:none;");
                            continue;
                        }
                    }
                }
               entries[i].setAttribute("style", "display:table-row;");
            }
        }
        </script>
        <table>
            <thead>
                <tr id="headers">
                    <th class="crn-cell">CRN</th>
                    <th class="code-cell">Code</th>
                    <th class="title-cell">Course Title</th>
                    <th class="section-cell">Section</th>
                    <th class="days-cell">Days</th>
                    <th class="time-cell">Time</th>
                    <th class="seats-cell">Seats Left</th>
                </tr>
            </thead>
            <tbody>
        <?php
        foreach($xml->rec as $class)
        {
            $dept = trim((string)$class->Sort_Key);
            $name = explode("-", (string)$class->Class_Name);
            $len = count($name);

            $section = trim($name[$len - 1]);
            $code = trim($name[$len - 2]);
            $school = trim($name[strlen($code)-1]);
            $crn = trim($name[$len - 3]);
            $title = implode(" ", array_slice($name, 0, $len-3));

            $daytime = explode("_", (string)$class->Crn_Day_Time);
            $days = trim($daytime[0]);
            $time = trim($daytime[1]);
            $online = strcasecmp($time, "Online") == 0;

            // of these three, one is redundant because the other two can be used to calculate the third.
            $seats_total = (int)$class->Total_Seats;
            $seats_consumed = (int)$class->Seats_Consumed;
            $seats_left = (int)$class->Seats_Left;
            // of these three, one is redundant because the other two can be used to calculate the third.
            $wait_seats_total = (int)$class->WaitList_Total_Seats;
            $wait_seats_consumed = (int)$class->WaitList_Seats_Consumed;
            $wait_seats_left = (int)$class->WaitList_Seats_Left;

            if($online or $seats_total<=0) //make sure we don't div by 0 in else clause.
            {
                echo "<tr ";
                if($online)
                {
                    echo "class=\"online\" ";
                }
                $seats_left = ""; // onlines don't show seats_left.
                echo ">";
                echo "<td><div class=\"content\"> $crn </div></td>"; //because else handles this weirdly

            } else {

                $percent_filled = round(100 * $seats_consumed/$seats_total);

                if($percent_filled >= 100){ // if class is full
                    $percent_filled = 100;
                    $seats_left = "";
                }
                echo "<tr data-percent_filled=\"" . $percent_filled . "\" >";
                echo "<td class=\"crn-cell\"><div class=\"progress\" style=\"width:" . round(10.7 * $percent_filled) . "%;\"></div><div class=\"content\">$crn</div></td>";
            }

            echo "<td class=\"code-cell\"><div class=\"content\">$code</div></td>";
            echo "<td class=\"title-cell\"><div class=\"content\">$title</div></td>";
            echo "<td class=\"section-cell\" ><div class=\"content\">$section</div></td>";
            echo "<td class=\"days-cell\"><div class=\"content\">$days</div></td>";
            echo "<td class=\"time-cell\"><div class=\"content\">$time</div></td>";
            echo "<td class=\"seats-cell\"><div class=\"content\">$seats_left</div></td>";
            echo "</tr>";
        } // next course
        ?>
            </tbody>
        </table>
    </body>
</html>