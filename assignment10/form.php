
<?php
include "top.php";
?>


<?php
$debug = false;

if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";

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
$firstName = "";
$lastName = "";
$email = "";

$post = "";
$title = "";

$homemade = true;
$healthy = false;
$budget = false;
$vegan = false;
$gluten = false;


$hash = "";

$skill = "Beginner";



//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$firstNameERROR = false;
$lastNameERROR = false;
$emailERROR = false;

$postERROR = false;

$titleERROR = false;
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();

// array used to hold form values that will be written to a CSV file
$dataRecord = array();


$data = array();
$dataEntered = false;

$mailed = false; // have we mailed the information to the user?
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
    // SECTION: 2a Security
//
    if (!securityCheck(true)) {
        $msg = "<p>Sorry you cannot access this page. ";
        $msg.= "Security breach detected and reported</p>";
        die($msg);
    }

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
    // SECTION: 2b Sanitize (clean) data
// remove any potential JavaScript or html code from users input on the
// form. Note it is best to follow the same order as declared in section 1c.

    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $firstName;

    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $lastName;


    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;




    $post = htmlentities($_POST["txtPost"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $post;

    $hash = htmlentities($_POST["lsthash"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $hash;

    $skill = htmlentities($_POST["radSkill"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $skill;

    $title = htmlentities($_POST["txtTitle"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $title;




// I am not putting the ID in the $data array at this time
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
    // SECTION: 2c Validation
//
    // Validation section. Check each value for possible errors, empty or
// not what we expect. You will need an IF block for each element you will
// check (see above section 1c and 1d). The if blocks should also be in the
// order that the elements appear on your form so that the error messages
// will be in the order they appear. errorMsg will be displayed on the form
// see section 3b. The error flag ($emailERROR) will be used in section 3c.

    if ($firstName == "") {
        $errorMsg[] = "Please enter your first name";
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg[] = "Your first name appears to have extra character.";
        $firstNameERROR = true;
    }

    if ($lastName == "") {
        $errorMsg[] = "Please enter your last name";
        $lastNameERROR = true;
    } elseif (!verifyAlphaNum($lastName)) {
        $errorMsg[] = "Your last name appears to have an extra character.";
        $lastNameERROR = true;
    }

    if ($email == "") {
        $errorMsg[] = "Please enter your email address";
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;
    }



    if (isset($_POST["chkfldHomemade"])) {
        $homemade = true;
    } else {
        $homemade = false;
    }
    $dataRecord[] = $homemade;

    if (isset($_POST["chkfldHealthy"])) {
        $healthy = true;
    } else {
        $healthy = false;
    }
    $dataRecord[] = $healthy;

    if (isset($_POST["chkfldBudget"])) {
        $budget = true;
    } else {
        $budget = false;
    }
    $dataRecord[] = $budget;

    if (isset($_POST["chkfldVegan"])) {
        $vegan = true;
    } else {
        $vegan = false;
    }
    $dataRecord[] = $vegan;

    if (isset($_POST["chkfldGluten"])) {
        $gluten = true;
    } else {
        $gluten = false;
    }
    $dataRecord[] = $gluten;

    if ($title == "") {
        $errorMsg[] = "Please enter your title.";
        $titleERROR = true;
    }   elseif (!verifyAlphaNum($title)) {
        $errorMsg[] = "Your title appears to contain a special character.";
        $titleERROR = true;
    }



    if ($post == "") {
        $errorMsg[] = "You have no post to post!";
        $postERROR = true;
    }   elseif (!verifyAlphaNum($post)) {
        $errorMsg[] = "Your post appears to contain a special character.";
        $postERROR = true;
    }


//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
    // SECTION: 2d Process Form - Passed Validation
//
    // Process for when the form passes validation (the errorMsg array is empty)
//
    if (!$errorMsg) {
        if ($debug)
            print "<p>Form is valid</p>";

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
        // SECTION: 2e Save Data
//
        // This block saves the data to a CSV file.
        $sql = 'INSERT INTO tblPerson (fldFirstName, fldLastName, fldEmail) VALUES ("'
                . $firstName . '", "' . $lastName . '", "' . $email . '")';
        $sql2 = 'INSERT INTO tblPost (fldHealthy, '
                . 'fldBudget, fldVegan, fldGluten, fldPost, fldTitle, fldSkill, fldTrendingValue) VALUES ("' . $healthy . '", "' .
                $budget . '", "' . $vegan . '", "' . $gluten . '", "' . $post . '", "' . $title .
                '", "' . $skill . '", "' . $hash . '")';

        $data = array($firstName, $lastName, $email);
        $data2 = array($healthy, $budget, $vegan, $gluten, $post, $title, $skill, $hash);
// $data3 = array($post, $hash);
       // print "<p>SQL 1: " . $sql;
     //  print "<P>SQL 2: " . $sql2;

        $plan = $thisDatabaseWriter->insert($sql, "", 0, 0, 6, 0, false, false);
        $plan2 = $thisDatabaseWriter->insert($sql2, "", 0, 0, 16, 0, false, false);
//   $plan3 = $thisDatabaseWriter->insert($sql3, "", 0, 0, 4, 0, false, false);

       // $info2 = $thisDatabaseWriter->testquery($sql2, "", 0, 0, 16, 0, false, false);

        $planId = $thisDatabaseWriter->lastInsert();
        $planId2 = $thisDatabaseWriter->lastInsert();





//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
        // SECTION: 2f Create message
//
        // build a message to display on the screen in section 3a and to mail
// to the person filling out the form (section 2g).

       $message = "<h3></h3>" . $firstName. " - Thank you so much for posting through Cupcake Connection. We are sure that your post will be greatly appreciated by others. Thank you for being a part of our community! Do not forget to check out <span>Batter Chatter</span> for the latest and greatest posts!";

      
       foreach ($_POST as $key => $value) {

            $message .= "<p> ";

            $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));

            foreach ($camelCase as $one) {
                $message .= $one . " ";
            }
            $message .= " : " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
                }


//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
        // SECTION: 2g Mail to user
//
        // Process for mailing a message which contains the forms data
// the message was built in section 2f.
        $to = $email; // the person who filled out the form
        $cc = "ggirdzis@gmail.com";
        $bcc = "";
        $from = "Cupcake Connection";

// subject of mail should make sense to your form
        $todaysDate = strftime("%x");
        $subject = "Leave a recipe. Take a recipe!" . $todaysDate;

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    } // end form is valid
} // ends if form was submitted.
// end form is valid
// ends if form was submitted.
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
//####################################
//
// SECTION 3b Error Messages
//
// display any error messages before we print out the form


if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
    
    print "<div id='mainbod'";
    
    print "<h3>Your post has ";
    if (!$mailed) {
        print "not ";
    }
    print "been posted! To view it - visit <span>Batter Chatter!</span></h3>";
    print $message;
    print "<p>A copy of this message has ";
    if (!$mailed) {
        print "not ";
    }
    print "been sent";
    print " to: " . $email . "</p>";
    
    
} else {
    //####################################
    //
        // SECTION 3b Error Messages
    //
        // display any error messages before we print out the form
    if ($errorMsg) {
        print '<div id="errors">';
        print "<ol>\n";
        foreach ($errorMsg as $err) {
            print "<li>" . $err . "</li>\n";
        }
        print "</ol>\n";
        print '</div>';
    }
print "</div>";
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
                <legend>Post Something Sweet!</legend>
                <p>Share your cupcake wisdom with the world, and may the flavor be returned to you.</p>

                <fieldset class="wrapperTwo">
                    <legend>Just give us some information and post your post!</legend>

                    <fieldset class="contact">
                        <h4>Contact Information</h4>
                        <label for="txtFirstName" class="required">First Name
                            <input type="text" id="txtFirstName" name="txtFirstName"
                                   value="<?php print $firstName; ?>"
                                   tabindex="100" maxlength="45" placeholder="Enter your first name"
    <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>

                        <label for="txtLastName" class="required">Last Name
                            <input type="text" id="txtFirstName" name="txtLastName"
                                   value="<?php print $lastName; ?>"
                                   tabindex="100" maxlength="45" placeholder="Enter your first name"
    <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>

                        <label for="txtEmail" class="required">Email
                            <input type="text" id="txtEmail" name="txtEmail"
                                   value="<?php print $email; ?>"
                                   tabindex="120" maxlength="45" placeholder="Enter a valid email address"
    <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   >
                        </label>

                        <fieldset class="radio">
                            <h4>What is your baking level?</h4>
                            <label><input type="radio" 
                                          id="radSkill" 
                                          name="radSkill" 
                                          value="Beginner"
    <?php if ($skill == "Beginner") print 'checked' ?>
                                          tabindex="330">Beginner</label>
                            <label><input type="radio" 
                                          id="radInter" 
                                          name="radSkill" 
                                          value="Intermediate"
    <?php if ($skill == "Intermediate") print 'checked' ?>
                                          tabindex="340">Intermediate</label>
                            <label><input type="radio" 
                                          id="radPro" 
                                          name="radSkill" 
                                          value="Professional/Commercial"
    <?php if ($skill == "Profesional/Commercial") print 'checked' ?>
                                          tabindex="340">Professional/Commercial</label></label>
                        </fieldset>


                    </fieldset> <!-- ends contact -->

                    <fieldset class="post">
                        <h4>Post Information</h4>
                        <label for="txtTitle" class="required">Title
                            <input type="text" id="txtTitle" name="txtTitle"
                                   value="<?php print $title; ?>"
                                   tabindex="120" maxlength="45" placeholder="Enter a title for your post!"
    <?php if ($titleERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   >
                        </label>
                        <fieldset  class="textarea">					
                            <label for="txtPost" class="required">Post</label>
                            <textarea id="txtPost" 
                                      name="txtPost" 
                                      tabindex="200"
    <?php if ($postERROR) print 'class="mistake"'; ?>
                                      onfocus="this.select()" 
                                      style="width: 25em; height: 30 em;" ><?php print $post; ?></textarea>
                            <!-- NOTE: no blank spaces inside the text area -->
                        </fieldset>

                        <fieldset class="checkbox">
                            <h4>Check all that apply to your post:</h4>
                            <div id="check">
                                <label for="chkfldHealthy"><input type="checkbox" 
                                                                  id="chkfldHealthy" 
                                                                  name="chkfldHealthy" 
                                                                  value="Healthy">Healthy
                                </label>
                                <label for="chkfldBudget"><input type="checkbox" 
                                                                 id="chkfldBudget" 
                                                                 name="chkfldBudget" 
                                                                 value="Budget">Budget-Savor
                                </label>
                                <label for="chkfldVegan"><input type="checkbox" 
                                                                id="chkfldVegan" 
                                                                name="chkfldVegan" 
                                                                value="Vegan">Vegan
                                </label>
                                <label for="chkfldGluten"><input type="checkbox" 
                                                                 id="chkfldGluten" 
                                                                 name="chkfldGluten" 
                                                                 value="Gluten">Gluten-Free
                                </label>
                            </div>

                        </fieldset>

                        <fieldset class="listbox">
    <?php
    require_once('../bin/Database.php');
    $dbUserName = get_current_user() . '_writer';
    $whichPass = "w"; //flag for which one to use.
    $dbName = strtoupper(get_current_user()) . '_FINAL';

    $thisDatabase = new Database($dbUserName, $whichPass, $dbName);

// Step Two: code can be in initialize variables or where step four needs to be
    $query = "SELECT pmkHash ";
    $query .= "FROM tblInterest ";
    $query .= "ORDER BY fldHashId";


// Step Three: code can be in initialize variables or where step four needs to be
// $buildings is an associative array
    $buildings = $thisDatabase->select($query, "", 0, 1, 0, 0, false, false);
    ?>




    <?php
    $message = '<label for="lsthash">Trend"';
    $message .= '<select id="lsthash" ';
    $message .= '        name="lsthash"';
    $message .= '        tabindex="300" >';


    print "<h4>Categorize your post so other people can find it!</h4>";
    // or you can print it out
    print '<label for="lsthash">Trend ';
    print '<select id="lsthash" ';
    print '        name="lsthash"';
    print '        tabindex="300" >';


    foreach ($buildings as $row) {

        print '<option ';
        if ($hash == $row["pmkHash"])
            print " selected='selected' ";

        print 'value="' . $row["pmkHash"] . '">' . $row["pmkHash"];

        print '</option>';
    }

    print '</select></label>';
    ?>

                        </fieldset>






                    </fieldset>
                </fieldset>

            </fieldset> <!-- ends wrapper Two -->

            <fieldset class="buttons">
                <legend></legend>
                <input type="submit" id="btnSubmit" name="btnSubmit" value="Register" tabindex="900" class="button">
            </fieldset> <!-- ends buttons -->


            </fieldset> <!-- Ends Wrapper -->

        </form>



    <?php
} // end body submit
?>

</article>

<?php include "footer.php";

?>



</body>
</html>



