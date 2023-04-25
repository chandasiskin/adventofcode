<?php
    /**
     * https://adventofcode.com/2017/day/6
     *
     *
     *
     * A debugger program here is having an issue: it is trying to repair a memory reallocation routine,
     * but it keeps getting stuck in an infinite loop.
     */



    /**
     * Get input from file
     */
    if (!is_file("06.txt")) { // If file is missing, terminate
        die("Missing file 06.txt");
    } else {
        $input = file_get_contents("06.txt"); // Save file as a string
    }



    /**
     * My code for this solution is so ugly, but it works, and it's fast enough to pass my threshold. And I feel a bit lazy today.
     * We start with a list with some random value at each position. For every loop-iteration we find the position of the highest value
     * (lowest position wins if multiple values are the same). We take the value from that position, leaving nothing behind,
     * and start distributing it to the right. Meaning, if we have the value of 3 at position 2, we increment the value by one at positions 3, 4, 5.
     * If we have, lets say, only 4 values in our list, we just roll over and start over all the way to the left. With the same example as earlier,
     * we would increment the values by one at positions 3, 4 and 1 (in this example, indexing starts at 1, not at 0).
     * If the values is much higher than in our example, we just keep looping around the positions over and over again,
     * each time incrementing by one, until our total increments equal to the value we began with.
     * And yes, if we come back to the positions we started at, we increment that as well.
     * Once a value has been distributed, we check if the current state has occurred before. If not, we keep going.
     *
     *
     *
     * ** SPOILER **
     * Part 2: since we are keeping track of our past states, when a familiar state occurs, we just need to know at what step that state happened before.
     * We know that current state has happened before (because our loop quit) and we know after how many steps the loop quit (count the past states).
     * To count the length of two duplicates, we just take <steps until current state> - <steps until last time this state occurred>
     */
    function solve($input) {
        // Convert input into an array (by splitting by any space-characters) and force all values to integers
        $memoryBanks = array_map("intval", preg_split("/\s+/", $input));
        $size = count($memoryBanks); // Get the amount of blocks in our input
        $states = 0; // At what state-number we are currently on
        
        
        
        // Keep looping until a state occurs twice
        do {
            $history[implode("-", $memoryBanks)] = $states++; // Store current state and its state-number
            list($pos, $packages) = getMax($memoryBanks); // Get the maximum value and at what positions it's at
            $memoryBanks[$pos] = 0; // Resets the value to 0 at current position
            
            
            
            // Distribute the value to the right until exhausted
            for ($i = 0; $i < $packages; $i++, $pos++) {
                $memoryBanks[($pos + 1) % $size]++; // Increment the value at current pos
            }
        } while (!isset($history[implode("-", $memoryBanks)]));
        
        
        
        // For part 1, return the current state number
        // For part 2, return <current state number> - <state number when this state occurred the first time>
        return [$states, $states - $history[implode("-", $memoryBanks)]];
    }
    
    
    /**
     * @param $arr array The array to look in for the highest value
     * @return array Returns the key and the value of the highest value
     */
    function getMax($arr) {
        $max = [null, PHP_INT_MIN]; // Holds the key of the highest value, and the value itself
        
        
        
        // Loop through each value
        foreach ($arr as $key => $val) {
            // If current value is higher than the current highest
            if ($val > $max[1]) {
                $max = [$key, $val]; // Store the key and its value
            }
        }
        
        
        
        return $max;
    }



    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;



    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
