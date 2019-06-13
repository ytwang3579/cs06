# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]  
*Feature(s) in development should be displayed here, with information of who is actively dealing with it.* 

## 0.2.12 - 2019-06-13 - s106062129
## Added
- Added secret link to website 'https://ani.gamer.com.tw/'

## 0.2.11 - 2019-06-13 - s106062129
## Changed
- Update readme\_image for new look
- Modified README.md to have good introduction

## 0.2.10 - 2019-06-13 - s106062328(szlee118)
## Added
- Add css layers in chat\_room/add\_freind create\_private/public\_room  and admin

## 0.2.9 - 2019-06-13 - s106062128
## Added
- Add vote feature, but only in public chat room

## 0.2.8 - 2019-06-13 - ytwang
## Fixed
- syntax fixed in README.md
- contribution link added in README.md

## 0.2.7 - 2019-06-13 - s106062129
## Changed
- Finish README.md

## 0.2.6 - 2019-06-13 - ytwang
## Added
- Easter egg path to *admin/admin_login.php*
## Removed
- *chat_room/oindex.php*  
- *chat_room/main.css*

## 0.2.5 - 2019-06-12 - s106062129
## Changed 
- Modidied chat\_room/index.php to redirect to ../login if not login yet
- Add some comment in chat\_room's file

## 0.2.4 - 2019-06-12 - s106062328(szlee118)
## Changed
- After create chat room will not show "Create Successfully" message but refresh the whole page new update
  Sidebar list.
- Load picture in socket message
- Modified preparation in README.md

## 0.2.3 - 2019-06-12 - s106062129
## Changed
- After create chat room will not load chat\_room/index.php but show "Create Successfully" message
- Modified preparation in README.md

## 0.2.2 - 2019-06-12 - ytwang
### Changed
- README.md now contains some information
  > TODO: Demo picture and deploy instructions

## 0.2.1 - 2019-06-12 - ytwang
### Added
- Add *node_server/style.css* for chatroom iframe (**TODO: img src**)
### Fixed
- Add scrollbar to sidebar of *chat_room/index.php* ( in *chat_room/style.css* ) to avoid overflow

## 0.2.0 - 2019-06-11 - s106062328(szlee118)
### Changed
- Change all url to http
- Add column picture in table user\_list in mysql
- Change fb/,google/ insertion into mysql adding picture
- Insert add\_friend add\_public\_chatroom into iframe

## 0.1.20 - 2019-06-11 - s106062328(szlee118)
### Added
- Add logout function in chat\_room/index.php and admin/index.php

## 0.1.19 - 2019-06-11 - s106062129
### Changed
- Use create new message element function to create new message element
- Finish delete function

## 0.1.18 - 2019-06-11 - s106062128
### Added
-  Added delete event to everyone in chatroom response in node\_server/index.js

## 0.1.17 - 2019-06-11 - ytwang
### Added
- *node_server/style.css* added (chatroom's css, waiting for the message format)


## 0.1.16 - 2019-06-11 - s106062129
### Added
-  Added delete button in node\_server/index.html

## 0.1.15 - 2019-06-10 - s106062328(szlee118)
### Changed
- basically done with broadcating by modifying admin/index.php 

## 0.1.14 - 2019-06-10 - s106062328(szlee118)
### Added
- basically done with ban function by modifying node_server/index.js

## 0.1.13 - 2019-06-10 - ytwang
### Added
- *chat_room/index.php* is now with frontend design
- *chat_room/style.css*

### Deprecated
- the original *chat_room/index.php* is temporarily saved as *chat_room/index_old.php*  
  (if there is no problem, it will be removed soon)
- *chat_room/main.css*

### Removed
- *fb\index_old.php*

## 0.1.12 - 2019-06-09 - s106062328(szlee118)
### Added
- Add admin page index.php

## 0.1.11 - 2019-06-09 - s106062129
### Added
- Add admin function
- Start working on admin
- finish admin/admin\_login.php

## 0.1.10 - 2019-06-09 - s106062129
### Added
- Add button to go back to chatroom/index.php in addfriend.php create private chatroom.php create public chatroom.php

## 0.1.9 - 2019-06-09 - s106062128
### Added
- now we have chat history

## 0.1.8 - 2019-06-09 - s106062328(szlee118)
### Fixed
- synchronize google and fb url as cs06.2y.cc

## 0.1.7 - 2019-06-09 - s106062128
### Added
- put chatroom in chatroom/index.php by iframe

## 0.1.6 - 2019-06-07 - s106062328(szlee118)
### Added
- Add photo to Session variable

## 0.1.5 - 2019-06-06 - s106062129
### Changed
- Finish debug chatroom/create public chatroom.php

## 0.1.4 - 2019-06-06 - s106062129
### Changed
- Finish debug chatroom/create private chatroom.php

## 0.1.3 - 2019-06-06 - s106062129
### Changed
- Finish chatroom/create public chatroom.php

## 0.1.2 - 2019-06-06 - s106062129
### Changed
- Change chatroom/index.php to show chatroom list

## 0.1.1 - 2019-06-06 - s106062129
### Changed
- change chatroom/addfriend.php to prevent add yourself

## 0.1.0 - 2019-06-06 -s106062128
### Added
- add node\_server, now we can start a server and acheive real time chat(by socket.io)

## 0.0.20 - 2019-06-06 - s106062129
### Added
- add create chat room data base in chat\_room/create\_private\_chatroom.php

## 0.0.19 - 2019-06-06 - s106062328(szlee118)
### Fixed
- Change mysql insturctions when register

## 0.0.18 - 2019-06-06 - s106062129
### Fixed
- fix bug of chat\_room/index.php

## 0.0.17 - 2019-06-06 - s106062328(szlee118)
### Fixed
- fix bug of chat\_room/create\_private\_chatroom.php


## 0.0.16 - 2019-06-06 - s106062129
### Added
- finish chat\_room/create\_private\_chatroom.php

## 0.0.15 - 2019-06-05 - ytwang
### Changed
- *fb\index.php* is now with frontend design

### Deprecated
- the original *fb\index.php* is temporarily saved as *fb\index_old.php*  
  (if there is no problem, it will be removed soon)

## 0.0.14 - 2019-05-30 - s106062129
### Added
- Add create button to chat\_room/index.php
- Add chat\_room/create\_private\_chatroom.php (not finish yet)
- Add chat\_room/create\_public\_chatroom.php (empty file)

## 0.0.13 - 2019-05-30 - s106062328(szlee118)
### Added
- basically done with google login, modify the main page


## 0.0.12 - 2019-05-30 - s106062129
### Remove
- remove confirm friend function

## 0.0.11 - 2019-05-30 - s106062129
### Added
- Add show own id in chat\_room/add\_friend.php

## 0.0.10 - 2019-05-30 - s106062129
### Added
- Add chat\_room/add\_friend.php to add friend by using id

## 0.0.9 - 2019-05-28 - ytwang
### Fixed
- Bug fixed in chat_room/index.php  
> \<?php should be the only thing for its line  

### Added
- *fb/tmp.html* and *fb/login.css* reserved for unfinished index home page.

## 0.0.8 - 2019-05-27 - s106062328(szlee118)
### Added
- fb\main.php create user_chatlist user_friendlist when register, insert into user-list


## 0.0.7 - 2019-05-27 - s106062128
### Added
- database\_establish\_command.php done.  
  It can create user\_list chat\_list table.


## 0.0.6 - 2019-05-27 - s106062129
### Changed
- Rename chat\_room\chat\_room.php to index.php


## 0.0.5 - 2019-05-27 - s106062129
### Changed
- Changed chat\_room chat\_room.php to read friend list and chat room list

## 0.0.4 - 2019-05-27 - s106062129
### Changed
- Change config.php password

## 0.0.3 - 2019-05-27 - s106062129
### Fixed
- Change fb\_establish\_command to database\_establish\_command.php


## 0.0.3 - 2019-05-27 - s106062129
### Changed
- Change database\_establish\_command to database\_establish\_command.php

## 0.0.2 - 2019-05-27 - s106062328(szlee118)
### Added
- Basically done with fb login.

## 0.0.1 - 2019-05-27 - s106062129
### Added
- Add database\_establish\_command please add any create database command in it.
- Add chat\_room template in project.

## 0.0.0 - 2019-05-17 - ytwang
### Added
- Set up this CHANGELOG file
- README file now contains a brief introduction of the project.

