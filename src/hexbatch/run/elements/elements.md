# ♟ Elements, a stack which does a lot of work

Elements read other elements, keep state, set tags, execute commands and api through their logic, and update themselves to pass on data to other elements that read them

Elements only run in shells, and only instances 𝞴 can carry the gems into a shell, and only gems can store elements

Elements are only in the base mill state when outside a shell execution. But, elements, being a stack ≣, can have static data to remember things between executions, but this is shared by all spun up elements. To store some data distinct to the element in an instance 𝞴, the element can write to the instance box ,and read its instance box. The instance dynamic data will be unchanged when it enters a new shell execution.



There are two new types of structures that are used to make an element mill : mainly



*   gloms read data from other elements
*   targets give a place for other’s gloms to attach and read


## ☎ Gloms (reach out and touch something)

A glom is a segment that contains a selection. Its value when filled can pass to boxes on the bus or activate logic

Each element can have no,one, some or many gloms

When it is the element’s turn to run, its gloms will see if it cannot find a target (using their selections). The target needs to be in the shell or up above in the context chain of shells. Once the target is found, the data from the target is put on the bus through the glom connection . The bus can store this into one or more boxes, and also the bus entry will be active on the logic end, so the logic can start when a target is found

Each glom can only connect to  one target ◎ , each turn. Gloms will always try to connect if their element is on

The selection a glom uses can be either a specific trait id, or use a combination of



*   The shell that the target should be in
*   The gem that the shell belongs to, that the target should be in
*   The tags of the element the target belongs to
*   Other selectable things about the element
*   selectable things about the instance the element of the target belongs to
*   anything selectable about the target descriptive id

@spec put in which segment data this uses


## ◎ Targets (any trait can be a target)

A target is simply what gloms look for. Targets can be tags, the mills that define the elements, boxes that are inside the element . A target must be inside an element whic can only be in the same shell or the context of running shells that the glom's element can see


## Element timeline in a turn

It's important that all the elements in a shell context have the same view of all the other elements. So there is some care that when some elements are done with the turn, but others are not yet done, that all the tags and information is seen by all, as they were at the beginning of the turn. At the end of a turn, all the visible tags are updated to what they were changed to during the turn. This is done simply by having an old tag count on each stack bus



*   If there are any waiting callbacks for async commands (done with the logic), then their returns are filled at this time by them
*   All the elements in the same master shell context have their gloms scan for targets, if found the target data is put through the bus
*   In no particular order, all the element logic is fired, but no tag changes are yet visible to other elements, until the next turn. This is done by having everything reference old counts, when looking at other elements and shells
*   The old tag counts are set to the current tag counts