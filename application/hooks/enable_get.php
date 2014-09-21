<?php
function remake_get()
{
    parse_str($_SERVER['QUERY_STRING'],$_GET);
}
