# 💎 Gems, a mill that holds Shells


A gem is a mill organization, and only comes to life inside a running promise.

A gem holds both shells to run later, and the logic to release those shells inside a running promise.

A gem can also hold static and dynamic and transient boxes to remember state. These boxes, through their stacked bus, can be accessed by any gems or elements or shells inside the same run context (stack inheritance and mill organization ties)

When a promise runs, all the gems from all the instances gathered, are put on the stack


An interface owns zero or more gems, and when an instance is put on the stack, then each of those gems from its interface are also put on the stack.
The shells are not placed in a stack context until the gem launches the shell inside a promise



A gem can decide if it any of its shells can be run based on its logic , selections of that logic can use context to see which shells are running in which context, and the logic can place newly spawned shells inside another shell that fits a search done by the logic. Its expected that some gem logic would be to look for other gems, or their decendants to see if a contract can take place

The promise always provides the main shell, which is an empty shell which has selectable instances in that context

