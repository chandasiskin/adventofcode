<?php
    /**
     * https://adventofcode.com/2015/day/14
     *
     *
     *
     * This year is the Reindeer Olympics! Reindeer can fly at high speeds, but must rest occasionally to recover their energy.
     * Santa would like to know which of his reindeer is fastest, and so he has them race.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("14.txt")) { // If file is missing, terminate
        die("Missing file 14.txt");
    } else {
        $input = file("14.txt"); // Save file as an array
    }
    
    
    
    /**
     * In part 1 we calculate the furthest distance any reindeer can travel in 2503 seconds.
     * We create an array to store reindeer data (speed, duration, rest, etc.).
     * We need to keep track of whether the reindeer is flying or resting and for how long.
     * Then we keep looping one second at a time and move reindeer's accordingly.
     *
     *
     *
     * ** SPOILER **
     * During part 1, we also keep track of who is in the lead every second and reward a point to the leader.
     * If multiple leaders, they all get a point. The winner is the one with the most points.
     */
    function solve($input, $part2 = false) {
        $reindeers = []; // Holds reindeer data
        $distance = []; // Store how far every reindeer traveled
        $points = []; // Stores every reindeer's accumulated points
        $time = 2503; // This is how long the reindeers will race
        
        
        
        // Loop through all the input and collect all the reindeer-data
        foreach ($input as $row) {
            $tmp = explode(" ", $row); // Turn input string into an array
            $reindeers[$tmp[0]] = [
                "speed" => intval($tmp[3]), // How fast the reindeer travels
                "flyDuration" => intval($tmp[6]), // How long the reindeer flies
                "restDuration" => intval($tmp[13]), // How long does the reindeer rest
                "action" => ["action" => "fly", "duration" => intval($tmp[6])] // What is the reindeer currently doing and for how long?
            ];
            $distance[$tmp[0]] = 0; // The distance the reindeer have travelled
            $points[$tmp[0]] = 0; // Amount of points the reindeer have accumulated
        }
        
        
        
        // Loop through every second for 2503 seconds
        for ($t = 0; $t < $time; $t++) {
            // Loop through every reindeer and move them accordingly
            foreach ($reindeers as $name => &$reindeer) {
                $reindeer["action"]["duration"]--; // Decrease current action (fly/rest) duration
                
                // If reindeer is flying, increase distance travelled
                if ($reindeer["action"]["action"] === "fly") {
                    $distance[$name] += $reindeer["speed"];
                }
                
                // If reindeers current action-duration has expired, switch from flying to resting or vice versa.
                // Reset action-duration times as well.
                if ($reindeer["action"]["duration"] === 0) {
                    if ($reindeer["action"]["action"] === "fly") {
                        $reindeer["action"] = ["action" => "rest", "duration" => $reindeer["restDuration"]];
                    } else {
                        $reindeer["action"] = ["action" => "fly", "duration" => $reindeer["flyDuration"]];
                    }
                }
            } unset($reindeer);
            
            
            
            $maxDistance = max($distance); // Get the furthest any reindeer have travelled so far
            
            // Check all reindeers that have travelled said distance and give them a point
            foreach ($reindeers as $name => $a) {
                if ($distance[$name] === $maxDistance) {
                    $points[$name]++;
                }
            }
        }
        
        
        
        return [max($distance), max($points)];
    }
    
    
    
     // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and " . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true;
    //$start = microtime(true);
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
