# ≣ The stack

The stack allows a mill to be copied with a mixture of dynamic data (that will not affect the original mill) and static data.
Stacks are used both outside running promises, with instances, and inside running promises, with gems


When an [organization of mills](@ref MillOrganization )  is used , then all the connected mills get put on the stack together, with the id trait being the organization being put as the mill owner; and then all the stacked buses and boxes will use that mill stack as the owner 

Stacks can have levels, levels are a copy of the stack already existing which may be set with a limit. 𝞴 instances for example, are only allowed to create one level per stack

When adding a level to the stack



*   The 📦 box copies its 𝚫 json from the last level
*   The 🚌 bus copies or updates the two reference, the count and flag from the last level
*   the level will use the ground level (original) ⚙ logic

When removing a level from the stack



*   the 🚌 bus tells of each dynamic data in both the bus and the boxes
*   dynamic rows are forgotten, and removed
*   static data is copied down to the previous stack


## ≣ Mill Stack data storage



*   / 🏭 mill owner
    *   The mill, or mill organization,  this belongs to
*   / 🆔  the id-trait
    *   the unique trait generated for the ≣ stack
    *   each new stack layer gets a trait that inherits from the parent stack trait, which inherits from that 🏭 mill
*   / 🌐 world trait
    *   ≣ for stacks, there needs to be one world this belongs to
*   / parent trait
    *   ≣ for stacks, this is the parent pointer (can be null if this is the first stack for it)
*   / 𝝰 the alpha trait
    *   ≣ for stacks, this is the context
    *   if the stack is having a context, then can only interact with things that have this context, or things inside children contexts (if a B has a context of A, and then I make C have a context of B, then when D is put in the context of A, it can see C as they share the same context path
*   ⚑ operational flag
    *   ≣ for stacks, this shows if there can be dynamic data copying
    *    can set it to not copy back dynamic data to the parent
*   ℤ 𝝰 counter
    *   ≣ for stacks, this is the number of child stacks allowed
    *   each level added this is decreased, when 0, cannot copy to stack any more
    *   example: this will be a 0 for 𝞴 instances
    *   also, for other things, a known trait can hold any limiting in a box for the mill
*   ℤ 𝞫 counter
    *   ≣ for stacks, , ↪  turn this was last updated on


## ≣ 🚌 Bus Stack Data Storage

Only dynamic bus entries have another copy on the stack



*   / 🏭 stack owner
    *   The mill stack this ≣ 🚌 stack bus belongs to
*   / 🆔  the id-trait
    *   the unique trait generated for this ≣ 🚌 stack bus
    *   it will be a child of the stack bus trait in the previous level, or if first stack, then will be a child of the bus
*   / parent trait
    *   ≣ 🚌 for stacked buses, this is the ≣ stack owner
*   / 𝝰 the alpha trait
    *   ≣ 🚌 for stacked buses, this is the first trait
*   / 𝞫  the beta trait
    *   ≣ 🚌 for stacked buses, this is the second trait
*   / 𝝮 the grouping trait
    *   ≣ 🚌 for stacked buses, this is local collection trait
*   / ◎ the target trait
    *   ≣ 🚌 for stacked buses, this is the 🚌 pointer to the original bus
*   ℤ 𝝰 counter
    *   ≣ 🚌 for stacked buses, is the current tag counter
*   ℤ 𝞫 counter
    *   ≣ 🚌 for stacked buses, is the old tag counter

_note: if getting a known trait for standard info, that does not have an actual box, the ≣ stack  can override it if the 🏭 mill sets its 🚌 bus to be dynamic for that 📦 box_


## ≣ 📦 Box Stack Data Storage

Boxes are stacked if the bus they are associated with is dynamic or transient, and is put on the bus stack. 

Dynamic boxes are read from the parent or ancestor that has the most recently updated value. But are updated with the values from their last popped stack children

*   / 🏭 mill owner
    *   The mill stack this ≣ 📦  stacked box belongs to
*   / 🆔  the id-trait
    *   the unique trait generated for this ≣ 📦 stacked box
    *   it will be a child of the stacked box trait in the previous level, or if first stack, then will be a child of the box
*   𝚫 json data
    *    ≣ 📦  for stacked boxes, this is there copy of the data of the box they use
*   💠 multipolygon for associating area
    *    ≣ 📦  for stacked boxes, stores overridden (their copy) area from their data
*   📍 location for a place in the world, or other
    *    ≣ 📦  for stacked boxes, stores overridden (their copy) point from their data
*   / parent trait
    *   ≣ 📦  for stacked boxes, this is the ≣ stack owner
*   / 𝞫  the beta trait
    *   ≣ 📦 ∫ for stacked boxes, stores overridden (their copy) selection
*   ℤ 𝞫 counter
    *   ≣ 📦  for stacked boxes, ↪  turn this was last updated on