<?php
// This class is modeled after the idea of having an automatically generated pagination data page. 
class Paginator{
    // Gloabl Variables
    var $items_per_page;
    var $items_total;
    var $current_page;
    var $number_of_pages;
    var $middle_of_variable;
    var $low;
    var $high;
    var $limit;
    var $return;
    var $default_items_per_page = 25;
    

    // Overall there are 5 methods for the on the class. 
    // 1) Paginator()
    // 2) paginate()
    // 3) display_items_per_page()
    // 4) display_jump_menu
    // 5) display_pages

    //This function serves as the constructor for Paginator, As the constructor, it is called by default whenever the function is called.
    // Once the function is called, it initializes the basic variables.
    // If the items_per_page variable is not set in the URL, then the default is used. 
    function Paginator()
    {
        $this->current_page = 1;
        $this->middle_of_variable = 7;
        $this->items_per_page = (!empty($_GET['ipp'])) ? $_GET['ipp']:$tiis->default_Items_per_page;
    }

    // This function is the engine of Paginator.
    // This is the prime rib of the function.  
    // The Chris Blatchley of computer droppers
    // The Thaddeus Bond of narcolepsy
    // The Elliott Staude of Caffine. 
    function paginate()
    {
        //Consult the parameters and see if all pages are being requested at once
        if($_GET['ipp'] == 'All')
        {
            $this->number_of_pages = ceil($this->items_total/$this->deiault_Items_per_page);
            $this->items_per_page = $this->default_items_per_page;
        }
        // Not asking for all pages. Find out the number of links we will need for the number of pages. 
        // This is based on the number of items per page and the total number of items. 
        // Also throws in some error checking to see if 
        else
        {
            if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0) $this->items_per_page = $this->default_items_per_page;
            $this->number_of_pages = ceil($this->items_total/$this->items_per_page);

        }
        $this->current_page = (int) $_GET['page']; // must be numeric > 0
        
        if($this->current_page < 1 Or !is_numeric($this->current_page)) $this->current_page = 1;
        
        if($this->current_page > $this->number_of_pages) $this->current_page = $this->number_of_pages;

        // Sets the prev and next page. 
        $prev_page = $this->current_page-1;
        $next_page = $this->current_page+1;

        // This gets the page number we're on and checks to make sure that its a valid number in the range. It then sets the next and previous page. 
        // Checks the number of pages. IF more than ten, add the "..."
        if($this->number_of_pages > 10)
        {

            //Get the range of pages for this section
            $this->return = ($this->current_page != 1 And $this->items_total >= 10) ? "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$prev_page&ipp=$this->items_per_page\">« Previous</a> ":"<span class=\"inactive\" href=\"#\">« Previous</span> ";
            $this->start_range = $this->current_page - floor($this->middle_of_variable/2);
            $this->end_range = $this->current_page + floor($this->middle_of_variable/2);

            //Make sure that the endpoints for the range don't overflow
            if($this->start_range <= 0)
            {

                $this->end_range += abs($this->start_range)+1;
                $this->start_range = 1;

            }
            if($this->end_range > $this->number_of_pages)
            {

                $this->start_range -= $this->end_range-$this->number_of_pages;
                $this->end_range = $this->number_of_pages;

            }
            $this->range = range($this->start_range,$this->end_range);

            //Loop through all pages for this section
            for($i=1;$i<=$this->number_of_pages;$i++)
            {

                if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= " ... ";
                
                // loop through all pages. if first, last, or in range, display
                if($i==1 Or $i==$this->number_of_pages Or in_array($i,$this->range))
                {

                    //Partial assembly
                    $this->return .= ($i == $this->current_page And $_GET['page'] != 'All') ? "<a title=\"Go to page $i of $this->number_of_pages\" class=\"current\" href=\"#\">$i</a> ":"<a class=\"paginate\" title=\"Go to page $i of $this->number_of_pages\" href=\"$_SERVER[PHP_SELF]?page=$i&ipp=$this->items_per_page\">$i</a> ";

                }

                if($this->range[$this->middle_of_variable-1] < $this->number_of_pages-1 And $i == $this->range[$this->middle_of_variable-1]) $this->return .= " ... ";

            }

            //Assemble all the page's parts
            $this->return .= (($this->current_page != $this->number_of_pages And $this->items_total >= 10) And ($_GET['page'] != 'All')) ? "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$next_page&ipp=$this->items_per_page\">Next »</a>\n":"<span class=\"inactive\" href=\"#\">» Next</span>\n";
            $this->return .= ($_GET['page'] == 'All') ? "<a class=\"current\" style=\"margin-left:10px\" href=\"#\">All</a> \n":"<a class=\"paginate\" style=\"margin-left:10px\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All\">All</a> \n";

        }
        // This displays the range. Essentially, if the last page is number 208, and we're on page one, then it will show links to page 2,3,4,5,6,7,8,9 and then the last page. 
        else
        {

            //If there are few enough pages that no indexing is needed, show them, we're done here
            for($i=1;$i<=$this->number_of_pages;$i++)
            {

                //Partial assembly
                $this->return .= ($i == $this->current_page) ? "<a class=\"current\" href=\"#\">$i</a> ":"<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$i&ipp=$this->items_per_page\">$i</a> ";

            }

            //Assemble all the page's parts
            $this->return .= "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All\">All</a> \n";
        }
        
        $this->low = ($this->current_page-1) * $this->items_per_page;
        $this->high = ($_GET['ipp'] == 'All') ? $this->items_total:($this->current_page * $this->items_per_page)-1;
        $this->limit = ($_GET['ipp'] == 'All') ? "":" LIMIT $this->low,$this->items_per_page";

    }

    //This function grabs the items to be shown on each page's listings
    function display_items_per_page()
    {

        $items = '';
        $ipp_array = array(10,25,50,100,'All');
        foreach($ipp_array as $ipp_opt)    $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n":"<option value=\"$ipp_opt\">$ipp_opt</option>\n";
        return "<span class=\"paginate\">Items per page:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page=1&ipp='+this[this.selectedIndex].value;return false\">$items</select>\n";

    }

    //This function designates how many page "tabs" are required for each section of the application
    function display_jump_menu()
    {

        for($i=1;$i<=$this->number_of_pages;$i++)
        {

            $option .= ($i==$this->current_page) ? "<option value=\"$i\" selected>$i</option>\n":"<option value=\"$i\">$i</option>\n";

        }
        return "<span class=\"paginate\">Page:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page='+this[this.selectedIndex].value+'&ipp=$this->items_per_page';return false\">$option</select>\n";

    }

    //This function gets the content in its final, ready form and passes it to the application
    function display_pages()
    {

        return $this->return;

    }

}
?>
