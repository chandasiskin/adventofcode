<?php
    /**
     * https://adventofcode.com/2015/day/13
     *
     *
     *
     * In years past, the holiday feast with your family hasn't gone so well. Not everyone gets along!
     * This year, you resolve, will be different. You're going to find the optimal seating arrangement
     * and avoid all those awkward conversations.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("13.txt")) { // If file is missing, terminate
        die("Missing file 13.txt");
    } else {
        $input = file("13.txt"); // Save file as an array
    }
    
    
    
    /**
     * For part 1 we need to arrange persons around a round table in a way that we get the most happiness.
     * Firstly we create an array where we store the happiness between every person. Note that just because person A
     * enjoys sitting next to person B, it doesn't have to be the same vice versa.
     * After the array has been made, we try every single seating arrangement and calculate the total happiness
     * and store the highest one.
     *
     *
     *
     * ** SPOILER **
     * Part 2 is the same problem as part 1, except we add another person: ourselves. Our happiness towards everyone else
     * is the same as their happiness towards us: 0.
     */
    function solve($input, $part2 = false) {
        $happiness = []; // Stores the happiness between all persons
    
    
        /**
         * Generate the array storing all the happiness between people
         */
        // Loop through all input-rows
        foreach ($input as $row) {
            $tmp = explode(" ", substr(trim($row), 0, -1)); // Turn the row into an array, separated at spaces
            $happiness[$tmp[0]][$tmp[10]] = intval($tmp[3]); // Store person <$tmp[0]>:s happiness towards person <$tmp[10]>
            $happiness[$tmp[0]][$tmp[10]] *= $tmp[2] === "lose" ? -1 : 1; // If it's negative happiness, multiply it with -1
            
            // If part 2, add ourselves to the seating arrangement
            if ($part2) {
                $happiness["me"][$tmp[0]] = $happiness["me"][$tmp[0]] ?? 0; // Add our happiness towards person <$tmp[0]>
                $happiness["me"][$tmp[10]] = $happiness["me"][$tmp[10]] ?? 0; // Add our happiness towards person <$tmp[10]>
                $happiness[$tmp[0]]["me"] = $happiness[$tmp[0]]["me"] ?? 0; // Add person <$tmp[0]> happiness towards us
                $happiness[$tmp[10]]["me"] = $happiness[$tmp[10]]["me"] ?? 0; // Add person <$tmp[10]> happiness towards us
            }
        }
        
        
        
        $persons = array_keys($happiness); // Get all the different people sitting around the table
        $res = bfs($happiness, $persons); // Call function BFS, which creates every possible seating arrangement,
                                          // calculates the happiness level and returns the highest one.
        
        
        
        return $res;
    }
    
    
    
    function bfs($happiness, $toTable, $path = "") {
        if ($toTable === []) { // If no more people to seat at the table
            $sum = 0; // Store the happiness level
            $seatings = explode("-", $path); // Turn seating arrangement from string to array
            
            
            
            // Calculate happiness level
            for ($i = 0, $max = count($seatings); $i < $max; $i++) {
                if ($i === 0) { // If we are at the first seat
                    $sum += $happiness[$seatings[$i]][$seatings[$max - 1]]; // Check happiness towards the last seat
                } else {
                    $sum += $happiness[$seatings[$i]][$seatings[$i - 1]]; // Check happiness towards the previous seat
                }
                
                if ($i === $max - 1) { // If we are at the last seat
                    $sum += $happiness[$seatings[$i]][$seatings[0]]; // Check happiness towards the first seat
                } else {
                    $sum += $happiness[$seatings[$i]][$seatings[$i + 1]]; // Check happiness towards the next seat
                }
            }
            
            
            
            return $sum;
        }
        
        
        
        $bestValue = PHP_INT_MIN;
        
        foreach ($toTable as $k => $name) { // Loop through all person left to arrange around the table
            $tmp = $toTable; // Copy current list
            unset($tmp[$k]); // Remove current person
            
            // Call the function recursively with the new seating arrangement and the persons left to arrange.
            // If table is full, it returns it's total happiness level.
            $value = bfs($happiness, $tmp, $path === "" ? $name : "$path-$name");
            
            $bestValue = max($bestValue, $value); // Check if current happiness level is the best one.
        }
        
        
        
        return $bestValue;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true;
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
