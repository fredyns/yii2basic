<?php

namespace app\helpers;

/**
 * saveral helper function for number
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class NumberHelper
{

    /**
     * put number in certain format
     * 
     * @param string $format
     * @param int $number
     * @param string $placeholder
     * @param bool|int $zerofill either true/false or minimum digit length for number (zerofilling) 
     * @return string formated number
     */
    public static function format($format, $number, $placeholder = '?', $zerofill = true)
    {
        if (strpos($format, $placeholder) === FALSE) {
            return $format; // in case placeholder not found (wrong format)
        }

        $number = (string) $number; // treat as string
        $format_array = str_split($format);
        $number_array = str_split($number);
        $first_placeholder_key = array_search($placeholder, $format_array); // used in case number longer than number
        $reversed_format_array = array_reverse($format_array, TRUE);
        $reversed_number_array = array_reverse($number_array);
        $reversed_result_array = [];

        foreach ($reversed_format_array as $key => $char) { // enumerate format char backward
            if ($char != $placeholder) {                // not placeholder for number     
                if ($zerofill OR $key < $first_placeholder_key OR $reversed_number_array) {
                    // zerofill still valid OR string before placeholder OR any number left to put
                    $reversed_result_array[] = $char;
                }
                // when zerofill reach out/disabled && char is number separator && no number left, no need to put out
            } elseif (empty($reversed_number_array)) {  // number is or already empty
                if ($zerofill) {                        // zerofill enabled
                    $reversed_result_array[] = '0';     // fill with zero  
                    if (is_integer($zerofill)) {        // if zerofill is limited
                        $zerofill--;                    // decrease quota
                    }
                }
                // if zerofill is disabled or quota reach zero no addition to put
            } elseif ($key == $first_placeholder_key) { // first placeholder is the last change to fill in numbers
                while ($reversed_number_array) {        // while still any number left
                    $reversed_result_array[] = array_shift($reversed_number_array);        // shift 1st number in array & put it to result
                    if (is_integer($zerofill)) {        // if zerofill is limited
                        $zerofill--;                    // decrease quota
                    }
                }
            } else { // at this point: placeholder found & remaining number exist & not first placeholder in format
                $reversed_result_array[] = array_shift($reversed_number_array);        // shift 1st number in array & put it to result
                if (is_integer($zerofill)) {        // if zerofill is limited
                    $zerofill--;                    // decrease quota
                }
            }
        }

        $result_array = array_reverse($reversed_result_array); // put back to normal order

        return implode('', $result_array); // result as string
    }

}