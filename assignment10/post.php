

<?php
/* %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
 * the purpose of this page is to display a list of poets, admin can edit
 * 
 * Written By: Gretchen Girdzis ggirdzis@uvm.edu
 */
// %^%^%^^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
require_once('../bin/Database.php');

$dbUserName = get_current_user() . '_writer';
$whichPass = "w"; //flag for which one to use.
$dbName = strtoupper(get_current_user()) . '_FINAL';

$thisDatabase = new Database($dbUserName, $whichPass, $dbName);



$adminId = "ggirdzis";
$adminId2 = "rerickso";
$adminId3 = "adatta";

include "top.php";
?>
<h2>Batter Chatter</h2>
<div id="mainbod">
<?php
//##############################################################################
//
// This page lists your tables and fields within your database. if you click on
// a database name it will show you all the records for that table. 
// 
// 
// This file is only for class purposes and should never be publicly live
//##############################################################################
print '<table>';

    $columns=4;
         
    //now print out each record
  
    $query2 = 'select fldTitle, fldPost, fldSkill, fldFirstName from tblPost inner join tblPerson on tblPost.pmkUsername = tblPerson.pmkId';
   // $info3 = $thisDatabaseReader->testquery($query2, "", 0, 0, 0, 0, false, false);
    $info3 = $thisDatabaseReader->select($query2, "", 0, 0, 0, 0, false, false);

    $highlight = 0; // used to highlight alternate rows
    
   // print "<p>Total Records:".count($info3).'</p>';
   // print '<p>SQL'.$query.'</p>';
    
    
    foreach ($info3 as $rec) {
        
        $highlight++;
        if ($highlight % 2 != 0) {
            $style = ' odd ';
        } else {
            $style = ' even ';
        }
      
        
        print '<tr class="' . $style . '">';
         
        for ($i = 0; $i < $columns; $i++) {
            
            
            print '<td>'  . $rec[$i]  . '</td>';
            
        }
       
        print '</tr>';
        
    }

    // all done
    print '</table>';
    print '</aside>';

print '</article>';




if($username == $adminId || $username == $adminId2 || $username == $adminId3){
    $admin = true;
}else{
    $admin = false;
}
    




print "<article>";
// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// prepare the sql statement
$orderBy = "ORDER BY pmkUsername";

$query = "SELECT pmkUsername, fldTitle, fldPost ";
$query .= "FROM tblPerson inner join tblPost on tblPerson.pmkId = tblPost.pmkUsername " . $orderBy;

if ($debug)
    print "<p>sql " . $query;


$post = $thisDatabase->select($query, "", 0, 1, 0, 0, false, false);
//$info2 = $thisDatabase->testquery($query, "", 0, 1, 0, 0, false, false);

if ($debug) {
    print "<pre>";
    print_r($post);
    print "</pre>";
}

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// print out the results
print "<ol>\n";

if ($admin)
{    
foreach ($post as $onePost) {

    print "<li>";
    if ($admin) {
        print '<a href="update.php?id=' . $onePost["pmkUsername"] . '">[Edit]</a>';
        print '<a href="delete.php?id=' . $onePost["pmkUsername"] . '">[Delete]</a>';
        
    }
    print $onePost['fldTitle'] . " " . $onePost['fldPost']  ."</li>\n";
}

}
print "</ol>\n";
print "</article>";
include "footer.php";

print "</div>";
