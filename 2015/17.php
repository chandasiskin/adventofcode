<?php
    /**
     * https://adventofcode.com/2015/day/17
     *
     *
     *
     * The elves bought too much eggnog again.
     * To fit it all into your refrigerator, you'll need to move it into smaller containers.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("17.txt")) { // If file is missing, terminate
        die("Missing file 17.txt");
    } else {
        $input = file("17.txt"); // Save file as an array
    }
    
    
    
    /**
     * For part 1 we need to try every single cup-combination to determine which adds up to exactly 150.
     * For this, we use a binary approach. We know that there is 20 cups, meaning there are a total of 2^20 (~1,000,000) combinations.
     * We start by trying the binary combination of 0 (0000 0000 0000 0000 0000) where each number represent a cup.
     * After we tried that combination, we move on to 1 (0000 0000 0000 0000 0001), and then to 2, (0000 0000 0000 0000 0010),
     * 3, 4, 5, all the way to 2^20. A zero means "we do not use that cup" and a one means "we use that cup".
     *
     *
     *
     * ** SPOILER **
     * In part 2 we need to calculate what the minimum cup amount is needed to reach our goal of 150 and in how many combinations we can do that.
     * To achieve this easily, we store every successful combination from part 1 in an array where the key works as cup amount and the value as
     * the amount of successful combinations with this many cups.
     */
    function solve($input) {
        $cups = array_map("intval", $input); // Convert all cup-values to integers
        $combinations = pow(2, count($cups)) - 1; // Calculate the amount of possible combinations.
                                                        // We need to do -1 because the amount of possible combinations
                                                        // isn't 2^20 but 2^20 - 1 because we start our process at 0 and not 1.
        
        
        
        $result = bfs($cups, $combinations); // Call our combination-testing function and store the result in an array
        
        
        
        $res1 = array_sum($result); // Solution for part 1 is to count all possible combinations
        $res2 = $result[min(array_keys($result))]; // Solution for part 2 is the amount of combinations with the least amount of cups
        return [$res1, $res2];
    }
    
    
    
    function bfs($cups, $combinations) {
        $result = []; // Store the results here
        $len = strlen(decbin($combinations)); // Get the length of the binary string when converting decimal combination-count to binary
        
        
        
        // Try every single combination from 0 (0000 0000 0000 0000 0000) to 2^20-1 (1111 1111 1111 1111 1111)
        for ($i = 0; $i <= $combinations; $i++) {
            $binary = sprintf("%0{$len}s", decbin($i)); // Turn the current combination number ($i) to a binary number and pad it with zeroes
            $sum = 0; // Sum of all used cups
            $cupCounter = 0; // Amount of cups used
            
            
            
            // Loop through the binary string
            for ($j = 0; $j < $len; $j++) {
                // If we hit a 1, we use a cup. Otherwise, just keep looking
                if ($binary[$j] === "1") {
                    $sum += $cups[$j]; // Add the cups size to the total
                    
                    // If we exceed our limit, check next combination
                    if ($sum > 150) {
                        continue 2;
                    }
                    
                    $cupCounter++; // Increment amount of cups used
                }
            }
            
            
            
            // If we reach our target size
            if ($sum === 150) {
                $result[$cupCounter] = $result[$cupCounter] ?? 0; // If current cup amount is not preset in array, set it to 0
                $result[$cupCounter]++; // Increment combination count for that particular cup amount
            }
        }
        
        
        
        return $result;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
