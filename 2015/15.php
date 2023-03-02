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
        $ingredients = []; // Holds the ingredients with its data
        $highestTotalScore1 = $highestTotalScore2 = PHP_INT_MIN; // Hold the current highest score for part 1 and part 2
        
        
        
        // Loop through each row in input and store ingredient data in an array
        foreach ($input as $row) {
            preg_match_all("/-?\d+/", $row, $matches);
            $ingredients[] = $matches[0];
        }
        
        
        
        // Amount for the first ingredient
        for ($a = 1; $a <= 97; $a++) {
            // Amount for the second ingredient
            for ($b = 1; $b <= 97; $b++) {
                // Amount for the third ingredient
                for ($c = 1; $c <= 97; $c++) {
                    // Amount for the fourth ingredient
                    for ($d = 1; $d <= 97; $d++) {
                        // If all ingredient amounts sum up to 100
                        if ($a + $b + $c + $d === 100) {
                            // Add together the values of capacity from all ingredients (if negative, round up to 0)
                            $capacity = max(0,
                                $a * $ingredients[0][0] +
                                $b * $ingredients[1][0] +
                                $c * $ingredients[2][0] +
                                $d * $ingredients[3][0]);
                            
                            // Add together the values of durability from all ingredients (if negative, round up to 0)
                            $durability = max(0,
                                $a * $ingredients[0][1] +
                                $b * $ingredients[1][1] +
                                $c * $ingredients[2][1] +
                                $d * $ingredients[3][1]);
                            
                            // Add together the values of flavor from all ingredients (if negative, round up to 0)
                            $flavor = max(0,
                                $a * $ingredients[0][2] +
                                $b * $ingredients[1][2] +
                                $c * $ingredients[2][2] +
                                $d * $ingredients[3][2]);
    
                            // Add together the values of texture from all ingredients (if negative, round up to 0)
                            $texture = max(0,
                                $a * $ingredients[0][3] +
                                $b * $ingredients[1][3] +
                                $c * $ingredients[2][3] +
                                $d * $ingredients[3][3]);
    
                            // Add together the values of calories from all ingredients
                            $calories =
                                $a * $ingredients[0][4] +
                                $b * $ingredients[1][4] +
                                $c * $ingredients[2][4] +
                                $d * $ingredients[3][4];
                            
                            // Get the total score of the current recipe
                            $totalScore = $capacity * $durability * $flavor * $texture;
                            
                            // Compare it to the currently best ranked cookie for part 1
                            $highestTotalScore1 = max($highestTotalScore1, $totalScore);
                            
                            // If the current recipe has exactly 500 calories, compare it to the best ranked cookie for part 2
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
