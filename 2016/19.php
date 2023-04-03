<?php
    /**
     * https://adventofcode.com/2016/day/19
     *
     *
     *
     * The Elves contact you over a highly secure emergency channel.
     * Back at the North Pole, the Elves are busy misunderstanding White Elephant parties.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("19.txt")) { // If file is missing, terminate
        die("Missing file 19.txt");
    } else {
        $input = file_get_contents("19.txt"); // Save file as a string
    }
    
    
    
    /**
     * The problem for part 1 is called "Josephus problem" (https://en.wikipedia.org/wiki/Josephus_problem) and shortly is described:
     * "X amount of people are sitting in a circle. The first person kills the first one to the left.
     * Then, the next person kills the one to his/hers left. This keeps going around until there is only one person left.
     * Once a person dies, it's removed from the circle.
     * At what position do you need to be, to be the last one standing?".
     *
     * For the solution:
     * If you calculate the first 20 cases you are going to notice some nice patterns.
     * You can find the 100:st calculations for part 1 on https://michalmlozniak.com/notes/advent-of-code-2016-day-19-an-elephant-named-joseph.html.
     * Here, you are able to notice that when there are a power of 2 elves,
     * the first elf is always the winner (2 = 2^1 elves, 4 = 2^2 elves, 8 = 2^3 elves, and so on). Between the powers of 2,
     * the number of the winner increases by 2 (when 9 elves, number three wins. When 10 elves, number five wins.
     * When 11 elves, number seven wins).
     * That information is enough to write a short and fast solution. But there comes a neat trick:
     * if you look at the binary, you see an even easier pattern!
     * Let's take number 41 for example. 41 is 101001 in binary. If we add 1 from the right (left shift once and add 1) we get 1010011.
     * Then we remove the most significant number (the one all the way to the left) and that's it!
     * 1010011 becomes 010011, which is 19 in decimal. This works for every single number!
     * NOTE: In our code, we skip the bitwise operations and just translate our input to binary,
     * remove the one on the left and add a one to the right.
     *
     *
     *
     * ** SPOILER **
     * Same idea goes for part 2, except we no longer kill the one to our left, but the one sitting opposite of us.
     * If there are two people sitting opposite, we kill the left out of these two.
     *
     * If we calculate a few of these, we can see a pattern (first 100 results for part 2 can also be found on
     * https://michalmlozniak.com/notes/advent-of-code-2016-day-19-an-elephant-named-joseph.html).
     * The pattern is: for every <power of 3> + 1 the winner is the one sitting at position 1.
     * And for every person we add to the circle the winner seat increases by one.
     * Until the participant-count is double of the winning-seat. When that occurs, the winning seat does not increase by 1
     * but by 2.
     *
     * To calculate the winner, we start by finding between what exponents the elf-count is. We get that by solving 3^X = <input>.
     * The solution gives us a float, which rounded down gives us the lower bound and rounded up gives us the upper bound.
     * We now have that our solutions is between 3^X + 1 (lets call it A) and 3^(X + 1) (lets call it B).
     * Next step is to find at what participant-count we start to add 2 to the winner instead of one.
     * That point is right in the middle between A and B, which we get by doing A + <round down>((B - A) / 2). We call this C.
     * We can now determine if our input is before C or after.
     * If it's before, we just do <input> - A + 1 (the +1 is because the starting position is 1, not 0) and we have our answer.
     * Otherwise, we get the answer by doing (C - A + 1) + (<input> - C),
     * or as in the code:
     * - "increase by 1" from A to <input>
     * - if <input> is past C, increase another time from C to <input>
     */
    function solve($input, $part2 = false) {
        $elves = intval($input); // Convert input into an integer
        
        
        
        // If we are solving part 1
        if (!$part2) {
            $result = decbin($elves); // Convert integer to binary
            $result = substr($result, 1); // Remove leftmost one
            $result .= "1"; // Add a one to the right
            $result = bindec($result); // Convert binary to integer
            
            
            
            return $result;
        }
        
        // If we are solving part 2
        else {
            $exp = floor(log($elves) / log(3)); // Gives us the X in 3^X = <input>
            $start = pow(3, $exp) + 1; // Gives us the lower bound to our solution
            $end = pow(3, $exp + 1); // Gives us the upper bound to our solution
            $increaseWinnerPosAt = $start + floor(($end - $start) / 2); // At what point do we start increasing the winner by 2 instead of 1
            $winner = $elves - $start + 1; // Add 1 to the winning seat starting from the lower bound until our input
            
            // If our elf-count is past the "add 2 to winning seat",
            // we add another 1 for every participant between "start adding by 2" and our input
            if ($elves > $increaseWinnerPosAt) {
                $winner += $elves - $increaseWinnerPosAt;
            }
            
            

            return $winner;
        }
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
