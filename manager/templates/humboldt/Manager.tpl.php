<?php
header('Content-Type:text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo $this->doctitle; ?></title>
        <link rel="stylesheet" type="text/css" media="screen" href="templates/vanilla/manager_main.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="templates/vanilla/dialog/dialog_box.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/themes/<?php echo $this->calendar->theme ?>/jquery-ui.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"></script>
        <script type="text/javascript" src="templates/vanilla/manager.js"></script>
        <script type="text/javascript" src="templates/vanilla/dialog/dialog_box.js"></script>
    </head>
    <body <?php echo $this->uniquebody; ?>>
        <div id="mainWrapper">

            <div id="contentWrapper">
                <div id="breadCrumb" style='width: 933px'>
                    <a href="">Calendar</a>
                    <?php
                    if (!empty($this->calendar->website)) {
                        echo ' / <a href="'.$this->calendar->website.'">'.$this->calendar->name.'</a>';
                    }
                    ?>
                </div>
                <div id="contentSearch">
                    <div id="title" class="rightnav">
                        <?php if (isset($this->user)) {
                            UNL_UCBCN::displayRegion($this->calendarselect);
                        } //End if user
                        ?>
                        <div id="titlegraphic" style="clear:both">
                            <h1><?php
                                if (isset($this->calendar)) {
                                    echo $this->calendar->name;
                                } else {
                                    echo 'Event Publishing System';
                                }
                                ?></h1>
                            <h2>Plan. Publish. Share.</h2>
                        </div>
                    </div>

                    <div id="navigation">
                        <h4 id="sec_nav">Navigation</h4>
                        <div id="navlinks">
                            <?php
                            if (isset($this->user)) { ?>
                            <ul>
                                <li id="mycalendar"><a href="<?php echo $this->uri; ?>?" title="My Calendar">Pending Events</a></li>
                                <li id="create"><a href="<?php echo $this->uri; ?>?action=createEvent" title="Create Event">Create Event</a></li>
                                <li id="subscribe"><a href="<?php echo $this->uri; ?>?action=subscribe" title="Subscribe">Subscribe</a></li>
                            </ul>
                                <?php
                            } ?>
                        </div>
                        <div id="nav_end"></div>
                        <div id="leftcollinks">
                            <?php if (isset($this->user)) { ?>
                            <div class="cal_widget">
                                <h3><span><?php echo date("F jS, Y"); ?></span></h3>
                                <ul>
                                    <li class="nobullet">Welcome, <?php echo $this->user->uid; ?></li>
                                    <li><a href="<?php echo $this->frontenduri . '?calendar_id='.$this->calendar->id; ?>">Live Calendar</a></li>
                                    <li><a href="<?php echo $this->uri; ?>?action=calendar">Calendar Info</a></li>
                                    <li><a href="<?php echo $this->uri; ?>?action=users">Users &amp; Permissions</a></li>
                                    <li><a href="<?php echo $this->uri; ?>?logout=true">LogOut</a></li>
                                </ul>
                            </div>
                                <?php
                            }

                            if (!empty($this->plugins)) {
                                echo '	<div class="cal_widget"><h3>Plugins</h3><ul>';
                                foreach ($this->plugins as $plugin) {
                                    echo '<li><a href="'.$plugin->uri.'">'.$plugin->name.'</a></li>';
                                }
                                echo '</ul></div>';
                            }
                            ?>
                        </div>
                    </div>

                    <div id="main_right" class="mainwrapper">
                        <div id="maincontent">
                            <div>
                                <h2>Descriptive Text</h2>
                                <p>Fill in whatever information is pertinent to your calendar here.</p>
                            </div>
                            <?php if (isset($this->user)) { ?>
                            <form id="event_search" name="event_search" method="get" action="<?php echo $this->uri; ?>">
                                <input type='text' name='q' id='searchinput' value="<?php if (isset($_GET['q'])) { echo htmlentities($_GET['q']); } ?>" />
                                <input type='submit' class="search_submit" name='submit' value="Search" />
                                <input type='hidden' name='action' value='search' />
                            </form>
                                <?php }
                            UNL_UCBCN::displayRegion($this->output);
                            ?>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                </div>
                <div id="mainFooter">
                    <div class="footerLinks">
                        <strong>&#169; YOUR ORGANIZATION</strong>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
