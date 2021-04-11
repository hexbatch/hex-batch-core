# 𝞴 Instances, a type of limited ≣ Stack

Instances are always created by using an interface mill, whose instance constructor hooks can return a mix of things that always resolves to 0 or more instances

Instances are a single level stack of an interface. When their dynamic memory is written to, outside of a promise, it is never popped away, as the stack is never popped outside of a promise

Inside a shell, elements can write to the static and dynamic parts of an instance; but all the boxes written to are on a single new stack level, for each promise spun up,  and they get popped when the promise ends. Then the instance’s dynamic memory, and the static memory is written to

Because instances enter each main shell as a new stack layer. The same instance can do multiple main shells at once


## Predefined values that instance use


### Dynamic Boxes

These are dynamic , but have a flag to not write to them from a stack child popping



*   location (lat, lng)
    *   **This is a geojson point**
*   turn created
    *   **the numerical turn this was created, each turn has its own trait to be used as a tag**
*   location_to_be
    *   **The location where the instance is to go to next, but is not yet at**




### Calculated dynamic boxes for instances



*   : distance
    *   **The physical distance between the owner of the meta, and the object making the call**


### Predefined Bus Settings for each instance



*   Wait Position for Promise
    *   **If the instance is being gathered by a promise, the current promise it's going to will be position number 0**
    *   **if gathered by other promises at the same time, they can wait, and their queue is numbered after that**
    *   **The wait positions are data to be shared across sister worlds**
*   :unmovable
    *   **if there is this tag on the bus, the instance cannot be moved**
*   :unmovable-permanent, inherits from :unmovable
    *   **If this exists, then when a promise gathers it, the promise has to move to it. Otherwise the instance will move to the promise **
    *   **can also be declared in the #interface**
    *   **there is no time limit for this**
*   :unmovable-promise-lock, inherits from :unmovable
    *   **if this tag is set on the bus, a promise has locked this from moving until it’s done, tag will pop off when the promise is popped finishes**
    *   **tag association on the bus is to the promise on the stack**
*   :unmovable-timed-turn, inherits from :unmovable
    *   **if this tag is set on the bus, cannot be moved until after a turn number, tag will pop off automatically then**
    *   **tag association on the bus is to the turn number**
*   :unmovable-timed-unix-time, inherits from :unmovable
    *   **if this tag is set on the bus, cannot be moved until after a the unix time, tag will pop off automatically then**
    *   **tag association on the bus is to the unix time**


## Instances have a presence in the 🌐 world

Instances are the one thing that can physically occupy the world, and have position and take up space. While interfaces have a shape, they do not have a presence, and no location


### Standard Dynamic 📦 descriptors for instances

These

Instances have a wait queue of promises