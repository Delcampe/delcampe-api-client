# Add an item for sale #

  * Choose the parameter values for the item to be added.
    * Choose a category : you can retrieve the category list from[getCategoryList](getCategoryList.md)()
    * Choose if it's a fixed price or an auction, discover the wanted params with
      * [getFieldsNamesForItemAuction](getFieldsNamesForItemAuction.md)()
      * [getFieldsNamesForItemFixedPrice](getFieldsNamesForItemFixedPrice.md)()
  * Send your item
    * [addItemAuction](addItemAuction.md)() if it's an auction
    * [addItemFixedPrice](addItemFixedPrice.md)() if it's a fixed price

Now, your item is in a queue. It will be added on the Delcampe's website soon.
You will be informed by the Delcampe service when your item will be processed and added on the marketplace.
  * [Notifications Seller\_Item\_Add](Notifications#Seller_Item_Add.md)
So, check how to [Manage the notifications parameters](#Manage_my_notifications_parameters.md)

# How to retrieve an item previously added on the website #
> With [addItemAuction](addItemAuction.md)() and [addItemFixedPrice](addItemFixedPrice.md)() you can provide a _personal\_reference_.
> This reference is provided in the Notification [Seller\_Item\_Add](Notifications#Seller_Item_Add.md). As the id of the item is also provided, you can now merge this info.

If you miss it, you can search for it with [getMyClosedUnsoldItemAuctionList](getMyClosedUnsoldItemAuctionList.md)(), [getMyClosedUnsoldItemFixedPriceList](getMyClosedUnsoldItemFixedPriceList.md)(), [getMyOpenItemAuctionList](getMyOpenItemAuctionList.md)(), [getMyOpenItemFixedPriceList](getMyOpenItemFixedPriceList.md)()

# My item is online but I want to make a change on it #
  * Call [updateItem](updateItem.md)() to modify some fields. Be carefull : some fields cannot be changed if a first bid has been made .

# There is a bid on my auction #
When it happens, a notification "[Seller\_Item\_FirstBid](Notifications#Seller_Item_FirstBid.md)" is sent to you by the Delcampe Service. It's important for you to catch it and process it on your side if you have your item on sale on another place than the Delcampe Website.
Indeed, as you have a bidder, you have a potential winner, and you cannot withdraw your item.

If you cannot manage this feedback you must leave the exclusive sale of that object to Delcampe.

# End of a fixed price sale #

It is the easiest situation, a member of Delcampe website has accepted your price, the service sends you a notification [Seller\_Item\_Close\_Sold](Notifications#Seller_Item_Close_Sold.md)

You can use the API to retrieve the list of all your sold items [getMySoldItemFixedPriceList](getMySoldItemFixedPriceList.md)()

# Manage my notifications parameters #
There are many notification types. You can retrieve the full list with [getAvailableNotifications](getAvailableNotifications.md)().

These notifications are sent by email and/or by curl.

If you decide to use the API to manage your sales, the curl notification is the best way to handle feedbacks.

The most common and easiest use is to set the URL where the Service should send these notifications. You can do it by using [setDefaultNotificationUrl](setDefaultNotificationUrl.md)().

If you want to also receive notifications by mail, [setDefaultNotificationEmail](setDefaultNotificationEmail.md)() please choose a different email address than the one used in your seller's account.

Note also : the recipent is defined by the notification settings of the **owner** of the items, not of the **sender**

As email is a preconfigured target, for each item sent ie, the owner would receive an email.

# How to choose a Delcampe Category for my items #

Like on the website, items should be linked to a category.
In your item data for sending you need to provide a  valid [category id](AuthorizedValues#id_category.md).
To know the list you can call [getCategoryList](getCategoryList.md)() which return a list of [ResponseCategory](ResponseCategory.md)
