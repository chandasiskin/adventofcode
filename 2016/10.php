<?php
    /**
     * https://adventofcode.com/2016/day/10
     *
     *
     *
     * You come upon a factory in which many robots are zooming around handing small microchips to each other.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("10.txt")) { // If file is missing, terminate
        die("Missing file 10.txt");
    } else {
        $input = file("10.txt"); // Save file as an array
    }
    
    
    
    /**
     * In this task, we have a bunch of bots that examine microchips. They do this only if they hold two microchips.
     * When examined, they pass on the microchips to two different bots.
     * To solve this, we first create an array for the bots. They array-key is the bots name and the value is a
     * second array holding information about where to pass the microchips and what microchips the bot is currently holding.
     * We keep looping through all the bots and look for bots that are holding two microchips. When a match is found,
     * move the two microchips to their correct destination and move forward on the bot-list.
     *
     * For part 1, we make a note when we find the bot that compares microchip 61 and 17.
     *
     *
     * ** SPOILER **
     * For part 2 we keep going until output 0, 1 and 2 have received a chip. Once that occurs,
     * we multiply the microchip-values together and end the process.
     */
    function solve($input, $part2 = false) {
        $bots = []; // Holds all the bots and their corresponding data
        $result = []; // Holds the end-result
        
        
        
        // Loop through each input-row
        foreach ($input as $row) {
            $bot = explode(" ", trim($row)); // Remove unwanted characters and split the row into an array at every space-character
            
            
            
            // If the row starts with the word "bot", we have information about a bot
            if ($bot[0] === "bot") {
                $bots["$bot[0] $bot[1]"]["low"] = "$bot[5] $bot[6]"; // Who does the bot pass the lower-valued microchip?
                $bots["$bot[0] $bot[1]"]["high"] = "$bot[10] $bot[11]"; // Who does the bot pass the higher-valued microchip?
            }
            
            // If the row does not start with the word "bot", we have a row with information about what bots starts with a microchip
            else {
                $bots["$bot[4] $bot[5]"]["chips"][] = $bot[1]; // Give bot a microchip
            }
        }
        
        
        
        // Start the endless loop
        while (true) {
            // Loop through each bot
            foreach ($bots as $id => &$bot) {
                // If we have a microchip in output 0, 1 and 2, we are done with our assignment
                if (isset($bots["output 0"], $bots["output 1"], $bots["output 2"])) {
                    $result[1] = // Multiply the microchips together
                        $bots["output 0"]["chips"][0] *
                        $bots["output 1"]["chips"][0] *
                        $bots["output 2"]["chips"][0];
                    
                    
                    
                    return $result;
                }
                
                
                
                // If current bot has 2 chips, redistribute them accordingly
                if (isset($bot["chips"]) && count($bot["chips"]) === 2) {
                    // If current bot is comparing microchi 61 and 17, we have solved part 1
                    if (($bot["chips"][0] === "61" && $bot["chips"][1] === "17")
                        || ($bot["chips"][0] === "17" && $bot["chips"][1] === "61")) {
                        $result[0] = substr($id, 4); // Removes the word "bot" and just returns the bot-id
                    }
                    
                    
                    
                    $bots[$bot["low"]]["chips"][] = min($bot["chips"]); // Move the lower-valued microchip
                    $bots[$bot["high"]]["chips"][] = max($bot["chips"]); // Move the higher-valued microchip
                    
                    unset($bot["chips"]); // Remove all microchips from current bot
                }
            } unset($bot);
        }
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
