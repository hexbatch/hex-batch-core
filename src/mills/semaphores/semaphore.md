# 📣 Semaphores, a type of 🌈 segment

Semaphores control how many things at one time can do something. When a logic waits on a semaphore it does not activate until that semaphore pulses for that turn. Semaphores only are used in the logic and nowhere else. It's only in the logic that semaphores are waited on, and only in the logic where they are emitted

Semaphores can have the following properties



*   it can keep track of the number of emits it is given, and only really turn on after a certain count is reached. Then that count will reset. This count is different for each context its given
*   if the emit command has not tag given to it, in the logic action, then this semaphore is heard throughout the world for anything listening to it
*   if the emit command has a system defined local tag given to it, then only the parent stack and any children of that parent stack will hear this emit
*   the semaphore can only emit up to N things at once, even though there will be more listeners than that. The N chosen is random, so it's possible for something listening to the semaphore to not be toggled on, even after repeat emits

Semaphores are simple segments, and store their information in the following fields



*   🛈 𝝰 counter
    *   📣 semaphores,the minimum number of emit commands needed to toggle this emit
*   🛈 𝞫 counter
    *   📣 semaphores,the number of emit commands given to it
*   / ◎ the target trait
    *   📣 semaphores,when emitting, only this stack ≣  and its children can hear this
*   / parent trait
    *   📣 semaphores,when a context is given, then a child of the semaphore will be spun up and given its own row, so the target and counts is kept
*   𝚫 json data
    *   📣 semaphores, properties stored in two keys, which might be absent: the N key, which is how many waiters to let through; and the remember key, which if true will not erase the count of emit commands each turn