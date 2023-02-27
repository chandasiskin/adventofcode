<?php
    /**
     * https://adventofcode.com/2015/day/11
     *
     *
     *
     * Santa's previous password expired, and he needs help choosing a new one.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("11.txt")) { // If file is missing, terminate
        die("Missing file 11.txt");
    } else {
        $input = file_get_contents("11.txt"); // Save file as a string
    }
    
    
    
    /**
     * In part 1 we need to find the next password that satisfies all three requirements:
     * - Includes one increasing straight of at least three letters, like 'abc', 'bcd' or 'cde'. 'abd' is invalid.
     * - Does not contain 'i', 'o' or 'l'
     * - Has at least two different, non-overlapping pairs of letters, like 'aa', 'bb' or 'zz'.
     * We start with our current password (the input) and increment the least significant letter (the last one) with one.
     * We check if the password satisfies all requirements. If not, we increment the last one again. When the last letter
     * hits 'z', we roll over back to 'a'.
     * To solve this the easy way (but not the fastest) we convert all letters to their corresponding ASCII-decimal value.
     * That is: a = 97, b = 98, ..., z = 122. This simplifies the "check next password by incrementing the least-significant-letter".
     * We just add 1 to the last "letter" (that is now a number) and check if that number has exceeded the number representing 'z'.
     * If it has, we roll it back to the lowest value ('a') and increment the second-to-last value. We then check THIS value,
     * and take same action as before if necessary. We keep doing this until all values are within the allowed range (97 <= x <= 122).
     *
     *
     *
     * ** SPOILER **
     * For part 2, we just keep rolling new passwords until we find a second match.
     */
    function solve($input) {
        $lowestCharacter = ord("a"); // Decimal ASCII-value for the letter "a", for lower limit
        $highestCharacter = ord("z"); // Decimal ASCII-value for the letter "z", for upper limit
        // Get the decimal ASCII-values for the illegal letters
        $illegalCharacters = [ord("i") => 1, ord("o") => 1, ord("l") => 1];
        $passwordLength = strlen($input); // Get length of the password
        $password = str_split($input); // Make password into an array
        $password = array_map("ord", $password); // Convert all values in the array to their decimal ASCII-values
        
        
        
        // Keep looping until passwords are found
        while (true) {
            $password[$passwordLength - 1]++; // Increment the last value
            
            // Loop through all the values checking if they are all within the allowed range
            for ($i = $passwordLength - 1; $i >= 0; $i--) {
                // If current value is above the upper limit
                if ($password[$i] > $highestCharacter) {
                    $password[$i] = $lowestCharacter; // Roll back to lower limit
                    $password[$i - 1]++; // Increment the next value
                }
                
                // If the current value is within range, then the rest are too. No need to keep looping.
                else {
                    break;
                }
            }
    
    
            
            // Does password satisfy the first rule: "increasing straight of at least three letters"?
            $isValid = false;
            
            // Loop through all the values
            for ($i = 0; $i < $passwordLength - 2; $i++) {
                if ($password[$i + 1] === $password[$i] + 1 // Is the next value the same as the current value + 1?
                && $password[$i + 2] === $password[$i] + 2) { // Is the next-next value the same as the current value + 2?
                    // If we reach here, we satisfy the rule and there's no point in looking further.
                    $isValid = true;
                    
                    break;
                }
            }
            
            // If requirement was not satisfied, start over.
            if (!$isValid) {
                continue;
            }
    
    
            
            // Does password satisfy the second rule: "Does not contain 'i', 'o' or 'l'"?
            // Loop through all values checking for illegal values
            for ($i = 0; $i < $passwordLength; $i++) {
                // If an illegal character was found, start over.
                if (isset($illegalCharacters[$password[$i]])) {
                    continue 2;
                }
            }
    
    
    
            // Does password satisfy the third rule: "Has at least two different, non-overlapping pairs of letters"?
            $isValid = false;
            
            // Loop through all the values
            for ($i = 0; $i < $passwordLength - 3; $i++) {
                // Does current value equal the next one?
                if ($password[$i] === $password[$i + 1]) {
                    // Loop through the rest of the password, checking for a second pair
                    for ($j = $i + 2; $j < $passwordLength - 1; $j++) {
                        if ($password[$j] !== $password[$i] // Does current value differ from the first pair?
                        && $password[$j] === $password[$j + 1]) { // Is the current value the same as the next one?
                            // If we reach here, we satisfy the rule and there's no point in looking further.
                            $isValid = true;
                            
                            break 2;
                        }
                    }
                }
            }
    
            // If requirement was not satisfied, start over.
            if (!$isValid) {
                continue;
            }
            
            
            
            // New password found!
            $newPassword = array_map("chr", $password); // Convert numbers back to letters
            $newPassword = implode("", $newPassword); // Convert array back to string
            
            
            
            // If we have no passwords stored, store the first one
            if (!isset($result[0])) {
                $result[0] = $newPassword;
            }
            
            // If we already have one password stored, store the second one and end loop
            else {
                $result[1] = $newPassword;
                
                return $result;
            }
        }
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
