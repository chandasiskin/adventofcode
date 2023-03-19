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
    if (!is_file("25.txt")) { // If file is missing, terminate
        die("Missing file 25.txt");
    } else {
        $input = file_get_contents("25.txt"); // Save file as a string
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
        preg_match_all("/\d+/", $input, $matches);
        $coords = array_map("intval", $matches[0]);
        $number = 20151125;
        $y = $x = 1;
        $yMax = 2;
        
        
        
        do {
            if ($y === $coords[0] && $x === $coords[1]) {
                return $number;
            }
            
            
            
            //$arr[$y][$x] = $number;
            $y--;
            $x++;
            $number = ($number * 252533) % 33554393;
            
            if ($y < 1) {
                $y = $yMax;
                $yMax++;
                $x = 1;
            }
        } while (true);
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res . " (solved in " . (microtime(true) - $start) . " seconds)";
