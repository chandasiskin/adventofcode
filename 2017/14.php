<?php
    /**
     * https://adventofcode.com/2017/day/14
     *
     *
     *
     * Suddenly, a scheduled job activates the system's disk defragmenter.
     * Were the situation different, you might sit and watch it for a while, but today, you just don't have that kind of time.
     * It's soaking up valuable system resources that are needed elsewhere, and so the only option is to help it finish its task as soon as possible.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("14.txt")) { // If the file is missing, terminate
        die("Missing file 14.txt");
    } else {
        $input = file_get_contents("14.txt"); // Save file as an array
    }
    
    
    
    /**
     * This problem is divided into 3 steps:
     * 1. Create our knot hashes
     * 2. Turn the resulting hex into binary
     * 3. Solve the resulting map
     *
     * I'm not going into detail about the knot hashing algorithm because we covered that during problem 10.
     * Instead, I'm just going to mention that the 32-bit hexadecimal hash we got from our kno-hash-function, we need to
     * turn into a 128-character long binary. We do this by looking at a single hex-character and turn it into its corresponding
     * binary representation (with leading zeros). We do this a total of 128 times. The first time, we add "-0" to the
     * end of our input before hashing, the second time we add "-1", the third time "-2" and so on, until we have completed the "-127"-hash.
     * Now, we have a 128x128 sized map, where the first row is the binary result of hashing <input>-1, the second row
     * is the result of <input>-2, and so on.
     *
     * For part 1, we need to calculate the number of "ones" we have on our map. When building the map, we only store the
     * "ones" and remove the "zeros" in our 2D-array, so it's really simple to just add a counter to our map-builder.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we need to calculate the number of regions we have on our map. A region is a minimum of 1 "one" where all
     * the adjacent "ones" (not counting diagonals) are included in the current region. This is simply achieved with the
     * help of our old friend: BFS.
     * First, we need to find our first "one". When that is done, we keep looking for adjacent "ones" as long as we can find them.
     * For every "one" we find, we remove it from our array. When no more adjacent "ones" are found, we increment our
     * region-counter and restart from the next uncounted "one". We keep doing this until we no longer can find a "one"
     * to start at.
     */
    function solve($input, $part2 = false) {
        $input = trim($input); // Remove unwanted characters from input
        $disc = []; // Stores the map
        $usedCount = 0; // The number of "ones" in our map
        $numberOfRegions = 0; // The number of regions on our map
        
        
        
        // Create 128 knot hashes
        for ($i = 0; $i < 128; $i++) {
            $binary = myhex2bin(knotHash("$input-$i")); // Call the knot-hash-function with our input together with a counter.
                                                            // Convert the result into a 128 character long binary string
            $len = strlen($binary); // Get the length of our binary string
            
            // Loop though every character in our binary string
            for ($c = 0; $c < $len; $c++) {
                // If the current character is a one, store it in our map-array
                if ($binary[$c] === "1") {
                    $disc[$i][$c] = 1;
                    $usedCount++; // Increment our "one"-counter
                }
            }
        }
        
        
        
        // Keep looping through our map until it's empty
        while ($disc != []) {
            reset($disc); // Set pointer to the first row (the first row changes as we unset rows during our search)
            $y = key($disc); // Get the row-number
            
            // If the current row is empty, remove it and continue to the next row
            if ($disc[$y] == []) {
                unset($disc[$y]);
                
                continue;
            }
            
            reset($disc[$y]); // Set pointer to the first column in the current row
                                    // (the first column changes as we unset columns during our search)
            $x = key($disc[$y]); // Get the column-number
            unset($disc[$y][$x]); // Unset current square
            
            $query = [[$x, $y]]; // Insert our starting square into our query
            
            
            
            // Keep looking for neighbors as long as our query is populated
            do {
                $tmp = []; // Holds the next round of neighbors
                
                
                
                // Loop through every element in our query
                foreach ($query as $q) {
                    $x = $q[0]; // Get the x-coordinate from current square
                    $y = $q[1]; // Get the y-coordinate from current square
                    
                    
                    
                    // If there is a neighbor above, move to it
                    if (isset($disc[$y - 1][$x])) {
                        $tmp[] = [$x, $y - 1];
                        unset($disc[$y - 1][$x]); // Remove the neighbor above from our map
                    }
                    
                    // If there is a neighbor below, move to it
                    if (isset($disc[$y + 1][$x])) {
                        $tmp[] = [$x, $y + 1];
                        unset($disc[$y + 1][$x]); // Remove the neighbor below from our map
                    }
                    
                    // If there is a neighbor to the left, move to it
                    if (isset($disc[$y][$x - 1])) {
                        $tmp[] = [$x - 1, $y];
                        unset($disc[$y][$x - 1]); // Remove the neighbor to the left from our map
                    }
                    
                    // If there is a neighbor to the right, move to it
                    if (isset($disc[$y][$x + 1])) {
                        $tmp[] = [$x + 1, $y];
                        unset($disc[$y][$x + 1]); // Remove the neighbor to the right from our map
                    }
                }
                
                
                
                $query = $tmp; // Overwrite the current query with the upcoming
            } while ($query != []);
            
            
            
            $numberOfRegions++; // Increment our region-counter
        }
        
        
        
        return [$usedCount, $numberOfRegions];
    }
    
    
    
    /**
     * @param $input String Input key string
     * @return string The hashed string
     */
    function knotHash($input) {
        $currentPosition = 0; // Where to start reversing
        $skipSize = 0; // How much to increment <current position>
        $listSize = 256; // The number of entries in our list
        $valueInKeys = array_fill(0, $listSize, 0); // Create a list of size <lise size> where the array-key represents
                                                                    // the number in the list, and the array-value represents
                                                                    // its position in the list.
        $posInKeys = []; // Prepares a list where the array-key represents the position of the lists' number,
                        // and the array-value represents the number at that position
        
        
        
        // Update current numbers positions (0 at positions 0, 1 at positions 1, a.s.o.)
        foreach ($valueInKeys as $key => &$value) {
            $value = $key;
        } unset($value);
        
        
        
        $extra = explode(", ", "17, 31, 73, 47, 23"); // Create an array of the extra numbers to add to our input
        $extra = array_map("chr", $extra); // Convert them FROM their ascii-value
        $input = trim($input) . implode("", $extra); // Insert them into our input
        $input = array_map("ord", str_split($input)); // Create an array from our input and convert all characters INTO their ascii-value
        $hash = ""; // Holds our final hash
        
        
        
        // Run through our input a total of 64 times
        for ($i = 0; $i < 64; $i++) {
            // Loop through every ascii-value in our input
            foreach ($input as $length) {
                reverse($valueInKeys, $currentPosition, $length); // Call function to reverse the list starting at
                                                                    // position <current position> and reverse <length>
                                                                    // amount of numbers
                
                $currentPosition = ($currentPosition + $length + $skipSize) % $listSize; // Update current position by adding
                                                                                        // length of the list just reversed
                                                                                        // and the size of our skip. Use modulo
                                                                                        // to keep current position on bounds
                $skipSize++; // Increment value of the skip
            }
        }
        
        $posInKeys = array_flip($valueInKeys); // Update one of the arrays with the positions and numbers from the other
        
        
        
        // Divide our list into 16 blocks and loop through each
        for ($i = 0; $i < 16; $i++) {
            $xor = 0;
            
            
            
            // Divide each block into 16 entries and loop through each
            for ($j = 0; $j < 16; $j++) {
                $pos = 16 * $i + $j; // Get position of current entry in current block
                
                $xor = $xor ^ $posInKeys[$pos]; // XOR that entry
            }
            
            
            
            $hash .= $xor < 16 ? "0" : ""; // If our resulting XOR is smaller than 16, the resulting hex will only be
                                            // 1 character long. So we append a "0" to it
            $hash .= dechex($xor); // Convert the integer from our XOR into a hex and store it in the grand hash
        }
        
        
        
        return $hash;
    }
    
    
    
    /**
     * @param $valueInKeys array The list of numbers
     * @param $currentPosition int Where to start reversing
     * @param $length int How many numbers to reverse
     * @return void
     */
    function reverse(&$valueInKeys, $currentPosition, $length) {
        $posInKeys = array_flip($valueInKeys); // Update the other list of numbers with the first list
        $listSize = count($valueInKeys); // Get the amount of numbers in our list
        
        
        
        // Loop through the submitted amount of numbers
        for ($i = 0; $i < $length; $i++) {
            $currentValue = $posInKeys[($i + $currentPosition) % $listSize]; // Get the value at current position
            // (modulo to stay in bounds)
            $valueInKeys[$currentValue] =
                ($currentPosition + ($length - 1) - $i + $listSize) % $listSize; // Update position of current value according
            // to the reverse-rules
        }
    }
    
    
    /**
     * @param $hex string Hexadecimal string
     * @return string The hex turned into binary
     */
    function myhex2bin($hex) {
        $len = strlen($hex); // Get the length of our hex-string
        $result = ""; // Holds the resulting binary-string
        
        
        
        // Loop through every character in our hex-string
        for ($c = 0; $c < $len; $c++) {
            // Add the converted binary-string (with leading zeros when necessary) into our final result
            $result .= sprintf("%04d", base_convert($hex[$c], 16, 2));
        }
        
        
        
        return $result;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
