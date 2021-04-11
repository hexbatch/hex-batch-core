# # Interfaces, a type of 🏭 Mill

Interfaces are the mills which 𝞴 instances are made from. Interfaces define a few standard properties of interfaces, and give a way for all 𝞴 interfaces made from that mill to have a set of shared static data


## Static Information used by interfaces and instances

Standard static (non stack) box traits associated with interfaces. Only the area is really required. These static boxes cannot be overwritten



*   Area
    *   **Required**
    *   **All 𝞴 instances need some sort of geometrical presence**
    *   **Does not include location, and is poly-polygon with lat/lng** .** Instances cannot change their areas but can change their location**
    *   **Does not include the world, as 𝞴 can travel to different worlds after being created**
*   #Area Restriction
    *   **Optional**
    *   **If this is defined, then the area here limits the locations of the instances**
*   #Starting area
    *   **Optional**
    *   **If this is defined, then the area from this interface is used to calculate starting location**
*   Starting Location
    *   **optional**
    *    **Instances can always start in a location in each world, when they are created. This is either absolute to the world , or relative to the starting area. This can also be set to be empty, for a random location in either the world or starting area**
*   Live Time Policy (json)
    *   **Optional**
    *   ** json time struct goes here. If set, then instances of this will only be interactable during these times**
*   Optional 🛈 delete by timestamp
    *   **Optional **
    *   **if set, the interface will be deleted by this time,or turn**

Any other boxes in this mill is automatically shared among all of its instances for static data sharing


#### Calculated Boxes

Calculated boxes do not really hold any data, but are calculated based on the current factor. Interfaces have a couple of these





*   :number-instances
    *   **to get the number of instances the interface has**
*   :number-promises
    *   **To get the number of promises that this interface or its instances are currently involved in**


#### Conditional Boxes: :Time-Turn

Interfaces have a tag showing if they are on with time. The time tag is calculated at the beginning of a turn, but there should be a period process that calculates the time ranges ahead of time. However, there is no guarantee that a turn will be in a certain time slot, looking ahead, so there is that to consider for the lookaheads. But, there needs to be something that makes this calculation quicker



*   :time-turn
    *   **If existing, then this interface is on for this turn, based on the live time policy. Any interfaces that do not have this policy will always have this calculated box. Notice that this calculated box can be missing for those interfaces which are not on at this time**

See the instances for the calculated boxes they use


## Inheritance to create new #interfaces

Can make new interfaces that are pure descendants. However, the calculated boxes will be different. 𝞴 instances that have pure ancestors can be selected using these ancestors


## Constructors

When new instances are needed, a constructor is used. Constructors are 📎 hooks that return 0 or more 𝞴  instances, other # interfaces, or deferred 𝞇  promises. There can be any combination. Each # interface is allowed zero or more of these hooks, and each can contribute none , some or all of the returns. The returns of the constructors is what is actually produced, so its possible to only create instances if certain conditions are met. When there is nothing returned by a constructor, then no instances are created. If there are other interfaces or promises returned, those are run until they, or what they return, is all resolved down to an instance list of 0 or more instances only

Instances can share constructors from inheritance, or create new ones


## Destructors

Each time an instance is destroyed, a hook can run in response. There can be zero or more hooks for this, attached to an instance.

Destructor hooks can do anything


## 💎 Gems

Interfaces may have zero or more gems