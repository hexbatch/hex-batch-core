# 📦 Boxes

Boxes hold and describe data, and give it properties. Since we are describing a template, or a singleton if this is static, we are not concerned about how it copies. That is for later in another structure. How this memory is used is also up to other structures.

All boxes belong to a mill, but also have a unique trait that identifies them. That id-trait can only be associated with this box, so if I have a trait id, or name, it means this box


## Box data storage



*   / 🏭 mill owner
    *   The mill this belongs to for the 📦 box
*   / 🆔  the id-trait
    *   the unique trait generated for the 📦 box
*   𝚫  json data
    *   📦 for boxes, this is where the main info is stored
*   💠 multipolygon for associating area
    *   📦 for boxes, stores the area set by the geojson in the 𝚫 data
*   📍 location for a place in the world, or other
    *    📦  for boxes, stores the point set by the geojson in the 𝚫 data
*   / 𝝰 the alpha trait
    *   📦 for boxes, a possible link to another trait id that describes this box. This is called the descriptor trait
*   / 𝞫  the beta trait
    *   📦 ∫ for boxes, stores the selection trait
*   ⚑ operational flag
    *   📦  for boxes, can a descendant ≣ 📦 write to this ?
*   ⚑ descriptor flag
    *   📦  for boxes, this gives hints on how to share the dynamic data with other worlds
*   🛈 𝞫 counter
    *   📦 for boxes, ↪  turn this was last updated on
    

## Box API for library 




A mill can have data set, read or cleared, used in [user objects](@ref UserAccounts ) 

@spec  Mill boxes need api to set read and clear data from text on the command line or via file contents

