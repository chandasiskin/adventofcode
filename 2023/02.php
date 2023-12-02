<?php
    /**
     * https://adventofcode.com/2023/day/1
     *
     *
     *
     * You're launched high into the atmosphere!
     * The apex of your trajectory just barely reaches the surface of a large island floating in the sky.
     * You gently land in a fluffy pile of leaves. It's quite cold, but you don't see much snow.
     * An Elf runs over to greet you.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("02.txt")) { // If file is missing, terminate
        die("Missing file 02.txt");
    } else {
        $input = file("02.txt"); // Save file as an array
    }
    
    
    
    /**
     * Part 1 is to loop through each row, check every set (separated by a semicolon) and compare each color
     * to the maximum allowed. If any set has more cubes than allowed, that set is impossible.
     * If no set is impossible, that games' ID is added to the grand total.
     *
     *
     *
     * ** SPOILER **
     * For part 2, the task is to calculate what the minimum allowed of cubes is needed to make every single set
     * possible. This is recalculated for each game. Once a minimum-allowed combination is found, the product of each
     * cube is added to a grand total.
     */
    function solve($input, $part2 = false) {
        $sum_part1 = 0; // The grand total of all possible game IDs
        $sum_part2 = 0; // The grand total of all the minimum required cube-combinations
        // The max number of cubes allowed in a set
        $max_allowed_cubes = ["red" => 12, "green" => 13, "blue" => 14];
        $min_required_cubes = []; // The minimum number of cubes needed to make each game possible
        
        
        
        // Loop through every input-row
        foreach ($input as $row) {
            $errFlag = false; // If an impossible game occured, raise the flag
            // Set a default value for minimum-number of cubes required
            $min_required_cubes = ["red" => PHP_INT_MIN, "green" => PHP_INT_MIN, "blue" => PHP_INT_MIN];
            
            // Explode current row to obtain the game ID and all the sets
            list(, $id, $sets) = preg_split("/Game |: /", $row);
            
            // Explode all the sets to obtain data about one individual set
            foreach (explode("; ", $sets) as $set) {
                $splitted = preg_split("/, | /", $set);
                
                // Loop through each color, where the color is a every odd position
                for ($i = 1, $max = count($splitted); $i < $max; $i += 2) {
                    // Remove unwanted spaces
                    $splitted[$i] = trim($splitted[$i]);
                    
                    // If the amount cubes lifted from the bag greater than allowed, raise the error-flag
                    if ($max_allowed_cubes[$splitted[$i]] < $splitted[$i - 1]) {
                        $errFlag = true;
                    }
                    
                    // Update current minimum-number of cubes required to make this game possible.
                    $min_required_cubes[$splitted[$i]] = max($min_required_cubes[$splitted[$i]], $splitted[$i - 1]);
                }
            }
            
            
            
            // If no error-flag was raised, add the games' ID to the grand total
            if (!$errFlag) {
                $sum_part1 += $id;
            }
            
            // Multiply the minimum-number of required cubs and add the sum to the grand total
            $sum_part2 += $min_required_cubes["red"] * $min_required_cubes["green"] * $min_required_cubes["blue"];
        }
        
        
        
        return [$sum_part1, $sum_part2];
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and<br />";
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";