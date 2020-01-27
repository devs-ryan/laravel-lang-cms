<?php

/****************************
 * getKey($text, $index)
 ****************************
 * Finds key previous to => separator in a lang file
 *
 * @param    string  $text The lang file converted to a text string
 * @param    int     $index The index where => is location in the string
 * @return      string
 *
 */
if(!function_exists('getKey')) {
    function getKey($text, $index) {
        
        $quote_count = 0;
        $str = '';
        while($quote_count < 2) {
            //add to string
            if ($quote_count > 0 && $text[$index] != "'") {
                $str.= $text[$index];
            }
            //increase quote count when found
            if ($text[$index] == "'") $quote_count++;
            
            $index--;
        }
    
        return strrev($str);
        
    }
}

/****************************
 * getValue($text, $index)
 ****************************
 * Finds value after => separator in a lang file
 *
 * @param    string  $text The lang file converted to a text string
 * @param    int     $index The index where => is location in the string
 * @return      string
 *
 */
if(!function_exists('getValue')) {
    function getValue($text, $index) {
        
        $quote_count = 0;
        $quote_type = '';
        $str = '';
        
        while($quote_count < 2) {
            //add to string
            if ($quote_count > 0 && $text[$index] != $quote_type) {
                $str.= $text[$index];
            }
            //increase quote count when found
            if ($text[$index] == "'" || $text[$index] == '"') {
                if ($text[$index - 1] == "\\") {
                    $str.= $text[$index];
                    $index++;
                    continue;
                }
                
                if ($quote_count == 0) {
                    $quote_type = $text[$index];
                    $quote_count++;
                }
                else if ($text[$index] == $quote_type) {
                    $quote_count++;
                }
            } 
            
            $index++;
        }
    
        return $str;
        
    }
}