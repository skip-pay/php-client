MallPayLib, PHP Skip Pay API client (deprecated)
==============================
MallPayLib is a PHP Skip Pay API client that makes it easy to use Skip Pay payment gateway.
* It implements most of the API methods from Skip Pay API, see https://developers.skippay.cz/
* The input and output JSON for the API calls converted to and from the associative arrays.
* It includes a demo scripts
 
The Demo
--------
Included demo illustrates use of MallPayLib\MallPayClient class with sample data.

* The particular Skip Pay API calls are demonstrated in respective php files.
* The responses from Skip Pay gateway are handled by files reply.php and notify.php.
* All communication is logged in a log file.<p/>
 
Getting started
---------------
1. Download the MallPayLib
2. Run `composer install`
3. Create _demo/config.php_ from _demo/config.php.example_
4. Run `php -S localhost:8000`
5. Open http://localhost:8000/demo/index.php in your browser
6. Click the link _createApplication_
7. Press the button _createApplication_
8. Observe the sample request data, scroll down to _Result summary_ and click the _gatewayRedirectUrl_
9. Finish  the MALL application form
10. After redirection back to the _reply.php_ click the link _getApplicationDetail_
11. Keep the filled applicationId field and press _getApplicationDetail_ button
12. Observe the result of your Skip pay application request

The demo can handle also the notifications from Skip Pay. To test the notifications, the demo has to run on a public internet domain, not on localhost.   


