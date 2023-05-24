<?php
    /**
     * https://adventofcode.com/2017/day/16
     *
     *
     *
     * You come upon a very unusual sight; a group of programs here appear to be dancing.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("16.txt")) { // If the file is missing, terminate
        die("Missing file 16.txt");
    } else {
        $input = file_get_contents("16.txt"); // Save file as a string
    }
    
    
    
    /**
     * In our input, we have a bunch of instructions. These instructions break down to three types:
     * 1. Shift everything to the right X number of steps
     * 2. Switch two letters based on their position
     * 3. Switch two letters based on their value
     * We start our list with 16 letters, A to P, in ascending order and one instruction at a time we operate on our list.
     *
     * Once all moves have been made, we have our solution for part 1.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we are to repeat all our moves a billion times (1.000.000.000). Well, that is not going to happen.
     * Instead, we are going to keep repeating part 1 and store every solution. When we get a solution that we already ran upon,
     * we stop. At this point, we have all the different solutions we can get.
     * Let's say we have 30 different solutions, solutions 1 to 30. When we run our moves on solution 30, we end back to solution 1.
     * This means we need to figure out which of these 30 solutions is the billionth solution.
     * By dividing one billion with the number of solutions and keeping the remainder (modulo), we know the last time the first solution
     * appears while we are still being below one billion. Let's say that the last time solution 1 appears below one billion is at
     * 999.999.998 (one billion minus one). This means that at loop-number 999.999.999 the solution is number 2, and finally, after
     * one billion loops we get that the correct solution is 3.
     */
    function solve($input, $part2 = false) {
        $moves = explode(",", trim($input)); // Remove unwanted characters from our input and convert it into an array
        $programs = []; // Holds all the different programs (from A to P)
        $solutions = []; // Stores all the different solutions
        $solutionCounter = 1; // Keeps track of the number of solutions we get (note that indexing starts at 1)
        $firstLetter = ord("a"); // This is our first letter converted into an integer
        $lastLetter = $firstLetter + 16; // This is the number of letters we are to add
        $result = ""; // Holds the current solution
        
        
        
        // Add all the 16 letters into our array
        for ($i = $firstLetter; $i < $lastLetter; $i++) {
            $programs[] = chr($i); // Convert the number into it's ascii-corresponding letter
        }
        
        
        
        // Keep looping until we find a solution that has already been discovered
        do {
            // Loop through every move from our input
            foreach ($moves as $move) {
                $m1 = $move[0]; // Get type of move
                $m2 = explode("/", substr($move, 1)); // Get how many moves or what to move
                
                
                
                // Determine what type of move to make
                switch ($m1) {
                    // If we are spinning (shifting to the right)
                    case "s":
                        spin($programs, intval($m2[0])); // Call spinning function
                        break;
                        
                    // If we are exchanging (shift two letters based on their positions)
                    case "x":
                        exchange($programs, intval($m2[0]), intval($m2[1])); // Call exchanging function
                        break;
                        
                    // If we are partnering (shift two letters)
                    case "p":
                        partner($programs, $m2[0], $m2[1]); // Call partnering function
                        break;
                    
                    // If an invalid move occurred
                    default:
                        die("Invalid option: $m1");
                }
            }
            
            
            
            ksort($programs); // Sort the solution by position
            $result = implode("", $programs); // Convert solution into a string
            
            
            
            // If this solution has occurred before, stop looking for new solutions
            if (isset($solutions[$result])) {
                break; // Jump out of the loop
            }
            
            
            
            $solutions[$result] = $solutionCounter++; // Store the current solution together with the solution-counter
        } while (true);
        
        
        
        $solutions = array_flip($solutions); // Flip keys with values, so the keys are the solution number and the values are the solution itself
        
        
        
        // For part 1, return the first solution
        // For part 2, calculate what solution is at iteration number "one billion"
        return [$solutions[1], $solutions[1000000000 % count($solutions)]];
    }
    
    
    /**
     * @param $arr char[] The array with all the letters
     * @param $n int The number of steps to move everything to the right
     * @return void
     */
    function spin(&$arr, $n) {
        $size = count($arr); // Get the size of the array
        $tmp = array_flip($arr); // Flip values with keys to make it simpler to increment the position
        
        
        
        // Loop through every letter
        foreach ($tmp as &$v) {
            $v += $n; // Increase the current position with the number of steps gotten from our parameter
            $v %= $size; // Modulo to keep position within bounds
        } unset($v);
        
        
        
        $arr = array_flip($tmp); // Flip the updated array back to its original format
    }
    
    
    /**
     * @param $arr char[] The array with all the letters
     * @param $a char The positions of the first letter to swap
     * @param $b char The position of the second letter to swap
     * @return void
     */
    function exchange(&$arr, $a, $b) {
        $tmp = $arr[$a]; // Copy the first letter into a temp variable
        $arr[$a] = $arr[$b]; // Update the first letter with the second letter
        $arr[$b] = $tmp; // Update the second letter with the letter from the temp variable
    }
    
    
    /**
     * @param $arr char[] The array with all the letters
     * @param $a char The first letter to swap
     * @param $b char The second letter to swap
     * @return void
     */
    function partner(&$arr, $a, $b) {
        // Call the exchange function with the current array and the positions of the two letters passed to this function
        exchange($arr, array_search($a, $arr), array_search($b, $arr));
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
