  * ItemFixedPriceParam is an object with 25 datafields, containing the data needed to create an ItemFixedPrice item :
    * **id\_country** : _integer_ : (mandatory) id of the country related to the item. See [AuthorizedValues](AuthorizedValues.md) to have details on values for this field
    * **id\_category** : _integer_ : (mandatory) id of the category related to the item. See [AuthorizedValues](AuthorizedValues.md) to have details on values for this field
    * **title** : _string_ : (mandatory) title of the item
    * **subtitle** : _string_ : subtitle of the item
    * **personal\_reference** : _string_ : (mandatory) your personal reference for this item
    * **description** : _string_ : description of the item
    * **fixed\_price** : _float_ : (mandatory) price of a fixed price item
    * **currency** : _string_ : (mandatory) currency set for the item. See [AuthorizedValues](AuthorizedValues.md) to have details on values for this field
    * **date\_end** : _string_ : end date of the item
    * **duration** : _integer_ : sale duration of the item. See [AuthorizedValues](AuthorizedValues.md) to have details on values for this field
    * **prefered\_end\_day** : _string_ : prefered end day for the sale of the item. See [AuthorizedValues](AuthorizedValues.md) to have details on values for this field
    * **prefered\_end\_hour** : _string_ : prefered end hour for the sale of the item
    * **qty** : _integer_ : quantity of the item to sell
    * **renew** : _integer_ : number of times the sale will be renewed if the item is not sold. See [AuthorizedValues](AuthorizedValues.md) to have details on values for this field
    * **option\_boldtitle** : _boolean_ : option chosen (true) or not (false) for the item
    * **option\_coloredborder** : _boolean_ : option chosen (true) or not (false) for the item
    * **option\_highlight** : _boolean_ : option chosen (true) or not (false) for the item
    * **option\_keepoptionsonrenewal** : _boolean_ : option chosen (true) or not (false) for the item
    * **option\_lastminutebidding** : _boolean_ : option chosen (true) or not (false) for the item
    * **option\_privatebidding** : _boolean_ : option chosen (true) or not (false) for the item
    * **option\_subtitle** : _boolean_ : option chosen (true) or not (false) for the item
    * **option\_topcategory** : _boolean_ : option chosen (true) or not (false) for the item
    * **option\_toplisting** : _boolean_ : option chosen (true) or not (false) for the item
    * **option\_topmain** : _boolean_ : option chosen (true) or not (false) for the item
    * **images** : _array_ of [String](String.md) objects : url of the images