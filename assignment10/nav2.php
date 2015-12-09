<nav id = "two">
    <ol>
        

<?php
       
        
        
        
        /* This sets the current page to not be a link. Repeat this if block for
         *  each menu item */
        if ($path_parts['filename'] == "vegan") {
            print '<li class="activePage">Vegan Cakes</li>';
        } else {
            print '<li><a href="vegan.php">Vegan Cakes</a></li>';
        }
        
         if ($path_parts['filename'] == "healthy") {
            print '<li class="activePage">Healthy Cakes</li>';
        } else {
            print '<li><a href="healthy.php">Healthy Cakes</a></li>';
        }
        

        /* example of repeating */
        if ($path_parts['filename'] == "budget") {
            print '<li class="activePage">Budget-Saver Cakes</li>';
        } else {
            print '<li><a href="budget.php">Budget-Saver Cakes</a></li>';
        }
        
        
        if ($path_parts['filename'] == "gluten") {
            print '<li class="activePage">Gluten-Free Cakes</li>';
        } else {
            print '<li><a href="gluten.php">Gluten-Free Cakes</a></li>';  
        }
        
         ?>
    </ol>
</nav>
<div id="para1">



<p>
    Here at Cupcake Connection, we hope to accommodate all the wants and needs of a cupcake! Check out our specialty cakes and we hope you find for whatever you are looking!
</p>

</div>


