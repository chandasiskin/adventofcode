<?php
    /**
     * https://adventofcode.com/2017/day/13
     *
     *
     *
     * You need to cross a vast firewall.
     * The firewall consists of several layers, each with a security scanner that moves back and forth across the layer.
     * To succeed, you must not be detected by a scanner.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("13.txt")) { // If the file is missing, terminate
        die("Missing file 13.txt");
    } else {
        $input = file("13.txt"); // Save file as an array
    }
    
    
    
    /**
     * For this problem, we are going to create an array that stores at what depth a layer is at and what range that layer has.
     * The depth is used to determine at what point we are going to reach that layer. And the range is used to calculate
     * where the scanner is when we reach that layer.
     * To determine the range, we need to figure out how many moves the scanner makes before it repeats itself. For example,
     * a scanner that has 4 locations it can be at, we will print it out like this.
     * 0 []
     * 1 []
     * 2 []
     * 3 []
     * This means, when the scanner starts at 0, it will move down to 1, 2 and 3 and then reverse and return to 0, first via 2
     * and via 1. This means the scanner moves 0->1->2->3->2->1 before it starts repeating itself. So, a range of 4
     * has 6 moves. A range of 3 has 4 moves (0->1->2->1). The general formula, to determine the amount of moves based on range,
     * is 2 * <range> - 2. In plain text, this means "in a single iteration it visits every location twice (2 * <range>) except two:
     * the first and the last (that's why the -2) which it only visits once".
     * NOTE! 2 * <range> - 2 can be rewritten as 2 * (<range - 1)
     *
     * To determine where the scanner is at any given time, we simply check
     * "<the time passed since we started> % <total number of moves in that layer>. The result is the location of the scanner.
     * For example, if the answer is 3, this means the scanner is at location 3.
     * NOTE! The time passed is calculated by taking the delay before we start into account and add the depth of the current layer.
     * If there are layers at depth 0, 1 and 3, and we delay our start with 3 seconds, we reach the first layer when time is 3,
     * the second layer at <time> = 4 and the last layer at <time> = 6
     *
     * For part 1, we start at <time> = 0 and see how many scanners see us. If a scanner location is at 0 when we reach that depth,
     * the scanner sees us, and we add the received damage (<depth> * <range> [take note that it's the layer range we multiply with,
     * not the number of possible moves]) to the total.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we need to determine the amount of delay needed to receive 0 damage. So, we simply test every single delay
     * until we reach the end without taking any damage.
     */
    function solve($input, $part2 = false) {
        $firewalls = []; // Holds the firewalls at various depths
        
        
        
        // Loop through every single row in our input
        foreach ($input as $row) {
            list($layer, $range) = explode(": ", trim($row)); // Extract the depth and range information from the current row
            $firewalls[$layer] = 2 * ($range - 1); // Calculate the number of possible moves and store it in an array
        }
        
        
        
        // If we are doing part 1
        if (!$part2) {
            $time = 0; // Zero delay
            $damage = 0; // The total amount of damage received


            
            // Loop through every single depth containing a firewall
            foreach ($firewalls as $layer => $range) {
                // If the firewalls' scanner detects us, calculate damage received
                if (($layer + $time) % $range === 0) {
                    $damage += $layer * (($range / 2) + 1); // Reverse the calculation of "number of moves based on range"
                }
            }



            return $damage;
        }
        
        // If we are doing part 2
        else {
            // Loop through every single delay
            for ($time = 0; ; $time++) {
                
                
                
                // Loop through every single depth containing a firewall
                foreach ($firewalls as $layer => $range) {
                    // If the firewalls' scanner detects us, we've failed and need to try another delay
                    if (($layer + $time) % $range === 0) {
                        continue 2;
                    }
                }


                
                // If we reach this point, no scanner has detected us, and we found our answer
                return $time;
            }
        }
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
