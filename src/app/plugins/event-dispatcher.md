1. __listener__ notifies __central dispatcher__ to listen __kernel.response__

2. __kernel__ dispatch __kernel.response__ to __central dispatcher__

3. __kernel.response__ is an __Event__ which has access to read and modify __Response__

4. __central dispatcher__ call a method on all __listener__ to notify them

5. At this point all __listeners__ has access to modify the response

---

# Dispatcher

- Is the central piece of the event dispatch system
- In general, only e a single dispatcher is created which maintains a registry of all __listeners__
- When an __event__ is sent to the dispatcher, it notifies to all its listeners subscribed to that event
- Each listener is notified synchronously sorted by priority and the call includes an event object
-
# Event
- Is created __somewhere__ and passed to dispatcher which uses it to call the listeners

# Listener
- A listener is any valid PHP __callable entity__ registered in the dispatcher
- 
# Event Class
- 

---
# Infrastructure
Create a dispatcher and register listeners to it, is not all, the system needs also:

1. Resolves aliased classes names which allows to refer to an event via the fully qualified class name of the event class
2. 