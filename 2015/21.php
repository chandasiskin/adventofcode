<?php
    /**
     * https://adventofcode.com/2015/day/21
     *
     *
     *
     * Little Henry Case got a new video game for Christmas. It's an RPG, and he's stuck on a boss.
     * He needs to know what equipment to buy at the shop. He hands you the controller.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("21.txt")) { // If file is missing, terminate
        die("Missing file 21.txt");
    } else {
        $input = file("21.txt"); // Save file as an array
    }
    
    
    
    /**
     * We're fighting a dragon. We take turns attacking each other where we always make the first attack.
     * Whoever reaches below 1 hp first, dies.
     * For help, we can buy gear. Rules say that we must have one weapon, and one only.
     * We can have an armor, but it's optional. We can also equip up to 2 different rings, but these are also optional.
     * We build the weapons-, armors- and ring-arrays like [<cost>, <attack>, <armor>] and our and dragons stats
     * as [<hp>, <attack>, <armor>].
     * We try every single build by nesting loops with one loop for the weapons, one loop for armors, one loop for one of the rings
     * and a fourth loop for the second ring.
     * For every round, we check how many rounds we and the dragon can survive. Since we attack first every round, it's enough
     * for us to survive at least as many rounds as the dragon.
     * Part 1 is to calculate how little money we can use and still survive.
     *
     *
     *
     * ** SPOILER **
     * Part 2 is how much money we can spend and still not survive.
     */
    function solve($input) {
        $weapons = [ // Holds all the weapons [<cost>, <attack>, <armor]
            0 => [8, 4, 0],     // Dagger
            1 => [10, 5, 0],    // Shortsword
            2 => [25, 6, 0],    // Warhammer
            3 => [40, 7, 0],    // Longsword
            4 => [74, 8, 0]     // Greataxe
        ];
        $armors = [ // Holds all the armors [<cost>, <attack>, <armor]
            0 => [0, 0, 0],     // None
            1 => [13, 0, 1],    // Leather
            2 => [31, 0, 2],    // Chainmail
            3 => [53, 0, 3],    // Splintmail
            4 => [75, 0, 4],    // Bandedmail
            5 => [102, 0, 5]    // Platemail
        ];
        $rings = [ // Holds all the rings [<cost>, <attack>, <armor]
            0 => [0, 0, 0],     // None
            1 => [0, 0, 0],     // None
            2 => [25, 1, 0],    // Damage + 1
            3 => [50, 2, 0],    // Damage + 2
            4 => [100, 3, 0],   // Damage + 3
            5 => [20, 0, 1],    // Defence + 1
            6 => [40, 0, 2],    // Defence + 2
            7 => [80, 0, 3]     // Defence + 3
        ];
        $dragon = [
            intval(substr($input[0], 12)),  // Dragon hp
            intval(substr($input[1], 8)),   // Dragon attack
            intval(substr($input[2], 7))    // Dragon armor
        ];
        $cheapestBuild = PHP_INT_MAX; // Holds the current cheapest build where you win
        $mostExpensiveBuild = PHP_INT_MIN; // Holds the current most expensive build where you still lose
    
    
    
        for ($weapon = 0; $weapon < 5; $weapon++) { // Weapon loop
            for ($armor = 0; $armor < 6; $armor++) { // Armor loop
                for ($ring1 = 0; $ring1 < 8; $ring1++) { // Ring 1 loop
                    for ($ring2 = 0; $ring2 < 8; $ring2++) { // Ring 2 loop
                        if ($ring1 !== $ring2) { // Check that we haven't bought the same ring twice
                            // Calculate the total cost
                            $cost = $weapons[$weapon][0] + $armors[$armor][0] + $rings[$ring1][0] + $rings[$ring2][0];
                            $me = [
                                100,                                                                                // Our hp
                                $weapons[$weapon][1] + $armors[$armor][1] + $rings[$ring1][1] + $rings[$ring2][1],  // Our attack
                                $weapons[$weapon][2] + $armors[$armor][2] + $rings[$ring1][2] + $rings[$ring2][2]   // Our armor
                            ];
                            
                            
                            
                            // Calculate how many rounds the dragon can survive by <dragons hp> / (<our attack> - <dragons armor>).
                            // NOTE: because you die at 0 hp, we need to decrease total hp by one.
                            $dragonSurvivesForRounds = floor(($dragon[0] - 1) / max(1, $me[1] - $dragon[2]));
                            // Calculate how many rounds we can survive by <our hp> / (<dragons attack> - <our armor>).
                            // NOTE: because you die at 0 hp, we need to decrease total hp by one.
                            $meSurviesForRounds = floor(($me[0] - 1) / max(1, $dragon[1] - $me[2]));
                            
                            
                            
                            // If we out-survive the dragon (part 1)
                            if ($meSurviesForRounds >= $dragonSurvivesForRounds) {
                                // Check if current cost is cheaper than the current cheapest
                                $cheapestBuild = min($cheapestBuild, $cost);
                            }
                            
                            // If dragon out-survives us (part 2)
                            else {
                                // Check if current cost is greater than the current greatest
                                $mostExpensiveBuild = max($mostExpensiveBuild, $cost);
                            }
                        }
                    }
                }
            }
        }
        
        
        
        return [$cheapestBuild, $mostExpensiveBuild];
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
