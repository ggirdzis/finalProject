select DISTINCT fldBuilding, count(fldNumStudents) from tblSections where fldDays like "%W%" group by fldBuilding order by count(fldNumStudents) desc;
