
Should be a single api for all mills

mills have logic,boxes, busses and can pushed and popped from a stack that is created for each mill

so, ideally, I can do m = new mill() ; m->logic->set ; m->bus->peek ; b = m->box.new_box; b->set(a)