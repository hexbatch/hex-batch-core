# Exceptions

Exceptions are a segment type , not belonging to a mill, which can store data in any of the columns which is used to give information about the event. The segment-type is called exception

Exceptions can be thrown by logic, when that happens, no further logic for that mill is done during the turn, there is no roll back from earlier operations


When an api call goes wrong, with the parent trait being the caller of the api, and the context being in the alpha trait , there can be given a bus box reference to store the bad api call message


