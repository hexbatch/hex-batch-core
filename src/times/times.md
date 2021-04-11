# ⌚ Compiled Times data structure (table)

Times are stored in crontab and related json formats, which is horrible to lookup times on the fly. So, the stored times are compiled a ahead of time, and linked to the rules

It is a very simple table


*   / 📦 link, as these store the times as json
*    🛈 unix timestamp of start of range
*   🛈 unix timestamp of end of range

A json time in a box can have many ranges. To get the context or links to this time range, then the detail can be found by going to the box

