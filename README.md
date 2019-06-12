# CS 0.6

*CS 0.6* is a final term project for 2019-Spring *Software Studio* Course in NTHU CS.  
Here is the [SPEC](https://hackmd.io/eKbYWhuwT_qhxavD3OAUPQ?view) from approved proposal.  

## Features
- Based on PHP, MySQL, HTML, Javascript
- Login with Facebook and Google accounts
- Friends
- Private and public chatrooms
- Voting
- Unsend message
- Admin page: banning users, broadcast

## Demo

> TODO : Some demo pictures

## Usage

### Preparation
Before deploying this project, you wil need to:  
1. Set up PHP.
2. Set up [Facebook PHP] and [Google OAuth].
3. Install and set up MySQL.
4. Modified config.php to set Mysql database name, location, password, admin name, admin password etc.
5. Execute database\_establish\_command.php to initialize Mysql database.
6. Install and set up Node.js.

### Deploy
1. Execute node\_server/index.js by execute command "node index.js" and keep it running

## Contributors

- 106062119 王元廷
- 106062128 陳頌恩
- 106062129 梁瑜軒
- 106062328 李思佑

## License
MIT License

Copyright (c) 2019 NTHU softwarestudio team6

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


[Facebook PHP]: https://developers.facebook.com/docs/reference/php  
[Google OAuth]: https://developers.google.com/api-client-library/php/auth/web-app#protectauthcode
