# Project 1 - Server Programming
## E-commerce website created for Server Programming at RIT. 

### Requirements for the project: 
#### index.php
- There will be a minimum of 15 items total in the database
- Sales items do not appear in the catalog section
- There will be links that allow the user to go to the next or previous page of items as applicable with selected page number validation
- The "buy" button adds the item to cart table and updates the quantity on hand in the products table

#### cart.php
- All items added to the cart with a total price for all items listed.
- The postings are loaded dynamically from the database table
- There is a button that empties the cart (removes items from the database table).

#### admin.php
- A form to put items on sale or remove them from being on sale.  You must validate that there is a minimum of 3 items on sale at any one time with a maximum of 5 items on sale at one time.
- A form to add items to the catalog including a button to upload an image.
- A password field. No posting/update should happen without a match (or some sort of login with session management)
- All input will be validated and sanitized as appropriate based on the information in the field.
- The method of the form(s) will be POST.

#### DB.class.php and LIB_project1.php
- The code in this file(s) will be structured as reusable functions that will be called by the other pages.
- Copious comments will describe the inputs, outputs, and purpose of each function.

#### EXTRA CREDIT
- When uploading file, file automatically updates to the images folder, along with adding the image name to the database. Therefore, when new item is added, the catalog shows new item's description along with image uploaded

#### Note: admin password is "password"

