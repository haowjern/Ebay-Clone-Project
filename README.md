# ebay-database-system-project

## Overview
This project is part of our Database and Information Management Systems module at UCL. Working in groups of 4, we were tasked to create a shopping website with buy and sell functionality using the WAMP stack - Windows, Apache, MySQL and PHP over 8 weeks. This was my first experience in these languages, using them to create a website and to link it with a database. 

Grading for this module was primarily focused on functionality and database design, hence less attention is paid to presentation and style of the website. We achieved a mark of 81% on this project. The report and video demo for this project are located in the files for viewing.

## Database Design
We employed the traditional Relational Database design methodology following the steps of Conceptual Design followed by Logical Design.
1. Conceptual Design
We created a list of entities and their relationships to be turned into an Entity-Relationship Diagram .
2. Logical Design
We transformed the Entity Relationship Diagram into a 3NF Database Schema following normalisation steps.  

## Functionality
The complete list of functionality follows:
 	
I. Users can register with the system and create accounts. Users have roles of seller or buyer with different privileges.
A user can both be a seller and a buyer simulatenously, but depending on the website links they go to, different sell/buy functionality is presented. 

II. Sellers can create auctions for particular items, setting suitable conditions and features of the items including the item description, categorisation, starting price, reserve price, end date and photos.

III. Buyers can search the system for particular kinds of item being auctioned and can browse and visually re-arrange listings of items within categories.

IV. Buyers can bid for items and see the bids other users make as they are received. The system will manage the auction until the set end time and award the item to the highest bidder. The system should confirm to both the winner and seller of an auction its outcome.

V. Buyers can watch auctions on items and receive emailed updates on bids on those items including notifications when they are outbid.

VI. Buyers can receive recommendations for items to bid on based on collaborative filtering (i.e., ‘you might want to bid on the sorts of things other people, who have also bid on the sorts of things you have previously bid on, are currently bidding on).
Sellers can leave reviews and ratings for items bought. These ratings are then used to implement Ratings Based Collaborative Filtering [1] to generate 'Personalised', 'Generalised', 'New' and 'Buy again' type recommendations served to the user. 

[1] Lemire, Daniel & McGrath, Sean. (2005). Implementing a Rating-Based Item-to-Item Recommender System in PHP/SQL. 

## Technology Stack + Software Used
1. Github
2. WAMP 
3. Visual Studio Code
4. MySQL Workbench

## To Run Instructions
Initialise database
1. Run dummy.sql
2. Import files from database/dummy_data to initialise data for the website

Set up server using preferred stack - WAMP/LAMP/MAMP and interact with the website through localhost. Available users' details are:
- name: user1 pw: abc123
- name: user2 pw: abcd1234

## Personal thoughts 
I think this was a great learning experience in that I finally knew how to create a website and to link it to a database. It was a huge challenge in learning new languages but I think one of the toughest ones were learning how to work as a group. I learnt a lot about task delegation, project planning, reading & understanding of other members' code, and essentially how to be a better team member. In the future I think I want to incorporate some type of architectural design pattern like MVC to better organise our code which helps to make it easier to delegate tasks. 
