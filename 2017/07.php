<?php
    /**
     * https://adventofcode.com/2017/day/7
     *
     *
     *
     * Wandering further through the circuits of the computer, you come upon a tower of programs that have gotten themselves into a bit of trouble.
     * A recursive algorithm has gotten out of hand, and now they're balanced precariously in a large tower.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("07.txt")) { // If file is missing, terminate
        die("Missing file 07.txt");
    } else {
        $input = file("07.txt"); // Save file as an array
    }
    
    
    
    /**
     * Let's see if I can explain my thought-pattern for this problem:
     * 1. We organize our input into an array containing information about the weight of the current disc,
     *      the total weight of the current disc together with all of its sub-discs and what its sub-discs are (if any).
     * 2. To find the disc that all other discs rests on, we need to find the disc with the largest total weight.
     *      Part 1 solved1
     *
     *
     * ** SPOILER **
     * For part 2, we expand the list from above:
     * 3. Starting from our heaviest disc at the bottom,
     *      we work our way up one step at a time looking for the one disc that stands out from the rest. Weight-wise.
     *      That disc has a sub-disc that creates the unbalance.
     * 4. When recursively following above step (3), once we find an unbalanced disc, we know that's the disc we are looking for.
     * 5. Calculate how much over-/underweight that disc has compared to the others, add/subtract that difference from its weight,
     *      and we have our answer for part 2!
     */
    function solve($input) {
        $discs = []; // Holds all the different discs with their respective data
        $solutions = []; // Stores the solutions for part 1 and part 2
        
        
        
        // Loop through each row in our input
        foreach ($input as $row) {
            // Remove any unwanted characters together with the characters ",", "(" and ")" and convert row into an array
            $splitted = explode(" ", trim(preg_replace("/[,()]+/", "", $row)));
            // Get information about the weight of the current disc and its sub-discs
            $discs[$splitted[0]] = ["weight" => intval($splitted[1]), "totalWeight" => 0, "discs" => array_splice($splitted, 3)];
        }
        
        
        
        $currentHighestWeight = PHP_INT_MIN; // Stores the currently largest total weight (for part 1)
        
        // Loop through each disc and update the value for "total weight"
        foreach ($discs as $discname => &$arr) {
            $arr["totalWeight"] = getWeight($discs, $discname); // Get total weight of current disc (its weight plus all its sub-discs weight)
            
            
            
            // If current "total weight" exceeds the currently highest total weight, update
            if ($arr["totalWeight"] > $currentHighestWeight) {
                $currentHighestWeight = $arr["totalWeight"]; // Update best total weight
                $solutions[0] = $discname; // Store then name of the disc that currently has the highest total weight
            }
        } unset($arr);
        
        
        
        $unbalancedDisc = findUnbalance($discs, $solutions[0]); // Find the disc that's causing the unbalance
        
        
        
        // Calculate and store the needed weight to bring balance to everything
        $solutions[1] = $discs[$unbalancedDisc[0]]["weight"] - $unbalancedDisc[1];
        
        
        
        return $solutions;
    }
    
    
    /**
     * @param $discs array Holds all the discs and their respective data
     * @param $discname string Current disc to calculate weight for
     * @return int Returns the total weight of the current disc
     */
    function getWeight($discs, $discname) {
        $totalWeight = $discs[$discname]["weight"]; // Initiate the total weight with the weight of the current disc
        
        
        
        // Loop through every sub-disc
        foreach ($discs[$discname]["discs"] as $name) {
            $totalWeight += getWeight($discs, $name); // Add the sub-discs total weight to the current discs total weight
        }
        
        
        
        return $totalWeight;
    }
    
    
    /**
     * @param $discs array Holds all the discs and their respective data
     * @param $discname string Current disc to check if it's causing the unbalance
     * @return array Return the name of the unbalancing disc and the weight difference between the rest of the sub-discs
     */
    function findUnbalance($discs, $discname) {
        $results = []; // Stores the result
        
        
        
        // Loop through every sub-disc
        foreach ($discs[$discname]["discs"] as $name) {
            $results[] = [$name, $discs[$name]["totalWeight"]]; // Store the sub-discs name and total weight
        }
        
        // Sort the resulting array by total weight (this makes it easier to find the odd one out)
        uasort($results, function($a, $b) {
            return $b[1] - $a[1];
        });
        
        
        
        // If the total weight of the first and second discs differ, the first disc is the odd one out
        if ($results[0][1] !== $results[1][1]) {
            $res = findUnbalance($discs, $results[0][0]); // Check the first discs sub-discs for unbalance
        }
        
        // If the total weight of the second to last and last disc differ, the last disc is the odd ones out
        elseif ($results[count($results) - 2][1] !== $results[count($results) - 1][1]) {
            $res = findUnbalance($discs, $results[count($results) - 1][0]); // Check the last discs sub-discs for unbalance
        }
        
        // If no unbalance was found, the disc causing all the unbalance has been found!
        else {
            return [$discname]; // Return the name of the disc
        }
        
        
        
        // If we only have the name of the unbalancing disc but not the amount of weight it unbalances with,
        // calculate the unbalancing weight
        if (!isset($res[1])) {
            // Calculate the difference between the first and second disc (if their weight is the same, the result will be 0).
            // Calculate the difference between the second to last and last disc (if their weight is the same, the result will be 0).
            // Since one of the results will always be 0, we can add the results together.
            $res[1] = ($results[0][1] - $results[1][1]) + ($results[count($results) - 2][1] - $results[count($results) - 1][1]);
        }
        
        
        
        // Return [<unbalanced discs name>, <the amount of weight it unbalances>]
        return $res;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
