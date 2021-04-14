# Hex Batch Core Library 0.5.0 Alpha.0   


## Introduction

This allows many things to work with maps and space and time...

Hex Batch allows things to be organized with maps. Designed to allow a single map to be divided into areas, each controlled by a different server: this distributed library potentially manages billions of objects, having 3rd party connections, and time and space coordinates, be interacted with and manipulated . Providing an open sandbox type of programming using a type of biological logic which is not your traditional programming platform


## Quick Concepts

A list of all the high level ideas this library uses



*   / Traits
  *   They are simple things that inherit from a single parent which is also a trait. Most of the data structures and logic here use traits as a basic building block to do more complicated ideas. Most of the searches use traits to select things
*   @ Tags
  *   Tags are traits which are used to add logic and organization to things
*   🌐 Worlds
  *   All trait based things exist in a world. There are different world types. Some worlds divide naturally to allow massive amounts of objects to be manipulated in a single combined world. Other worlds exist in the web browser page, or app, and know how to interact with other worlds - a thin client.  Still other worlds are stand alone. And yet another kind of world simply allows containers and special areas in host world
*   ◓ Shards
  *   Divides the world into geographical sections. A promise cannot operate across shards. Shards are important to split up servers that get too busy
*   𝞴 Instances
  *   Are the only such trait based structure which can move about to different coordinates in a world, and have a shape which is plotted on the map
*   \# Interfaces
  *   Control the bounds, creation and destruction of instances. Also a trait based structure
*   𝞇 Promises
  *   Instances run code inside a concept called a promise, which either succeeds and changes things outside of the promise afterwards. Or if it fails nothing is changed. Promises will collect instances from different areas and servers to do work on them. Promises is also how a lot of instances can move around
*   ↝ Wind
  *   How the things in the world (instances) are moved around
*   🐚 Shells
  *   Code is always run in shells, using elements
*   💎 Gems
  *   How we organize and find the shells
*   🏭 Mills
  *   What elements, instances, interfaces, and shells are made out of. These are based on combinations of traits working together. Mills use busses, boxes, and logic
*   🚌 Bus
  *   Part of a mill, helps connect , expose, hide and organize the traits. Tagging and grouping is done here
*   📦 Box
  *   Part of a mill, stores data and has  details for data used in the bus
*   ⚙ Logic
  *   Part of a mill,  has rules of what to do with traits used internally, based on tags and changes on the bus. Can do api calls or call services outside the library to get or set data
*   ≣ Stacks
  *   When a mill produces items, the copies are on the stack
*   𝝮 Groups
  *   Made from a stack, makes collections
*   ♟ Element
  *   Made from a mill template, allows us to do dynamic coding. Elements use gloms and targets , in addition to the standard logic, bus and box
*   ☎ Gloms
  *   Part of an element. Gloms find targets, and then suck that data into a box for use for targets, or doing tag, bus or logic stuff. An element can have 0 to many gloms
*   ◎ Targets
  *   Part of an element. Targets are what gloms find, the element fills up a target with data to be read by the glom
*   📎 Hooks
  *   Run when certain things happen. Helps add rate limits and special logic, some event handling, cron jobs
*   ‼ Permission
  *   Tells which traits can see, and work with, other traits. Everything needs permission to do anything
*   ∫ Selections
  *   Searches, Moves around, and organizes trait data structures
*   📣 Semaphores
  *   synchronizes things running in shells
*   ↪ Turns
  *   Instead of continually moving and doing things. The library does a certain number of steps to process and move things, and then starts again. Each of these cycles of operation is called a turn. The turn number starts at 0 and goes on to infinity , or until the worlds is reset or stops


## Other Data Structures


### ⌚ Time

Time is set by both the clock and calendar; or by the turn number the library is on.



*   When specifying a range of time you can use an array of {start:, end:} in json where these are exact times.
*   When needing to set a periodic start and stop range, you can use the crontab format. An array of  {crontab:} . You can use crontab format for times only
*   For periodic turns, there is a different format related to crontab but , of course only uses turns with each position using a different turn range
*   When needing to set time via the turn number you can use the integer value of the turn. Turns are not used in time ranges in the api, but can be used in the logic of the elements and hook events


### 💠 📍 Area and Location

All coordinates are in lat,lng and use GeoJson json notation for both area and position. Internally, all area is stored in multi-polygons


### 🆔 GUIDS and Identification

Almost all the major concepts mentioned above in the quick concepts individually have their own unique id. These guids are used in the api to specify something unless you use the trait names.

Names can be any unicode (see exceptions below). Since all traits have a parent, you can map out the name, starting with the furthest ancestor. Since everything starts with root, we always leave out root in the name path. Notice that names can be reused as long as two siblings do not share the same name. But siblings cannot have names that differ in only minor, hard to read, details. For example aPP1e and apple cannot be used by two siblings having the same parent. Whitespace and punctuation in names are not allowed. Dashes and underscores are allowed but are considered hard to distinguish so considered as mixed cases when comparing sibling names.



*   Example of a guid 12345678-1234-5678-1234-567812345678
*   Example of a name path red.beans.and.rice


### 𝚫 Data Json Structure inside Traits and Elements

Of course this library stores data. Lots of it. This regular data is always stored in Json format. Since the data will be a mix of many different , and sometimes incompatible things. We do our own typecasting. Since these types are juggled in both the code and sql to fit together when possible , if they are combined into one operation. If a value cannot have something done to it, then its marked as an error

Each value is stored in the following json format :

{data_type:,data_bounds:, data_flags:, data_value: }

any arrays or objects stored directly will be stored as json , and will be seen as json and not a primitive type. However, for storing json, you can add your own json schema, and use the name of that to be your type



1. data_type
  1. signed integer
  2. unsigned int
  3. decimal
  4. string
  5. date/time string format (of a list of standard formats)
  6. base64 string
  7. boolean
  8. json
  9. register-json-schema (this registers a json schema type for use elsewhere)
    *   Geo Json is an automatically registered mime type
  10. [any mime type]
  11. [any registered json schema type]
2. data_bounds
  12. for string, regex
  13. for string, regex flags
  14. for all numbers, min and max, and multiples
  15. for decimals , range on each side
3. data_flags
  16. is base 64 encoded
  17. name of json schema being registered
4. data_value
  18. where the goodies are stored


## Storage of Data Structures

This library is meant to run on servers, as well as browsers and other environments. So, we cannot always have a nice database. Or the database being used might be lacking in features. So, the defined data structures can be stored in many different ways.

To make this work without having to recode anything, there is a data layer which can be dynamically or statically linked into the program. This layer handles all trigger and storage and mass sql queries and updates,  and allows us to not worry about if the data is organized into db tables, or json structures or even plain text files with serialized structures

As a result, in the specification, the things needed for normal db life: primary keys, foreign keys, automatic timestamps,indexes are left out. But, each section does have a list of things that need to be implemented by the data layer . If some requirement is left out of the data layer, then its expected to be implemented in the code


## ‼ Permission Concepts

Each api call here uses a trait id, given to it, as a context seeing if the requested can be done. Since traits are hierarchical , a user for the library will start out with their most privileged trait, and then use that to create or manage other traits that they either create, or are allowed to interact with.

Most permissions boil down to : Can I see the trait ? Can I read the trait ? Can I write to the trait ? But there are some other permissions that facilitate some specific operations. They will be listed later

Hex Batch is not meant to be run alone when different users are managing their own things here. However, it is  easy to add a user auth layer and assign each user to have their own trait guid. It's also very easy to create admin, super, power and regular users with this strategy



