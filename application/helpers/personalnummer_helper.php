<?php
  /** 
   * **   **     **     ******  **    **
   * 88  **   **    **  **      **    **
   * !!**     ** && **  ******  **    **
   * **       **    **      **  **    **
   * 8        **    **  ******     **  
   * 
   * Manage Personal Number via PHP.
   * Created By : Vasudevan.EP
   * Company : Pointservices - Sweden AB
   * 
   * Add Century To Personal Id
   * Send personal no as request 
   * Get 12 digit as response
   * @Param  personal id number
   * @Param  seperator
   * @return personal id string
   *  */ 
 
  function AddCenturyToPersonalid($personal_id,$separator='-')
  {
    if (strpos($personal_id, '+') !== false) {
        $separator='+';
    }
    $personal_id = preg_replace('/[^\\d]+/','',$personal_id);
    
    if(strlen($personal_id) > 0 && strlen($personal_id) < 12)
    {
        $personal_id = getPIdYear($personal_id,$separator,false);
    }

    return $personal_id;
  }

  /**
   * Get Year 
   * @param personal_id - 10 digit numbers
   * @param separator - Find the century if want 19th century old pass + 
   * eg: - : 2005, + : 1905
   * @param only_year - Boolean send year or year appended with personal id.
   * @return string
   */

  function getPIdYear($personal_id,$separator='-',$only_year=true) : string
  {
    $prefix = str_split($personal_id,2);

    $year = $prefix[0];
    $month = $prefix[1];
    $date = $prefix[2];

    $birthday = $year . '-' . $month . '-' . $date;

    $todayObj = new DateTime('today');

    $century = '19';

    if ($todayObj < new DateTime('20' . $birthday)) {
        $century = '19';
    } elseif ($todayObj >= new DateTime('20' . $birthday) && $separator === '-') {
        $century = '20';
    } elseif ($todayObj >= new DateTime('19' . $birthday) && $separator === '+') {
        $century = '19';
    }
    if($only_year)
    return $century . $year;

    return $century . $personal_id;
  }

/**
   * Get Current Age 
   * @param personal_id - 10-12 digit numbers
   * @param today - Add check date 
   * @return int - Sends Age in number
   */
  
  function getAge(string $personal_id,string $today = 'today'): int {
    
    $personal_id = trim(preg_replace('#[^\d]#', '', $personal_id));

    $prefix = str_split($personal_id,2);
    if(strlen($personal_id) < 12)
    {
        $year = getPIdYear($personal_id);
        $month = $prefix[1];
        $date = $prefix[2];
    }
    else{
        /* Add Century */
        $year = $prefix[0].$prefix[1];
        $month = $prefix[2];
        $date = $prefix[3];
    }

    $birthday = $year . '-' . $month . '-' . $date;
    
    if (!($month <= 12 && $date <= 31)) {
        return false;
    }

    $from = new DateTime($birthday);
    $todayObj = new DateTime($today);

    return $from->diff($todayObj)->y;
}

/**
 * Returns the gender from the personal number.
 * @return string
 */
function getGender(string $personal_id): string {
    
    $genderElement = str_split(substr($personal_id, -4),3);

    $identifier = intval($genderElement[0]);

    if (($identifier % 2) === 0) {
        return 'f';
    }

    return 'm';
}

 /**
     * Returns a censored version of the personal number.
     * Will be censored at the end of the code.
     * @return string
     */
function getCensored(string $personal_id,$sensor_digits='4'): string {
    
    $personal_id = substr($personal_id,0,'-'.$sensor_digits);
    
    return $personal_id.str_repeat('*',$sensor_digits);
}

/**
 * Calculates the Luhn checksum of a personal no.
 *
 * @param string $number
 * @return int The modulo 10 of the number
 */
function luhnChecksum(string $number): int {

    $number = trim(preg_replace('#[^\d]#', '', $number));
    // Remove the first 2 digits
    if(strlen($number) > 10 && strlen($number) == 12)
    {
        $number = substr($number,2);
    }
    if($number == 0)
    {
        return false;
    }

    // Reverse the string
    $number = strrev($number);
    
    $total = 0;

    // From the rightmost digit, which is the check digit,
    // and moving left, double the value of every second digit.
    for ($i = 0; $i <= strlen($number) - 1; $i++) {
        $val = (int) $number[$i];

        $val = ($i & 1) ? $val * 2 : $val;

        // If the result of this doubling operation is
        // greater than 9 then subtract 9 from the product
        if ($val > 9) {
            $val -= 9;
        }

        // Take the sum of all the digits
        $total += $val;
    }

    // If the total modulo 10 is equal to 0
    // then the number is valid according to the Luhn formula
    return $total % 10 === 0;
}