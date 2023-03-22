<?php
    /**
     * https://adventofcode.com/2016/day/4
     *
     *
     *
     * Finally, you come across an information kiosk with a list of rooms.
     * Of course, the list is encrypted and full of decoy data, but the instructions to decode the list are barely hidden nearby.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("04.txt")) { // If file is missing, terminate
        die("Missing file 04.txt");
    } else {
        $input = file("04.txt"); // Save file as an array
    }
    
    
    
    /**
     * For part 1 we need to calculate the occurrence of all the letters in the room name (the part before the number).
     * We then have to compare the occurrence-result with the characters in the bracket.
     * The first character in the bracket should be the character that occurs the most in the room name.
     * If two characters tie, they are ranked alphabetically.
     * When a room is considered "real", we add the rooms sector ID to the total.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we play with a "Caesar cipher" (https://en.wikipedia.org/wiki/Caesar_cipher).
     * We take every character in the room name, one by one, and move "room sector ID" times to the next character.
     * When we reach character "z", the next character will be "a".
     * For this, we use the ascii-value for each character. The problem is that "a":s ascii-value is not 0, but 97,
     * so we have to turn "a" to 0 by subtracting 97. We do this for every character (b = 98 turns to 1, c = 99 turns to 2, and so on).
     * We then add our "room sector ID", do mod 26 (the total amount of different characters),
     * and finally re-add the previously subtracted 97 to get to the new character.
     *
     * We are looking for the room that "North Pole objects are stored", so for every room that we discover we look for the words:
     * 1. north
     * 2. pole
     * 3. object
     * When all these three words are found, return the sector room ID
     */
    function solve($input, $part2 = false) {
        // If doint part 1
        if (!$part2) {
            $validRoomSum = 0; // Holds the total sum of all sector room ID:s
    
            
            
            // Loop through each input-row
            foreach ($input as $row) {
                // Split current row into an array. First part is "room name", second part is "sector room id" and the third part is the bracket-part
                preg_match("/^([^\d]+)-([\d]+)\[(.*?)\]$/", trim($row), $matches);
                
                // Count all characters in the room name and store the result in an array
                $characterCount = count_chars($matches[1], 1);
                
                ksort($characterCount); // Sort alphabetically
                arsort($characterCount); // Sort by occurrence
                
                $c = 0; // Counts how many characters we have checked
                // Loop through the first five characters in the character-count array
                foreach ($characterCount as $char => $count) {
                    // If the character is a hyphen, skip to next
                    if (chr($char) === "-") {
                        continue;
                    }
            
                    // If current position of the character-count is not matching to the position in the bracket, continue with the next room
                    if ($char !== ord($matches[3][$c])) {
                        continue 2;
                    }
            
                    $c++; // Increment the amount of characters we have checked
                    if ($c > 4) { // If more than 5 characters have been checked, room is done
                        break;
                    }
                }
                
                
                
                $validRoomSum += intval($matches[2]); // Add room sector id to the total
            }
    
    
            
            return $validRoomSum;
        }
        
        // If doing part 2
        else {
            // Loop through each row
            foreach ($input as $row) {
                // Split current row into an array. First part is "room name", second part is "sector room id" and the third part is the bracket-part
                preg_match("/^([^\d]+)-([\d]+)/", trim($row), $matches);
                
                $len = strlen($matches[1]); // Get length of the room name
                $result = ""; // Holds the current decrypted room name
                for ($c = 0; $c < $len; $c++) { // Loop through each character in the room name
                    // If character is a hyphen, decode it to a space
                    if ($matches[1][$c] === "-") {
                        $result .= " ";
                    }
                    
                    // Otherwise, find the decrypted letter
                    else {
                        $tmp = ord($matches[1][$c]); // Get the ascii-value of the current letter
                        $tmp -= 97; // Subtract 97 to turn the value of letter "a" to 0, "b" to 1, "c" to 2 and so on
                        $tmp += intval($matches[2]); // Add the room sector id
                        $tmp %= 26; // Modulo by the amount of characters in the alphabet
                        $tmp += 97; // Re-add the previously subtracted 97
                        $result .= chr($tmp); // Get the letter from it's ascii-value
                    }
                }
                
                
                
                // If the decrupted room name contains the words "north", "pole", and "object", we have a match!
                if (strpos($result, "north") !== false
                && strpos($result, "pole") !== false
                && strpos($result, "object") !== false) {
                    return $matches[2];
                }
            }
        }
        
        
        
        // If we reach this point, we haven't found our room
        return -1;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
