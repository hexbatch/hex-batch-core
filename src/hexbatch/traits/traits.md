#  / Traits

Traits know their position in the hierarchy, have a name, and an id. Very minimal. But, not much information is needed here, as they are simply the bricks to build the rest of the library


## Trait data structure (table)



*   🆔 🔤 GUID
    * Auto generated string guid, can use in api calls
*   👪 / inherited parent
    *  all descend from root. Parents cannot be changed for a trait after it's created
* 🛈 interface depth
    * Auto generated and is the number of levels below root
*   🔤 name of trait
    *   Unique for all siblings from the same parent, any unicode non spaces, non punctuation. Differences between siblings must be easy to read, so no mixed case or 1 and lowercase L, or mixed - and _ , etc
    *   Sometimes auto naming is used, when system created
*   🌐 nested world id
    *   nested worlds have their own traits to manage
*   ⚑ role
    *   a role pins a trait to a certain fate
        *   tag,  mill, stack, box, bus, logic-operation, logic-action, mill-inheritance, mill-organization, stack-part-bus, stack-part-box, selection-rule,selection-action
        *   might be others, for traits that define types of mills which are system standard, like promises, instances, interfaces,hooks,shells,elements, etc
*   ⚑  internal-flag
    *   optional integer which is used to help tie together some internal library concepts to the the system defined traits
    *   System traits cannot be deleted by non system traits
*   ⚑ hub flag
    *   Not used except for routers who need to know when to pass information to the hub
*   ⌚ timestamps
    *   created-at (filled in when created), and delete-at (default null)
    *   can trash a trait, while still keeping it in memory

Different roles of traits can start their inheritance anywhere


## Data layer requirements for basic trait management



*   When creating, need to be able to make sure that there are no recursive inheritance , and need to generate the interface depth automatically
*   When updating, do not allow anything to change the parent
*   Never allow exact same sibling names, and never save with whitespace on the ends
*   When deleting, do not allow if anything is using the trait