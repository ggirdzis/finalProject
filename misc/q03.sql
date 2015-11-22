select fnkCourseId, fldCRN, fnkTeacherNetId, fldMaxStudents, fldNumStudents, fldSection, fldType, fldStart, fldStop, fldDays, fldBuilding, fldRoom
from tblSectons
where fldStart like "1:10" and fldBuilding like "Kalkin";