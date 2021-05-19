# 🐚 Shells, a ≣ stack that runs only with 𝞴 instances

Shells are always grouped into gems, belonging to an interface; except for the main shell.

Shells only run when an instance, created from an interface, enters into a promise. The promise starts a main shell

The main shell  is automatically created when the execution starts in a promise and is always the same. The main shell is system created, and always has the same name and guid, has no tags that are readable or writable by any of the shells or elements that are inside of it. When the main shell is created, it is a new stack which has the context of the stack (promise) which created it

@spec list how the shell maps into a segment


## Shell Timelines

In the first turn, the main shell is created. Then the standard timeline is done on the first turn, and every turn after



*   each of  the ground state shells (mill, not on the stack), in all the gems, will decide
    *   spawn a new shell based on conditions of the other, already spawned shells, which decide to change their tags based on their own logic
        *   picks an existing shell to spawn inside
        *   the newly spawned shell is a either a fresh first stack, or can be a child of an existing shell, keeping its state of the parent
*   spawning shells will be created the same turn as the ground state shell decides
    *   instantiates any elements it has explicitly listed, these are done the same turn
    *   transfers instantiated elements to itself, these are transferred the same turn
        *   transferred elements have a flag set that makes sure the dynamic memory is not popped off when the shell ends
    *   copies any elements to copy, these are also instantiated the same turn
        *   Copying elements makes a parallel stack, with the same parent stack as what is copied. The last copy will be the stack to write back to the parent dynamic boxes
    *   When a shell starts, the shell is pushed onto the stack, and then the elements its creating are added to the stack, and the elements its copying from the environment is copied to the stack, finally any elements its taking possession of is moved to a group inside the shell
    *   Each element can only belong to one shell at a time; unlimited copies of the same element can be in unlimited shells though, however each of these copies only have one shell parent themselves
    *   When a shell pops, any dynamic data on its bus is copied back to the parent shell
        *   Any sub shells are popped also, before the parent shell, or its elements are popped
*   each of the spawned shells (including the shells that just spawned)
    *   does it’s logic, altering its tags
    *   decides if it should end
        *   it will actually end the next turn
        *   when a shell ends, the instantiated elements pop off their dynamic memory. For each dynamic memory popped off, the first same element existing further up the context chain, has its dynamic memory updated by it the next turn
            *   the memory is popped off at the end of the turn, so this is automatically done
        *   When a shell ends, its elements are popped off first, then the shell is popped off
*   each of the elements in each of the shells
    *   does it thing
*   Stacks that are flagged to pop will pop, updating the stack below. For the elements, which can have multiple copies on different levels of nested shells, the parent stack is automatically the nearest same element, and so the memory will be updated one time for the closest element
*   If there are no shells but the main one instantiated, the main shell ends

When the main shell ends:

The element static data is written to the ground level static boxes. All the elements that run start the main shell with an extra stack before they enter any other shell, so that by the time the last running shell ends, the static data will be updated to the first level stack for the elements. So, when the main shell exists, that first level will update the static in the ground level of the elements

All the instances that are written to have a single stack level added for the whole main shell and all the other shells. When the main shell pops, those instances levels are popped too, and both the dynamic data in the first level stack, and the static data in the ground level, are written to




## Shell Settings

After the main shell, or any other shell, is created, each other shell in all the instances use their bus and logic to decide whether to add themselves to the stack, and inside which shell  (context) they are adding themselves to

Shells have groups that are marked by flags on the bus, which determine their behavior



*    Required Shells/Starting requirements
    *   this selection/group must be successful for the shell to be spawned
*   Start Shell
    *   The starting parent shell for the new shell. If there is no starting point, then its immediate context is the main shell
*   Finish Shell
    *   The ending parent shell for the new shell, after it grabs elements. When the shell pops
*   Ending Requirements
    *   These are the selections which poll shells or elements , or other, to decide when to pop
*   Element List for forking (non popped)
    *   The group of elements it is going to fork
        *    ( copy the stack, and the new stack has the same parent as the old stack)
    *   these elements do not pop when the shell pops, they are put into the parent shell element group
*   Element List for forking (popped)
    *   Same as above, but these forks pop when the shell pops
*   Element List for stacking
    *   spin new stacks, using the existing elements going up its context chain, or if not there, using the ground state for the element
*   Element list for absorption
    *   The group of elements that will be taken, without copy, from the  context chain
    *   absorbed elements do not pop when the shell pops, but are put into the parent shell’s chain


## Special Traits and Selection

There is a selectable condition where the shell is about to pop, one turn before it does

The corollary to this is that there is a one turn pause between when the shell decides to pop, and when it does pop

The bus and logic in a shell can use special traits which handle the elements , as a group, and the running of the elements , inside of it. The same traits can select on all the elements of one or more other shells, if it's trying to scope out what is happening elsewhere

Visibility wise, a spawned shell can only see up its context stack, when selecting or using logic


## Exceptions

There is an api, available to elements, which will throw a single trait. When an exception is thrown, if not caught, the shell and all its children shells will pop, taking whatever element state there is, never to be seen again.

Then the exception will propagate up through the context stack, to every parent above, until it gets to the master shell. If not caught then, the master shell will exit, and no instance or element state will be saved

Exceptions can be caught by shells, and they can instantiate themselves into existence . They can listen for exceptions in any or a selection of shells. If there is more than one shell listener for a trait that is thrown, then all of them are instantiated where they are supposed to be at

Shells which capture exceptions can also have elements that rethrow the exception, or another exception


## Examples

Params pop into existence when the shell they are params to (the required shell here) is created, they start in the parent shell and end in their required shell, releasing the elements when they pop

Returns pop into existence when the shell they required to start in, is about to pop. They take in elements inside the shell, where they start at, and then they end in the parent shell, releasing the elements

A shell can monitor if there are certain elements to pick up, and either copy to or transfer to another shell