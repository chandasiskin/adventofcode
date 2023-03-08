<?php
    /**
     * https://adventofcode.com/2015/day/20
     *
     *
     *
     * To keep the Elves busy, Santa has them deliver some presents by hand, door-to-door.
     * He sends them down a street with infinite houses.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("20.txt")) { // If file is missing, terminate
        die("Missing file 20.txt");
    } else {
        $input = file_get_contents("20.txt"); // Save file as a string
    }
    
    
    
    /**
     * Credits goes to https://www.markheath.net/post/advent-of-code-day20 for the optimized code!
     *
     *
     *
     * There are infinite many houses. An infinite many elves deliver presents to these houses according to this:
     * - Elf 1 delivers to every house
     * - Elf 2 delivers to every other house
     * - Elf 3 delivers to every third house
     * - Elf n delivers to every n:th house
     * - Every elf delivers <elf-number> * 10 present to each house.
     * For part 1, we need to find out what is the lowest house-number that receives at least <input> many presents.
     *
     * We just brute force it, but we do it in a clever way.
     * First of all, we only need to loop until the <input-number> / 10 house.
     * Second trick is to predefine the array holding all the houses. It speeds up the process when no having to create
     * new elements as we go along.
     * So, we loop through each elf from 1 to <input-number> / 10, and insert <elf-number> * 10 present to each house
     * from house 1 to <input-number> / 10. When done, we look through each house to find the first one to exceed our target.
     *
     *
     *
     * ** SPOILER **
     * Part 2 is the same as above. Except elves deliver <elf-number> * 11 presents and only to 50 houses.
     * So we need to keep track of the house-counter.
     */
    function solve($input, $part2 = false) {
        $target = $input; // Stores the present-target we are aiming for
        
        
        
        // Are we solving part 1?
        if (!$part2) {
            $size = ($target / 10) + 1; // Set the limit for loops and array-sizes
            $houses = array_fill(1, $size, 0); // Create the house array starting from house 1 all the way to house <target> / 10
                                                            // and set their present-counter to 0
            
            
            
            // Loop through each elf
            for ($elf = 1; $elf < $size; $elf++) {
                // Loop through each house
                for ($house = $elf; $house < $size; $house += $elf) {
                    $houses[$house] += $elf * 10; // Insert presents
                }
            }
    
            
            
            // Loop through each house looking for the first house to exceed our target
            for ($house = 1; $house < $size; $house++) {
                // Does current house exceed target?
                if ($houses[$house] >= $target) {
                    return $house;
                }
            }
        }
        
        // Solving part 2
        else {
            $size = floor($target / 11) + 1; // Set the limit for loops and array-sizes
            $houses = array_fill(1, $size, 0); // Create the house array starting from house 1 all the way to house <target> / 10
                                                            // and set their present-counter to 0
    
    
    
            // Loop through each elf
            for ($elf = 1; $elf < $size; $elf++) {
                // Loop through each house, keeping track of how many houses the elf has visited
                for ($house = $elf, $n = 0; $house < $size && $n < 50; $house += $elf, $n++) {
                    $houses[$house] += $elf * 11; // Insert presents
                }
            }
    
    
            // Loop through each house looking for the first house to exceed our target
            for ($house = 1; $house < $size; $house++) {
                // Does current house exceed target?
                if ($houses[$house] > $target) {
                    return $house;
                }
            }
        }
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true;
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
