# ‼ Permissions

Every trait in the library has three kinds of relationships with every other trait. Sometimes a trait has to have enough permissions for all the traits in an operation to make it work. For example, tagging. Need to be able to write to both the target and the tag traits


## Type of Permissions


### Can this trait see the other trait ?

If not, then the other trait is invisible, there is nothing known about it, and it passes like a ghost.

If it can see the other trait, then it can do at least a minimal selection on it, and apply associated tags to it


### Can this trait read the other trait ?

If it can read the other trait, it can automatically see the other trait

If it can read the target and the tag, it can select on the tags something has

if it can read a box trait id, then it can read the contents of a box

if it can read an api trait, it can set up a filter to be called when that api is called. If can read another trait, it can set up a selection or filter when that trait calls the api as its context


### Can this trait write the other trait ?

If it can write to a trait, it can read and see the trait.

If it can write to a box with a trait id, then it can set the data on it. If it can write to both a tag and the bus trait, it can set a tag on the bus

If it can read the api trait, and can write to another trait, it can rate limit that trait using the api


## Permission rules for multiple traits

Since all traits are in the same hierarchy, can only have one parent, and any two traits will eventually share the same ancestor, then we can set up a rule for a branch of traits

This means that trait A can be a parent, and set up a rule  for N generations of its children




## Permission Defaults and Rule Negation

By default any trait is denied any permission for all other traits. So initial rules for a trait will always grant it something

However, for instance, say that we do not want some of its descendants to have the same rules, we want to modify it. So we add a new rule with different permissions

In general, more specific rules, a longer ancestor path, will overwrite more general rules, a shorter ancestor path

However, a trait needs to have permission to write to another trait before it can set rules for it. When the library starts up, there are traits that are set up with permissions to allow them to set permissions for all the other traits that might be created


## Permission data structure (table)



*   🆔 🔤 guid for permission rule
    *   **automatically created**
*   / 🌐 Nested World id
    *   **nested worlds have their permissions branch after using the same system traits**
*   / trait action
    *   **the trait we are setting the permissions for**
*   🛈 action-depth
    *   **how many descendants we are setting this rule for on the action side**
*   / trait target
    *   **the trait that will be acted on**
*   🛈 trait-depth
    *   **how many descendants we are setting this rule for on the target side**
*   ⚑ permission
    *   **will be see,read, write. Write means can read and see. Read means can also see**


## Selecting on Permissions

It's possible to select on permission relationships. For instance I can select on a targets in a running shell that can be read by a certain glom




## Changing Permission via Selections and Groups

If the context trait to the api call has the proper permissions, can use the membership of a group , or a selection result to change permissions for many elements based on membership in the selection or group

Also, since setting permissions to anything is a listenable api , can set up hooks to listen for permission changes also
