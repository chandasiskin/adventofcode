<?php
    /**
     * https://adventofcode.com/2015/day/24
     *
     *
     *
     * It's Christmas Eve, and Santa is loading up the sleigh for this year's deliveries.
     * However, there's one small problem: he can't get the sleigh to balance.
     * If it isn't balanced, he can't defy physics, and nobody gets presents this year.
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
     * For part 1 we are to divide all the packages into 3 groups so that the weight of the groups are equal.
     * To weight of any group is therefore the sum of all the packages divided by 3.
     * We then need to find the smallest amount of packages that is needed to get to this weight.
     * If there are multiple combinations, we count the product of all the packages in that combination
     * and the lowest result is the winner.
     *
     *
     *
     * ** SPOILER **
     * Part 2 is exactly the same as part 1, except we have 4 groups.
     */
    function solve($input, $part2 = false) {
        // Trim away all unwanted characters and force the input into integers
        $packages = array_map("intval", array_map("trim", $input));
        // If we are doing part 1
        if (!$part2) {
            $goalWeight = array_sum($packages) / 3; // Divide the total weight with 3
        }
        
        // If we are doing part 2
        else {
            $goalWeight = array_sum($packages) / 4; // Divide the total weight with 4
        }
        
        
        
        // This function finds all the combinations that matches the goal weight
        findSolution($goalWeight, $packages);
    }
    
    
    
    function findSolution($goalWeight, $packages, $currentPackages = []) {
        global $bestResult; // Get current best result
        
        
        
        // If our current combination has more packages than the best one, skip
        if (count($currentPackages) > $bestResult["count"]) {
            return false;
        }
        
        
        
        // If the current combinations weight sum is greater than our goal weight, skip
        if (array_sum($currentPackages) > $goalWeight) {
            return false;
        }
        
        
        
        // If we've reached our goal weight
        if (array_sum($currentPackages) === $goalWeight) {
            // If current package-count is smaller than the best, make current package the best
            if (count($currentPackages) < $bestResult["count"]) {
                $bestResult = ["count" => count($currentPackages), "QE" => array_product($currentPackages)];
            }
            
            // If current-package count isn't smaller, it has to be the same (otherwise one of the if-statements above would have been triggered).
            // Keep the lower value between the best and the product of the current packages
            else {
                $bestResult["QE"] = min($bestResult["QE"], array_product($currentPackages));
            }
            
            
            
            return true;
        }
        
        
        
        // Loop through each package, starting from the last
        for ($i = count($packages) - 1; $i >= 0; $i--) {
            $currentPackages[] = $packages[$i]; // Move current package from the available packages into our current combination
            array_pop($packages); // Remove current package from the available packages
            $reducedPackages = $packages; // Save the new, reduced packages into a new variable
            
            
            
            // Call current function again with the new variables.
            // If the function returns false, no combination has been found, so remove the current package from the available packages
            if (!findSolution($goalWeight, $reducedPackages, $currentPackages)) {
                array_pop($currentPackages);
            }
        }
        
        
        
        return 0;
    }
    
    
    
    // Solve part 1
    $bestResult = ["count" => PHP_INT_MAX, "QE" => PHP_INT_MIN]; // Reset best solution
    $start = microtime(true);
    solve($input);
    echo "Part 1: " . $bestResult["QE"] . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true;
    $bestResult = ["count" => PHP_INT_MAX, "QE" => PHP_INT_MIN]; // Reset best solution
    $start = microtime(true);
    solve($input, $part2);
    echo "Part 2: " . $bestResult["QE"] . " (solved in " . (microtime(true) - $start) . " seconds)";
