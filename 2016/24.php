<?php
    /**
     * https://adventofcode.com/2016/day/24
     *
     *
     *
     * You've finally met your match; the doors that provide access to the roof are locked tight,
     * and all of the controls and related electronics are inaccessible. You simply can't reach them.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("24.txt")) { // If file is missing, terminate
        die("Missing file 24.txt");
    } else {
        $input = file("24.txt"); // Save file as an array
    }
    
    
    
    /**
     * This task has two very popular problems that needs to be solved: the first one is "find the shortest path from A to B"
     * and the second one is the "Travelling salesman problem" (https://en.wikipedia.org/wiki/Travelling_salesman_problem).
     * But one thing at a time. First, we create an array with all the different points we need to visit. This is done
     * by simply looping through the entire map and check for non-paths and non-walls.
     * When all the points have been established, next thing to do is to count the distance between them.
     * This is where the first problem is solved: "find the shortest path from A to B".
     * We use a simple BFS (https://en.wikipedia.org/wiki/Breadth-first_search) through the entire map starting at node 0
     * and every time we hit a node, we save that node and the step-count up to it. When the whole map have been searched,
     * we move on to the next node, 1, and re-do everything. And we keep doing this until we have used all nodes as a starting point.
     * When this is done, we have collect all the necessary data we need to solve the next problem: "Travelling salesman problem".
     * With the information we got from the BFS-part, we have the step count from any node to any other node.
     * Since the node-count is rather small, the amount of possible node-combinations is rather small (7! for my input),
     * meaning we can simply brute-force it. So, we create a list with all combinations, get the total step count for all these
     * and store the shortest one.
     *
     *
     *
     * ** SPOILER **
     * Part 2 just adds that we need to return back to node 0. This changes nothing, except when calculating total step-count
     * for each combination, we just add the step-count from our last node back to 0.
     */
    function solve($input, $part2 = false) {
        $map = array_map("trim", $input); // Remove unwanted characters from the input and store the result in a variable
        $width = strlen($map[0]); // Get the width of the map
        $points = []; // Holds all the different points
        $pointToPoint = []; // Holds data about the distance between all the points
        $allCombinations = []; // Holds all the different node-combinations
        $lowestStepCount = PHP_INT_MAX; // Holds the best total step-count
        
        
        
        // Loop through each position on the map and look for non-paths (.) and non-walls (#)
        foreach ($map as $y => $row) {
            for ($x = 0; $x < $width; $x++) {
                if ($row[$x] !== "." && $row[$x] !== "#") {
                    $points[$row[$x]] = [$x, $y]; // Store the coordinate of the node
                }
            }
        }
        
        
        
        $highestPoint = max(array_keys($points)); // Get the value of the highest note
        
        // Loop through every node and get the distance from that node to every other node
        for ($i = 0; $i <= $highestPoint; $i++) {
            $pointToPoint[$i] = BFS($points[$i], $map); // Call function BFS that calculates shortest distance to all points
        }
        
        
        
        // Create a list with keys starting from node 1 and ending with the highest node
        // NOTE: Because we ALWAYS start at node 0, we do not include 0 in the combinations
        $allCombinations = array_fill(1, $highestPoint, 0);
        // Create a new array from the keys
        $allCombinations = array_keys($allCombinations);
        // Call function combinations with the key-array to generate all the possible combinations
        $allCombinations = combinations($allCombinations);
        
        
        
        // Loop through all the combinations and calculate total distance
        foreach ($allCombinations as $combo) {
            $comboArray = str_split($combo); // Convert string of numbers into an array
            // If we are doing part 2, add node 0 to the end of the array
            if ($part2) {
                $comboArray[] = "0";
            }
            $previousNode = "0"; // Starting node
            $stepCount = 0; // Total step-count
            
            
            
            // Loop through each node and get distance from <previous node> to <current node>
            foreach ($comboArray as $node) {
                $stepCount += $pointToPoint[$previousNode][$node]; // Add step-count between previous and current node
                $previousNode = $node; // Set current node as previous node
            }
            
            
            
            // Update best ste-count
            $lowestStepCount = min($lowestStepCount, $stepCount);
        }
        
        
        
        return $lowestStepCount;
    }
    
    
    /**
     * @param $start array Coordinates of the starting node
     * @param $map array The map
     * @return array Returns the distance to all nodes
     */
    function BFS($start, $map) {
        $query = [$start]; // Insert starting positions into query
        $visited = [$start[1] => [$start[0] => 1]]; // Set starting point as "already visited"
        $steps = 0; // Holds the current step-count
        $result = []; // Stores the distances to all nodes
        
        
        
        // Loop until query is empty (a.k.a. the entire map has been exhausted)
        do {
            $tmp = []; // Stores the query for next iteration
            
            
            
            // Loop through all queries
            foreach ($query as $q) {
                list($x, $y) = $q; // Store the x- and y-coordinate for easier and more readable access
                
                
                
                // Have we found another node?
                if ($map[$y][$x] !== "." && $map[$y][$x] !== "#") {
                    $result[$map[$y][$x]] = $steps; // Store the node and the step-count to it
                }
                
                
                
                // Can we move up?
                if (!isset($visited[$y - 1][$x]) // Have we been here before?
                    && isset($map[$y - 1][$x]) // Is it in bounds?
                    && $map[$y - 1][$x] !== "#") { // Is it not a wall?
                    $tmp[] = [$x, $y - 1]; // Store the move for the upcoming query
                    $visited[$y - 1][$x] = 1; // Set current coordinate as visited
                }
                
                // Down
                if (!isset($visited[$y + 1][$x]) // Have we been here before?
                    && isset($map[$y + 1][$x]) // Is it in bounds?
                    && $map[$y + 1][$x] !== "#") { // Is it not a wall?
                    $tmp[] = [$x, $y + 1]; // Store the move for the upcoming query
                    $visited[$y + 1][$x] = 1; // Set current coordinate as visited
                }
                
                // Left
                if (!isset($visited[$y][$x - 1]) // Have we been here before?
                    && isset($map[$y][$x - 1]) // Is it in bounds?
                    && $map[$y][$x - 1] !== "#") { // Is it not a wall?
                    $tmp[] = [$x - 1, $y]; // Store the move for the upcoming query
                    $visited[$y][$x - 1] = 1; // Set current coordinate as visited
                }
                
                // Right
                if (!isset($visited[$y][$x + 1]) // Have we been here before?
                    && isset($map[$y][$x + 1]) // Is it in bounds?
                    && $map[$y][$x + 1] !== "#") { // Is it not a wall?
                    $tmp[] = [$x + 1, $y]; // Store the move for the upcoming query
                    $visited[$y][$x + 1] = 1; // Set current coordinate as visited
                }
            }
            
            
            
            $steps++; // Increment step-count
            $query = $tmp; // Overwrite current query with the next one
        } while ($query != []);
        
        
        
        return $result;
    }
    
    
    /**
     * @param $left array Possible numbers left to combine
     * @return array|mixed Returns all the possible combinations
     */
    function combinations($left) {
        $result = []; // Holds the results
        
        
        
        // If no more numbers to try out
        if (count($left) <= 1) {
            return $left;
        }
        
        // If there are still possible combinations
        else {
            $max = count($left); // The amount of numbers left to combine
            
            // Loop through all the remaining numbers
            for ($i = 0; $i < $max; $i++) {
                $element = $left[$i]; // Get the current number
                $tmp = []; // Create a new, temporary array without the current number
                
                
                
                // Move all numbers, except the current one, to the new, temporary array
                for ($j = 0; $j < $max; $j++) {
                    if ($i !== $j) {
                        $tmp[] = $left[$j];
                    }
                }
                
                
                
                $combos = combinations($tmp); // Call current function with the reduced array
                
                
                
                $maxCombos = count($combos); // Get combo-count
                
                // Loop through all combinations and store them in the result-array
                for ($j = 0; $j < $maxCombos; $j++) {
                    $result[] = $element . $combos[$j];
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
