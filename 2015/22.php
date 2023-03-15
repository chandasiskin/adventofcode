<?php
    /**
     * https://adventofcode.com/2015/day/22
     *
     *
     *
     * Little Henry Case decides that defeating bosses with swords and stuff is boring. Now he's playing the game with a wizard.
     * Of course, he gets stuck on another boss and needs your help again.
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
     * Here we are playing the same game as before, except we use a mage instead of a knight.
     * The new rules are:
     * - If we run of mana, we die
     * - We can not cast an effect if that effect is still active from a previous cast
     * - Dragon does at least 1 damage, regardless of players armor level
     * - The player starts each round
     * To solve this, we do a simple DFS (depth-first-search, a.k.a. brute force).
     * We store the current best score in a global variable and compare current run with it. If current round is more mana-expensive
     * than the best, no need to keep going.
     * Before each player attack, we check if any effects are active (shield/poison/recharge). If so, execute these and check if
     * they're still active till next round. If not, remove them from the effects-array.
     * After effects have been executed, check if boss died. If yes, exit round and store current mana-usage.
     *
     * Next up is for the player to execute its attack. If boss is still alive, keep playing.
     *
     * Then, another run of effects. If boss lives, continue playing.
     *
     * Lastly, it's the bosses turn. Check if player survived the attack. If so, keep playing.
     *
     *
     * ** SPOILER **
     * Same as part 1, except the player loses 1 hp each round.
     */
    function battle($attacks, $p, $b, $attack, $manaSpent, $part2) {
        global $minManaSpent; // Get global variable holding the lowest mana-amount used
        
        
        
        // If current run is more mana-expensive than the best, exit run
        if ($manaSpent >= $minManaSpent) {
            return;
        }
        
        
        
        // Run the effects and the players turn
        foreach ($attack as $name => $timer) {
            $stats = $attacks[$name]; // Store current attack-stats
            $p["hp"] += $stats["hp"]; // Increase player hp from the current attack
            $p["def"] = isset($attack["Shield"]) ? $attacks["Shield"]["def"] : $stats["def"]; // Set player shield from the current attack
            $p["mana"] += $stats["mana"]; // Increase player mana from the current attack
            
            $b["hp"] -= $stats["dmg"]; // Decrease boss hp from the current attack
            
            
            
            // Did boss die?
            if ($b["hp"] < 1) {
                $minManaSpent = $manaSpent; // Store new lowest mana used
                
                return;
            }
            
            
            
            // Did effects run out?
            if (--$attack[$name] <= 0) {
                unset($attack[$name]);
                
                // If effect "Shield" ran out, set player "defence" to 0
                if ($name === "Shield") {
                    $p["def"] = 0;
                }
            }
        }
        
        
        
        // Boss attacks
        $p["hp"] -= max(1, $b["dmg"] - $p["def"]); // Decrease player hp by <dragon damage> - <armor>.
                                                                // If resulting attack is lower than 1 dmg, set damage to 1.
        // If playing part 2, decrease player hp by 1 each round
        if ($part2) {
            $p["hp"]--;
        }
        
        
        
        // If player died, exit
        if ($p["hp"] < 1) {
            return;
        }
        
        
        
        // Run through the effects again
        foreach ($attack as $name => $timer) {
            $stats = $attacks[$name]; // Store current attack-stats
            $p["hp"] += $stats["hp"]; // Increase player hp from the current attack
            $p["def"] = isset($attack["Shield"]) ? $attacks["Shield"]["def"] : $stats["def"]; // Set player shield from the current attack
            $p["mana"] += $stats["mana"]; // Increase player mana from the current attack
            
            $b["hp"] -= $stats["dmg"]; // Decrease boss hp from the current attack
            
            
            
            // Did boss die?
            if ($b["hp"] < 1) {
                $minManaSpent = $manaSpent; // Store new lowest mana used
                
                return;
            }
    
    
    
            // Did effects run out?
            if (--$attack[$name] <= 0) {
                unset($attack[$name]);
                
                if ($name === "Shield") { // If effect "Shield" ran out, set player "defence" to 0
                    $p["def"] = 0;
                }
            }
        }
        
        
        
        // Prepare players next attack
        foreach ($attacks as $name => $stats) {
            // If player has not enough mana
            if ($p["mana"] - $stats["cost"] < 0) {
                continue;
            }
            
            
            
            // If effect is already in use, go to next attack
            if (isset($attack[$name])) {
                continue;
            }
            
            $tmpP = $p; // Store a copy of the current player-stats
            $tmpP["mana"] -= $stats["cost"]; // Decrease cost of attack from players mana-pool
            $tmpAttack = $attack; // Store a copy of the current attack
            $tmpAttack[$name] = $stats["turns"]; // Store the amount of rounds the attack lasts
            
            
            
            // Initiate next round
            battle($attacks, $tmpP, $b, $tmpAttack, $manaSpent + $stats["cost"], $part2);
        }
    }
    
    
    
    function solve($input, $part2 = false) {
        $boss = [ // Set boss stats
            "hp" => intval(substr($input[0], 12)), // Get boss hp from input-file
            "dmg" => intval(substr($input[1], 8)) // Get boss-damage from input-file
        ];
        $player = [ // Set player stats
            "hp" => 50,
            "def" => 0,
            "mana" => 500
        ];
        // Define all the different attacks
        $attacks = [
            "Magic Missile" => ["cost" => 53, "dmg" => 4, "hp" => 0, "def" => 0, "mana" => 0, "turns" => 1],
            "Drain" => ["cost" => 73, "dmg" => 2, "hp" => 2, "def" => 0, "mana" => 0, "turns" => 1],
            "Shield" => ["cost" => 113, "dmg" => 0, "hp" => 0, "def" => 7, "mana" => 0, "turns" => 6],
            "Poison" => ["cost" => 173, "dmg" => 3, "hp" => 0, "def" => 0, "mana" => 0, "turns" => 6],
            "Recharge" => ["cost" => 229, "dmg" => 0, "hp" => 0, "def" => 0, "mana" => 101, "turns" => 5]
        ];
        
        
        
        if ($part2) { // For part 2, we lose a health point each round
            $player["hp"]--;
        }
        
        
        
        // Loop through each attack to initiate the battle
        foreach ($attacks as $attack => $stats) {
            $tmpPlayer = $player; // Save a copy of the original player-array
            $tmpPlayer["mana"] -= $stats["cost"]; // Reduce player-mana with the cost of the attack
            battle($attacks, $tmpPlayer, $boss, [$attack => $stats["turns"]], $stats["cost"], $part2); // Initiate battle
        }
    }
    
    
    
    // Solve part 1
    $minManaSpent = PHP_INT_MAX; // Set a global variable to store the lowest mana used
    $start = microtime(true);
    solve($input);
    echo "Part 1: " . $minManaSpent . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $minManaSpent = PHP_INT_MAX; // // Reset the global variable that stores the lowest mana used
    $part2 = true;
    $start = microtime(true);
    solve($input, $part2);
    echo "Part 2: " . $minManaSpent . " (solved in " . (microtime(true) - $start) . " seconds)";
