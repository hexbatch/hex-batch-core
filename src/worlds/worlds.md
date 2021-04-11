# 🌐 Worlds

Each world has a map, an empty place that has latitude, longitude and altitude ; and a collection of things that can interact in that map. A library, when it starts up, has one map.  This is called the overworld. But there are two other types of places besides the overworld, that go outside of the map


## Mapped Worlds

A mapped world can be used as a way to group entire operations and concepts inside the overworld. But all the things inside of a mapped world are still part of the library and can be interacted with more or less the same



*   A Mapped World 🌐 is part of the library that can be created by any trait
    *   Mapped worlds cannot nest
*   The mapped world is its own segment entry, and can be given permission rules
    *   Access to that world can be given/taken away by assigning permissions
    *   A Mill 🏭 can only go to that world if it has read permissions for that world
    *   The system traits that are the parent of the world, or other system things, have full permissions to move in and out
*   To move a segment, or segment complex to a mapped world, the top segment needs to have its mapped world id set.
    *   This can be done through api call, and can be done by anyone who can read the world, and write to the top segment trait id
    *   To move back to the overworld, then clear the mapped world id
    *   Can move from mapped to mapped without going to the overworld
    *   When a top segment moves to a mapped world for the first time, a child of that top segment is created as a system trait, and anyone who has write permissions to this can set the world id of the segment. This allows navigation control to be given to other things without them having the ability to write to the segment itself
        *   This allows for such things as common portals where if an instance goes into an area, a hook can sent it to a world, if the permissions are set up first
*   A mapped world start out empty, and is filled later by what is allowed
*   A mapped world has its own shard ◓ . This shard is managed independently from the overworld shards. Mapped world shards can have their own rate of splitting and combining, with sisters being on different servers
    *   This means promises cannot cross mapped worlds


## Nested Worlds

Nested Worlds are actually other libraries that use the resources of the overworld to run. They do not have their own database, or system objects, or computer. But can only be interacted with via regular library api calls. Things in the overworld cannot access things in the nested world except through the api , using it as a program outside the library would use it. The api responses are much like api responses for the overworld, so filters work fine, as well as object and mill creation and manipulation done through the api. There are no elements from nested worlds in the shells for example. Nested worlds are much like containers in an OS, they are a guest library using the resources of the parent to run. Nested worlds can do api operations on the parent, using a special address, that is always constant, to direct remote calls. Since the parent is the one that gets these commands, and executes them, the parent can simply substitute in its overworld in place of the constant. Of course, nested worlds can also run while being denied this, and only a subset of the api calls are allowed.

One api the parent provides, which is quite valuable, is the ability to get the address of the other nested worlds, and thus be able to communicate directly with them. Nested worlds can decide to sync a certain trait tree, to use for inter-world communications. They can also talk to the parent this way too, and vice versa

Nested worlds can also call the parent api to construct an instance(s) which can roam around the parent, this instance can call back to the nested world with updates via filters, and the nested world can do actions on that

Nested worlds can have nested worlds of their own. Each world can only be interacted with in the parent world, so cannot skip down into the grandchildren to access worlds. Unlike mapped worlds, which cannot be nested, nested worlds do not have a limit to how many times a nesting takes place, or how deep

Nested worlds all share the same system traits as the ultimate overworld. And all traits in a nested world are descended from these system traits, however the library runs each nested  like its own separate library. If a nested world nests, then the ancestor tree of the next level of nesting comes from the parent nested world (whose traits descend from its parent, and so on to the ultimate overworld)

For rate limiting, all the user defined traits in a nested world are descended by the trait associated with the nested/compartment. And while there can be rate limit checks done by the nested world, the outer world does the first set of rate limit checks, and only if that world okays the rate limits, can that world do its own limiting. Recursively nested worlds do rate limiting with the outermost parent doing the checks first, and then in order from ancestor to descendant, each level of world does it own checks until , finally, the innermost worlds do their checks

At any time, a nested world can be saved as a wholly independent world, and copied and run as its own overworld. Similarly, any overworld can be streamed to save, and a copy can be started up as a nested world somewhere. When an overworld is saved, there is an option to also save all the nested worlds too. So, a system of nested worlds can be tested out, then saved, and run as a container

Nested worlds have their own sharding system. Their shards belong to the host library, but these are managed just like a separate world, and shards can be combined and split to run on sister worlds, just as it were an overworld. A nested world can become its own system of sister worlds. The parent will sync these apart from the rest. And when syncing a parent, its nested worlds are never part of the same sync.


## Portals Between Worlds

Often there needs to be a way for two different overworlds to be able to trade instances back and forth. They can sync on a restricted set of instances, and set up an area in each world where these instance types, that meet the selection, are transported back and forth between the worlds


## Sister Worlds (sharding auto groups)

Sharding and syncing systems for load change are supported by the library. While the library does not concern itself how different instances and databases are spun up or down. It does have the ability to support load sharing once that is provided

The shard ◓ is the basic unit of load sharing here. Each map , be it overworld, mapped world or nested world, is divided across one or more shards. The number of shards in a map depends on how many items are in that map, and how much cpu in their logic they are taking. Because a promise cannot go outside a shard, once things are gathered. Currently active promises are never disturbed as shards are split under high load into more shards, or combined under low load. When more shards are created, the shard area is divided into two, using total load to decide the new areas (so not even splitting using geometrical rules). If necessary promises are moved if they are not bound to an area.



And, if need be, the shards on the map can be split to run on different machines, with each group of shards as their own overworld, regardless if the shard came from a nested, or mapped or overworld. These are called sister worlds, and they are sisters only to the shards of the same map they come from. And these worlds only manage the part of the map their shards belong to. If an object goes off into another shard, which is not managed by one sister, then another sister world will manage that object, because it manages that shard.

When two sister worlds whose shards have an adjoining geographic range get below resource consumption target, then they merge again


## Routers (coordinating Sister worlds)

Sister worlds in the library do not have any real mechanism, by themselves, to coordinate with each other. There needs to be a directory to know where to find an instance, and there needs to be some method to do the exports and imports of syncing . Each sister world has the ip address of the router it can ask stuff about (stored in the system settings)

A router is when the library goes into read only mode. It syncs the data but does not do turns itself.  It has all the data of everything in all the shards of all the sister worlds that report to it, and it has a list of all the outstanding api calls that each sister is processing. When a sister is gathering, or needs another stack not on it, then the router will send the information. When a stack leaves a segment, and goes to another segment, the library has a hook where if it's going to a segment off library, then its moved off the list of its segments, and exported to the router, which will then import that stack to the other world . Each stack (each 𝞴 ) can only be in one world at a time

There are some edge cases to importing and exporting.



*    If a 𝞴 is being transferred, but is not needed any more (the promise ended gathering before it arrived, then the 𝞴 will be returned to its world of origin
*   If the rate limit goes out for the user doing the arg collection, and the 𝞴 is in transit, then still stays on destination server

    Routers can tell which stacks belong to which sisters because the shard trait id is assigned to everything with a stack in the segments, and that shard is mapped to the current sister worlds

    Routers also synchronize the turns for each sister world. At the end of a turn, the sister world sends a notification to the router, and waits to be synced by the router. The router in turn, once all the sister worlds have ended their turns, will collect the sync files and call each sister to synchronize. At this time the imports and exports will be done, as the router makes a list of what to ask for and pass on. Once the sister world is synced, the router will tell it to start the next turn. While the router has all the stacks, the sisters only synchronize the static data, the trait data, etc

    Because the router is not processing its turns, but simply passing data back and forth, its more likely that the database engine will be something like **[https://docs.mongodb.com/manual/](https://docs.mongodb.com/manual/) ** instead of a regular relational database that the library would normally use on the server. As the router will mostly pass on and manage json data

    Each Sister only has one router it reports to




## Hub (managing more than one router for larger sister groups)

For large data loads, there are too many sisters for one router. A hub knows the router where each stack guid ,and each api callback, it at.

When the library goes into hub mode, then it uses a simple data table to remember where the resources are at


### Hub Lookup (table)





*    🔤 🆔 string guid of resources
    *   **The string guid of the stack, api call result, static data, etc**
*   ⚑ type of resource
    *   **Tells what kind of resource this is**
*   list of routers that has the information
    *   **Where to get the information**
*   ⚑ flag to notify parent hub of changes to this resource


### Router Lookup (table)



*   router ip address and port
    *   **How to connect to the router**
*   router type, which sister group this is for
    *   **There can be many different sister groups, some of which are not publicly accessible , like for nested worlds and mapped worlds**
*   router map area
    *   **Each router controls a group of sisters, so has an area of the map it controls. This area does not have to be contiguous **


### Outside API Requests (table)

When an api call comes in from the outside, if there is not a tasker available then it will be queued here, and when the tasker starts on it, the api request in this queue will be erased



*   🔤 🆔 guid for the wait queue
    *   **This guid will be the api call guid that is used for the lifetime of the call, even if passed to a sister , it will copy this and assign the api all guid to be this**
*   𝚫 json , the api call
    *   **includes the entire call with the instruction and data separated for each processing**


### How Hubs Work

When a router needs to get something it does not have, it calls its hub, and the hub sends back the ip address of the other router, then the first router can ask the second router

In a very large network, it is impractical to pass on all the different traits, and their permissions and segments to the hub. However when looking for instances while gathering, if there is a need to do a system wide search for things, and the resources are not known to the router, then the router will ask the hub about this. Once the hub knows it will ask the routers each turn in a list of things if they have this, or that, and then those routers reply back, then the hub will enter the shared resources in its list above, and send back the needed information to the requesting router. For stacks that need to be exported and imported, then the transit will be done while these lists are being filled . The routers keep a flag on the segment parts , and traits that need to have notices sent to the hub if anything changes, using the special hub flag set up for the trait and segment table. And then will notify the hub of any data changes or deletions or permission changes, etc. The hub will notify all the other routers on its list for that resource


### Stacking Hubs

In very large systems, with many billions of stacks, it's not practical for one hub to do all the work, so a group of hubs can have a parent hub, and the hubs will console the parent hub for anything they cannot handle by themselves.


## Taskers (reducing the load from the routers and sisters)

If there is a heavy load, no one server should have to deal with too much traffic from library api requests coming from the outside. Taskers is a version of the library that knows how to fulfill many api requests using the data stored in the routers. For the things it cannot do, it knows which sister to pass on the api request to, and lodges the api call guid with the hub so that if something calls back, to get the results, the system knows which router to get the information from (the api result will be part of the synced data to the hub)

Taskers only exist if there is a hub setup. Outside API requests have two different flavours.


### Read only Requests

These are like selections and filtering or finding api results. The tasker first finds the data in the appropriate routers , by consulting the hub. It then collects the data and does the selection and filtering itself. It reads the raw data by using an api on the library to access raw data; which is normally just a very thin wrapper to the db


### Requests that need segments to do stuff

For anything that is not a selection or filtering or api result, then the tasker will figure out which sister world to place the correct api call to, and will leave a record of the api call guid with the hub, if it exists


### Tasker Pools

The taskers can wait for incoming requests, and if too many are idle, then the number is trimmed down. If there is a wait queue of requests to be assigned to taskers, then more taskers can be added to the pool






## Life Cycles of Worlds

Each world has the following events where the system supporting the library can add direct hooks, this helps manage resources. These hooks are simply normal code execution in the native language of the library



*   Startup
*   Turn End
*   Synchronizing
*   Import
*   Export
*   Shutdown


### Normal Shutdown

During normal shutdown the turn is allowed to end, and all the data for that turn is saved first. Promises do not change status. Things are paused mid stream


### Hard Shutdown

If a world is shut down mid turn, then all the data changes that have occurred so far, in that turn, are thrown out. If there is no time to rollback (server crash) then when the library starts up again it then throws out the partial turn and rolls things back


## World Engines

The philosophy of this library is that the data can be run on different systems, written in different languages, and stored in different database types. Exported , Imported, and Synced data are not implementation dependent