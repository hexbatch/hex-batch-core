# 📎 Hooks

Hooks react to events, they are placed, and wait for something to happen. Hooks can listen for api calls being made, instances being in places (or not being there at all), or listen for any other change throughout the library. The hook can only listen to traits it has permission to see , and sometimes needs to be able read them also. A hook can have a few different forms

For sister worlds, the hook setup data is considered static, so is shared automatically unless the creator limits the operation to an area


## Types of Hooks



*   a deferred promise that will run anew each time the hook is activated. This promise can do anything and run for any length of time. If the hook is fired again, then a new promise is run at the same time as the old, if they overlap. Rate limited will keep such overlapping hooks from running away in resources. This can listen for any changes
*   a limited deferred promise which cannot write out instance and element box data when succeeding, sometimes we need hooks that guarantee to not affect the world around it. This can listen for anything. Often this type of hook is used for logging or passive listening, or monitoring to a third party outside the library. These deferred promises can only run for one turn. So the logic is normally simple, and most likely just sends the data out
*   a mill or stack which uses some predefined traits in boxes and buses to limit api calls on one or more traits. This can be used by the system to rate limit everything, or by a specific family of traits to control how they are used by other traits. This can only be used on api call listening


## Types of hook events


### Timer (turns or real time)



*   uses a box setup to have the :filter-time-rule trait description to store the time rule(s)
*   when it's a turn that matches the time rules, then the hook will fire
*   Real time will fire if the beginning of the turn is inside an allowed time range, so this is only calculated each beginning of turn time for all hooks at once
*   Turn time will of course fire if the turn number matches up the rules
*   Hooks can fire each and every turn, or only occasionally, however needed


### Presence



*   uses a box setup to have the :filter-presence trait description to store the selection link. This finds 𝞴 instances, or anything else selectable. The selection can select on api , position, tags, mill type, a gem or shell that is currently running, etc
*   Can also select to only look at a certain area
*   Non-Presence
    *   Selections can have negative logic,so same but uses a negated selection
*   simply selecting on api calls to wait on, and then filtering for the traits, will create a hook for api calls. This type of event cannot block api calls though, only react to them
*   Requires the ability to see the traits being monitored
*   Can do anything they have permissions for  in response to what they see
*   Hooks can listen for dynamic or static changes. The difference is that dynamic changes do leave the world. So a hook listening to events for dynamic changes is propagated and active in each sister world, unless the creator limits this (rate limits apply too). While a hook listening to static changes is only needing to be in one world.
*   Presence can be used to listen for inter or intra world signals. Create an interface and it can be reacted to. Or simply use a tag state on something well known
*   Other use examples are
    *   Setting up area for things to be teleported to another world (older concept of doorways)
    *   Ejecting traits from a world if certain conditions exit
    *   Automatically delete a trait if a condition exists
    *   Per Promise Hooks

            Sometimes one just needs that extra bit of oomph in a promise, hooks latched on to a promise can update things before a promise ends, for example, or cancel a promise when rules set up are violated



### Rate Limiting



*   Similar to presence, but no deferred promise will run here. Uses system define traits in the bus to manage rate and limits
*   Requires the ability to write to the traits being rate limited

Unlike the other hooks, rate limit hooks are set but not run like normal. Instead, they have some static boxes that are used to inline decision making about whether a trait can do an api action. Further logic can be followed up on this, for example adjusting the limits for what is being watched, by other hooks that can select on the static traits changing in value

To set up the filter for the rate limit. The creator has to have write privileges to what is being watched, a selection string that uses a set of api traits to pre-filter, and enough static data set with the following traits

The api hook can be for a single specific call or any call in a family of api commands. Because the traits for api calls have the same family structure as the commands themselves. For example, to include all the api calls for a type of operation, then include the parent for all those apis as the trait id


#### static values for rate limiting



*   rate-limiting-inline-counter
    *   This is incremented this when the hook is activated
*   rate-limiting-inline-limit
    *   Do not allow operation past this limit
*   rate-limiting-inline-last-reset-turn
    *   Keep track of when the counter was last adjusted

With this setup, it's possible to set up rate limits that can be adjusted by any mechanism that ultimately changes the counter or limit above. It's also possible to select on the filters that have low or expired rate limits, as well as do other operations with normal selects and edits


#### Multiple Hooks on a trait

If multiple hooks are deciding if a trait can do something, then if anyone  decides “no”, then the trait cannot do whatever it was going to do


### Listening To Life Cycle Changes

Hooks can run when combining pre-filtering api calls that signify life cycle changes (for instance new creation, or world stages) coupled with a target of what they are watching for.


#### World Bootstrapping

When a world starts up, there can be flags set in the hooks to run those at different lifetimes in the world (startup, dividing or merging sisters, embedding, saving, restoring)


### Logging

A hook that is guaranteed to not change state outside of itself. It's allowed to use commands to send data to the outside, and can update static data in itself only . Its selection can include an api pre-filter, but does not have to. It can only take 1 turn to run

The advantage of registering a logging hook, is that the system knows data will not be changed, and knows its safe to run this, without altering data in others when they should not be changed, or slow down the turn