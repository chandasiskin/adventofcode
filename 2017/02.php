<?php
    /**
     * https://adventofcode.com/2017/day/2
     *
     *
     *
     * As you walk through the door, a glowing humanoid shape yells in your direction.
     * "You there! Your state appears to be idle. Come help us repair the corruption in this spreadsheet - if we take another millisecond,
     * we'll have to display an hourglass cursor!"
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
     * We get some rows with numbers on it, numbers separated by tabs.
     * For part 1 we need to calculate the difference between the highest and lowest value and add the difference to the total.
     * We do this by simply splitting each row at the tab-character and getting the highest and lowest value in the resulting array.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we split each row the same way as in part 1. But in this part, we are looking for the only two numbers that divide evenly.
     * To find these, we loop through each number, check if dividing A by B has a remainder or if dividing B by A has a remainder.
     * If there is no remainder, we have found our pair. Divide these and add the result to the total.
     */
    function solve($input, $part2 = false) {
        $input = array_map("trim", $input); // Remove unwanted characters from each input-row
        $result = 0; // Holds the total sum
        
        
        
        // Loop through each row in the input
        foreach ($input as $row) {
            $numbers = preg_split("/\s+/", $row); // Split each row at the tab-character
            
            // If we are doing part 1
            if (!$part2) {
                $result += max($numbers) - min($numbers); // Gets the highest and lowest value and add the difference to the total
            }
            
            // If we are doing part 2
            else {
                $max = count($numbers); // Amount of numbers to loop through
                
                // Loop through every number except the last one
                for ($i = 0; $i < $max - 1; $i++) {
                    // Loop through all numbers starting from next number after the current number
                    for ($j = $i + 1; $j < $max; $j++) {
                        // Is A evenly divisible with B?
                        if ($numbers[$i] % $numbers[$j] === 0) {
                            $result += $numbers[$i] / $numbers[$j]; // Divide A with B and add the result to the total
                        }
                        
                        // Is B evenly divisible with A?
                        elseif ($numbers[$j] % $numbers[$i] === 0) {
                            $result += $numbers[$j] / $numbers[$i]; // Divide B with A and add the result to the total
                        }
                    }
                }
            }
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
