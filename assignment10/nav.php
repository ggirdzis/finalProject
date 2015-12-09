<!-- ######################     Main Navigation   ########################## -->
<nav id = "one">
    <ol>
        <?php
        /* This sets the current page to not be a link. Repeat this if block for
         *  each menu item */
        if ($path_parts['filename'] == "index") {
            print '<li class="activePage">Home</li>';
        } else {
            print '<li><a href="index.php">Home</a></li>';
        }

        /* example of repeating */
        if ($path_parts['filename'] == "form") {
            print '<li class="activePage">Post Something Sweet!</li>';
        } else {
            print '<li><a href="form.php">Post Something Sweet!</a></li>';
        }
        
        
        if ($path_parts['filename'] == "post") {
            print '<li class="activePage">Batter Chatter</li>';
        } else {
            print '<li><a href="post.php">Batter Chatter</a></li>';  
        }
        
        if ($path_parts['filename'] == "trend") {
            print '<li class="activePage">Hot & Fluffy Trends</li>';
        } else {
            print '<li><a href="trend.php">Hot & Fluffy Trends</a></li>';  
        }
        
         if ($path_parts['filename'] == "special") {
            print '<li class="activePage">Specialty Cakes</li>';
        } else {
            print '<li><a href="special.php">Specialty Cakes</a></li>';  
        }
        
        
        $username = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8"); 
        
        
        ?>
    </ol>
</nav>

<hr>
