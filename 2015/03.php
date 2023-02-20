<?php
    /**
     * https://adventofcode.com/2015/day/3
     *
     *
     *
     * Santa is delivering presents to houses. He moves one house at a time: up (^), down (v), left (<) or right (>).
     * He drops a present in the house he starts in, before he moves on. If he revisits a house, that house gets another present.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("03.txt")) { // If file is missing, terminate
        die("Missing file 03.txt");
    } else {
        $input = file_get_contents("03.txt"); // Save file as a string
    }
    
    
    
    /**
     * In the first part we need to calculate how many houses get at least one present.
     * We do hits by creating a 2D-map (array with x- and y-coordinates) in this format: $map[<y>][<x>] = <visited>
     * This way, we don't have to worry about duplicates. If $map[<y>][<x>] is not set, we set it, otherwise we just skip it.
     * It doesn't matter where Santa starts, but x = 0 and y = 0 seems natural.
     * It also has no impact if moving up increases or decreases the y-value or moving left increases or decreases the x-value.
     * You just need to be consistent.
     * In our case, up increases y-value and right increases x-value
     *
     *
     * ** SPOILER **
     * In part 2 we do the same thing as in part 1, except we get help from Robo-Santa.
     * Santa does the first move, and from there on Santa and Robo-Santa take turns moving.
     * We keep track of Santa's and Robo-Santa's position in the same array. Santa gets index 0 and Robo-Santa gets index 1.
     * To keep track of whose turn it is we do modulo on the current move index. If the result is even, it's Santa's turn, otherwise it's Robo-Santa's turn.
     */
    function solve($input, $part2 = false) {
        $moveCount = strlen($input); // Get move count
        $pos = [[0, 0], [0, 0]]; // We set Santa's and Robo-Santa's starting point as x = 0 and y = 0 [x, y]
        $map = [0 => [0 => 1]]; // Setting a gift in the first house (coordinates x = 0 & y = 0)
        $giftCount = 1; // Keep track of the amount of gifts Santa has given out (remember to give a gift to the starting house)
    
        
        
        // If we are doing part 2, we need to take Robo-Santa into account.
        if (!$part2) {
            $santaCount = 1;
        } else {
            $santaCount = 2;
        }
        
        
        
        for ($i = 0; $i < $moveCount; $i++) {
            $whoseTurn = $i % $santaCount; // Is it Santa's or Robo-Santa's turn?
            
            
            
            // Get next direction
            switch ($input[$i]) {
                case "^": // Up?
                    $pos[$whoseTurn][1]++; // Increase y-value
                    break;
    
                case "v": // Down?
                    $pos[$whoseTurn][1]--; // Decrease y-value
                    break;
    
                case "<": // Left?
                    $pos[$whoseTurn][0]--; // Decrease x-value
                    break;
    
                case ">": // Right?
                    $pos[$whoseTurn][0]++; // Increase x-value
                    break;
    
                default: // If we get an illegal direction
                    die("Illegal direction: $input[$i]");
            }
            
            
            
            // If there is no gift in the current house, put one in and increase gift counter
            if (!isset($map[$pos[$whoseTurn][1]][$pos[$whoseTurn][0]])) {
                $map[$pos[$whoseTurn][1]][$pos[$whoseTurn][0]] = 1;
                $giftCount++;
            }
        }
        
        
        
        return $giftCount;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
