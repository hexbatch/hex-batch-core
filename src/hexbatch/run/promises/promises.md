# 𝞇 Promises , where 𝞴 are gathered and 🐚 run

A promise is always made by an api call, or hook, so it uses an interface or instance as the main context for permissions when gathering

The promise gathers things to run. It works by selecting instances, or things that resolve to instances, and then when all those instances are collected, it will run the shells of the instances at the same time. If there are any exceptions or errors that are not handled, then the promise will end without affecting any boxes or busses in the rest of the world; so anything that happens in a promise will not affect the rest of the world unless it completes successfully.

While instances can be moved by themselves using api commands to set their location and world, the promise moves instances also. When a promise selects a group of instances, they will all gather together in the same space. All the instances that run in a promise must overlap by at least 1 point in the same world. The area of the promise itself is the area of intersection of all of its instances



Instances can only be in one location at the same time. So, if more than one promise is trying to gather instances, there is going to be a wait queue inside each instance that is requested at the same time. That wait queue is organized on the bus of the instance

If two or more promises share the same promise area (the intersection area of all the instances) then they can run the same instance at the same time . The wait queue is for moving the instance to the promise needing it.



Promises can mark instances on their list as required, if the instances cannot be found, and are not allowed to be created fresh, the promise gathering fails

The movement of the instances is called ↝  wind. Because of how worlds are managed, instances for the same promise must gather in the same ◓ shard (and of course the same 🌐) . For worlds that share instances, when a promise wants to gather an instance found in another world, that instance is transported to the world waiting for it.

A request for each instance is put on the wait queue on that instance. The position number of that request, for a promise,  is used to tell if instances are flocking,scattering or a conundrum relative to the promise. And these are actually states with values for the gathering. that can be selected on by anything that can see both the promise and the interfaces

Promises themselves never have an area and location. All their geography is defined about where their instances gather

Once a promise has successfully gathered and resolved its instances. The mill creation is complete, and the promise to run is made into a ground level stack ≣ . it is at the ground level stack where the copies of the instances are made , to start running. So, after this ground stack is made, the instances can move on and the promise will still run okay.  When the promise runs, that stack is increased by one, which allows the ground state promise, holding the gathered instance copies, to be run again and again using the same instances, which could be already deleted or moved or glued elsewhere.

The ground level stack can be returned before running it, as an interface constructor  can do, or an api command to create the promise can flag. When that happens,  this pre-running promise can be moved or saved anywhere. This kind of promise is called a deferred promise. And it can be run repeatedly with the same instance copies associated with its ground state

While the instances in a promise must always be in the same shard, the promise itself, once created, can be run anywhere

The gathering is done by a type of group that will move the instances to the same place or fail, and the gathering has the same mechanics and flags as a group.If the group is large, then pagination for the selection happens, and the gathering takes longer. Also, the gathering is affected by rate limits set up by hooks. If the rate limits are exceeded, then the gathering is paused. A paused gathering is still a valid state, even if that pause lasts forever. The group mill will automatically resolve any interfaces or instances or deferred promises down to the instances. Once that is done, the group state is **success **, and the promise will start its main shell.

A promise can have, but does not need, a success handler and a fail handler. There can be any number of these handlers, if there is more than one for the type, then all of them are run in parallel.  The handlers are themselves promises, but have the restriction that they only use the same instances as the main promise did. However, nothing is preventing them from spawning other promises, that gather other instances


## Promises on the stack ≣ and how they run

The stack flow chart for a promise and its handlers  is below



*   promise created and starts to gather
    *   The instance group is made first
    *   The main shell (called the body)  is the mill of  what runs the shells for all the instances
    *   the handlers are attached as child mills as the type of handler they are
*   ground stack made once the gathering succeeds
    *   The body of the promise now has its context parent the group that collected
*   When the promise runs, the main body of the promise has a copy put on the stack
    *   The body main shell runs
    *   When the body main shell ends,  not popped from the stack until the handlers are run
    *   The success or fail handlers, if existing, will be created into a ground level , with a main shell , and a next element  level is pushed down onto the stack,  and run as a promise with all the instances available to it, with the context of the promise stack as its parent.
    *   When the handler finishes, if there are no handlers of its own, then it is popped from the stack, the body of its parent promise is popped too.
        *   If a handler has its own handlers then nothing is popped until all the handlers run and are complete. Then we unravel the stack like all the way to the body of the original promise, like above.

When a promise is made inside another promise, that child promise is put on the stack in the context of the promise it started in. That way, there is a clear line of context, and if an ancestor promise suddenly ends, then all the child promises are popped from the stack also.

When a promise fails, then all still running context children promises automatically fail also , on the same turn, however each of the descendant promise fail handlers run first, the newest promise running theirs first

When a promise ends, there is no signal sent, the handlers will be run and can do stuff based on how well the promise did.  If state needs to be saved between the promise and its handlers or children, or outside, then instances running inside the promise can save the state.

The dynamic and static data set to the instances are seen by handlers and children, before the whole promise ends and those changes are seen by the world.

After a promise starts,the instances inside of it can be moved without issue. This is because the promise is now running with copies of those instances

Deleting an instance while it's being used inside a promise or handlers does not remove that instance copy from the promise. But the deletion does make the instance invisible and un-interactable outside the promise , and when the last promise ends that uses a copy, the instance is really deleted in total. Child promises can select on instances of the parent or ancestor  promise, so they use the instance copies also (the copies of the instance on the stack), and the same rules apply.

An instance is free to go to another area, without breaking the promise, as soon as the promise that gathered it runs. However, sometimes an instance needs to stay in the area, and not move, while the promise is running.

The promise main body has an optional box for a selection of these instances that must not move

A promise can be looped, by created a deferred promise inside a running shell, and then storing that deferred promise to be called as needed with whatever logic or element or shell interaction is needed



Promises can be chained, by using the handlers

Promises, before they gather, can be propagated to run in parallel on other worlds . There is an api way to do this

it's possible to interrupt a promise, ending all the running for it and all child promises in one turn. Interrupted promises, and all child promises, all fail without calling any handlers. There is a way to interrupt a promise after N turns, when creating it, if its still running






## 𝞇 Selections and conditions of Promise Gathering

Sometimes logic and decisions need to know how well a gathering is going, and how far from completion it is. Gatherings can be cancelled, or instances shifted in priority, so they go to another promise in a different order. The api to shift the wait queue depends on the context trait having permissions to write to both the two promises being switched, and the instance with the wait queue

When asking for the state of the gathering, this can be resolved to a single number for each turn. We calculate the number using the following rules . We add up the values for each instance being waited on to a sum, then divide that by the number of instances to create an average.



*   if the instance is on the way now, to the promise, without other promises. In other words this promise is at the top of the wait queue, then this adds 1 to the sum
*   If the instance has this promise at the top of the queue, but is not yet on the way (world lag or other holdup, perhaps the instance is stuck in a place for a while, as instances can be glued and unglued to a position). Then nothing is added to the sum (we add 0)
*   For each instance that has this promise not at the top of the queue, then the number is -n for each level down. So, if we are position number 2 on the wait list, this number would be -1 added to the sum. And if we are 4th, then the number is -5


### Flocking , Scattering , and  Conundrums



A selection can be made on the filter of instances coming to the gathering. This can be all the attempted gathering, or it can be a sub selection

Definitions:



*   When a selection of instances are coming to the gathering, and the average is 1 then they are **gathering**
*   When a selection of instances have some coming, and some waiting, and the average is between 1 and -1 (not inclusive), then this is a **conundrum**
*   When a selection of instances are all waiting waiting , and the average is &lt;= -1, then this is called a **scattering**


#### Comparing gathering from turn to turn

The value of the wait queue for each of the gathering instances is important enough that this is remembered for each turn, and saved as world shareable data , in the promise. So, anybody who can see the trait of the promise mill can look up the history of the queue states for each gathering request.  The  traits that remember this change of queue state, have a family that inherits from the trait called state-of-gathering. The first turn is the immediate child of state-of-gathering, the second turn is the grandchild of state-of-gathering, and so on. Each turn count is a direct descendant from the last one, so it's possible to do selections based on the amount of turns you want to look at, or the ranges of turns. Each turn, for each instance being gathered, this value of queue state is stored in a box , with the updates only happening when the queue state changes. So eventually, all the last remembered queue states will be 1 for each instance of a successful gathering. And one can see if a requested instance is slipping away from a gathering, as its queue state for this gathering will get lower and lower. Or one can see if an instance is slowly coming towards a promise, as the queue state gets higher and higher.

The history of each instance is put in a box, one box for each instance, in the group that is gathering these instances

Importantly, one can track the acceleration of change, over the turns

Because of this record keeping one use number comparisons to see if selections of instances are **flocking, scattering or being more or less of a conundrum **

And since this is a selectable pattern then many things, from elements in a branch of a promise, to api calls, can make instant (synchronous) decisions based on this

If you are trying to gather something, and it's not working out , or getting worse after a while, you can launch other actions to help correct it, or do contingency plans . For example, If I am trying to do something, and I notice that after a while some of my instances have stopped coming towards me (and now I can do selection based on the category of “instances that are getting further away since last time”) then I can see what my competition is, or what the issue is , and maybe take corrective steps


## ↝   Winds : internal routing and sharding

↝ Winds are the moving of the instances to different areas, shards, and worlds. To discuss winds means discussing the algorithms of moving instances.Winds are traffic control, moving instances around so that promises can run with the instances they call for.  Winds resolve conflicts where two or more promises want the same instance at the same time

Each ◓ is a segment with position and area inside the world, they are not regular mills. What shards do is that they subdivide a world, such that each shard does not overlap, they touch boundaries, and each point in the world is inside exactly one shard. Shards do not have to be in any sort of shape. Shards are subdivisions of each world. All instances occupy one shard at any time. Ideally, each shard has an even number of instances. To achieve this we re-balance the shards sometimes, redoing their areas if need be. What shards do later is help divide and combine sister worlds so that the work of managing large numbers of things in each world can be shared across many computers at once

When a promise gathers, a search is made for the instances required. If no other promise is moving a promise, then there is an algorithm to decide how to move the instance. The algorithm uses a list of rules to be applied. Because we are only worried about the position of the instance, and whether we can move the instance (because an instance can be used in more than one promise at the same time as long as the instance is in the correct area); we do not worry about if an instance is  currently running in a promise or not. But: can the instance be moved at all, can the instance be moved later, and if there is a wait list already for the instance.



The goal is to get all the instances to share some sort of common area in the same shard, moving the minimum of the instances, if possible

For a promise that has no promise parent, we start the gathering area fresh



1. If there are two or more instances that are glued in place, that do not intersect in the same shard, with no expiration time, the gathering fails. Else, if there is some sort of expiration to the gluing of these instances, we use them (gathering will pause though until enough have become unglued to work with).
2. Else if there is one or more glued instances, and we can use their intersected area. Then their intersection area is the starting area to gather the other instances in . It does not matter if there is a wait for these instances anywhere
3. Else we pick one instance to be the start area. If the trait that is the api context of the promise call is an instance, then we pick the closest instance to that in the gathering. Else we pick an instance at random in the world to start out with
4. For each mobile instance, we add to the start area. We put ourselves in the wait queue, when the queue gets to our promise, the instance will move later. If there are multiple instances to choose from,for the same selected instance we pick the closest one that is in the same shard, then pick the closest one in another shard. If the multiple instances to pick from are in another world, then we let that other world grab one at random
5. If there are interfaces in the gathering list, then we call the constructor of each interface in no particular order
    1. That interface can construct an instance that starts anywhere, if that is the case we check to see if that instance is glued, if it is, and outside of instances we already use that are glued themselves, the gathering fails. Else, we move the area of gathering to this new glued interface
    2. The interface can return another interface, if so we just call it until we get only deferred promises or instances
    3. If we have any deferred promises, we run them first, and they have to all succeed, or the gathering fails
    4. A deferred promise can return a list of already created instances, inside a static box trait of :listed-instances, then we add all those instances to the list of what is to be gathered, using the same glue rules
    5. A deferred promise can also return a list of interfaces, inside a static box trait of :listed-instances, if so, we add it to the list of instances that we call their constructors
    6. we keep on going until we have no more interfaces and deferred promises to run

   For a promise started as a child promise, we must be constrained to use its area of gathering as the outer possible boundary of our own gathering. And of course must use the same shard. And if the child promise selects the same instance type the parent is using, then we use the next stack push of the same instance the parent is using

   When the promise gets to the top of the wait queue its moved the same turn