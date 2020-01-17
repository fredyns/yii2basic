<?php

namespace app\helpers;

/**
 * saveral helper function for number
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class NumberHelper
{

    public static function format($format, $number, $placeholder = '?')
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
                $reversed_result_array[] = $char;       // printout format as is
            } elseif (empty($reversed_number_array)) {  // number is or already empty
                $reversed_result_array[] = '0';         // fill with zero                
            } elseif ($key == $first_placeholder_key) { // the last placeholder for number
                while ($reversed_number_array) {        // while still any number left
                    $reversed_result_array[] = array_shift($reversed_number_array);        // shift 1st number in array & put it to result
                }
            } else { // at this point: placeholder found & remaining number exist & not first placeholder in format
                $reversed_result_array[] = array_shift($reversed_number_array);        // shift 1st number in array & put it to result
            }
        }

        $result_array = array_reverse($reversed_result_array); // put back to normal order

        return implode('', $result_array); // result as string
    }

}