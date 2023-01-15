<?php 
    function my_date_convert($value)
    {
        return date("D, d M Y - H:i:s", strtotime($value));
    }
?>