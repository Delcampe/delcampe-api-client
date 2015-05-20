  * ResponseSimpleBulk is an object, returned by some API functions, with 4 datafields :
    * **status** : _boolean_ : true (if call ok) or false (if call not ok)
    * **errorMsg** : _string_ : message giving you more information on the function call failure
    * **data** : _array_ of [ResponseSimple](ResponseSimple.md) objects
    * **personal\_reference** : _string_ : giving you the personal reference of the item (if relevant)


  * If **status** is _true_
    * All items were correctly add in process queue
    * **errorMsg** will be empty
  * If **status** is _false_
    * If **data** is null
      * **errorMsg** will be filled with relevant information : not authorized to call this method, too much items to insert,...
    * If **data** is not null
      * **data** will be filled with [ResponseSimple](ResponseSimple.md) objects. Loop on this objects to detect the ones with a **status** set on _false_.