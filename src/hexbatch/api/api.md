# Library Operations


## Calling the api

When an api call is made, it’s executed the next turn. All api calls need a caller trait. And optionally can have a trait to receive the output.. If the api call has arguments those are supplied also

If the call is made outside of the library, then can specify how long to keep the results in the queue, then the gui associated with the call can be checked to see if the api call is done, and to get the results

Since API calls are not completed instantly, there is a way to track what is going on with them. Job guid is the guid of the api call




```
>get-job-status job-guid
>get-job-data job-guid  (returns json data) 
```



### The eight parts of an api call



*   🔤 API command, short name, 🔤 🆔 guid , or full name, or alias name
    *   **The default language of the api call is set in the library preferences**
*   / optional language for call, short name, 🔤 🆔 guid , or full name
    *   **if not using the default language, and are using names for api call and params, then specify language here**
*   / 🌐 the world this api call is going to, 🔤 🆔 guid , or full name
    *   **Used for both nested worlds, and to help coordinated full worlds**
    *   **not used means its for the main world of the library**
*   / the calling trait 🔤 🆔 guid , or full name
    *   **the permissions to do the api, and the rate limits, are taken from here**
*   / optional recipient trait id 🔤 🆔 guid , or full name
    *   **if not given, then the results are only stored in the queue, if keeping the cache**
    *   **This output may be delayed by more than one turn if the data takes a while**
    *   **A trait can only be waiting for one result at a time, its an api error to use it again before the api call is finished**
    *   **the caller and recipient can be the same**
*   𝚫 json argument list
    *   **params names can be trait 🔤 🆔 guid , or full name, or alias name**
    *   **binary data in the params must be base64 encoded**
*   𝚫 json metadata
    *   **echoed back out to the caller without any processing**
*   🛈 turns to cache
    *   **If you want to keep the results around, put in the number of turns to keep this. Usually for polling later. Default is 0**
    *   **If > 0 then the results are stored in the queue**


### Queuing and storing the API calls (table)



*   🔤 🆔 guid for the queued api call
    *   **to related to this call cross world**
*   / trait for the api call (context)
    *   **store the trait id , so it does not matter what language the call made in**
*   / recipient for the api call
    *   **must be registered in a 🌈 segment**
*   🌐 / World trait the api call belongs to
    *   **if the api call is for a nested world **
    *   **null means api is for default world**
*   ◓ / Shard trait the api call is waiting on for a response
    *   **this is only filled in when waiting for something to complete past one turn, helps with synchronization**
*   🛈 turn the api call received
    *   **the integer pk keeps it organized **
*   🛈 turn the api call completed
    *   **some multi page selections or remote commands can take a long time**
*   🛈 turns to cache results (default is 0)
    *   **starts after the call completes**
*   ⚑ status
    *   **waiting, started, finished **
    *   **The only time started flag is used is when the call is not complete by the end of the turn. Otherwise waiting or finished **
*   𝚫 json incoming
    *   **all params are stored here, since binary data is base64 fields inside, anything , including file uploads and images, can be here**
    *   **the param names are replaced by trait ids, so it does not matter the language the params were made in**
    *   **metadata is stored here too**
*   𝚫 json outgoing
    *   **only used if cached**
    *   **results are stored as json**
    *   **the json keys are the trait guids of the returns, its converted to the preferred language when displayed or the data sent**


## API Permissions



Every API call has its own trait. Which means api calls can be managed by the regular permission table. Since all API calls need a context, the permissions are calculated by the context being able to see, read and write to the trait of the API call it is making. A world can control which context do which api calls by adjusting the permissions

However, when making API calls from outside the library, the hosting software can pick literally any trait from that world to use as the API context (such as the root, or any sudo, or any other trait). An independent world depends on the hosting software to verify users interacting with that outer layer, which is beyond the control of the library here, and the hosting software assigns its own users at least one trait to use the library with. Perhaps the hosting software creates a new trait for each user, and then stores that trait guid in the user data.

But a nested world only has the inside of a parent world to manage its access. Which means it is up to the parent world to figure out which traits inside of it can do which api calls to the nested world. It needs a standard and safe way to ask the nested world to create sudo traits, and then the parent can give the trait guids generated by the nested world to the parent’s traits that request them. And that access is calculated by how the parent traits have permission with the nested world id. When a nested world is installed in a world, it's given a world id that has the same permissions, by itself as a new user level trait id.



*   If a trait in the parent world can write to the nested world id, then that trait can request a sudo guid to do privileged api calls on the nested world. Even after having the correct guid to make calls with , only traits that have current permissions to write to the nested world id can make the sudo call
*   Any trait that can read the world id can call that nested world’s api to make a new user-level trait, so it can start to do regular api calls. Just like the sudo calls, the traits that have these user level guids can only call the nested world API if they currently have read access
*   And any trait in the parent world that cannot see the nested world trait id, cannot make any api calls, regardless if it has the correct sudo or user guids for the api context to the nested world

    This is all enforced by the fact that a nested world’s api access is only done through the parent world. There is no way to directly call the api of the nested world. So, to handle calling the api of the nested world, each api call has an optional world id it can use to direct the call to the nested instead, and that is where the permission checks are done

When the access trait are generated, they have to be stored as strings (full name or guid) in the db as part of box 𝚫 json, instead of linked to by the primary key of the trait. Even though the nested world traits and segments are stored in the same db tables as the parent ones, traits and segments across different worlds cannot interact. The only way to transfer traits and segments across those world boundaries is to use the regular import and export apis




## System Definitions

Each api call is assigned a trait of its own, and the definition traits have their own family which follow the api call relationships. Because it's a trait, selections can filter for traits related to specific api calls. Each different param for an api trait also has its own trait. Api calls return json in key value pairs.Additionally, the api names, each of their param names, and each of the return keys can have aliases. The aliases allow both internationalization and allows defining short aliases on the command line.

Aliases have their own traits, which are the direct children of the traits they are aliases for. Each of the trait types: api, param, return, alias are not allowed in the 🌈 table at all. Instead, the api, returns, params, and aliases are defined in the api table

Using the trait families, it's possible to walk the tree down to build up all the api

In addition to the API calls, there are several system defined traits, which always have a constant name and purpose, which are defined here. These trait types are just _system_

The table also stores documentation in each language


### API Definitions (table)



*   / trait id
    *   **The trait id of the command, param, return, alias. or system trait**
*   / alias of trait id
    *   **if this is an alias, then it points to the parent**
    *   **the trigger will fill this in based on the inheritance of the alias**
*   ⚑ type_of_entry
    *   **assigned by the trigger based on the trait type, used to easy reading of the table**
*   ⚑ language flag
    *   **can be null for original entries, otherwise is the iso language code**
*   ⚑ regional flag
    *   **can be null for the original entries, or null for a language without region, otherwise is the iso regional code**
*   ⚑ required flag
    *   **Used in the api call parameters, and returns**
*   🔤 name
    *   **This is either the original name (with null language) or the alias**
    *   **system types do not have aliases, but can have docs in different languages**
    *   **names cannot be duplicated in the same language**
*   𝚫 json docs
    *   **the docs for the entry. For aliases the docs are in the local language**
    *   **docs are always plain text, but organized**


## Mills as users calling the command line

While any trait can call an api while operating inside an element and such, doing command lines requires using the guid of a user mill registered in the system. User mills can have boxes to store user data, although at this time there are done listed out


## Synchronizing Segments Between World



Worlds can be set to sync on one or more selections at the same time. The selections can have overlap or be different. And the selections can be any range or complexity. Sister worlds sync with the root trait, for example, while nested worlds sharing the same host can select on traits or tags used to signal between the different worlds. To help with this, there are some api calls



*    sync start &lt;selection>
    *   Enable selection to be synced
    *   returns the guid of the selection trait, use this as an argument for the other api
*   sync stop &lt;🔤 🆔 >
    *   Stops syncing
*   sync export
    *   ⚑ of full will render the entire selection as json to be exported, with all data
    *   ⚑ of diff will render the patch of the changes since the last turn specified
    *   🛈 turn number, to be used with the diff option
*   sync import
    *   send in the rendered json file from another worlds export
*   sync trim
    *   trims older turns saved up, or can also be called when the last call to generate a diff for a turn is made
*   sync history
    *   gets information about a sync import done already

When the synchronization is made, it is a new segment so it has its own trait and guid, and can remember the selection for it, as well as its start turn. Each turn the sync has a child made which is used to link to the diff table

Each sync export has its own hash, this is used as a handle when being imported


### Syncing Diff (table)

When synchronization is turned on, then turn by turn changes, done in this world, need to be tracked, and recorded.



*   new and removed **_traits_**
*   new , changed, removed **_segments_**
*   new, changed, and removed **_permissions_**



Data



*    trait id of the synchronization
    *   **The synchronization this belongs to, since each synchronization has its own child made each turn, then the turn this is for is linked through the sync trait id here**
*   / trait of what is tracked
    *   **Each thing in the selection has its own row when changed, added, or removed**
*   ⚑ created, removed, changed
    *   **If this trait is new to the selection this turn, its created, if the trait used to be in the selection last turn, its removed, else it's a change**
*   𝚫 json of the changes
    *   **if anything changes in the data table of the segment, permission or trait then its json of the changes is here**

When accepting incoming synchronization data. The new data is made into its own segment, before it's sorted out, and it will check to see if the same hash has been done before (it goes through and does the hash again, on the incoming json, and compares it to the json’s stated hash, to check)

Then the changes are played back. This is done at the end of the turn, where the data is overwritten in a first come first server, if more than one sync file comes in, and has the data different, then that last data played is the final authority.

Only traits that would be in the selection are played, all the others are discarded. There is data to show how many things are discarded and overwritten


## Importing and Exporting

Sister worlds need to export and import stacks, when a stack is exported, then everything on that stack, and all its children, and all the stacks associated with it, if they are dependent on that, are removed from the data table in the segments and put into json form. The traits and permissions are not erased though. The export is just like, or very similar , to the syncing json.

When a world imports the stack, it should already have all the trait information through syncing, as sister worlds will always sync anything to do with traits. The importing world adds the json import on to its segment table


## Filterings Between Worlds

The other way to get data from a world, other than syncing, is by using filters. Which map the information from a selection to a json data dump and returns it

The api to help with this filtering takes a selection, and the selection has an action to map certain things to a json key, and the json is assembled following the selection tree as its rendered per page. The selections above have the map functionality for this

The json is returned in string form, there is no file option to save it, that is up to the caller. Since this is a regular api function call, this can be used in library to pipe snapshots of arbitrary data relations, mapped to a structure something else understands, and the json data can be piped to logic, outside commands, etc

A world can be observed from the inside by having an instance inside of it, and then it regularly creates filters using tags or other things it recognizes, and those filters can go back through a remote call, thus giving a birds eye view, and other data. It's possible to navigate through a world like this

Example of a filter: a series of shapes and colors that map to tags and instances


## Turn Sequence (older notes, needs updating)

Blow by blow details

_(insert turn details here)_

_Older turn thing: really, really out of date, and using different concepts, but use as template to make new run order_

_Time in HexBatch is marked by turns. So when describing what happens in a turn, then all of history can be understood_



*   _Get list of all signaled objects_
    *   _Do signal coordination with the objects, turn signals on and off_
*   _Update all element read-buffers; clear all write-buffers and reset dirty bits_
    *   _Trait commands going to the unregulated urls, sockets, command line etc, don’t send until all the elements run_
    *   _There is a difference between trait commands that write to a safe socket (sockets that do not change data outside the trait) and unregulated sockets. Any unregulated sockets or commands can’t be rolled back if an element fails. So they only run after any chance of failure on the element level is eliminated _
*   _For each signaled object, ordered by active interface RUN RANK  _
    *   _Find each interface elements that:_
        *   _ Is on an active step _
        *   _Can glom onto all their params and targets_
        *   _Run the element once (the bookends run with it)_
        *   _If a trait is signaled, it stays signaled until the end of the turn_
        *   _Objects that are signaled if traits are signaled will be signaled next turn, and not this turn. _
        *   _Elements that are added or removed now will actually be added or removed at the end of turn so it does not modify the active lists_
        *   _When an element data from a-register or s-register is read, all the readers get the same data as what was set in the read only buffer_
        *   _When the element data is written to or updated, then the write goes to the write buffer and the dirty bit is set_
        *   _If another write attempt is made on a dirty data, then the write will fail and that will be an error for all instances that have steps interacting with that element_
        *   _An error for an instance means that_
            *   _ all the elements that were involved with the step have any data they wrote thrown out_
            *   _All of the elements that were involved with the bookends firing for the now invalid step have what they wrote thrown out and are un-signaled_
            *   _If the instance has a registered error object, that object is signaled, and all signaled error objects will run at the end of the turn, but cannot write to any elements that they do not own_
        *   _All direct object properties are always written through elements associated with the object automatically, so any object properties are subject to the same rules_
*   _Run any error handler or final handlers_
    *   _They can only write to elements they own (but can send out data to outside the world)_
*   _It is here that the api will not process any requests except for logging and process signals and coordinating the management of a turn_
    *   _TODO add api for turn management so that things can coordinate to break up the data tasks_
*   _Send any unregulated commands from traits_
*   _Update any signal coordination because of signaled elements_
    *   _Set for signalling any object that will be signaled_
*   _Update any element changes_
    *   _Save write buffers to the real data_
*   _Update any object areas, world membership,times,direct properties_
*   _Update object group memberships due to changed permissions_
    *   _The object groups are set for the next turn, and are only changed at the end of turn each time_
    *   _If an action from the running of the elements add or remove objects and elements, this is updated now_
*   _Update any interface, member or method lists now_
    *   _Steps are incremented, labeled broke, or are reset from commands_
*   _Turn off all trait signals_
*   _Turn off all object signals, then turn on the objects that will be signaled for next turn_
*   _The api is open to all calls again_

_Notes :_

_deleted things are still fully functional during the turn it is deleted, although any api request that uses the deleted item as a context will be rejected automatically. Other things can still read and write to it. Actually deleting items should be almost the last thing done in a turn_