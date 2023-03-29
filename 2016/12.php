<?php
    /**
     * https://adventofcode.com/2016/day/12
     *
     *
     *
     * You finally reach the top floor of this building: a garden with a slanted glass ceiling.
     * Looks like there are no more stars to be had.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("12.txt")) { // If file is missing, terminate
        die("Missing file 12.txt");
    } else {
        $input = file("12.txt"); // Save file as an array
    }
    
    
    
    /**
     * This is a problem where you need to figure out a better way to calculate the final result. Yes, you can write a translator
     * that turns the instructions in the input into real code (like "cpy 1 a" would translate to "$a = 1"), but the runtime is
     * REALLY long. So the best thing to do is to figure out what the code does.
     * Let's look at my input with explanations (the number in the beginning refers to line number):
     1. cpy 1 a
     2. cpy 1 b
     3. cpy 26 d
     4. jnz c 2
     5. jnz 1 5
     6. cpy 7 c
     7. inc d
     8. dec c
     9. jnz c -2
    10. cpy a c
    11. inc a
    12. dec b
    13. jnz b -2
    14. cpy c b
    15. dec d
    16. jnz d -6
    17. cpy 19 c
    18. cpy 14 d
    19. inc a
    20. dec d
    21. jnz d -2
    22. dec c
    23. jnz c -5
     *
     * Lines 1-3 is just a basic "give variable X the value of Y".
     * Line 4 is an if-statement: "if c is not zero, jump 2 rows down".
     * Line 5 is the else-statement: "else skip 4 rows and jump 5 rows down".
     * Lines 6-8, which is just "set value", "increment" and decrement", runs if line 4 validates to true.
     * Line 9 is a do-while-loop: "if c is not zero, jump 2 rows up.
     * Lines 10-12 is basic "set value", "increment" and "decrement".
     * Line 13 is, again, a do-while-loop, this time with "if b is not zero".
     * Line 14-15 is "set value" and "decrement".
     * Line 16 is another do-while-loop: "if d is not zero, jump up 6 rows".
     * Lines 17-20 is "set value", "set value", "increment" and finally "decrement".
     * Line 21 is a do-while-loop.
     * Line 22 is "decrement".
     * Line 23 is a do-while-loop.
     *
     * If we translate this to PHP, we get:
     *  1. $a = 1;
     *  2. $b = 1;
     *  3. $d = 26;
     *  4. if ($c !== 0) {
     *  6.      $c = 7;
     *          do {
     *  7.          $d++;
     *  8.          $c--;
     *  9.      } while ($c !== 0);
     *     }
     *     do {
     * 10.      $c = $a;
     *          do {
     * 11.          $a++;
     * 12.          $b--;
     * 13.      } while ($b !== 0);
     * 14.      $b = $c;
     * 15.      $d--;
     * 16. } while ($d !== 0);
     * 17. $c = 19;
     *     do {
     * 18.      $d = 14;
     *          do {
     * 19.          a++;
     * 20.          $d--;
     * 21.      } while ($d !== 0);
     * 22.      $c--;
     * 23. } while ($c !== 0);
     *
     * Lines 6-9 can be rewritten as $d += 7;
     * Lines 10-16 is the Fibonacci sequence:
     * First time, we do the loop at line 13 once (since $b is 1), meaning we increment $a once.
     * The second time, we still do the "line 13"-loop once because in the previous run, we set $b = $c and $c was, at that point, 1.
     * The third run, we do the "line 13"-loop twice, because $b is now 2.
     * The fourth run, the "line 13"-loop runs three times.
     * The fifth run, we loop 5 times.
     * Sixth is 8 loops.
     * Seventh is 13 loops.
     * This means that lines 10-16 is "what number in the Fibonacci series is at position 26 (which we got from line 3).
     *
     * Lines 17-23 is "add 14 (from line 18) to $a 19 times (which we get from line 17)". In short, add 14 * 19 to $a.
     *
     * In conclusion: the answer is "get the value at position X in the Fibonacci sequence and add a product to it".
     *
     * NOTE: since the Fibonacci sequence is the same for every one, to make all the users submit a different answer
     * the creator could alter the value at line 3, which is the "value at position X in the Fibonacci sequence" along with
     * product from line 17 and 18. This means that my code might not work for you, because I hardcoded those values into my code.
     * If it doesn't work for you, change the variable "$max" to whatever integer you have at line 3 (or around there)
     * and the variables in "$multipliers" to whatever integers you have at lines 17 and 18 (or around there).
     *
     *
     * ** SPOILER **
     * Exactly the same as part 1, except we keep going a few more steps on the Fibonacci sequence.
     * The amount of steps you continue is the integer on line 6, so if my code doesn't get you the right answer,
     * just enter your integer in the "$max"-variable in the first if-statement below.
     */
    function solve($input, $part2 = false) {
        $previous = 1;
        $current = 1;
        $max = intval(preg_replace("/[^\d]+/", "", $input[2]));
        $multipliers = [
            intval(preg_replace("/[^\d]+/", "", $input[16])),
            intval(preg_replace("/[^\d]+/", "", $input[17]))
        ];
        
        if ($part2) {
            $max += intval(preg_replace("/[^\d]+/", "", $input[5]));
        }
        
        
        
        for ($i = 0; $i < $max; $i++) {
            $next = $previous + $current;
            $previous = $current;
            $current = $next;
        }
        
        
        
        $a = $current;
        
        
        
        $a += array_product($multipliers);
        
        
        
        return $a;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
