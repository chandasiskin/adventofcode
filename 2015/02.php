<?php
    /**
     * https://adventofcode.com/2015/day/2
     *
     *
     *
     * The elves are wrapping presents and need to calculate how much material they need.
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
     * For part 1 the elves need to know how much wrapping-paper they need.
     * For each present they need 2*l*w + 2*w*h + 2*h*l (where l = length, w = width, h = height).
     * They also need a little extra, just in case.
     * The extra is the size of the smallest area.
     * To get the total amount of wrapping paper, we just loop thrugh each present,
     * calculate the paper needed for that particular present, and sum it into the total.
     *
     *
     *
     * ** SPOILER **
     * In part 2 we need to calculate how much ribbon the elves need.
     * The amount is 2*<smallest side> + 2*<second smallest side> + a bow (the bow is <volume of the present (l*w*h)>)
     */
    function solve($input) {
        $paperNeeded = 0;
        $ribbonNeeded = 0;
        
        
        
        // Loop through each present
        foreach ($input as $present) {
            list($l, $w, $h) = array_map("intval", explode("x", $present)); // Split current present data into length, width and height and turn the data into integers
            
            
            
            $paper = 2 * ($l * $w + $l * $h + $w * $h); // Count the amount of paper the elves need for the present
            $extraPaper = $l * $w * $h / max($l, $w, $h); // Count how much extra paper the elves need,
            // which can be calculated by multiplying all the sides divided with the largest side
            
            
            
            $ribbon = 2 * ($l + $w + $h - max($l, $w, $h)); // Ribbon length is 2 * <shortest side> + 2 * <second shortest side>
            $bow = $l * $w * $h;
            
            
            
            $paperNeeded += $paper + $extraPaper; // Add the amount of paper needed for current present into the total
            $ribbonNeeded += $ribbon + $bow;
        }
        
        
        
        return [$paperNeeded, $ribbonNeeded];
    }
    
    
    
    $res = solve($input); // Calculate both part 1 and 2 simultaneously
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: $res[0] and" . PHP_EOL;
    
    
    
    // Solve part 2
    $start = microtime(true);
    echo "Part 2: $res[1] (solved in " . (microtime(true) - $start) . " seconds)";
