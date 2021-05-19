
Should be a single api for all mills

mills have logic,boxes, busses and can pushed and popped from a stack that is created for each mill

so, ideally, I can do m = new mill() ; m->logic->set ; m->bus->peek ; b = m->box.new_box; b->set(a)

Anything based on segment data has the same base class, which holds members for all the different structures

non segments are: permissions, traits, timers, api data structures
everything else uses this base class.
Some methods of this base class is : create, destroy, save