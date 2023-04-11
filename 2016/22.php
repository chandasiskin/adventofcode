<?php
    /**
     * https://adventofcode.com/2016/day/22
     *
     *
     *
     * You gain access to a massive storage cluster arranged in a grid;
     * each storage node is only connected to the four nodes directly adjacent to it (three if the node is on an edge,
     * two if it's in a corner).
     *
     * You can directly access data only on node /dev/grid/node-x0-y0, but you can perform some limited actions on the other nodes
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("22.txt")) { // If file is missing, terminate
        die("Missing file 22.txt");
    } else {
        $input = file("22.txt"); // Save file as an array
    }
    
    
    
    /**
     * I found the task for part 1 a little confusing at first, but every line in the input (except the first two) is a node with:
     * - Coordinates (x and y)
     * - Total size
     * - Used size
     * - Remaining size
     * - Used size in %
     *
     * The task is to compare every node to all the other nodes, check that the first node is not empty,
     * the first and second nodes are not the same node and that the first note fits in the second node
     * (the first nodes USED is smaller than the second nodes AVAILABLE).
     *
     * HINT: If you control your input, you can find that most of the nodes have a SIZE around 90T and
     * a USE% of around 75%. Let's call these REGULAR nodes. The two exceptions are a few nodes that have a SIZE of ~500T and a USE% of 95%
     * (which we can call LARGE nodes) and the one node that has a USE% of 0 (an EMPTY node).
     *
     * Basically, to solve part 1, we need to calculate how many nodes would fit in the empty node.
     *
     *
     *
     * ** SPOILER **
     * Part 2 is basically the famous children puzzle game: sliding puzzle (https://en.wikipedia.org/wiki/Sliding_puzzle).
     *
     * HINT: If you do as in the example, and print out the grid with REGULAR nodes as ".", LARGE nodes as "#" and
     * the empty node as "_", it gets much easier to understand.
     *
     * To make things very easy, we translate our nodes into a 3D-array: $array[<y-coordinate>][<x-coordinate>][<some info about the node>].
     * We then calculate the shortest distance from our empty node to the node with the data we want (at the top-right-corner) with BFS.
     * After that we calculate the shortest distance from that position to the node where we can retrieve the data (which is top-left-corner)
     * the same way as earlier (BFS).
     * Why we need to do this, and not just take the most straight forward route, is because the LARGE nodes, "#", acts as impassable walls,
     * so we need to find a path around these.
     *
     * NOTE 1: Once we reach the top-right-corner, we actually move the data to the neighbouring node,
     * so the distance left to move is actually one less.
     *
     * NOTE 2: Once we have moved the data from one node to another, we need to circulate around the current data-node
     * to be able to move it to the next node. Like this (where "D" is the data-node and "_" is the empty node):
     *
     * . . D _        . . D .        . . D .         . . D .        . _ D .      . D _ .
     * . . . .   =>   . . . _   =>   . . _ .   =>    . _ . .   =>   . . . .   => . . . .
     * . . . .        . . . .        . . . .         . . . .        . . . .      . . . .
     *
     * To be able to move the data-node one step we need to move the empty node 5 steps.
     *
     * In conclusion, to find the total amount of moves we need to:
     * 1. Count the amount of steps to move the empty node from its starting positions to the top-right-corner.
     * 2. Count the amount of steps to move the data-node from the node just left of the top-right-corner all the way to top-left-corner.
     * 3. One step of the data-node in "2." is worth 5 steps in the total count.
     *
     * Total step count is =<distance from start to top-right-corner> + <5 * (1 less than the distance from top-right-corner to top-left-corner)>
     */
    function solve($input, $part2 = false) {
        $grid = []; // Holds all the nodes and their information
        $emptyStart = []; // Where the empty node starts
        $dataNode = []; // Where the data-node is located
        $viablePairs = 0; // Holds teh amount of valid pairs for part 1
        $steps = 0; // How many steps it takes to solve part 2
        
        

        // Loop through each node
        foreach ($input as $row) {
            // If the current row starts with a "/", we are at a row with node-information
            if ($row[0] === "/") {
                $viablePairs++; // Treat this node as a viable pair until other is proven
                // From the node, retrieve:
                // - X- and Y-coordinates
                // - Data about total size
                // - Data about used size
                // - Data about used size in percentage
                preg_match("/^\/dev\/grid\/node-x(\d+)-y(\d+)\s+(\d+)T\s+(\d+)T\s+\d+T\s+(\d+)%$/", trim($row), $matches);
                // Force all values to integers and store them in separate variables
                list(, $x, $y, $size, $used, $usedPercentage) = array_map("intval", $matches);
                $symbol = "."; // Set current node-symbol to "REGULAR", by default

                // If the USAGE % is more than 90%, we have us a LARGE node
                if ($usedPercentage > 90) {
                    $symbol = "#"; // Update symbol to "LARGE"
                    $viablePairs--; // The node is no longer considered a viable pair
                }

                // If the USAGE % equals 0, we have found our empty node
                elseif ($usedPercentage === 0) {
                    $symbol = "_"; // Update symbol to "EMPTY"
                    $emptyStart = [$x, $y]; // Set starting point of the empty node
                    $viablePairs--; // The node is no longer considered a viable pair
                }
                
                
                
                // Shore the nodes SYMBOL, SIZE, USED and USED % in an array where the first key works as the y-coordinate
                // and the second key works as the x-coordinate
                $grid[$y][$x] = ["symbol" => $symbol, "size" => $size, "used" => $used, "usedPercentage" => $usedPercentage];
            }
        }



        $dataNode = [max(array_keys($grid[0])), 0]; // Set location of the data-node (top-right-corner)

        // Calculate the distance from the empty node to the top-right-corner
        $steps += BFS($emptyStart, $dataNode, $grid);
        // Calculate the distance between the top-left-corner and the point just left of the top-right-corner
        $steps += 5 * BFS([$dataNode[0] - 1, $dataNode[1]], [0,0], $grid);
        
        
        
        return [$viablePairs, $steps];
    }


    /**
     * @param $start array The starting coordinates [X, Y]
     * @param $goal array The coordinates to look for [X, Y]
     * @param $map array The map to search through
     * @return int|void If goal reached, return step count. Otherwise, kill with an error message
    */
    function BFS($start, $goal, $map) {
        $query = [0 => [$start[0], $start[1]]]; // Insert starting point into the query-array
        $history = [$start[1] => [$start[0] => 1]]; // Insert starting point as "already visited"
        $steps = 0; // Holds total step-count
        
        

        // Keep looping until query-array is empty
        do {
            $tmp = []; // Holds the query for the next round
            
            

            // Loop through the current query
            foreach ($query as $q) {
                // If we have reached our goal, end search and return step-count
                if ($q == $goal) {
                    return $steps;
                }
                
                
                
                // Move up?
                if (!isset($history[$q[1] - 1][$q[0]]) // If this coordinate is unvisited
                    && isset($map[$q[1] - 1]) // If it's in bounds
                    && $map[$q[1] - 1][$q[0]]["symbol"] !== "#") { // If it's not an impassable wall
                    $tmp[] = [$q[0], $q[1] - 1]; // Insert into next rounds query
                    $history[$q[1] - 1][$q[0]] = 1; // Set coordinate to "already visited"
                }
                
                
                
                // Move down?
                if (!isset($history[$q[1] + 1][$q[0]]) // If this coordinate is unvisited
                    && isset($map[$q[1] + 1]) // If it's in bounds
                    && $map[$q[1] + 1][$q[0]]["symbol"] !== "#") { // If it's not an impassable wall
                    $tmp[] = [$q[0], $q[1] + 1]; // Insert into next rounds query
                    $history[$q[1] + 1][$q[0]] = 1; // Set coordinate to "already visited"
                }
                
                
                
                // Move left?
                if (!isset($history[$q[1]][$q[0] - 1]) // If this coordinate is unvisited
                    && isset($map[$q[1]][$q[0] - 1]) // If it's in bounds
                    && $map[$q[1]][$q[0] - 1]["symbol"] !== "#") { // If it's not an impassable wall
                    $tmp[] = [$q[0] - 1, $q[1]]; // Insert into next rounds query
                    $history[$q[1]][$q[0] - 1] = 1; // Set coordinate to "already visited"
                }
                
                
                
                // Move right?
                if (!isset($history[$q[1]][$q[0] + 1]) // If this coordinate is unvisited
                    && isset($map[$q[1]][$q[0] + 1]) // If it's in bounds
                    && $map[$q[1]][$q[0] + 1]["symbol"] !== "#") { // If it's not an impassable wall
                    $tmp[] = [$q[0] + 1, $q[1]]; // Insert into next rounds query
                    $history[$q[1]][$q[0] + 1] = 1; // Set coordinate to "already visited"
                }
            }
            
            
            
            $steps++; // Increase step-counter
            $query = $tmp; // Update query-array
        } while ($query != []);
        
        
        
        die("No path found");
    }



    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
