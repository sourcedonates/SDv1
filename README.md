[Setup]
This is only a developement System. Make Backups for everything before you use it.

1. Copy SD to your Webspace
1.1. Download the latest files from the SD repo
1.2. Upload them to the webspace (overwrite the exiting files).
2. Change the URL in the file /application/config/config.php to point to the dir where you have copied SD to
3. Change the Database Details in /app.../config/database.cfg to the database you would like to use for SD
4. Import the sdv1.sql to your database
6. Visit the index.php/admin/login url and login with your account provider
7. There will be a error message with a long url, copy this url
8. Navigate to the admin_users table and create a new record with the copied url as openid_identity
8.1. Use this line for the permissions field: donators_view,donators_approval_view,settings_sd_view,settings_sd_edit,settings_admins_view,settings_admins_edit,settings_forumgroups_view,settings_forumgroups_edit,categories_view,categories_edit,items_view,items_edit,plans_view,plans_edit
9. You can now log into the admin backend
10. Setup SD at the settings page in the admin menu
11. Create a Cronjob to run index.php/cron every 60 min and index.php/cron_light every min
12. Check the twitter feed every week for database updates and contact us via mail to get the update
    https://twitter.com/SourceDonates
    dev-preview@sourcedonates.com
[/Setup]


[Plans/Items/Categories]
The donation perks are structured into Plans Items and Categories

Plans:
You Users donate for specific Plans
There can be only one active Plan for each Donator
A Plan need categories and Items to work
You also need to setup:
	a name
	a description
	a price
	a time (0 for infinite)
	a color
	the sm groupid: The is the ID of the Sourcemod SQL group the donators should get
	bdi_level: the bdi level the donators should get
	forum_usergroup (id): The Forum UsergroupID the Donators should get

	
Categories:
The items are seperated into different categories.
The Cat only needs a name to work


Items:
For Each Item you have to specify in what plans it should be displayed and in wich categorie it should be
Enter the plan_id like this if you only want to add a item for planid 1: "1"
If you want to add a Item for planid 1 and 2 use this syntax: "1,2"
Then you have to enter the categorie id where the item should apper.
Dont forget to add a name
A Item Picture is optional (use the path to the picture (not the url) 
[/Plans/Items/Categories]

[Support]
Since there might be bugs, I am trying my best to help you with SD
You can add me on steam (http://steamidconverter.com/NotAGamer) send me a Email (dev-preview@sourcedonates.com) or write me a PM on the Allied Mods Forum (I prefer the first two options)
Do NOT contact us on twitter if you need Support ! You will NOT get support over Twitter !
[/Support]