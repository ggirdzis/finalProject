<?php include("top.php");?>
<h2>Specialty Cakes: Gluten-Free</h2> 
<div id="mainbod">
<?php

include("nav2.php");
?>
<?php
require_once('../bin/Database.php');
$dbUserName = get_current_user() . '_writer';
$whichPass = "w"; //flag for which one to use.
$dbName = strtoupper(get_current_user()) . '_FINAL';

$thisDatabase = new Database($dbUserName, $whichPass, $dbName);

print '<table>';

$columns = 3;

//now print out each record

$query2 = 'select fldTitle, fldPost, fldSkill, fldFirstName from tblPost inner join tblPerson on tblPost.pmkUsername = tblPerson.pmkId where fldGluten = 1';

$info2 = $thisDatabaseReader->select($query2, "", 1, 0, 0, 0, false, false);

$highlight = 0; // used to highlight alternate rows


// print '<p>SQL'.$query.'</p>';


foreach ($info2 as $rec) {

$highlight++;
if ($highlight % 2 != 0) {
$style = ' odd ';
} else {
$style = ' even ';
}


print '<tr class="' . $style . '">';

for ($i = 0;
$i < $columns;
$i++) {


print '<td>' . $rec[$i] . '</td>';

}

print '</tr>';

}

// all done
print '</table>';
print '</aside>';

print '</article>';


include("footer.php");?>
</div>