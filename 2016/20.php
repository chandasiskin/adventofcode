<?php
    /**
     * https://adventofcode.com/2016/day/20
     *
     *
     *
     * You'd like to set up a small hidden computer here so you can use it to get back into the network later.
     * However, the corporate firewall only allows communication with certain external IP addresses.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("20.txt")) { // If file is missing, terminate
        die("Missing file 20.txt");
    } else {
        $input = file("20.txt"); // Save file as an array
    }
    
    
    
    /**
     * So, we have a bunch of ranges in a random order, and we need to sort them out. The ranges vary between 0 and 4294967295
     * For part 1, we need to find the lowest number that is not within any range.
     * Our goal is to loop through an array that is sorted from the lowest range to the highest (where "lowest" means the lowest lower bound),
     * and populate our final array with the smallest amount of ranges. For example, if we have two ranges,
     * one going 1-6 and another going 2-4, we can neglect the 2-4, because 1-6 range covers that.
     * We do also want to merge ranges when possible. For example, if we have ranges 1-5 and 3-7, we want to merge these to 1-7.
     *
     * But first, we are going to create a new, sorted array from our input.
     * The new array will hold the lower bound on index 0 and the upper bound on index 1.
     * The array will also be sorted by the lower bound, ascending. Then we loop through this array,
     * checking if the lower bound is greater than the max bound in the current, final range. If it's not,
     * extend the upper bound of the current range. If IT IS larger, our current, final range is completed,
     * and we need to start building our next range.
     *
     * Once all rows in our sorted array have been read, all of our final ranges have been built.
     * The answer to part 1 is the number between the first and the second range.
     *
     *
     *
     * ** SPOILER **
     * By building our final array in the way we did on part 1, all we need to do for part 2 is to count the amount
     * numbers between all ranges, that is: amount of ranges - 1.
     */
    function solve($input, $part2 = false) {
        $blacklist = []; // Holds our sorted ranges
        $ranges = [[0, 0]]; // Holds our final range-array
        
        
        
        // Loop through each row in our input
        foreach ($input as $row) {
            // Separate string into an array at the dash-character (-) and force the results into an integer
            $blacklist[] = array_map("intval", explode("-", $row));
        }
        
        // Sort the list ascending by lower bound firstly, and upper bound secondly
        usort($blacklist, function($a, $b) {
            // If the two lower bounds differ, return the difference
            // (<0 means $a comes before $b, >0 means $b comes before $a)
            if ($a[0] !== $b[0]) {
                return $a[0] - $b[0];
            }
            
            // Else return the difference of the upper bounds
            // (<0 means $a comes before $b, =0 means it makes no difference, >0 means $b comes before $a)
            else {
                return $b[1] - $a[1];
            }
        });
        
        
        
        // Holds the key of the range we are currently building
        $key = 0;
        
        // Loop through our sorted list
        foreach ($blacklist as $arr) {
            // If current lower bound is greater than the upper bound of the range we are currently building,
            // we have ourselves a new range
            if ($arr[0] > $ranges[$key][1] + 1) {
                $key++; // Jump to next range
                $ranges[$key] = [$arr[0], $arr[0]]; // Initiate the new range
            }
    
            
            
            $ranges[$key][1] = max($ranges[$key][1], $arr[1]); // Update the upper bound if necessary
        }
        
        
        
        // Index 0 is the first number that is out of all ranges.
        // Index 1 is the amount of numbers outside all ranges, meaning any number(s) that are between all the ranges.
        return [$ranges[0][1] + 1, count($ranges) - 1];
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
