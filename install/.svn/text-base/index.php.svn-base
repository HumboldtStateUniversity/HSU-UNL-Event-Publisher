<?php
/**
 * This is the setup file for the UNL UCBCN Calendar System.
 * This file installs/upgrades the database and inserts the default
 * permissions.
 *
 * PHP version 5
 *
 * @category  Events
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */

//Make necessary ini changes
set_include_path(implode(PATH_SEPARATOR, array( 
    dirname(dirname(__FILE__)).'/includes/pear',
    dirname(dirname(__FILE__)).'/includes/backend',
    get_include_path())));

require_once 'UNL/UCBCN/Autoload.php';
require_once 'UNL/UCBCN_setup.php';

$setup = new UNL_UCBCN_setup_postinstall();
$setup->data_dir = 'http://unl-event-publisher.googlecode.com/svn/UNL_UCBCN/trunk/data';

session_start();
?>
<html>
    <head>
        <title>UNL Event Publisher Installer</title>
        <link rel="stylesheet" href="../templates/vanilla/css/uniform.aristo.css" type="text/css" media="screen" charset="utf-8" />
        <link rel="stylesheet" href="install.css" type="text/css" media="screen" charset="utf-8" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
        <script type="text/javascript" src="../templates/vanilla/javascript/jquery.tools.min.js"></script>
        <script type="text/javascript" src="../templates/vanilla/javascript/jquery.uniform.min.js"></script>
        <script type="text/javascript">
        $(function(){ 
            $("select, input:checkbox, input:radio, input:file").uniform(); 
            $("tr").tooltip({

            	// place tooltip on the right edge
            	position: "center right",

            	// a little tweaking of the position
            	offset: [-2, 10],

            	// use the built-in fadeIn/fadeOut effect
            	effect: "fade",

            	// custom opacity setting
            	opacity: 0.7

            });
        });
        </script>
    </head>
    <body>
        <center><h1>UNL Event Publisher Installer</h1></center>

		<?php
		if(isset($_POST['submit'])) {
			if($_POST['createDB']) {
				$setup->createDB = true;
			}

			if($setup->createDB) {
				$r = $setup->handleDatabase($_POST);
				if($r) {
					$setup->setupPermissions($_POST);
					$setup->setupEventTypes($_POST);
					$setup->setupSponsors($_POST);

					echo "<p>Assuming there are no reported errors, the database was ",
						 "successfully initialized. The next step is to configure the config ",
						 "file(s) for the frontend and manager.<p>";
					echo "<p>Create a <strong>config.inc.php</strong> file with the following:<br />";
					outputConfig($setup->dsn);
					echo "</p>";
					echo "</p>";
					echo "<p>NOTE: You probably need to further modify the manager to use ",
						 "your own authentication system. The UNL Event Publisher uses the ",
						 "PEAR Auth library. You can find information on configuring this ",
						 "at <a href='http://pear.php.net/manual/en/package.authentication.auth.intro.php'>",
						 "http://pear.php.net/manual/en/package.authentication.auth.intro.php</a>.
						 For addition information on authentication, pleae visit our 
						 <a href='http://code.google.com/p/unl-event-publisher/wiki/Authentication'>wiki</a></p>";
					echo "<p>The default login for the admin user: <br />
					           Username: admin <br />
					           Password: admin</p>";
					echo "<p><a href='../'>Click here</a> to go to your new event publisher installation.</p>";
				}
			}
		}else {
		?>
        <form method="post" action="index.php" id="setupform">
        	<fieldset>
        		<legend>Database Setup</legend>
	        	<table>
				<tr title="Create the DB if it doesn't exist, or upgrade it if you have an older version.">
					<td><label for="createDB">Create/Upgrade Database</label></td>
					<td><input type="checkbox" name="createDB" id="createDB" checked="checked" /></td>
				</tr>
				<tr title="Populate the database with sample events (for testing).">
					<td><label for="addeventtypes">Install Sample Event Types</label></td>
					<td><input type="checkbox" name="addeventtypes" id="addeventtypes" value="yes"/></td>
				</tr>
				<tr title="Populate the database with sample sponsors (for testing).">
					<td><label for="addsponsor">Install Sample Sponsors</label></td>
					<td><input type="checkbox" name="addsponsors" id="addsponsor" value="yes"/></td>
				</tr>
				<tr title="Select your database type. Currently supported databases include MySQL and MySQLi.">
					<td><label for="dbtype">Database Type</label></td>
					<td><select name="dbtype" id="dbtype">
						<option value="mysql">MySQL</option>
					    <option selected="selected" value="mysqli">MySQLi</option>
					</select></td>
				</tr>
				<tr title="The hostname of the database server. Generally, this will be 'localhost' if the database server is running on the same machine.">
					<td><label for="dbhost">Database Host</label></td>
					<td><input type="text" name="dbhost" id="dbhost" value="localhost"/></td>
				</tr>
				<tr title="The database name where you want the event publisher data stored.">
					<td><label for="database">Database Name</label></td>
					<td><input type="text" name="database" id="database" value="eventcal"/></td>
				</tr>
				<tr title="The username of the database. Must have read/write access to the database specified.">
					<td><label for="user">Database Username</label></td>
					<td><input type="text" name="user" id="user"/></td>
				</tr>
				<tr title="Password for specified user.">
					<td><label for="password">Database Password</label></td>
					<td><input type="password" name="password" id="password"/></td>
				</tr>
				<tr>
					<td><input type="submit" name="submit" value="Install UNL Event Publisher"/></td>
					<td></td>
				</tr>
			</table>
			</fieldset>
		</form>
		<?php } //end if ?>
	</body>
</html>

<?php
	/**
	 * Output Frontend Index
	 *
	 * Put the DSN into the correct space for the frontend index
	 */
	function outputConfig($dsn) {
		echo '<textarea rows="15" cols="100" onclick="this.select(); return false;">';
		$frontend = file_get_contents('../config.sample.inc.php');
		$frontend = str_replace('{{DSN}}', $dsn, $frontend);
		echo htmlspecialchars($frontend);
		echo '</textarea>';
	}
?>