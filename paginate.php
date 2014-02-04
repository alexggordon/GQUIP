<?php

class Paginator{
    var $items_per_page;
    var $items_total;
    var $current_page;
    var $num_pages;
    var $mid_range;
    var $low;
    var $high;
    var $limit;
    var $return;
    var $default_ipp = 25;
    
    //This function serves as the constructor for Paginator
    function Paginator()
    {

        $this->current_page = 1;
        $this->mid_range = 7;
        $this->items_per_page = (!empty($_GET['ipp'])) ? $_GET['ipp']:$this->default_ipp;
    
    }

    //This function is the engine of Paginator
    function paginate()
    {

        //Consult the parameters and see if all pages are being requested at once
        if($_GET['ipp'] == 'All')
        {
            
            $this->num_pages = ceil($this->items_total/$this->default_ipp);
            $this->items_per_page = $this->default_ipp;
        
        }

        //Check for illegal values
        else
        {
            
            if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;
            $this->num_pages = ceil($this->items_total/$this->items_per_page);
        
        }
        $this->current_page = (int) $_GET['page']; // must be numeric > 0
        
        if($this->current_page < 1 Or !is_numeric($this->current_page)) $this->current_page = 1;
        
        if($this->current_page > $this->num_pages) $this->current_page = $this->num_pages;
        
        //Make sure the next and previous pages are what they should be
        $prev_page = $this->current_page-1;
        $next_page = $this->current_page+1;

        //See whether it will be necessary to index the pages for the current site section
        if($this->num_pages > 10)
        {

            //Get the range of pages for this section
            $this->return = ($this->current_page != 1 And $this->items_total >= 10) ? "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$prev_page&ipp=$this->items_per_page\">« Previous</a> ":"<span class=\"inactive\" href=\"#\">« Previous</span> ";
            $this->start_range = $this->current_page - floor($this->mid_range/2);
            $this->end_range = $this->current_page + floor($this->mid_range/2);

            //Make sure that the endpoints for the range don't overflow
            if($this->start_range <= 0)
            {

                $this->end_range += abs($this->start_range)+1;
                $this->start_range = 1;

            }

            if($this->end_range > $this->num_pages)
            {

                $this->start_range -= $this->end_range-$this->num_pages;
                $this->end_range = $this->num_pages;

            }
            $this->range = range($this->start_range,$this->end_range);

            //Loop through all pages for this section
            for($i=1;$i<=$this->num_pages;$i++)
            {

                if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= " ... ";
                
                // loop through all pages. if first, last, or in range, display
                if($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))
                {

                    //Partial assembly
                    $this->return .= ($i == $this->current_page And $_GET['page'] != 'All') ? "<a title=\"Go to page $i of $this->num_pages\" class=\"current\" href=\"#\">$i</a> ":"<a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"$_SERVER[PHP_SELF]?page=$i&ipp=$this->items_per_page\">$i</a> ";
                
                }

                if($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1]) $this->return .= " ... ";
            
            }

            //Assemble all the page's parts
            $this->return .= (($this->current_page != $this->num_pages And $this->items_total >= 10) And ($_GET['page'] != 'All')) ? "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$next_page&ipp=$this->items_per_page\">Next »</a>\n":"<span class=\"inactive\" href=\"#\">» Next</span>\n";
            $this->return .= ($_GET['page'] == 'All') ? "<a class=\"current\" style=\"margin-left:10px\" href=\"#\">All</a> \n":"<a class=\"paginate\" style=\"margin-left:10px\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All\">All</a> \n";
        
        }
        else
        {
            
            //If there are few enough pages that no indexing is needed, show them, we're done here
            for($i=1;$i<=$this->num_pages;$i++)
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
        
        for($i=1;$i<=$this->num_pages;$i++)
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
