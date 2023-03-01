<?php
    /**
     * https://adventofcode.com/2015/day/15
     *
     *
     *
     * Today, you set out on the task of perfecting your milk-dunking cookie recipe.
     * All you have to do is find the right balance of ingredients.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("15.txt")) { // If file is missing, terminate
        die("Missing file 15.txt");
    } else {
        $input = file("15.txt"); // Save file as an array
    }
    
    
    
    /**
     * For this task, we need to determine how much we need of each ingredient. The catch is that the sum of the ingredients
     * has to be exactly 100. For this, we use 4-loops, one for each ingredient.
     * Once we have the ingredient amount, we add up all the ingredients properties. A negative property-value is rounded up to 0.
     * The total score is obtained by multiplying all the property-values.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we add one more requirement: the sum of the calories must be exactly 500.
     */
    function solve($input) {
        $ingredients = [];
        $highestTotalScore1 = $highestTotalScore2 = PHP_INT_MIN;
        
        
        
        foreach ($input as $row) {
            preg_match_all("/-?\d+/", $row, $matches);
            $ingredients[] = $matches[0];
        }
        
        
        
        for ($a = 1; $a <= 97; $a++) {
            for ($b = 1; $b <= 97; $b++) {
                for ($c = 1; $c <= 97; $c++) {
                    for ($d = 1; $d <= 97; $d++) {
                        if ($a + $b + $c + $d === 100) {
                            $capacity = max(0,
                                $a * $ingredients[0][0] +
                                $b * $ingredients[1][0] +
                                $c * $ingredients[2][0] +
                                $d * $ingredients[3][0]);
                            $durability = max(0,
                                $a * $ingredients[0][1] +
                                $b * $ingredients[1][1] +
                                $c * $ingredients[2][1] +
                                $d * $ingredients[3][1]);
                            $flavor = max(0,
                                $a * $ingredients[0][2] +
                                $b * $ingredients[1][2] +
                                $c * $ingredients[2][2] +
                                $d * $ingredients[3][2]);
                            $texture = max(0,
                                $a * $ingredients[0][3] +
                                $b * $ingredients[1][3] +
                                $c * $ingredients[2][3] +
                                $d * $ingredients[3][3]);
                            $calories =
                                $a * $ingredients[0][4] +
                                $b * $ingredients[1][4] +
                                $c * $ingredients[2][4] +
                                $d * $ingredients[3][4];
                            
                            $totalScore = $capacity * $durability * $flavor * $texture;
                            
                            $highestTotalScore1 = max($highestTotalScore1, $totalScore);
                            
                            if ($calories === 500) {
                                $highestTotalScore2 = max($highestTotalScore2, $totalScore);
                            }
                        }
                    }
                }
            }
        }
        
        
        
        return [$highestTotalScore1, $highestTotalScore2];
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and " . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
