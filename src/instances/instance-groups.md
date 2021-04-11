# 𝝮 𝞴 Instance Groups, a special collection

There is a special kind of group that manages collections of instances. This is a specialized mill, with certain boxes, and state

It is these instance groups that do the collecting and resolving of instances. As the membership here always starts with a selection of instances and interfaces. These interfaces can construct an arbitrary collection of deferred promises, instances and more interfaces.  A group status can fail if not all of these are resolved. See interfaces, and deferred promises below.


## Group States

Each group has a single state at a time. This is a tag of system type instance group state, whose associated value is a system tag that can be one of the following (they do not inherit from each other, but are siblings)



*    **init**
    *   The group is brand new, or has not begun automatic collection yet. Or manual collection has not begun.
*    **gathering**
    *   actively collecting or resolving the items added
    *   If gathering 𝞴 instances that are not in the shard, or there is a wait list for them, then this state will stay in gathering until they are moved and here in the collection
    *   interfaces need to be called to get other 𝞴 and deferred promises
        *   instances can create a combination of other instances, 𝞴 and 𝞇 deferred promises, all of which need to be run to produce the final list of 𝞴
    *   deferred promises need to be run, the 𝞴 list gathered from a well known static trait after that promise trait has finished,
    *   gathering might take several turns, or run into rate limits, so have to be paused, there should be a flag to skip gathering for n turns if that happens
*    **success**
    *   all the items have been gathered, and all the promises resolved, and all the interfaces have resolved to instances
*   **page-is-ready**
    *   If not complete yet, but has filled a page, then this works like the success
*   **fail**
    *   a deferred promise failed, an instance could not be used, permission error, an exception was thrown when using the api to add, or time exceeded
    *   if a page fails, then the next page attempt does not resume until manually instructed
*   **paused**
    *   The gathering is halted. This can be automatically done, or manually done


## System settings

System traits can affect group state. These are boxes with the system tags below



*   max turns to collect
*   delete copies when done
*   selection of what it can create
*   selection of what it must find


## Large Selections

A selection of instances can be divided into pages, each having the same number of instances. If that case, when there are enough instances to meet up the page, the page is ready and can be used by the promise

The page size can be any number. But if no page size is set , then the default system selection page size will be used. And no page can be more than the system page max size for a selection. This means there will always be a page, and there is a limit to how many instances can go into a promise at once

Each page when ready, is copied to a new group with active state, and it is this active group which is used in the promise


## Managing Instances



Instance groups during collection move the instances to the same shard and location, so that all their areas overlap at least some. This overlapping area is called the intersection of the instances. It's only when there is a valid intersection for all instances that the group moves to success state

After the promise ends, if the auto destruct flag is set in the box; the group can try to auto delete any instances made during an interface constructor call, or created during a deferred promise

The options set for this will tell it what can be created or what must be found. For the things that are found, then the transfer of the instance will start , and the wait queue on the instance will be togged to include this group. And then  flocking, scattering and conundrum statuses can be used, see the promise area for more description