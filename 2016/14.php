<?php
    /**
     * https://adventofcode.com/2016/day/14
     *
     *
     *
     * In order to communicate securely with Santa while you're on this mission,
     * you've been using a one-time pad that you generate using a pre-agreed algorithm.
     * Unfortunately, you've run out of keys in your one-time pad, and so you need to generate some more.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("14.txt")) { // If file is missing, terminate
        die("Missing file 14.txt");
    } else {
        $input = file_get_contents("14.txt"); // Save file as a string
    }
    
    
    
    /**
     * This problem has a pretty straightforward solution:
     * 1. We take our unique salt from our input, merge it with an incrementing index and produce a md5-hash.
     * 2. Then we do the same thing for the next 1000 hashes and compare if the first hash has 3 of the same character in a row
     * and any of the next 1000 hashes has the same character but 5 in a row. We keep doing this until we have found a total
     * of 64 matches.
     *
     * To decrease runtime we store all generated hashes. This way, we don't have to generate the same hash multiple times.
     *
     *
     *
     * ** SPOILER **
     * Same as above, except we take our newly generated hash and do another md5-hashing on it. We do this a total of 2017 times.
     */
    function solve($input, $part2 = false) {
        $salt = trim($input); // Holds our unique salt
        $hashes = []; // Stores all our generated hashes
        $validHashCount = 0; // Holds the count of all valid hashes
        
        
        
        // Start at index 0 and keep incrementing
        for ($index = 0; ; $index++) {
            // Have we already generated a hash for this index?
            if (isset($hashes[$index])) {
                $hash = $hashes[$index]; // Get that hash from memory
            }
            
            // If we do not have that hash stored from earlier
            else {
                $hash = getHash($salt . $index, $part2); // Generate the hash
                $hashes[$index] = $hash; // Store the hash
            }
            
            
            
            // Does the hash have 3 of the same character in a row?
            if (preg_match("/(.)\\1\\1/", $hash, $matches)) {
                $max = $index + 1000; // Investigate the next 1000 indexes
                $character = $matches[1]; // Get the triplet-character
                
                
                
                // Loop through the next 1000 indexes
                for ($i = $index + 1; $i <= $max; $i++) {
                    // Have we already generated a hash for this index?
                    if (isset($hashes[$i])) {
                        $newHash = $hashes[$i]; // Get that hash from memory
                    }

                    // If we do not have that hash stored from earlier
                    else {
                        $newHash = getHash($salt . $i, $part2); // Generate the hash
                        $hashes[$i] = $newHash; // Store the hash
                    }
    
                    
                    
                    // Does the new hash contain 5 in a row of the same character as the first hash contained 3 in a row of?
                    if (preg_match("/" . $character . "{5}/", $hashes[$i])) {
                        $validHashCount++; // A valid hash has been found. Increment counter.
                        
                        // Have we found all 64 hashes?
                        if ($validHashCount === 64) {
                            break 2; // Exit all loops
                        }
                        
                        continue 2;
                    }
                }
            }
        }
        
        
        
        return $index;
    }
    
    
    /**
     * @param $salt string The string to md5-hash
     * @param $part2 boolean If we are to do 1 or 2017 hashes
     * @return string Returns the md5-hashed string
     */
    function getHash($salt, $part2) {
        $stretches = !$part2 ? 1 : 2017; // Are we doing 1 hashing for part 1, or 2017 hashes for part 2?
        $hash = $salt; // The salt
        
        
        
        // Rehash 1 or 2017 times
        for ($i = 0; $i < $stretches; $i++) {
            $hash = md5($hash); // Generate a new hash
        }
        
        
        
        return $hash;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
