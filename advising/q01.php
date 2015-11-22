<?php

//##############################################################################
//
// This page lists your tables and fields within your database. if you click on
// a database name it will show you all the records for that table. 
// 
// 
// This file is only for class purposes and should never be publicly live
//##############################################################################
include "top.php";
    
    //print '<table>';

    $columns=10;
         
    //now print out each record
  
    $query = 'select fldFirstName, fldLastName, fldAdvisorFirstName, fldAdvisorLastName, fldMajor, fldMinor, fldYear, fldTerm, fldCourseName, fldDepartment, fldCourseNumber, tblCourses.fldCredits from tblPlan join tblSemesterPlan on tblSemesterPlan.fnkPlanId = tblPlan.pmkPlanId join tblSemesterPlanCourses on tblSemesterPlan.fnkPlanId = tblSemesterPlanCourses.fnkPlanId and tblSemesterPlan.fldTerm = tblSemesterPlanCourses.fnkTerm and tblSemesterPlan.fldYear = tblSemesterPlanCourses.fnkYear join tblStudents on tblPlan.fnkStudentId = tblStudents.pmkStudentId join tblCourses on tblSemesterPlanCourses.fnkCourseId = tblCourses.pmkCourseId join tblAdvisors on tblPlan.fnkAdvisorId = tblAdvisors.pmkAdvisorId where fldLastName = "Girdzis" order by tblSemesterPlanCourses.fldDisplayOrder';
   // $info2 = $thisDatabaseReader->testquery($query, "", 1, 3, 2, 0, false, false);
    $info2 = $thisDatabaseReader->select($query, "", 1, 3, 2, 0, false, false);
    
    // the array $records is both associative and indexed, column zero is associative
// which you see in teh above print_r statement

////////////
    
    $semesterCredits = 0;
    $totalCredits = 0;
    
    $semester = "fldTerm" . "fldYear";
    
    if(is_array($info2)){
        foreach ($info2 as $row){
            /**
            print $row["fldFirstName"];
            
            if($semester != $row["fldTerm"] . $row["fldYear"]){
                if($semester != ""){
                    print "</ol>";
                    print "<p>Total Credits: " . $semesterCredits;
                    print "</section>";
                    
                }
            if($semester != "" AND [$row["fldTerm"] == "Fall"]){
                echo "</div" . LINE_BREAK;
            }
            
            if($row["fldTerm"] == "Fall")
                print "<div class = 'academicYear clearFloats'>";
            }
            
            print '<section class="fourColumns';
            print $row["fldTerm"];
            
            print '">';
            print '<h3>' . $row["fldTerm"] . "" . $row["fldYear"];
            $year = $row["fldYear"];
            $semesterCredits = 0;
            
            
             print "<ol>";
            
          **/  
        print '<li class="' . $row["fldCourseName"] . '">';
        print $row["fldDepartment"] . " " . $row["fldCourseNumber"];
        print '</li>' . LINE_BREAK;
        $semesterCredits = $semesterCredits . $row["tblCourses.fldCredits"];
        
       
        }
        
        
        
        
        
    }
    
    
    
////////////    

    $highlight = 0; // used to highlight alternate rows
    
    print "<p>Total Records:".count($info2).'</p>';
   // print '<p>SQL'.$query.'</p>';
    
$fields = array_keys($info2[0]);
    $labels = array_filter($fields, "is_string");
    
    
    $columns = count($labels);
    
    
print '<table>';
print '<tr><th colspan="' . $columns . '">' . " " . '</th></tr>';
// print out the column headings, note i always use a 3 letter prefix
// and camel case like pmkCustomerId and fldFirstName
print '<tr>';

foreach ($labels as $label) {
    print '<th>';
    $camelCase = preg_split('/(?=[A-Z])/', substr($label, 3));
    foreach ($camelCase as $one) {
        print $one . " ";
    }
    print '</th>';
}
print '</tr>';
    
foreach ($records as $record) {
    print '<tr>';
    for ($i = 0; $i < $columns; $i++) {
        print '<td>'. $record[$i] . '</td>';
    }
    print '</tr>';
}
   

       
    
foreach ($info2 as $rec) {
        $highlight++;
        if ($highlight % 2 != 0) {
            $style = ' odd ';
        } else {
            $style = ' even ';
        }
        print '<tr class="' . $style . '">';
        for ($i = 0; $i < $columns; $i++) {
            print '<td>' . $rec[$i] . '</td>';
        }
        print '</tr>';
    }    
    
    


print '</article>';
include "footer.php";
?>