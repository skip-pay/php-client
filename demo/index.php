<html><body>

<h1>MallPayLib Demo</h1>

This demo illustrates use of MallPayLib\MallPayClient class with sample data.<br/>
For the MALLPay API documentation see <a href="https://mallpayapi.docs.apiary.io">https://mallpayapi.docs.apiary.io</a><p/>
The particular MALLPay API calls are demonstrated in respective php files. <br/>
The responses from MALLPay gateway are handled by files reply.php and notify.php.<br/>
All communication with MallPay is logged in app.log file.<p/>

<?php
if (!file_exists('config.php')) {
    echo "<p>The configuration file config.php is missing. Please, create one from config.php.example</p>";
}
?>


<h2>Supported MallPayClient API calls</h2>
<p/>
<a href="createApplication.php">createApplication</a> ...start here
<p/>
<a href="getApplicationDetail.php">getApplicationDetail</a>
<p/>
<a href="cancelApplication.php">cancelApplication</a>
<p/>
<a href="changeApplicationOrder.php">changeApplicationOrder</a>
<p/>
<a href="markOrderItemsAs.php">markOrderItemsAs...</a>
<p/>
<a href="precheck.php">precheck</a>
<p/>
<a href="apiHealthCheck.php">apiHealthCheck</a>
<p/>


<h2>Log file</h2>
<a href="app.log">app.log</a>



</body></html>


