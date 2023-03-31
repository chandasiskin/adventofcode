<?php
    /**
     * https://adventofcode.com/2016/day/16
     *
     *
     *
     * You're done scanning this part of the network, but you've left traces of your presence.
     * You need to overwrite some disks with random-looking data to cover your tracks
     * and update the local security system with a new checksum for those disks.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("16.txt")) { // If file is missing, terminate
        die("Missing file 16.txt");
    } else {
        $input = file_get_contents("16.txt"); // Save file as a string
    }
    
    
    
    /**
     * A straight forward solution:
     * 1. Take the input-string and make a copy of it
     * 2. Inverse the copy (turn ones to zeros and vice versa)
     * 3. Merge the copy with the original string with a zero between them (<original>0<inverted copy>)
     * 4. Repeat the process with this new, longer string until the length of the string is the same or greater than required
     * 5. Shorten the string, if necessary, to the required length by removing characters from the right.
     * 6. For checksum, look at every pair of characters. If they are the same, return 1. Otherwise, return 0.
     * 7. Once all characters have been checked, if the length of the checksum is even, repeat above step. Otherwise, we have our solution
     *
     *
     *
     * ** SPOILER **
     * Part 2 is exactly the same as part 1, except the required length of the string is larger
     */
    function solve($input, $part2 = false) {
        $string = trim($input); // Remove unwanted characters from our input
        $limit = !$part2 ? 272 : 35651584; // Set the length of the string depending on if we're doing part 1 or part 2
        
        
        
        $result = dragonCurve($string, $limit); // Function to process our string
        $result = checksum($result); // Function to get checksum of said string
        
        
        
        return $result;
    }
    
    
    /**
     * @param $string string Holds our binary string
     * @param $limit int How long we want the string to be
     * @return string Return the resulting string
     */
    function dragonCurve($string, $limit) {
        // Keep looping until we have acquired the correct length
        while (strlen($string) < $limit) {
            $invertedCopy = ""; // Holds the inverted copy of the original string
            
        
            
            // Loop through each character in the original string
            for ($c = strlen($string) - 1; $c >= 0; $c--) {
                // If the current character is a one, store a zero
                if ($string[$c] === "1") {
                    $invertedCopy .= "0";
                }
            
                // If the current character is a zero, store a one
                else {
                    $invertedCopy .= "1";
                }
            }
        
        
            
            // Append a zero and the new, inverted string into the original
            $string .= "0" . $invertedCopy;
        }
        
        
        
        // Return only the correct length of the string
        return substr($string, 0, $limit);
    }
    
    
    /**
     * @param $string string A binary string
     * @return string Return the resulting checksum
     */
    function checksum($string) {
        // Keep looping as long as the length of the checksum is even
        while (strlen($string) % 2 === 0) {
            $checksum = ""; // Stores the next checksum
            $len = strlen($string); // Get length of the current checksum
            
            
            
            // Loop through every other character in the current checksum
            for ($c = 0; $c < $len; $c += 2) {
                // If current character is the same as the next one, insert a one in the next checksum
                if ($string[$c] === $string[$c + 1]) {
                    $checksum .= "1";
                }

                // If current character is not the same as the next one, insert a zero in the next checksum
                else {
                    $checksum .= "0";
                }
            }
            
            
            
            $string = $checksum; // Overwrite current checksum with the new one
        }
        
        
        
        return $checksum;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
