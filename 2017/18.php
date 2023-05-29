<?php
    /**
     * https://adventofcode.com/2017/day/18
     *
     *
     *
     * You discover a tablet containing some strange assembly code labeled simply "Duet".
     * Rather than bother the sound card with it, you decide to run the code yourself.
     * Unfortunately, you don't see any documentation, so you're left to figure out what the instructions mean on your own.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("18.txt")) { // If the file is missing, terminate
        die("Missing file 18.txt");
    } else {
        $input = file("18.txt"); // Save file as a string
    }
    
    
    
    /**
     * We have a set of instructions in our input, where some add a value, some multiply, some modulate, etc.
     * For every instruction (and almost every argument these instructions come with), we have to check for two things:
     * 1. If the argument is an integer, we use the value of that integer
     * 2. If the argument is a character, we use the value (an integer) found in our memory registered to that character
     * The run stops if we get to an instruction outside our input.
     *
     * For part 1, we just keep looping until we reach the instruction "rcv" where the argument associated with it is non-zero.
     * When this occurs, we return the value from our last "snd"-instruction.
     *
     *
     *
     * ** SPOILER **
     * Part 2 is way more tricky than the first! Here we are running two programs "simultaneously" so each program has its
     * own <current position>, its own <register> and its own <queue>. To solve this, we are going to run one of the programs
     * as long as we can. When it halts (for either jumping out of bounds or waiting for the next value in the queue),
     * we check if the other program is also in a halt (for the same reasons as the former program). If they both are in a halt-position,
     * a deadlock has occurred, and we terminate both programs and return the value asked for in this task.
     * If both programs are NOT in a deadlock, we simply switch to the other program and keep running until it halts.
     * We repeat this process until we reach a deadlock. To be able to switch between programs and keep track of how far in the process we are,
     * we need:
     * 1. Two registers
     * 2. Two positions
     * 3. Two queues
     * 4. Something to tell which program we are running
     *
     * For items 1-3 we simply use a 2D-array, where the array stored in position 0 holds the values for program 0,
     * and the array stored in position 1 holds all the values for program 1.
     * For item 4 we just use a variable that either holds the value 0 (for program 0) or 1 (for program 1).
     */
    function solve($input, $part2 = false) {
        $instructions = []; // Holds all the instructions from our input
        $registers = []; // Holds all the values in our register
        $pos = [0, 0]; // Set starting position for program 0 and 1 [<starting position for program 0>, <starting position for program 1>]
        $queues = [[], []]; // Holds all the queued values for program 0 and 1 [<program 0>, <program 1>]
        $currentProgram = 0; // Keeps track of what program we are currently running
        $sendCounter = 0; // Counts the number of times program 1 encountered the "snd" instruction
        
        
        
        // Loop through every row in our inpuot
        foreach ($input as $row) {
            $ops = explode(" ", trim($row)); // Remove unwanted characters and turn the current row into an array
            $instructions[] = $ops; // Store the current "arrayed" instruction
            
            
            
            // If the first argument is not a number, it's a reference to our register
            if (!is_numeric($ops[1])) {
                $registers[0][$ops[1]] = $registers[1][$ops[1]] = 0; // Store that reference with a starting value of 0
            }
            
            
            
            // If a second argument exists
            if (isset($ops[2])) {
                // If that argument is not a number, it's a reference to our register
                if (!is_numeric($ops[2])) {
                    $registers[0][$ops[2]] = $registers[1][$ops[2]] = 0; // Store that reference with a starting value of 0
                }
            }
        }
        
        $registers[1]["p"] = 1; // Set the register reference "p" to 1 for program 1
        
        
        
        // Keep looping through the instructions indefinitely
        do {
            // If we have reached an instruction out of bounds
            if (!isset($instructions[$pos[$currentProgram]])) {
                $currentProgram = intval(!$currentProgram); // Switch to the other program
                
                
                
                // If the other program has also reached an instruction out of bounds
                if (!isset($instructions[$pos[$currentProgram]])) {
                    break; // Jump out of loop
                }
            }
            
            
            
            $instruction = $instructions[$pos[$currentProgram]]; // Get instruction from current position for current program
            
            // Check was instruction to run
            switch ($instruction[0]) {
                // ** SPOILER **
                // If we are to send a value to the other program
                // ** END OF SPOILER **
                case "snd":
                    // ** SPOILER **
                    // If the argument in the instruction is a number, convert it to an integer and send it.
                    // If the argument in the instruction is not a number, it's a reference to a location in our register.
                    // Get the value from the register and send it to the other program
                    // ** END OF SPOILER **
                    $queues[intval(!$currentProgram)][] = is_numeric($instruction[1]) ?
                        intval($instruction[1]) :
                        $registers[$currentProgram][$instruction[1]];
                    $pos[$currentProgram]++; // Jump the current program to the next instruction
                    
                    
                    
                    // If we are currently running program 1, increment our sending-counter
                    if ($currentProgram === 1) {
                        $sendCounter++;
                    }
                    break;
                
                // If we are setting a value
                case "set":
                    // If the second argument in the instruction is a number, convert it to an integer and store it where
                    // the first argument is referencing.
                    // If the second argument in the instruction is not a number, it's a reference to a location in our register.
                    // Get the value from the register and store it in the register referenced by the first argument.
                    $registers[$currentProgram][$instruction[1]] = is_numeric($instruction[2]) ?
                        intval($instruction[2]) :
                        $registers[$currentProgram][$instruction[2]];
                    $pos[$currentProgram]++; // Jump the current program to the next instruction
                    break;
                
                // If we are adding to a value
                case "add":
                    // If the second argument in the instruction is a number, convert it to an integer and add it to the value of
                    // wherever the first argument is referencing.
                    // If the second argument in the instruction is not a number, it's a reference to a location in our register.
                    // Get the value from the register and add it to the register referenced by the first argument.
                    $registers[$currentProgram][$instruction[1]] += is_numeric($instruction[2]) ?
                        intval($instruction[2]) :
                        $registers[$currentProgram][$instruction[2]];
                    $pos[$currentProgram]++; // Jump the current program to the next instruction
                    break;
                
                // If we are multiplying to a value
                case "mul":
                    // If the second argument in the instruction is a number, convert it to an integer and multiply it to the value of
                    // wherever the first argument is referencing.
                    // If the second argument in the instruction is not a number, it's a reference to a location in our register.
                    // Get the value from the register and multiply it to the register referenced by the first argument.
                    $registers[$currentProgram][$instruction[1]] *= is_numeric($instruction[2]) ?
                        intval($instruction[2]) :
                        $registers[$currentProgram][$instruction[2]];
                    $pos[$currentProgram]++; // Jump the current program to the next instruction
                    break;
                
                // If we are modulating a value
                case "mod":
                    // If the second argument in the instruction is a number, convert it to an integer and modulate it to the value of
                    // wherever the first argument is referencing.
                    // If the second argument in the instruction is not a number, it's a reference to a location in our register.
                    // Get the value from the register and modulate it to the register referenced by the first argument.
                    $registers[$currentProgram][$instruction[1]] %= is_numeric($instruction[2]) ?
                        intval($instruction[2]) :
                        $registers[$currentProgram][$instruction[2]];
                    $pos[$currentProgram]++; // Jump the current program to the next instruction
                    break;
                
                // ** SPOILER **
                // If we are to receive a value from our current programs queue
                // ** END OF SPOILER **
                case "rcv":
                    // If we are doing part 1
                    if (!$part2) {
                        // Check if the argument is a number and is NOT 0,
                        // or check if the argument is not a number, and it refers to a place in our register that is NOT 0
                        if ((is_numeric($instruction[1]) && intval($instruction[1]) !== 0)
                        || (!is_numeric($instruction[1]) && $registers[$currentProgram][$instruction[1]] !== 0)) {
                            return end($queues[intval(!$currentProgram)]); // Return the value from our last "snd" instruction
                                                                                // ** SPOILER **
                                                                                // Which is the last value in the queue for the other program
                                                                                // ** END OF SPOILER **
                        }
                    }
                    
                    
                    
                    // If the current queue is empty and there is no values to receive
                    if (empty($queues[$currentProgram])) {
                        $currentProgram = intval(!$currentProgram); // Switch to the other program
                        
                        
                        
                        // If the other program is also waiting to receive a number, and it's queue is empty as well, we have a deadlock
                        if ($instructions[$pos[$currentProgram]][0] === "rcv" && empty($queues[$currentProgram])) {
                            break 2; // Exit main-loop
                        }
                    }
                    
                    // If there are values to receive from current programs queue
                    else {
                        // Get the first value from the current programs queue and store it wherever the argument is point at.
                        $registers[$currentProgram][$instruction[1]] = array_shift($queues[$currentProgram]);
                        $pos[$currentProgram]++; // Jump the current program to the next instruction
                    }
                    break;
                
                // If we are about to make an unusual jump in our instructions
                case "jgz":
                    // If the first argument is a number and greater than 0,
                    // or if the first argument is not a number, but a reference to a point in the register of the current program,
                    // and the value in the register is greater than 0
                    if ((is_numeric($instruction[1]) && intval($instruction[1]) > 0)
                    || (!is_numeric($instruction[1]) && $registers[$currentProgram][$instruction[1]] > 0)) {
                        // If the second argument in the instruction is a number, convert it to an integer and
                        // add it to the current position for the current program.
                        // If the second argument in the instruction is not a number, it's a reference to a location in our register.
                        // Get the value from the register and add it to the current position for the current program.
                        $pos[$currentProgram] += is_numeric($instruction[2]) ?
                            intval($instruction[2]) :
                            $registers[$currentProgram][$instruction[2]];
                    }
                    
                    // If the first argument or the value in the register is not greater than 0, do a regular one-step-jump
                    else {
                        $pos[$currentProgram]++;
                    }
                    break;
                
                // If we reach an invalid instruction in our input
                default:
                    die("Invalid instruction: " . implode(" ", $instruction)); // Print out error message and kill program
            }
        } while (true);
        
        
        
        return $sendCounter;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
