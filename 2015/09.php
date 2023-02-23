<?php
    /**
     * https://adventofcode.com/2015/day/9
     *
     *
     *
     * Every year, Santa manages to deliver all of his presents in a single night.
     * This year, however, he has some new locations to visit; his elves have provided him the distances between every pair of locations.
     * He must visit each location exactly once.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("09.txt")) { // If file is missing, terminate
        die("Missing file 09.txt");
    } else {
        $input = file("09.txt"); // Save file as a string
    }
    
    
    
    /**
     * The problem in part 1 is easy: what's the shortest distance Santa can travel when visiting all locations exactly once?
     * This is also known as the TSP = Travelling Salesman Problem.
     * First, we define a global variable to store the current minimum distance. Whenever we have visited all locations,
     * we compare the current distance with the current global minimum distance. If the new distance is shorter, overwrite the old one.
     * With the help from the input, we create a 2D-array of [<location 1>][<location 2>] = <distance between them>,
     * so we can easily access distances between places.
     * We also create an array with all the stops.
     * For each stop, we call our DFS-function which calls itself with a new stop and current step count until stops run out.
     * When this happens, we check the step counter and (if better) we store it in the global variable.
     * When calling the DFS-function, it's important to remember to remove the next target and reduce the stop-list,
     * otherwise it will just keep feeding Santa new targets to visit.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we do exactly the same as part 1. The only difference is that we want the LONGEST distance.
     */
    $minDistance = PHP_INT_MAX; // Global variable holding shortest distance
    $maxDistance = PHP_INT_MIN; // Global variable holding longest distance
    
    
    
    function solve($input) {
        $distanceMap = []; // Holding distance-information between locations
        $stops = []; // Holding all the different stops
        
        
        
        // Create the distance-map
        foreach ($input as $row) {
            $tmp = explode(" ", $row); // Split string into an array at space-characters
            $distanceMap[$tmp[0]][$tmp[2]] = $tmp[4]; // Set distance from a to b
            $distanceMap[$tmp[2]][$tmp[0]] = $tmp[4]; // Set distance from b to a
            $stops[$tmp[0]] = 1; // Add location to stop-list
            $stops[$tmp[2]] = 1; // Add location to stop-list
        }
        
        
        
        // Loop through all stops, setting them as starting location one at a time and call our DFS-function with the data
        foreach ($stops as $stop => $a) {
            $reducedStops = $stops; // Create a copy of the current stop-list
            unset($reducedStops[$stop]); // Remove the current stop from the copied stop-list
            
            dfs($stop, $reducedStops, $distanceMap, "$stop");
        }
    }
    
    
    
    function dfs($pos, $stops, $distanceMap, $path, $stepCount = 0) {
        // If we ran out of stops, check if current step count is better than the current best
        if ($stops == []) {
            global $minDistance,
                   $maxDistance;
            
            
            
            $minDistance = min($minDistance, $stepCount);
            $maxDistance = max($maxDistance, $stepCount);
            
            
            
            return;
        }
        
        
        
        // Loop through all remaining stops and set each stop as the next one, one at a time.
        foreach ($stops as $stop => $a) {
            $reducedStops = $stops; // Create a copy of the current stop-list
            unset($reducedStops[$stop]); // Remove current stop from the copied stop-list
            
            dfs($stop, $reducedStops, $distanceMap, "$path-$stop", $stepCount + $distanceMap[$pos][$stop]);
        }
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    solve($input);
    echo "Part 1: " . $minDistance . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $maxDistance . " (solved in " . (microtime(true) - $start) . " seconds)";
