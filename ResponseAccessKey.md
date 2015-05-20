  * ResponseAccessKey is an object, returned by [getAccessKey](getAccessKey.md) function, with 4 datafields :
    * **status** : _boolean_ : true (if call ok) or false (if call not ok)
    * **errorMsg** : _string_ : message giving you more information on the function call failure
    * **accessKey** : _string_ : the access key you asked
    * **acceptanceUrl** : _string_ : url where to redirect the member on Delcampe's website, so he can accept (or not) your request to manage his Delcampe account

  * If **status** is _true_
    * **accessKey** will be filled with relevant information
    * **acceptanceUrl** will be filled with relevant information
    * **errorMsg** will be empty
  * If **status** is _false_
    * **data** will be empty
    * **errorMsg** will be filled with relevant information