# ∫ Selections


## Introduction

Selections are always identified by their own trait and are stored in the 🌈 segment data.

Selection rules are stored similarly to ⚙ 🏁 logic triggers, so it's possible to build up complex rules. Selection are run on api command, although some of these commands are inbuilt and part of other api logic

Selections always return sets of traits, but can return potentially a huge number, so paging is built into this. With selections, you can get any group of traits using any set of rules or relationships. Selections can be nested, combined and be any complexity

Selections also allow for group calls to the api, to change a series of things at once, and to do mass updates. For example, if one needs to change the permission rules for a large set of things, or update millions of pieces of data at once, or to increment the counters for some data associated with … basically can do any updates. If you need to do large updates, set the amount to page to be very high. Rate limits will not allow large pages to be executed unless allowed


### Variable Segments

For 🌈 segments that use the 𝝰 , 𝞫 and 𝝮 ◎ traits, if these point to a segment-active-paging trait id, then the value of these is resolved to be a random pick of the cached results


## Selection Rules


### Parts of a Selection Rule



*   A selection rule has a unique trait and no mill owner
*   There is always an operator and zero to two operands
*   The parent can call up to two children (a binary tree), and the children are walked in-order, then this is how grouping (parentheses are used)
*   Top selection rules can be found because they have nothing calling them from the operand slots, except for the operation of do-selection
*   Top selection rules always return sets of traits {}, which can be an empty set
*   While some operators can give a value without an operand, there are always one or two operands if needed.
*   when calling a child trait as an operand, the child trait will return either a json value, a number, a string or a set of selected traits
*   no more than two children , or operands , can be called. The operands are stored in the 𝝰 and 𝞫 traits for the segment. The operator is stored in the ⚑ operational flag


### Paging in a Selection Rule

There can be several calls to the same selection per turn, using different contexts. These contexts are dealt with by putting the differences in variables , which are filled in for the selection. Selection rules can have variables in them that are filled when the selection runs. This enables different targets, in a shell, for example, to use the same selection but get different results.  The variable values are filled in with the selection-active-paging data

When the selection runs for the first time, its results are put into the selection-cache. If there is more than one page,  a segment type called selection-active-paging is created, which remembers the variables and current page of the selection, and will run the next page on command




### Selection Rule Operators


#### Simple Operators

_binary operators_

* _math, logic , comparison, json path, bit operators,group operator (max, min),geometry operators_

_single operators_

* _used with first operand_
    * regex (is it in string),json path (is it existing),floor,ceil, round



*   set operators
    *   union, intersection, diff (is in a but not b), symmetrical diff (is only in a, or is only in b) , is subset, is superset
*   set selection
    *   random (get x random things from the set)
    *   splice ( 𝝰 is the set to choose from and splice args are stored in 𝚫 json)
        *   optional arg starting index
        *   optional arg ending index
*   flow
    *   if ,else can be nested
    *   if no branch selected, then result is null
*   group operators
    *   min,max,avg,std,count,sum
*   logic operators
    *   and, or, xor
        *   an empty set is a false
        *   any primitive is cast to truthfulness
    *   not
        *   single operand, can be set or primitive
*   primitive operators
    *   number operators
        *   trig, arithmetic
        *   unary operators → abs, log,ln,exp,floor
    *   string operators
    *   json operators
    *   geometry operators
        *   in area
        *   area operations
        *   location operations
    *   relational operators
        *   &lt; > = etc


#### Operators for generating sets of traits



*   There is a system defined trait for the set of everything
*   if a trait chosen is a selection, then its current page of results is used
*   has-full-path
    *   Filters for traits with this path
    *   𝝰 is trait or {}
    *   𝞫 either a period delimited string, or an array of strings inside a json
    *   **⏎**  all 𝝰 that has this full path
*   has-part-path
    *   Filters for traits which has a path that starts with this
    *   𝝰 is trait or {}
    *   𝞫 either a period delimited string, or an array of strings inside a json
    *   **⏎**  all 𝝰 that has a path that start with this
*   has-guid
    *   Filters for traits with this guid
    *   𝝰 is trait or {}
    *   𝞫 string guid
    *   **⏎**  all 𝝰 that has this guid
*   has-name
    *   Filters for traits with this name (individual name, not part of a path)
    *   𝝰 is trait or {}
    *   𝞫 string name
    *   **⏎**  all 𝝰 that has this name
*   has-role
    *   Filters for traits with this role (trait role)
    *   𝝰 is trait or {}
    *   𝞫 string name
    *   **⏎**  all 𝝰 that has this role
*   permission-x
    *   variants are read and write (if cannot see, cannot do selection rule on)
    *   𝝰 is trait or {}
    *   𝞫 is a trait or {}
    *    **⏎**  all 𝝰 that can be read|write by all 𝞫
*   has-flag-x
    *   variants are operational,contextual, descriptor
    *   Filters for traits with a flag associated with them, each flag in the system has a different value
    *   𝝰 is trait or {}
    *   𝞫 flag value, either the name of the flag, or the number value of the flag
    *   **⏎**  all 𝝰 that has this flag
*   has-relationship
    *   Filters set for being in a relationship
    *   𝝰 is trait or {}
    *   𝞫 is a trait / which is the thing to be in a relationship with
    *   𝚫 json of the row has a object min, max
        *   siblings are 0 level relationships
        *   ancestors are negative level relationships, with parent being -1
        *   descendants are positive level relationships with children being 1
        *    can select a range of relationships at once.
        *   @example to select siblings and parents, [-1,0]
        *   @example to select only grandchildren and great grandchildren [2,3]
    *   **⏎**  all 𝝰 that have this relationship with  𝞫
*   has-connection-x
    *   variants are alpha,beta,grouping,target,mill-owner,parent
    *   Filters set for having a connection in the segments
    *   𝝰 is trait or {}
    *   𝞫 is a trait / which is the thing to be in a relationship with
    *   𝚫 json of the row has a object min, max
        *   0 level means there is a direct link, the segment row with is defined by the trait 𝝰 has the value of trait 𝞫 in that column
        *   N level (>0) means there is an indirect link from the 𝞫 to the 𝝰 using the same column
        *   N level (&lt;0) means there is an indirect link from the 𝝰 to the 𝞫  using the same column, the reverse relationship
        *   N == 0 simply means 𝞫 directly points to 𝝰
        *   @example level 1 means 𝝰 has a trait value in the column which itself links to 𝞫
        *   @example 3 is the same as above but two more steps removed: 𝝰 has a trait value which links to something in the same column, which then itself links to something in the same column, which that one then links directly to 𝞫
        *   @example -1 means 𝞫  has a trait value in the column which itself links to 𝝰
        *   @example the (3) example but switch out 𝝰 and 𝞫
        *   so can do a range of relationships [0,5] , [2,4] [-3,6] etc or just [0,0] for direct
    *   **⏎**  all 𝝰 that directly (or maybe indirectly depending on the min max) have 𝞫 as a value with this column , ( or vice versa depending on min max)
*   order-set-by-x
    *   where x is any column in the segments. This is used usually to order a set before splicing or before a selection ends
    *   𝝰 is {}
    *   𝞫 is the asc or desc or no order
    *   **⏎** set of ordered primitives
*   do-selection
    *   runs a top selection
    *   𝝰 is a single trait that is the ∫ 🏁
    *   **⏎ **{}
*   argument-list
    *   gets the traits used in the current api call
    *   **⏎ **{}
*   get-variable-value
    *   gets the value (a primitive) from the variable given in the api call to do the selection. If this is the next page (after the initial api call), its stored in the selection-active-paging
    *   𝝰 the name of the variable
    *   **⏎ **the primitive
*   json-data-from
    *   gets the json 𝚫 from a trait
    *   𝝰 is a single trait
    *   **⏎ **is the json
*   bus-value-from
    *   gets the current value of a bus
    *   𝝰 is a single trait that needs to be a bus or trait used as a bus group
    *   **⏎ **is the bus’s single or group value (primitive)
*   alpha-counter-from
    *   gets the (integer) alpha value from a single trait or a json array of integers from a group of traits
    *   𝝰 is the single trait or the {}
    *   **⏎ **is an integer or json containing the array
*   beta-counter-from
    *   just like the alpha-counter-from
*   location-from, area-from
    *   just like above
*   data-operation
    *   for any primitive or set or logic operation
        *   if operation produces a boolean expression
            *   if 𝝰 must is trait or set, and 𝞫 is a primitive
                *   if the node right under 𝝰 is group operation on a set, or produces a json array from location,area,counter then this becomes a set filter
        *   if set operation, then both 𝝰 and 𝞫 must be sets or trait
        *   if group operation, then only 𝝰 should be set and 𝞫 is ignored
        *   if logic operation, then 𝝰 and 𝞫 can be anything
        *   if primitive operation, then both 𝝰 and 𝞫 must be not sets
    *   𝝰 is {} ,single trait, or a primitive value
    *   𝞫 is {} , single trait, or a primitive value, or empty
    *   **⏎**  can be set or primitive


## Selection Actions

actions can change the values of what is selected



*   can map the selected values with the selected replacements, and set the selected with a 1:1 relationship (ordering both selections , both selections need to have the same number of values)
    *   maps only the page
    *   the target and replace selections need to have the same page size
*   can set all the selected with a constant primitive , or single selection
    *   only the page
*   can set all the selected with a random choice from a selection
    *   only the page

    The selection action is run on each page of the selection rule that is advanced, and it only runs for that page. So after all the selection rules run, and their results are put into the cache, then selection actions are run. Each selection rule can have 0 or more actions. The actions can be limited to the top-n selections , by setting the 𝝰 ⚑ alpha flag


### Selection Action Operators



*   set-replace-x
    *   where x is any column in the segments
    *   replaces the values of the target and specified column with an ordered list
    *   𝝰 a set of ordered primitives
    *   𝞫 is the asc or desc
    *   **⏎**  (no return)
*   values-ordered-by-x
    *   where x is any column in the segments
    *   𝝰 is ∫ 🏁 selection rule trait id
    *   𝞫 is the asc or desc or no order
    *   **⏎** set of ordered primitives** **
*   json-data-from
    *   gets a single value or an array of values from  json 𝚫 from a trait
    *   𝝰 is a single trait
    *   𝞫 is the json path
    *   **⏎ **set of primitives
*   value-replace-x
    *   where x is any column in the segments
    *   replaces the values of the target and specified column with a single value
    *   𝝰 is the primitive value
    *   𝞫 is the asc or desc
    *   **⏎**  (no return)
*   map-x
    *   where x is any column in the segments
    *   generates a json structure that takes  the values of the target and puts it into a key of the generated json
    *   each selection generates one json where the different maps add a key for each selection result
    *   𝞫 is an optional json path for getting parts of data out to the key, if using a json column
    *   𝚫 json on this row will have the key name
    *   **⏎**  (no return)
*   random-replace-x
    *   where x is any column in the segments
    *   replaces the values of the target and specified column with random values from a list of primitives
    *   𝝰 a set of primitives
    *   **⏎**  (no return)
*   Call Api
    *   calls the api with a command, calls for each page of selection results
    *   𝝰 is the api trait to call
    *   𝞫 is optional child to gather the param-list which is the params to the api call
    *   ▶ the action target will be the trait that receive the results of the api call in the 𝚫 json  , it must have an entry on the segments
    *   ⚑ contextual flag tells how the api will be called
        *   once per thing in the selection, if the param 𝝰 is null, it will be filled in using the selection trait as the value
        *   as an array of values, , if the param 𝝰 is null, it will be filled in as a json array of all the values in this page
        *   as a single command that does not use the selection list
    *   The 𝝰 flag sets how many api calls to do per page
    *   The 𝞫 flag sets how many pages to do api calls on
*   Param-List
    *   𝝰 is a child to another param list, or a param
    *   𝞫 is a child to another param list, or a param
    *   **⏎** a {}
*   Param
    *   fills in a name value for the api call param list
    *   𝝰 is the trait to get the 𝚫 json, it must have an entry on the segments
        *   if 𝝰 is a selection then it runs that selection filling in any variables defined in this parent selection. If that selection is multi-paged, then the parent param calling it will wait to be filled before the api is called
    *   𝚫 json on this row will have the name , and optionally data to be merged into this parm’s value


## Selection Paging

All results for each selection run is put into a cache for quick lookup. But only one page of a selection running is found and saved each turn. So, the first turn has page 0, then the when the selection running is told to go to the next page, the page 0 is erased from the cache and page 1 stuff is put in there

If a selection that is running has more than one page, then a new segment is added of type selection-active-paging, which stores the variables used to call this selection, and remembers the next page to do. When the last page is done, and its actions are run (if any) then the selection-active-paging is kept around until the cache times out for that page

If the selection results are used to create virtual mill parts, then the selection-active-paging is created, even if there is only one page, and the virtual mill parts refer to that to get the values

The selection can limit how many actions is done by the 🛈 𝝰 and 🛈 𝞫 counters, which limit the actions per page, and pages of actions


### Selection Page Cache (table)



*   ∫ / selection trait
    *   **all caches link to the selection**
*   / selection-active-paging trait
    *   **if there is more than one page in all the results, then the cache will remember the active-paging segment**
*   / trait id
    *   **the traits for each selection page are listed in the order returned**
*   page number
    *   **n >= 0**
*   turn this was cached
    *   **used to see how old this is**


## Selection data storage

In storage, Selection is divided into the trigger ∫ 🏁  and action ∫ ▶ parts



*   / 🏭 mill owner
    *   always null
*   / 🆔  the id-trait
    *   the unique trait generated for the ∫ ▶  selection actions and ∫ 🏁  selection rules
    *   ∫ ▶  selection actions are trait type selection-action
    *   ∫ 🏁 selection rules are trait type selection-rule
*   / 🌐 world trait
    *   ∫ 🏁 selection rules, without children, they need to belong to a world
*   / parent trait
    *   ∫ ▶ selection actions, this connects them with the ∫ 🏁 selection rule
    *   selection-active-paging, this connects them with the ∫ 🏁 selection rule
*   / 𝝰 the alpha trait
    *   ∫ 🏁 selection rules, this is the first operand trait
    *   ∫ ▶ selection actions, this is the first operand trait
*   / 𝞫  the beta trait
    *   ∫ 🏁 selection rules, this is the second operand trait
    *   ∫ ▶ selection actions, this is the second operand trait
*   / ◎ the target trait
    *   ∫ ▶ for selection actions, this is trait that receive the results of the api call in the 𝚫 json  , it must have an entry on the segments
*   ⚑ operational flag
    *   ∫ 🏁 selection rules, this is the operator
    *   ∫ ▶ selection actions, this is the operator
*   ⚑ contextual flag
    *   ∫ ▶ selection actions, when it calls the api, to tell how to use the selection results in that call
*   𝚫 json data
    *   ∫ 🏁 selection rules, when they use literal data
    *   ∫ ▶ selection actions, when it uses literal data for args to its operations
        *   will always be numbers or strings, no geometry needed
    *   selection-active-paging, stores the variables (all of them in one json)
*   💠 multipolygon for associating area
    *   ∫ 🏁 selection rules, when the 𝚫 data has geojson area
*   📍 location for a place in the world, or other
    *   ∫ 🏁 selection rules, when the 𝚫 data has geojson location
*   🛈 𝝰 counter
    *   ∫ 🏁 selection rules, the size of the page
    *   selection-active-paging, the current page
    *   ∫ ▶ selection actions, tells how many actions to do per page
*   🛈 𝞫 counter
    *   selection-active-paging, turn this page was created on
    *   ∫ ▶ selection actions, tells how many pages to do actions on

