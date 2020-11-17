<?php


namespace _Common\Helpers;


class HtmlHelper
{
    static public function encode($str)
    {
        return htmlentities($str,ENT_QUOTES, 'UTF-8');
    }
}
