<?php
/* the purpose of this page is to display a form to allow a poet and allow us
 * to add a new poet or update an existing poet 
 * 
 * Written By: Robert Erickson robert.erickson@uvm.edu

 */

require_once('../bin/Database.php');

$dbUserName = get_current_user() . '_writer';
$whichPass = "w";
$dbName = strtoupper(get_current_user()) . '_FINAL';

$thisDatabase = new Database($dbUserName, $whichPass, $dbName);

include "top.php";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
$update = false;

$_SERVER["REMOTE_USER"]="ggirdzis";
// SECTION: 1a.
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1b Security
//
// define security variable to be used in SECTION 2a.
$yourURL = $domain . $phpSelf;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form

if (isset($_GET["id"])) {
    $pmkUsername = (int) htmlentities($_GET["id"], ENT_QUOTES, "UTF-8");

    $query = 'SELECT fldTitle, fldPost ';
    $query .= 'FROM tblPost WHERE pmkUsername = ?';

    $results = $thisDatabase->select($query, array($pmkUsername), 1, 0, 0, 0, false, false);

    $title = $results[0]["fldTitle"];
    $post = $results[0]["fldPost"];
} else {
    $pmkPoetId = -1;
    $title = "";
    $post = "";
}

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();
$data = array();
$dataEntered = false;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2a Security
//
    /*    if (!securityCheck(true)) {
      $msg = "<p>Sorry you cannot access this page. ";
      $msg.= "Security breach detected and reported</p>";
      die($msg);
      }
     */
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2b Sanitize (clean) data
// remove any potential JavaScript or html code from users input on the
// form. Note it is best to follow the same order as declared in section 1c.
    $pmkUsername = (int) htmlentities($_POST["hidUsername"], ENT_QUOTES, "UTF-8");
    if ($pmkUsername > 0) {
        $update = true;
    }
  
    // I am not putting the ID in the $data array at this time

  



//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2c Validation
//

    

    // should check to make sure its the correct date format
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2d Process Form - Passed Validation
//
// Process for when the form passes validation (the errorMsg array is empty)
//
    if (!$errorMsg) {
        if ($debug) {
            print "<p>Form is valid</p>";
        }

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2e Save Data
//

        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();

            if ($update) {

                
                
                

                $query = 'DELETE FROM tblPost where pmkUsername = ' . $pmkUsername . '';
                
                

                if ($_SERVER["REMOTE_USER"] == 'ggirdzis') {
                    $results = $thisDatabase->delete($query,"", 1, 0, 0, 0, false, false);
                }
            }


            // all sql statements are done so lets commit to our changes
            if ($_SERVER["REMOTE_USER"] == 'ggirdzis') {
                $dataEntered = $thisDatabase->db->commit();
            } else {
                $thisDatabase->db->rollback();
            }
            if ($debug)
                print "<p>transaction complete ";
        } catch (PDOExecption $e) {
            $thisDatabase->db->rollback();
            if ($debug)
                print "Error!: " . $e->getMessage() . "</br>";
            $errorMsg[] = "There was a problem with accepting your data please contact us directly.";
        }
    } // end form is valid
} // ends if form was submitted.
//#############################################################################
//
// SECTION 3 Display Form
//
?>
<article id="main">
<?php
//####################################
//
// SECTION 3a.
//
//
//
//
// If its the first time coming to the form or there are errors we are going
// to display the form.
if ($dataEntered) { // closing of if marked with: end body submit
    print "<h1>Record Deleted.</h1> ";
} else {
//####################################
//
// SECTION 3b Error Messages
//
// display any error messages before we print out the form
    if ($errorMsg) {
        print '<div id="errors">';
        print '<h1>Your form has the following mistakes</h1>';

        print "<ol>\n";
        foreach ($errorMsg as $err) {
            print "<li>" . $err . "</li>\n";
        }
        print "</ol>\n";
        print '</div>';
    }
//####################################
//
// SECTION 3c html Form
//
    /* Display the HTML form. note that the action is to this same page. $phpSelf
      is defined in top.php
      NOTE the line:
      value="<?php print $email; ?>
      this makes the form sticky by displaying either the initial default value (line 35)
      or the value they typed in (line 84)
      NOTE this line:
      <?php if($emailERROR) print 'class="mistake"'; ?>
      this prints out a css class so that we can highlight the background etc. to
      make it stand out that a mistake happened here.
     */
    ?>
        <form action="<?php print $phpSelf; ?>"
              method="post"
              id="frmRegister">
            <fieldset class="wrapper">
                <h3>Delete Record?</h3>

                <input type="hidden" id="hidUsername" name="hidUsername"
                       value="<?php print $pmkUsername; ?>"
                       >

                
                <!-- NOTE: no blank spaces inside the text area -->



                <!-- ends contact -->
            </fieldset> <!-- ends wrapper Two -->
            <fieldset class="buttons">
                <legend></legend>
                <input type="submit" id="btnSubmit" name="btnSubmit" value="Delete" tabindex="900" class="button">
            </fieldset> <!-- ends buttons -->
            </fieldset> <!-- Ends Wrapper -->
        </form>
    <?php
} // end body submit
?>
</article>

    <?php
    include "footer.php";
    if ($debug)
        print "<p>END OF PROCESSING</p>";
    ?>
</article>
</body>
</html>