# 𝝮 Group Operations

Operations on groups that are on the bus

## Introduction

Collections of traits are tags with associations on a bus. Since all tags can also have a grouping trait, these traits can be used to organize things inside a collection . Theoretically, any mill, or stack, is a group. But often are not used that way. Anytime a mill or stack needs to be used as a collection, there are specific api operations to help with that

Because groups are buses on stacks, they can be copied, they can be passed into sub shells, inside promises,  with their current collection, and then when the shell pops the collection will be unchanged, no matter what happens to it before.

For large selections, groups can paginate , having a page of results at any one time in their collections (see in selections how pagination works)

There are three ways for managing collections of traits



*   through logic rules
    *   These just pop and push things on, this is more used for hard wired groups
*   using api calls
    *    inside a logic action
    *   or api calls being made outside the world
    *   provides more dynamic handling and flexibility
*   using selections


## Type of Operations

Using the api, groups can be merged, split, copied, intersected, or other things

Types of operations to create new groups out of already existing groups



*   merge groups
*   split out groups using selection
*   do intersections : only shared elements
*   do xors : only elements that are not shared
*   filter a group using a selection, to create a sub group that meets criteria

When new groups are created, then these are new mills with buses, but no logic or boxes of their own


## Auto Membership

Groups can automatically manage their members by one or more selections. This allows dynamic changes happening automatically, based on how tags change on the items being selected on



*   Add to the group based on selection
    *   If double added, then the tag count goes up for the member
*   Remove from the collection based on selection
    *   for instance, if a member of the collection changes its tags
    *   only actually removed from the collection if the tag count goes down to zero
*    Copy to another group based on selection
    *   Can pipe traits from one thing to another