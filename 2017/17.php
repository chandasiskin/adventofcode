<?php
    /**
     * https://adventofcode.com/2017/day/17
     *
     *
     *
     * Suddenly, whirling in the distance, you notice what looks like a massive,
     * pixelated hurricane: a deadly spinlock. This spinlock isn't just consuming computing power,
     * but memory, too; vast, digital mountains are being ripped from the ground and consumed by the vortex.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("17.txt")) { // If file is missing, terminate
        die("Missing file 17.txt");
    } else {
        $input = file_get_contents("17.txt"); // Save file as a string
    }
    
    
    
    /**
     * In this problem we have a circular list with one number (we start with zero) and for each step we add the next number to that list.
     * There are some rules though to determine at what position to add the new number. The rules are:
     * 1. From our current position, move <input> number of steps forward
     * 2. To stay in bounds of our list, we use modulo
     * 3. Insert the new number AFTER the position we land at
     * 4. For the next number, update current position to where we added recently added the new number
     * 
     * To achieve this, we create a new list with the number zero at position zero as the starting value.
     * We then loop 2017 times, where we first insert the next number into the correct position according to the rules above.
     * Every number after the current position is moved one position "to the right" to make room for the new number.
     * 
     * 
     * 
     * The solution for part 1 is the number that is one position to the right of the number 2017.
     * 
     * 
     * 
     * 
     * ** SPOILER **
     * For part 2, we need to find the number that is position right after the number zero.
     * Unfortunately we can't use the same method as for part 1, because we are inserting 50.000.000 numbers,
     * which just takes too long. But no worries, we have a trick up our sleeve!
     * When calculating where to put the next number, there are 3 different cases we need to look at:
     * 1. If we are inserting anywhere in the middle
     *      - 
     * 2. If we are inserting into the end
     *      - Lets say the list has 10 numbers, where the last number is at position 9.
     *        If our calculation says that we are about to insert a new number AFTER position 9,
     *        we are inserting the number to position 10, NOT position 0.
     * 3. If we are inserting into the beginning
     *      - If our calculation says that we are about to insert a new number AFTER position 0,
     *        we are inserting the number to position 1.
     * 
     * We can conclude this to: the number zero at position zero WILL NEVER BE MOVED!
     * To solve part 2, where we need to find the number that comes right after zero (at position 1),
     * we are only interested in the numbers that are stored on position 1.
     * To translate this into code, we are never inserting any numbers into our circular list.
     * We increment the size of the list each time we are about to insert a new number,
     * but unless the new number is inserted at position 1, we are not interested in it.
     * If the new number IS to be placed at position 1, we overwrite any existing numbers,
     * since the previous number at position 1 is technically moved to position 2.
     */
    function solve($input, $part2 = false) {
        $steps = intval(trim($input)); // Amount of steps to move before each insertion
        $list = [0]; // Our list, initiated with a value
        $size = count($list); // The size of our list and also the next number to insert
        $currentPosition = 0; // At what position we are starting
        $nextPosition = 0; // Holds the value of where to insert the next number
        $numbersToInsert = !$part2 ? 2017 : 50000000; // If we are doing part 1, insert 2017 is our last number.
                                                        // If we are doing part 2, 50.000.000 is our last number.
        $resultPart2 = null; // Holds the solution for part 2
        
        
        
        // Insert 2017/50.000.000 numbers, depending on if we are doing part 1 or part 2
        for ($i = 0; $i < $numbersToInsert; $i++) {
            $nextPosition = $currentPosition + $steps; // Update the new position
            $nextPosition %= $size; // Do some modulo to keep new position on bounds of our array
            $nextPosition++; // Insert the new number just to the right of the position we ended up with
            
            
            
            // If we are doing part 1
            if (!$part2) {
                array_splice($list, $nextPosition, 0, $size); // Insert new number into position
            }
            
            // If we are doing part 2
            else {
                // If the next number is about to be placed at position 1
                if ($nextPosition === 1) {
                    $resultPart2 = $size; // Update number at position 1
                }
            }
            
            
            
            $size++; // Increment the size of our list
            $currentPosition = $nextPosition; // Update our starting position for the next iteration
        }
        
        
        
        // If we are doing part 1, return the number just to the right of our last insert number (2017)
        // If we are doing part 2, return the number at position 1
        return !$part2 ? $list[array_search($size - 1, $list) + 1] : $resultPart2;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
