# ⚙ Logic

Logic rule is activated by a bus trait, a bus group trait, or a parent.  Logic rules use the values of their caller, or something on the bus, with an operator to decide on a value. A truthful value results in something being done

If a logic rule listens to a 🚌 group trait, then it also listens to all its children too, and each of the children will toggle the rule separately


## Parts of a Logic Rule


### A logic rule has a unique trait

_/ 🆔 this unique trait_

Each logic rule always has its own unique trait. This can be traits all related or not_


### A logic rule listens to bus activity, or is called by a parent



*   Can listen for bus group traits, or individual bus traits
*   It will also listen to all the descendants of the trait
*   Each trait and any descendants will call this rule separately , on the same turn
    *   When descendant boxes and buses are also used, this does not include descendants that are on a different stack
*   Will listen to a bus change for any trait or descendants of that trait which is is stored in the ◎ / target column of the segment
*   Will listen to a bus group change for any trait or descendants which is stored in the 𝝮 / group column of that segment
*   Any rule can listen, even if its also called as an arg in another rule that listens to the same or something different
*   A rule part can listen to both a bus and a group at the same time
*   Recursive loops for rule parts is not allowed, a rule cannot eventually call itself again


### A ⚙ 🏁 logic Rule has a set run order

Children run before parents;  top level will run based on priority number

So, the children of the top levels will be called based on their parents order, and once each child is called, it goes through its own children tree first, before going to the next one. The first operand will be called before the second operand , when calling the children


### A logic rule makes a value

The final value of a top rule will be either a truthful or non truthful value

Unlike selections, the operators and values of the parent and children always works with  primitives and not sets. However the two share the same other operators

Truthful Values:



*   logical true
*   non zero number
*   non empty json

    Each rule part, in a logic,  has one or two operands, and an operator

    The value parts of the logic rule is called the **⚙ 🏁  logic triggers**

****

Is at most a binary tree, with the children being walked in order from left to right, so the leftmost operands are evaluated first



*   _/ first operand trait is the 𝝰 trait value in the segment_
*   _/ second operand trait is the 𝞫 value in the segment_
*   _⚑ operator is the operations flag in the segment (see selections for non group operators)_


### Operators for logic rules



*   json-data-from
    *   gets the json 𝚫 from a trait
    *   𝝰 is a single trait
    *   **⏎ **is the json
*   bus-value-from 🚌
    *   gets the current value of a bus
    *   𝝰 is a single trait that needs to be a bus
    *   **⏎ **is the bus’s single or group value (primitive)
*   bus-group-value-from 🚌 𝝮
    *   gets the current value of a bus group
    *   𝝰 is a single trait that needs to be a used as a bus group
    *   **⏎ **is the bus’s single or group value (primitive)
*   box-value-from 📦
    *   gets the current value of a box 📦 𝚫
    *   𝝰 is a single trait that needs to be a used as a bus group
    *   **⏎ **is the json of a box
*   semaphore-value-from 📣
    *   gets the current value of a semaphore. if its firing this turn its 1, else it is 0
    *   𝝰 is a single trait that needs to be a used as a semaphore
    *   **⏎ **is the semaphore value
*   Semaphore Wait
    *   does not activate until the semaphore fires
    *   𝝰 is the trait id of the semaphore to wait on
*   box-location-from 📦
    *   gets the current location of a box 📦 📍
    *   𝝰 is a single trait that needs to be a used as a box
    *   **⏎ **is the geojson of the point, or empty json
*   box-area-from 📦
    *   gets the current area of a box 📦 💠
    *   𝝰 is a single trait that needs to be a used as a box
    *   **⏎ **is the geojson of the area, or empty json
*   data-operation
    *   for any non group operation, some operators do not need both args
    *   𝝰 is a string, number or json
    *   𝞫 is a string, number or json
    *   **⏎**  can be set or primitive


### A logic rule does something based on the value

If the value is non zero, it can choose to do an action. All actions the logic does is push and pop tags on a bus. By default is pushes on its own bus, however that can be changed in a rule to push or pop anywhere it has permissions to_

⚙ ▶   is the symbol for logic actions. A logic action is stored as a different segment than the logic trigger, and more than one action can be fired for each trigger


###  logic action if rule matches



*   Often an action(s) can result in more than one things being altered, from one ⚙ 🏁  this is subject to rate limits, and if not enough rate, then none of the actions will be run
*   When descendant boxes and buses are also used, this does not include descendants that are on a different stack
*   The actions can be selections, the logic action trait which defines the rule is automatically added as a variable to the selection , the actions here can define other variables for the selection
    *   Selections can have vars set via the selection variable operation
*   @ push tag
    *   𝝰 is the tag to push
    *   𝞫 optional associated trait
    *   𝝮 optional grouping trait
    *   ▶ if not pushing on the default bus link, set the action target
        *   any descendants will also have this tag pushed
*   @ pop tag
    *   𝝰 the tag to pop
    *   𝝮 optional grouping trait to limit pops to the grouping tag
        *   children of this trait are included
    *   ▶ if not pushing on the default bus link, set the action target
*   📣 emit semaphore
    *   variant of the push tag
    *   𝝰 the tag to push to the semaphore
    *   ▶ the action target will be the semaphore
        *   any descendants the semaphore has will also have this tag pushed
*   Call Api
    *   calls the api
        *   this includes throwing exceptions
    *   𝝰 is the api trait to call
    *   𝞫 is optional child to gather the param-list which is the params to the api call
    *   ▶ the action target will be the bus trait or group trait  to receive the output
        *   any descendants will also get this
*   Param-List
    *   𝝰 is a child to another param list, or a param
    *   𝞫 is a child to another param list, or a param
    *   **⏎** a {}
*   Param
    *   𝝰 is the box, bus or bus group trait, that will get the json for the call
    *   𝚫 json on this row will have the name , and optionally data to be merged into this parm’s value
*   Selection-Vars
    *   For the selections used in this logic rule’s actions, we may need to customize it
    *   𝝰 is the selection to reference
    *   𝞫 points to a node that does a param-list
*   Copy
    *   transfer or copy a tag (and any properties) from this bus or another
        *   if the tag does not exist on the source , nothing will happen
        *   if this is associated, and the association is not exiting , then the tag is copied, but if the association is already on the target trait, then nothing happens
        *   if this is regular, then the count is added to the exiting count, if exiting already
    *   𝝰 is the tag to copy
        *    if it's a regular one, then the push count is copied
        *   if an associated tag, the association will be copied over
    *   ▶ the action target will be the (source) bus trait to copy from
        *   if has descendants, will do additional copies
    *   ◎ target trait will be the bus to make a copy to
        *   will also copy to descendants
*   Set a Grouping
    *   changes the 𝝮 for the tag, without doing a push. If the tag is not on the bus, nothing will happen
    *   𝝰 is the tag to change the grouping for
    *   𝝮 grouping trait
    *   ▶ the bus to do this on
        *   any descendants will also have this grouping changed

How the segment parts are used



*   / action tag trait
    *   the tag to use in the action
*   / action association trait
    *   the trait to associate the
*   / action grouping trait number
    *   The grouping used for the action
    *   If the grouping has children, on the target bus, then some actions will do things for each child
*   / action target trait
    *   sets the mill or element where we want this operation to be at
    *   children of targets have the same things done
    *   if not target, then the default is our bus


## How the ⚙ is Run for the 🚌



*   After all the buses are run for the world turn, then we organize each logic group and make a list of each logic rule to be called due to bus listening
    *   For ≣ stacks, we use the bus traits, that point to the 🏭 bus positions, to resolve to the proper bus stack entries
    *   Each working ≣ runs the logic based on its context
*   The same bus listening rules can be called for each descendant of what it listens to
*   Organize the bus listeners according to rank, if called repeatedly by a series of descendants, then called walking right to left on the descendant tree
*   For each bus listener , we call its children, in order of arg placement

The running of the pushing and popping is done via an sql statement, if the server runs sql


## Logic data storage

In storage, Logic is divided into the trigger ⚙ 🏁  and action ⚙ ▶ parts



*   / 🏭 mill owner
    *   The mill this belongs to for the ⚙ ▶  logic actions and ⚙ 🏁  logic triggers
*   / 🆔  the id-trait
    *   the unique trait generated for the ⚙ ▶  logic actions and ⚙ 🏁  logic triggers
*   / parent trait
    *   ⚙ ▶  for logic actions, this connects them with the logic trigger
*   / 𝝰 the alpha trait
    *   ⚙ 🏁  for logic triggers, this is the first operand trait
    *   ⚙ ▶ for logic actions, this is the action tag
*   / 𝞫  the beta trait
    *   ⚙ 🏁  for logic triggers, this is the second operand trait
    *   ⚙ ▶ for logic actions, this is the association tag
*   / 𝝮 the grouping trait
    *   ⚙ ▶ for logic actions, this is the grouping trait
    *   ⚙ 🏁  for logic triggers, this is the bus group that will active the top rule
*   / ◎ the target trait
    *   ⚙ ▶ for logic actions, this is used to define the target of the action
    *   ⚙ 🏁  for logic triggers, this is the bus that will active the top rule
*   ⚑ operational flag
    *   ⚙ 🏁  for logic triggers, this is the operator
*   𝝰 ℤ counter
    *   ⚙ 🏁  for logic triggers, is the order which top rules will run
        *   does not have to be set, rules with same ranking will be run however
    *   ⚙ ▶  for logic actions, is the order which top actions will run for the same rule
        *   optional


## Remote Calls

There is an api for making remote calls, while in a run context, and the param that shows where the return data is stored can be the trait for a bus, which will put the return directly to the bus on the stack. Other params for this api call tell



*    what kind of call type this is (http, https, ftp,socket, system command
*   the url, ip or socket name, includes port if different from default
*   optional public or private keys to allow access or verify data

## Copying boxes

There is an api to copy the contents of one box to another (with casting done), this replaces the concept of buses automatically piping one box to another. Now, the bus can set up a box reference, then the logic rule can listen for that change, and then copy the contents


## Bus Trigger Cache data structure (table)

Since the busses 🚌  run before the logic rules ⚙ 🏁 , we need to know which rules to test out later. There can be a lot of buses, and a lot of rules, but the system only needs to know about the buses which ran, who have listeners in the logic rules. These listeners might be listening to a parent also. So, if any bus runs this turn, and will be listened to, its put on this table, which is cleared out at the beginning of each turn



*   / 🚌  bus trait id
    *   **the bus which ran**