<?php

/**
 * Net_Vpopmaild 
 * 
 * PHP Version 5
 *
 * @category Net
 * @package  Net_Vpopmaild
 * @author   Bill Shupp <hostmaster@shupp.org> 
 * @author   Rick Widmer <vchkpw@developersdesk.com>
 * @license  PHP 3.01  {@link http://www.php.net/license/3_01.txt}
 * @link     http://trac.merchbox.com/trac/Net_Vpopmaild
 * @todo Finish ezmlm functions, waiting on vpopmaild updates
 * @todo Robot creation - check for existing accounts first?  or 
 * is it an issue with OS X fs, or vpopmaild?
 * @todo getQuota() should support maildir++ completely (file count: C)
 */

require_once 'Net/Vpopmaild/Base.php';
require_once 'Net/Vpopmaild/Exception.php';
require_once 'Net/Vpopmaild/FatalException.php';

/**
 * Net_Vpopmaild 
 * 
 * A package for talking to vpopmaild over tcp/ip sockets.  
 * {@link http://vpopmail.sf.net/ Vpopmail} is a Virtual Mail add-on 
 * package for qmail and postfix.
 * 
 * @category Net
 * @package  Net_Vpopmaild
 * @author   Bill Shupp <hostmaster@shupp.org> 
 * @author   Rick Widmer <vchkpw@developersdesk.com>
 * @license  PHP 3.01  {@link http://www.php.net/license/3_01.txt}
 * @link     http://trac.merchbox.com/trac/Net_Vpopmaild
 */
class Net_Vpopmaild extends Net_Vpopmaild_Base
{
    /**
     * modUserParms 
     * 
     * Array of string parameters and flag parameters that are used by modUser().
     * 
     * @var    array
     * @see    modUser()
     * @see    getModUserParms()
     * @access protected
     */
    protected $modUserParms = array('stringParms' => array('quota',
                                    'comment',
                                    'clear_text_password'),
                                'flagParms' => array('no_password_change',
                                    'no_pop',
                                    'no_webmail',
                                    'no_imap',
                                    'no_smtp',
                                    'bounce_mail',
                                    'no_relay',
                                    'no_dialup',
                                    'user_flag_0',
                                    'user_flag_1',
                                    'user_flag_2',
                                    'user_flag_3',
                                    'system_admin_privileges',
                                    'system_expert_privileges',
                                    'domain_admin_privileges',
                                    'override_domain_limits',
                                    'no_spamassassin',
                                    'no_maildrop',
                                    'delete_spam')
                              );

    /**
     * setLimitsParms 
     * 
     * Array of string parameters and flag parameters that are used by setLimits().
     * 
     * @var    array
     * @see    setLimits()
     * @see    getSetLimitsParms()
     * @access protected
     */
    protected $setLimitsParms = array('stringParms' => array('max_popaccounts',
                                        'max_aliases',
                                        'max_forwards',
                                        'max_autoresponders',
                                        'max_mailinglists', 
                                        'disk_quota',
                                        'max_msgcount',
                                        'default_quota',
                                        'default_maxmsgcount'),
                                    'flagParms' => array('disable_pop',
                                        'disable_imap',
                                        'disable_dialup',
                                        'disable_password_changing',
                                        'disable_webmail',
                                        'disable_external_relay',
                                        'disable_smtp',
                                        'disable_spamassassin',
                                        'delete_spam',
                                        'perm_account',
                                        'perm_alias',
                                        'perm_forward',
                                        'perm_autoresponder',
                                        'perm_maillist',
                                        'perm_maillist_users',
                                        'perm_maillist_moderators',
                                        'perm_quota',
                                        'perm_defaultquota')
                                );

    /**
     * clogin 
     * 
     * compact login.  Returns a compact list of user info which is stored in
     * {@link $loginUser}
     * 
     * @param mixed $email    email address
     * @param mixed $password password
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success, false on failure
     */
    public function clogin($email, $password)
    {
        $status = $this->sockWrite("clogin $email $password");
        $in     = $this->sockRead();
        if (!$this->StatusOk($in)) {
            throw new Net_Vpopmaild_Exception($in);
        }
        $this->loginUser = $this->readInfo();
        return true;
    }
    /**
     * getGidBit 
     * 
     * Get gid bit flag value.
     * 
     * @param mixed $bitmap bitmap
     * @param mixed $bit    bit flag
     * @param mixed $flip   flip it?
     *
     * @access public
     * @return bool true on success, false on failure
     * @throws Net_Vpopmaild_Exception if $bit is unknown
     * @see setGidBit()
     */
    public function getGidBit($bitmap, $bit, $flip = false)
    {
        if (!isset($this->gidFlagValues[$bit])) {
            throw new Net_Vpopmaild_Exception(
                "Error - unknown GID Bit value specified: $bit");
        }
        $bitValue = $this->gidFlagValues[$bit];
        if ($flip) {
            return ($bitmap&$bitValue) ? false : true;
        }
        return ($bitmap&$bitValue) ? true : false;
    }

    /**
     * setGidBit 
     * 
     * Set gid bit flag value.
     * 
     * @param mixed &$bitmap bitmap to set
     * @param mixed $bit     bit flag
     * @param bool  $value   value
     * @param mixed $flip    flip it?
     *
     * @access public
     * @return void
     * @throws Net_Vpopmaild_Exception if $bit is unknown
     * @see getGidBit()
     */
    public function setGidBit(&$bitmap, $bit, $value, $flip = false)
    {
        if (!isset($this->gidFlagValues[$bit])) {
            throw new Net_Vpopmaild_Exception(
                "Unknown GID Bit value specified. $bit");
        }
        $bitValue = $this->gidFlagValues[$bit];
        if ($flip) {
            $value = ($value == true) ? 0 : $bitValue;
        } else {
            $value = ($value == true) ? $bitValue : 0;
        }
        $bitmap = (int)$value|(~(int)$bitValue&(int)$bitmap);
    }

    /**
     * getIPMap 
     * 
     * Get IP Map entry.  Requires vpopmail to have IP Alias 
     * Domains support compiled in.
     * 
     * @param string $ip ip address
     *
     * @access public
     * @return string domain on success, null on failure
     */
    public function getIPMap($ip)
    {
        $lists = array();
        $this->sockWrite("get_ip_map $ip");
        $in = $this->sockRead();
        if (!$this->statusOk($in)) {
            // Error returned is not useful
            return null;
        }
        $in = $this->sockRead();
        while (!$this->statusErr($in) 
                && !$this->statusOk($in) 
                && !$this->dotOnly($in)) {
            $lists[] = $in;
            $in      = $this->sockRead();
        }
        if (count($lists) == 0) {
            return null;
        }
        $exploded = explode(' ', $lists[0]);
        return $exploded[1];
    }

    /**
     * addIPMap 
     * 
     * Add IP Map entry.  Requires vpopmail to have IP Alias 
     * Domains support compiled in.
     * 
     * @param mixed $ip     ip address
     * @param mixed $domain domain name
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success
     */
    public function addIPMap($ip, $domain)
    {
        $status = $this->sockWrite("add_ip_map $ip $domain");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        return true;
    }
    /**
     * delIPMap 
     * 
     * Delete IP Map entry.  Requires vpopmail to have IP Alias 
     * Domains support compiled in.
     * 
     * @param mixed $ip     ip address
     * @param mixed $domain domain name
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success
     */
    public function delIPMap($ip, $domain)
    {
        $status = $this->sockWrite("del_ip_map $ip $domain");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        return true;
    }
    /**
     * showIPMap 
     * 
     * List all IP map entries.  Requires vpopmail to have IP 
     * Alias Domains support compiled in.
     * 
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return array sorted ip map array on success
     */
    public function showIPMap()
    {
        $status = $this->sockWrite("show_ip_map");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        $lists = array();
        $in    = $this->sockRead();
        while (!$this->dotOnly($in) 
                && !$this->statusOk($in) 
                && !$this->statusErr($in)) {
            list($ip, $domain) = explode(' ', $in);
            if (!empty($lists[$ip])) {
                $lists[$ip] .= ", ".$domain;
            } else { // Not duplicate
                $lists[$ip] = $domain;
            }
            $in = $this->sockRead();
        }
        ksort($lists);
        return $lists;
    }

    /**
     * dotQmailSplit 
     * 
     * Split .qmail file into an array.
     * 
     * @param mixed $fileContents file contents
     *
     * @access public
     * @return array
     */
    public function dotQmailSplit($fileContents)
    {
        $result = array('Comment'  => array(),
                        'Program'  => array(),
                        'Delivery' => array(),
                        'Forward'  => array(),);
        if (!is_array($fileContents)) {
            return $result;
        }
        reset($fileContents);
        while (list(, $line) = each($fileContents)) {
            if (strlen($line) < 1) {
                continue;
            }
            switch ($line{0}) {
            case '#':
                $result['Comment'][] = $line;
                break;
            case '|':
                $result['Program'][] = $line;
                break;
            case '/':
                $result['Delivery'][] = $line;
                break;
            case '&':
            default:
                $result['Forward'][] = $line;
                break;
            }
        }
        return $result;
    }

    /**
     * robotDel 
     * 
     * Delete mail robot.
     * 
     * @param mixed $domain domain name
     * @param mixed $user   user name
     *
     * @access public
     * @return bool true on success, false on failure
     */
    public function robotDel($domain, $user)
    {
        $result = $this->robotGet($domain, $user);
        if (!is_array($result)) {
            $this->recordio($result);
            return false;
        }
        $robotDir     = strtoupper($user);
        $dotQmailName = ".qmail-$user";

        // Get domain directory for robotPath
        $domainArray = $this->domainInfo($domain);
        $robotPath   = $domainArray['path']."/$robotDir";
        $result      = $this->rmDir($robotPath);
        $result      = $this->rmFile($domain, '', $dotQmailName);
        return true;
    }

    /**
     * robotSet 
     * 
     * Set mail robot.
     * 
     * @param mixed $domain  domain name
     * @param mixed $user    user name
     * @param mixed $subject subject
     * @param mixed $message message
     * @param mixed $forward forward destination
     * @param mixed $time    time
     * @param mixed $number  number
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success
     */
    public function robotSet(
                        $domain,
                        $user,
                        $subject,
                        $message,
                        $forward,
                        $time = '',
                        $number = '')
    {
        if ($time == '') {
            $time = $this->vpopmailRobotTime;
        }
        if ($number == '') {
            $number = $this->vpopmailRobotNumber;
        }
        $robotDir     = strtoupper($user);
        $dotQmailName = ".qmail-$user";
        if (!is_array($message)) {
            $message = explode("\n", $message);
        }

        // Get domain directory for robotPath
        $domainArray = $this->domainInfo($domain);
        $robotPath   = $domainArray['path']."/$robotDir";

        $messagePath = "$robotPath/message";
        $program     = $this->vpopmailRobotProgram;
        // Build the dot qmail file
        $dotQmail = array("|$program $time $number $messagePath $robotPath");
        if (is_array($forward)) {
            array_merge($dotQmail, $forward);
        } elseif (is_string($forward)) {
            $dotQmail[] = $forward;
        }
        $result = $this->writeFile($dotQmail, $domain, '', $dotQmailName);
        $result = $this->mkDir($domain, '', $robotDir);
        // NOTE:  You have to add them backwards!
        array_unshift($message, '');
        array_unshift($message, "Subject: $subject");
        array_unshift($message, "From: $user@$domain");
        $result = $this->writeFile($message, $messagePath);
        return true;
    }

    /**
     * robotGet 
     * 
     * Get mail robot values.
     * 
     * @param mixed $domain domain name
     * @param mixed $user   user name
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return array robot on success
     */
    public function robotGet($domain, $user)
    {
        $dotQmailName = ".qmail-$user";
        $dotQmail     = $this->readFile($domain, '', $dotQmailName);
        if (!is_array($dotQmail)) {
            return $dotQmail;
        }
        $this->recordio("dotQmail: " . print_r($dotQmail, 1));
        $dotQmail = $this->dotQmailSplit($dotQmail);
        $this->recordio("dotQmaili split: " . print_r($dotQmail, 1));
        if (count($dotQmail['Program']) > 1) {
            //  Too many programs
            throw new Net_Vpopmaild_Exception(
                '-ERR 0 too many programs in robot dotqmail file');
        }
        if (!preg_match("({$this->vpopmailRobotProgram})",
            $dotQmail['Program'][0])) {
            throw new Net_Vpopmaild_Exception(
                '-ERR 0 Mail Robot program not found');
        }
        list($Program, $Time, $Number, $MessageFile, $RobotPath) 
            = explode(' ', $dotQmail['Program'][0]);
        $message = $this->readFile($MessageFile);
        if (!is_array($message)) {
            throw new Net_Vpopmaild_Exception(
                '-ERR 0 Unable to find message file - ' . $message);
        }
        $result           = array();
        $result['Time']   = $Time;
        $result['Number'] = $Number;
        array_shift($message); // Eat From: address
        $result['Subject'] = substr(array_shift($message), 9);
        array_shift($message); // eat blank line
        if (0 == count($dotQmail['Forward'])) { // Empty
            $result['Forward'] = '';
        } elseif (count($dotQmail['Forward']) > 1) { // array
            $result['Forward'] = $dotQmail['Forward'];
        } else { // Single entry
            $result['Forward'] = $dotQmail['Forward'][0];
        }
        $result['Message'] = $message;
        return $result;
    }


    /**
     * listLists 
     * 
     * List ezmlm mailing lists.
     * 
     * @param mixed  $domain domain name
     * @param string $user   user name optional
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return array lists array on success, error on failure
     */
    public function listLists($domain, $user = '')
    {
        $basePath = $this->formatBasePath($domain, $user);
        $status   = $this->sockWrite("list_lists $basePath");
        $status   = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        $lists = array();
        $in    = $this->sockRead();
        while (!$this->dotOnly($in)
                && !$this->statusOk($in)
                && !$this->statusErr($in)) {
            $lists[] = $in;
            $in      = $this->sockRead();
        }
        return $lists;
    }

    /**
     * listAlias 
     * 
     * List aliases.  You can list all aliases for a domain
     * or just the alias that matches the optional user 
     * argument.
     * 
     * @param mixed  $domain domain name
     * @param string $user   user name optional
     *
     * @access public
     * @return array alias array on success, null on failure
     */
    public function listAlias($domain, $user = '')
    {
        $basePath = $this->formatBasePath($domain, $user);
        $status   = $this->sockWrite("list_alias $basePath");
        $status   = $this->sockRead();
        if (!$this->statusOk($status)) {
            return null;
        }
        $alii = array();
        $in   = $this->sockRead();
        while (!$this->dotOnly($in)
                && !$this->statusOk($in)
                && !$this->statusErr($in)) {
            $alii[] = $in;
            $in     = $this->sockRead();
        }
        return $this->aliasesToArray($alii);
    }

    /**
     * removeAlias 
     * 
     * Remove destination from alias.
     * 
     * @param mixed $alias       alias name
     * @param mixed $destination destination address
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success
     */
    public function removeAlias($alias, $destination)
    {
        $result = $this->sockWrite("remove_alias $alias $destination");
        $result = $this->sockRead();
        if (!$this->statusOk($result)) {
            throw new Net_Vpopmaild_Exception($result);
        }
        return true;
    }

    /**
     * deleteAlias 
     * 
     * Completely delete an alias.
     * 
     * @param string $alias alias name
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success
     */
    public function deleteAlias($alias)
    {
        $result = $this->sockWrite("delete_alias $alias");
        $result = $this->sockRead();
        if (!$this->statusOk($result)) {
            throw new Net_Vpopmaild_Exception($result);
        }
        return true;
    }

    /**
     * addAlias 
     * 
     * Add alias destination.
     * 
     * @param mixed $alias       alias name
     * @param mixed $destination destination address
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success
     */
    public function addAlias($alias, $destination)
    {
        $result = $this->sockWrite("add_alias $alias $destination");
        $result = $this->sockRead();
        if (!$this->statusOk($result)) {
            throw new Net_Vpopmaild_Exception($result);
        }
        return true;
    }

    /**
     * getLimits 
     * 
     * Get vlimits for a domain.
     * 
     * @param mixed $domain domain name
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return array limits array on success
     */
    public function getLimits($domain)
    {
        $status = $this->sockWrite("get_limits $domain");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        $status = $this->sockRead();
        $limits = $this->readInfo();
        return $limits;
    }

    /**
     * getSetLimitsParms 
     * 
     * Getter for $this->setLimitsParms
     * 
     * @access public
     * @return array     $this->setLimtsParms array
     */
    public function getSetLimitsParms()
    {
        return $this->setLimitsParms;
    }

    /**
     * setLimits 
     * 
     * Set vlimits for a domain.
     * 
     * @param string $domain domain name
     * @param array  $limits domain limits
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success
     */
    public function setLimits($domain, $limits)
    {
        $limitsParms = $this->getSetLimitsParms();
        $stringParms = $limitsParms['stringParms'];
        $flagParms   = $limitsParms['flagParms'];

        $status = $this->sockWrite("set_limits $domain");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        // string parms
        foreach ($stringParms as $name) {
            if (!empty($limits[$name])) {
                $value  = $limits[$name];
                $status = $this->sockWrite("$name $value");
            }
        }
        // flag parms
        foreach ($flagParms as $name) {
            if (isset($limits[$name])) {
                $value  = $limits[$name];
                $status = $this->sockWrite("$name $value");
            }
        }
        $status = $this->sockWrite(".");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        return true;
    }

    /**
     * delLimits 
     * 
     * Delete vlimits on a domain.
     * 
     * @param mixed $domain domain name
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success
     */
    public function delLimits($domain)
    {
        $status = $this->sockWrite("del_limits $domain");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        return true;
    }

    /**
     * domainInfo 
     * 
     * Get domain info.
     * 
     * @param mixed $domain domain name
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return array dom_info array on success
     */
    public function domainInfo($domain)
    {
        $out = $this->sockWrite("dom_info $domain");
        $in  = $this->sockRead();
        if (!$this->statusOk($in)) {
            throw new Net_Vpopmaild_Exception($in);
        }
        return $this->readInfo();
    }
    /**
     * listDomains 
     * 
     * List domains.  The page and perPage arguments support
     * optional pagination.
     * 
     * @param int $page    page number, default 0 (don't paginate)
     * @param int $perPage domains per page, default 0 (don't paginate)
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return array domains array on success
     */
    public function listDomains($page = 0, $perPage = 0)
    {
        $return = $this->sockWrite("list_domains $page $perPage");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        $domains = array();
        $list    = array();
        $in      = $this->sockRead();
        while (!$this->dotOnly($in)
                && !$this->statusOk($in)
                && !$this->statusErr($in)) {
            list($parent, $domain) = explode(' ', $in, 2);
            $domains[$domain]      = $parent;
            $in                    = $this->sockRead();
        }
        return $domains;
    }


    /**
     * domainCount 
     * 
     * Count domains.
     * 
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return int count on success
     */
    public function domainCount()
    {
        $status = $this->sockWrite("domain_count");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        $in = $this->readInfo();
        if (array_key_exists('count', $in)) {
            return $in['count'];
        }
        throw new Net_Vpopmaild_Exception('Error getting domain count');
    }
    /**
     * addDomain 
     * 
     * Add domain.
     * 
     * @param string $domain   domain name
     * @param string $password password
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success
     */
    public function addDomain($domain, $password)
    {
        $status = $this->sockWrite("add_domain $domain $password");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        return true;
    }

    /**
     * addAliasDomain 
     * 
     * Add alias domain.
     * 
     * @param mixed $domain domain name
     * @param mixed $alias  alias name
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success
     */
    public function addAliasDomain($domain, $alias)
    {
        $status = $this->sockWrite("add_alias_domain $domain $alias");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        return true;
    }
    /**
     * delDomain 
     * 
     * Delete domain.
     * 
     * @param mixed $domain domain name
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success
     */
    public function delDomain($domain)
    {
        $status = $this->sockWrite("del_domain $domain");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        return true;
    }

    /**
     * findDomain 
     * 
     * Return page number that the domain occurs on.
     * 
     * @param mixed $domain  domain name
     * @param mixed $perPage domains per page
     *
     * @access public
     * @return mixed page number on success, null on on failure
     */
    public function findDomain($domain, $perPage = 0)
    {
        $status = $this->sockWrite("find_domain $domain $perPage");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            return null;
        }
        $in = $this->readInfo();
        if (empty($in) || !array_key_exists('page', $in)) {
            return null;
        }
        return $in['page'];
    }

    /**
     * addUser 
     * 
     * Add a user.
     * 
     * @param string $domain   domain name
     * @param string $user     user name
     * @param string $password password
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success
     */
    public function addUser($domain, $user, $password)
    {
        $status = $this->sockWrite("add_user $user@$domain $password");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        return true;
    }

    /**
     * delUser 
     * 
     * Delete a user.
     * 
     * @param string $domain domain name
     * @param string $user   user name
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true on success
     */
    public function delUser($domain, $user)
    {
        $status = $this->sockWrite("del_user $user@$domain");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        return true;
    }

    /**
     * getModUserParms 
     * 
     * Getter for $this->modUserParms
     * 
     * @access public
     * @return array     modUserParms array
     */
    public function getModUserParms()
    {
        return $this->modUserParms;
    }

    /**
     * modUser 
     * 
     * Modify a user.
     * 
     * @param string $domain   domain name
     * @param string $user     user name
     * @param array  $userInfo user info data
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return bool true success
     */
    public function modUser($domain, $user, $userInfo)
    {
        $userParms   = $this->getModUserParms();
        $flagParms   = $userParms['flagParms'];
        $stringParms = $userParms['stringParms'];

        $status = $this->sockWrite("mod_user $user@$domain");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        foreach ($stringParms as $name) {
            if (!empty($userInfo[$name])) {
                $value  = $userInfo[$name];
                $status = $this->sockWrite("$name $value");
            }
        }
        foreach ($flagParms as $name) {
            $flip   = false;
            $value  = $this->getGidBit($userInfo['gidflags'], $name, $flip);
            $value  = ($value) ? '1' : '0';
            $status = $this->sockWrite("$name $value");
        }
        $status = $this->sockWrite(".");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        return true;
    }
    /**
     * userInfo 
     * 
     * Get user info array.
     * 
     * @param string $domain domain name
     * @param string $user   user name
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return array user info array on success
     */
    public function userInfo($domain, $user)
    {
        $status = $this->sockWrite("user_info $user@$domain");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        return $this->readInfo();
    }

    /**
     * listUsers 
     * 
     * List users for a domain.  page and perPage
     * arguments allow for optional pagination.
     * 
     * @param string $domain  domain name
     * @param int    $page    page number
     * @param int    $perPage domains per page
     *
     * @access public
     * @return array users array on success, null on failure
     */
    public function listUsers($domain, $page = 0, $perPage = 0)
    {
        $status = $this->sockWrite("list_users $domain $page $perPage");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            return null;
        }

        $this->recordio("<<--  Start collecting user data  -->>");
        $list = array();
        $in   = $this->sockRead();
        while (!$this->dotOnly($in)
                && !$this->statusOk($in)
                && !$this->statusErr($in)) {
            if (empty($in)) {
                $in = $this->sockRead();
                continue;
            }
            list($name, $value) = explode(' ', $in, 2);
            if ('name' == $name) {
                if (!empty($currentName)) {
                    $list[$currentName] = $user;
                }
                $currentName = $value;
                $user        = array();
            } else {
                $user[$name] = trim($value);
            }
            $in = $this->sockRead();
        }
        if (!empty($currentName)) {
            $list[$currentName] = $user;
        }
        $this->recordio("<<--  Stop collecting user data  -->>");
        return $list;
    }
    /**
     * userCount 
     * 
     * Count users in a domain.
     * 
     * @param string $domain domain name
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return int count on success
     */
    public function userCount($domain)
    {
        $status = $this->sockWrite("user_count $domain");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        $in = $this->readInfo();
        if (array_key_exists('count', $in)) {
            return $in['count'];
        }
        throw new Net_Vpopmaild_Exception($status);
    }

    /**
     * getLastAuth 
     * 
     * Get last authentication time and IP address as an array 
     * for a user.
     * 
     * @param string $domain domain name
     * @param string $user   user name
     *
     * @access public
     * @throws Net_Vpopmaild_Exception on failure
     * @return array array('time' => (timestamp), 'ip' => IP (0.0.0.0 for none))
     */
    public function getLastAuth($domain, $user)
    {
        $status = $this->sockWrite("get_lastauth $user@$domain");
        $status = $this->sockRead();
        if (!$this->statusOk($status)) {
            throw new Net_Vpopmaild_Exception($status);
        }
        $in = $this->readInfo();
        return $in;
    }

    /**
     * authenticate 
     * 
     * Authenticate user based on email and password
     * 
     * @param string $email    email address
     * @param string $password password
     *
     * @access public
     * @return bool true on success, false on failure
     */
    public function authenticate($email, $password)
    {
        try {
            $this->clogin($email, $password);
        } catch (Net_Vpopmaild_Exception $e) {
            return false;
        }
        // Easy way to access domain
        $email_array               = explode('@', $email);
        $this->loginUser['domain'] = $email_array[1];
        return true;
    }

    /**
     * isSysAdmin 
     * 
     * Does this $loginUser (user info array)  have system administrator 
     * rights?  The optional $loginUser (array) argument allows a 
     * system administrator to check the rights of a different account.
     * 
     * @param array $loginUser user account info
     *
     * @access public
     * @return bool result of getGidBit()
     * @see getGidBit()
     */
    public function isSysAdmin($loginUser = null)
    {
        if (is_null($loginUser)) {
            $loginUser = $this->loginUser;
        }
        return $this->getGidBit($loginUser['gidflags'], 'system_admin_privileges');
    }

    /**
     * isDomainAdmin 
     * 
     * Determine if the user is a domain administrator for a domain.  Optional
     * $loginUser argument defaults to the logged in user.
     * 
     * @param mixed  $domain    domain name
     * @param string $loginUser user account info
     * 
     * @access public
     * @return void
     */
    public function isDomainAdmin($domain, $loginUser = null)
    {
        if (is_null($loginUser)) {
            $loginUser = $this->loginUser;
        }
        if ($this->isSysAdmin($loginUser)) {
            return true;
        }
        if ($this->getGidBit($loginUser['gidflags'], 'domain_admin_privileges')) {
            return true;
        }
        if (($loginUser['name'] == 'postmaster')
            && $domain == $loginUser['domain']) {
            return true;
        }
        return false;
    }

    /**
     * isUserAdmin 
     * 
     * Determine if this user have privileges on this account.
     * Will not work if you did not authenticate using authenticate(),
     * as it relies on $this->loginUser['domain'] to be set.
     * 
     * @param string $name   user name
     * @param string $domain domain name
     *
     * @access public
     * @return void
     * @see authenticate()
     */
    public function isUserAdmin($name, $domain)
    {
        if ($this->isDomainAdmin($domain)) {
            return true;
        }
        return (($this->loginUser['name'] == $name) 
                && ($this->loginUser['domain'] == $domain));
    }

    /**
     * getQuota 
     * 
     * Format a maildir++ quota as human readable (10MB).
     * 
     * @param string $quota quota string
     *
     * @access public
     * @return string formatted quota string
     */
    public function getQuota($quota)
    {
        if (preg_match('/S$/', $quota)) {
            $quota = preg_replace('/S$/', '', $quota);
            $quota = $quota/1024;
            $quota = $quota/1024;
            $quota = $quota.'MB';
        }
        return $quota;
    }

    /**
     * Get Vacaation Message Contents
     *
     * Parse .qmail line contents to get message subject and meessage body.
     *
     * @param array  $user_info user info
     * @param string $line      .qmail line with path to vacation message.
     * defaults to ''
     *
     * @access public
     * @return mixed vacation array on success, null on failure
     */
    public function getVacation($user_info, $line = '')
    {
        if ($line == '') {
            $path = $user_info['user_dir'].'/vacation/message';
        } else {
            $line  = preg_replace('/^[|][ ]*/', '', $line);
            $array = explode(' ', $line);
            $path  = $array[3];
        }
        try {
            $contents = $this->readFile($path);
        } catch (Net_Vpopmaild_Exception $e) {
            return null;
        }
        if (!is_array($contents)) {
            return null;
        }
        array_shift($contents); // Eat From: address
        $subject = substr(array_shift($contents), 9);
        array_shift($contents); // Eat blank line
        return array(   'vacation_subject' => $subject,
                        'vacation_body' => implode("\n", $contents),
                        'vacation' => ' checked');
    }
    /**
     * setVacation 
     * 
     * Set vacation message (autorespond) values.
     * 
     * @param string $user        user name
     * @param string $domain      domain name
     * @param string $subject     subject line
     * @param string $message     message contents
     * @param string $vacationDir vacation directory name, 
     * defaults to 'vacation'
     *
     * @access public
     * @return void
     */
    public function setVacation($user,
                                $domain,
                                $subject,
                                $message,
                                $vacationDir = 'vacation')
    {
        $messageFile = $vacationDir . '/message';
        $contents    = array( "From: $user@$domain",
                            "Subject: $subject",
                            '',
                            $message);
        // We don't care of the directory doesn't exist
        // So catch the exception silently
        try {
            $this->rmDir($domain, $user, $vacationDir);
        } catch (Net_Vpopmaild_Exception $e) {
        }
        $this->mkDir($domain, $user, $vacationDir);
        $this->writeFile($contents, $domain, $user, $messageFile);
    }

    /**
     * delVacation 
     * 
     * Delete vacation message.
     * 
     * @param string $user        user name
     * @param string $domain      domain name
     * @param string $vacationDir vacation directory name, defaults to 'vacation'
     *
     * @access public
     * @return bool true on success, false on failure
     */
    public function delVacation($user, $domain, $vacationDir = 'vacation')
    {
        return $this->rmDir($domain, $user, $vacationDir);
    }

    /**
     * getAliasContents 
     * 
     * Take array of alias destinations and format them as 
     * a comma delimited list (qmailadmin style)
     * 
     * @param mixed $contentsArray alias contents
     *
     * @access public
     * @return string alias contents as string
     */
    public function getAliasContents($contentsArray)
    {
        $count  = 0;
        $string = '';

        while (list($key, $val) = each($contentsArray)) {
            if ($count > 0) {
                $string .= ', ';
            }
            $string .= preg_replace('/^&/', '', $val);
            $count++;
        }
        return $string;
    }

    /**
     * aliasesToArray 
     * 
     * Take raw listAlias() output, and format into 
     * associative arrays
     * 
     * @param array $aliasArray aliases
     * 
     * @access protected
     * @return array array of aliases
     */
    protected function aliasesToArray($aliasArray)
    {
        // generate unique list of aliases
        $aliasList = array();

        while (list($key, $val) = each($aliasArray)) {
            $alias = preg_replace('/(^[^ ]+) .*$/', '$1', $val);
            if (!in_array($alias, $aliasList)) {
                array_push($aliasList, $alias);
            }
        }
        // Now create content arrays
        $contentArray = array();
        reset($aliasList);
        while (list($key, $val) = each($aliasList)) {
            reset($aliasArray);
            $count = 0;

            while (list($lkey, $lval) = each($aliasArray)) {
                if (preg_match("/^$val /", $lval)) {
                    $contentArray[$val][$count] = 
                        preg_replace('/^[^ ]+ (.*$)/', '$1', $lval);
                    $count++;
                }
            }
        }
        return $contentArray;
    }

    /**
     * displayForwardLine
     *
     * Remove '&' prefix from a forward line for display.
     *
     * @param string $line forward line
     *
     * @access public
     * @return string parsed forward string
     */
    public function displayForwardLine($line)
    {
        return preg_replace('/^&/', '', $line);
    }

    /**
     * parseAliases 
     * 
     * Return correct type of aliases - forwards, responders, or lists (ezmlm)
     * 
     * @param array  $in_array aliases array
     * @param string $type     type to look for (forwards, responders, lists)
     * 
     * @access public
     * @return array array of parsed aliases
     */
    public function parseAliases($in_array, $type)
    {
        $out_array = array();
        foreach ($in_array as $parentkey => $parentval) {
            $is_type = 'forwards';
            foreach ($parentval as $key => $val) {
                if (preg_match('([|].*' . $this->vpopmailRobotProgram . ')', $val)) {
                    $is_type = 'responders';
                    break;
                }
                if (preg_match('/[|].*ezmlm-/', $val)) {
                    $is_type = 'lists';
                    break;
                }
            }
            if ($type == $is_type) {
                $out_array[$parentkey] = $parentval;
            }
        }
        return $out_array;
    }

    /**
     * paginateArray 
     * 
     * A simple function to paginate an array.
     * 
     * @param array $array array to paginate
     * @param int   $page  page to get
     * @param int   $limit limit
     * 
     * @access public
     * @return array paginated array
     */
    public function paginateArray($array, $page, $limit)
    {
        $page_count  = 1;
        $limit_count = 1;
        $out_array   = array();

        while ((list($key, $val) = each($array)) && $page_count <= $page) {
            if ($page_count == $page) {
                $out_array[$key] = $val;
            }
            $limit_count++;
            if ($limit_count > $limit) {
                $limit_count = 1;
                $page_count++;
            }
        }
        return $out_array;
    }
}
?>
