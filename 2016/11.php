<?php
    /**
     * https://adventofcode.com/2016/day/11
     *
     *
     *
     * You come upon a column of four floors that have been entirely sealed off from the rest of the building except for a small dedicated lobby.
     * There are some radiation warnings and a big sign which reads "Radioisotope Testing Facility".
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("11.txt")) { // If file is missing, terminate
        die("Missing file 11.txt");
    } else {
        $input = file("11.txt"); // Save file as an array
    }
    
    
    
    /**
     * This problem is a juice one! Solving this by hand is so much easier than trying to code it.
     * But here we are, finally with a general solution!
     * First, we need to convert our input into a string of numbers, where each number represents the floor that generator/microchip is located
     * (and the first number representing the elevator). To clarify, the example would translate into "12131",
     * where the first number would tell us that the elevator is at floor 1, the HG is at floor 2, the HM at floor 1,
     * LG at floor 3 and finally LM at floor 1.
     * With the input translated, we prepare our BFS (https://en.wikipedia.org/wiki/Breadth-first_search).
     * We have a function to determine when we are done, that simply checks if all items are on the top floor.
     * Another function we created is one that checks if any microchips have been fried.
     * This is done by checking if there is a generator on every floor with a non-matching microchip that is not protected by its matching generator.
     * NOTE: When keeping track of the moves we've made, it doesn't matter in what order we move the generators or microchips.
     *      Moving generator A with microchip A is equal as moving generator B and microchip B,
     *      so we need to take in consideration when storing our path history.
     *      We do this by simply storing the amount of generators and microchips on each floor.
     *      Otherwise, the solving time will be way too long.
     *
     *
     * ** SPOILER **
     * Same as part 1, except we add two more generators and microchips to the bottom floor.
     */
    function solve($input, $part2 = false) {
        $modules = input2string($input); // Translate the input into a string of numbers and save it
        // If we are doing part 2, add two generators and modules to the bottom floor
        if ($part2) {
            $modules .= "1111";
        }
        $bottomFloor = 1; // Set bottom floor
        $topFloor = 4; // Set top floor
        $query = [$modules]; // Set our starting point for our BFS
        $history[historify($modules)] = 1; // Holds our visited paths
        $steps = 0; // Holds our total step-count
        $max = strlen($modules); // The amount of items (used in the for-loops)
        
        
        
        // Start our BFS
        do {
            $tmp = []; // Stores the next states
            
            
            
            // Loop through every current state
            foreach ($query as $q) {
                // Have we managed to move everything to the top floor?
                if (isDone($q, $topFloor)) {
                    return $steps;
                }


                
                // Loop through every item (except the elevator)
                for ($i = 1; $i < $max; $i++) {
                    // If the elevator is on the same floor as the current item
                    if ($q[0] === $q[$i]) {
                        // If the elevator is not on the bottom floor, initiate a move down
                        if (intval($q[0]) > $bottomFloor) {
                            $nextSetup = $q; // Copy current state
                            $nextSetup[0] = strval(intval($q[0]) - 1); // Move elevator one floor down
                            $nextSetup[$i] = strval(intval($q[$i]) - 1); // Move current item one floor down
                            
                            
                            
                            if (!isset($history[historify($nextSetup)]) // Have we been in this state before?
                            && !isFried($nextSetup)) { // Have any chips been fried?
                                $tmp[] = $nextSetup; // Save our new, valid state
                                $history[historify($nextSetup)] = 1; // Add new, valid state to history
                            }
                        }
                        
                        // If the elevator is not on the bottom floor, initiate a move up
                        if (intval($q[0]) < $topFloor) {
                            $nextSetup = $q; // Copy current state
                            $nextSetup[0] = strval(intval($q[0]) + 1); // Move elevator one floor up
                            $nextSetup[$i] = strval(intval($q[$i]) + 1); // Move current item one floor up
                            
                            
                            
                            if (!isset($history[historify($nextSetup)]) // Have we been in this state before?
                                && !isFried($nextSetup)) { // Have any chips been fried?
                                $tmp[] = $nextSetup; // Save our new, valid state
                                $history[historify($nextSetup)] = 1; // Add new, valid state to history
                            }
                        }
                    }
                    
                    
                    
                    // Loop through every other set of items
                    for ($j = $i + 1; $j < $max; $j++) {
                        // If the elevator is on the same floor as the first and second items
                        if ($q[0] === $q[$i] && $q[0] === $q[$j]) {
                            // If the elevator is not on the bottom floor, initiate a move down
                            if (intval($q[0]) > $bottomFloor) {
                                $nextSetup = $q; // Copy current state
                                $nextSetup[0] = strval(intval($q[0]) - 1); // Move elevator one floor down
                                $nextSetup[$i] = strval(intval($q[$i]) - 1); // Move first item one floor down
                                $nextSetup[$j] = strval(intval($q[$j]) - 1); // Move second item one floor down
                                
                                
                                
                                if (!isset($history[historify($nextSetup)]) // Have we been in this state before?
                                    && !isFried($nextSetup)) { // Have any chips been fried?
                                    $tmp[] = $nextSetup; // Save our new, valid state
                                    $history[historify($nextSetup)] = 1; // Add new, valid state to history
                                }
                            }
                            
                            // If the elevator is not on the top floor, initiate a move up
                            if (intval($q[0]) < $topFloor) {
                                $nextSetup = $q; // Copy current state
                                $nextSetup[0] = strval(intval($q[0]) + 1); // Move elevator one floor up
                                $nextSetup[$i] = strval(intval($q[$i]) + 1); // Move first item one floor up
                                $nextSetup[$j] = strval(intval($q[$j]) + 1); // Move second item one floor up
                                
                                
                                
                                if (!isset($history[historify($nextSetup)]) // Have we been in this state before?
                                    && !isFried($nextSetup)) { // Have any chips been fried?
                                    $tmp[] = $nextSetup; // Save our new, valid state
                                    $history[historify($nextSetup)] = 1; // Add new, valid state to history
                                }
                            }
                        }
                    }
                }
            }
            
            
            
            $steps++; // Increment step-count
            $query = $tmp; // Overwrite current states with the new ones
        } while ($query != []);
        
        
        
        die("No solution found");
    }
    
    
    /**
     * @param $modules string Holds all the items, including the elevator
     * @return bool If a chip has been fried, return true. Else return false
     */
    function isFried($modules) {
        $max = strlen($modules); // Count the amount of items
        
        
        
        // Loop through every generator
        for ($g = 1; $g < $max; $g += 2) {
            // Loop through every chip
            for ($m = 2; $m < $max; $m += 2) {
                // If current generator and chip match, skip to the next chip
                if ($g + 1 === $m) {
                    continue;
                }

                
                
                // If current generator is on the same floor as the current microchip
                if ($modules[$g] === $modules[$m]) {
                    // If the current microchip is not on the same floor as its protective generator
                    if ($modules[$m - 1] !== $modules[$m]) {
                        return true; // A chip has been fried
                    }
                }
            }
        }


        
        // If we reach this point, no chip has been found fried
        return false;
    }
    
    
    /**
     * @param $modules string Holds all the items, including the elevator
     * @param $topFloor int The top floor
     * @return bool If all the items are on the top floor, return true. Else return false
     */
    function isDone($modules, $topFloor) {
        // Is the amount of numbers representing the top floor is equal to the total amount of characters,
        // we have all items on the top floor.
        return substr_count($modules, strval($topFloor)) === strlen($modules);
    }
    
    
    /**
     * @param $str string Holds all the items
     * @return string Convert the input string into another string holding information of the amount of generators
     *                  and microchips on each floor
     */
    function historify($str) {
        $max = strlen($str); // The amount of item
        // Initiate the storage-array
        $data = ["elevator" => $str[0], "1" => [0, 0], "2" => [0, 0], "3" => [0, 0], "4" => [0, 0]];
        
        
        
        // Loop through each generator and store its floor number
        for ($g = 1; $g < $max; $g += 2) {
            $data[$str[$g]][0]++;
        }
        
        // Loop through each microchip and store its floor number
        for ($m = 2; $m < $max; $m += 2) {
            $data[$str[$m]][1]++;
        }
        
        
        
        // Return the generator/microchip-data
        return "{$data["elevator"]}" .
                "-{$data["1"][0]}-{$data["1"][1]}" .
                "-{$data["2"][0]}-{$data["2"][1]}" .
                "-{$data["3"][0]}-{$data["3"][1]}" .
                "-{$data["4"][0]}-{$data["4"][1]}";
    }
    
    
    /**
     * @param $input array The user-input
     * @return string Returns the input converted into a floor-representation of each item where position zero is the elevator,
     *                  all the odd positions are generators and the even (except 0) positions represents the microchips.
     *                  Matching generators and microchips are next to each other. For example,
     *                  the item on position 3 is a generator that matches with the microchip on position 4.
     */
    function input2string($input) {
        $input = array_map("trim", $input); // Remove unwanted characters from input
        $modules = []; // Stores all the items
        $result = "1"; // Insert location of elevator into our resulting string
        
        
        
        // Loop through each row in our input
        foreach ($input as $floor => $row) {
            // Find all generator-names
            preg_match_all("/([a-z]+) generator/", $row, $matches);
            // Loop through each found generator
            foreach ($matches[1] as $match) {
                $modules[$match][0] = $floor + 1; // Add generator name with its floor to the array
            }
            
            // Find all microchip-names
            preg_match_all("/([a-z]+)-compatible/", $row, $matches);
            // Loop through each found microchip
            foreach ($matches[1] as $match) {
                $modules[$match][1] = $floor + 1; // Add microchip name with its floor to the array
            }
        }
        
        
        
        // Loop through the resulting array and insert its data to the resulting string
        foreach ($modules as $floors) {
            $result .= implode("", $floors);
        }
        
        
        
        return $result;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
