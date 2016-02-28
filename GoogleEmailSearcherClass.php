class GoogleEmailSearcher {
    
    private $imapAddress = "{imap.gmail.com:993/imap/ssl}";
    private $imapMainBox;
    private $maxMessageCount;
    private $user;
    private $password;
    private $serverEncoding;
    private $conn;
    private $messageSearchCriteria = "";
    

    const ORDER_BY_DATE = SORTDATE;
    const ORDER_BY_ARRIVAL = SORTARRIVAL;
    const ORDER_BY_FROM = SORTFROM;
    const ORDER_BY_SUBJECT = SORTSUBJECT;
    const ORDER_BY_TO = SORTTO;
    const ORDER_BY_CC = SORTCC;
    const ORDER_BY_SIZE = SORTSIZE;

    public function __construct($user = null, $password = null, $maxMessageCount = null, $imapMainBox = "INBOX", $serverEncoding = "UTF-8") {
        $this->imapMainBox = $imapMainBox;
        $this->maxMessageCount = $maxMessageCount;
        $this->user = $user;
        $this->password = $password;
        $this->serverEncoding = $serverEncoding;
    }

    public function connect() {
        if ($this->conn = imap_open($this->imapAddress . $this->imapMainBox, $this->user, $this->password)) {
            return $this;
        }

        throw new Exception("Can't connect to " . $this->imapAddress . "; reason is: " . imap_last_error());
    }

    public function getImapAddress() {
        return $this->imapAddress;
    }

    public function getImapMainBox() {
        return $this->imapMainBox;
    }

    public function getMaxMessageCount() {
        return $this->maxMessageCount;
    }

    public function getUser() {
        return $this->user;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setImapAddress($imapAddress) {
        $this->imapAddress = $imapAddress;
        return $this;
    }

    public function setImapMainBox($imapMainBox) {
        $this->imapMainBox = $imapMainBox;
        return $this;
    }

    public function setMaxMessageCount($maxMessageCount) {
        $this->maxMessageCount = $maxMessageCount;
        return $this;
    }

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function getMailBoxesList($searchKeyWord = "*") {
        $var = imap_listmailbox($this->conn, $this->imapAddress, $searchKeyWord);
        if (!$var) {
            throw new Exception("Can't list mailboxes: " . imap_last_error());
        }

        return $var;
    }

    private function getHeaders() {
        $var = imap_headers($this->conn);
        if (!$var) {
            throw new Exception("Can't get headers: " . imap_last_error());
        }

        return $var;
    }

    public function getTotalMessagesCount() {
        return count($this->getHeaders());
    }

    private function getMessageHeaderInfo($number) {
        $var = imap_headerinfo($this->conn, $number);

        if (!$var) {
            throw new Exception("Couldn't get header for message " . $number . " : " . imap_last_error());
        }

        return $var;
    }

    public function getMessageBody($number) {
        $var = imap_body($this->conn, $number);

        if (!$var) {
            throw new Exception("Couldn't get body of message " . $number . " : " . imap_last_error());
        }

        return $var;
    }

    public function matchSubject($keyWord) {
        if (trim($keyWord)) {
            $this->messageSearchCriteria .= 'SUBJECT "' . $keyWord . '" ';
        }

        return $this;
    }

    public function matchFrom($keyWord) {
        if (trim($keyWord)) {
            $this->messageSearchCriteria .= 'FROM "' . $keyWord . '" ';
        }

        return $this;
    }

    public function matchTo($keyWord) {
        if (trim($keyWord)) {
            $this->messageSearchCriteria .= 'TO "' . $keyWord . '" ';
        }

        return $this;
    }

    public function matchBCC($keyWord) {
        if (trim($keyWord)) {
            $this->messageSearchCriteria .= 'BCC "' . $keyWord . '" ';
        }

        return $this;
    }

    public function matchCC($keyWord) {
        if (trim($keyWord)) {
            $this->messageSearchCriteria .= 'CC "' . $keyWord . '" ';
        }

        return $this;
    }

    public function matchDateOn($keyWord) {
        if (trim($keyWord)) {
            $this->messageSearchCriteria .= 'ON "' . $keyWord . '" ';
        }

        return $this;
    }

    public function matchDateBefore($keyWord) {
        if (trim($keyWord)) {
            $this->messageSearchCriteria .= 'BEFORE "' . $keyWord . '" ';
        }

        return $this;
    }

    public function matchDateSince($keyWord) {
        if (trim($keyWord)) {
            $this->messageSearchCriteria .= 'SINCE "' . $keyWord . '" ';
        }

        return $this;
    }

    public function matchBody($keyWord) {
        if (trim($keyWord)) {
            $this->messageSearchCriteria .= 'BODY "' . $keyWord . '" ';
        }

        return $this;
    }

    public function matchKeyword($keyWord) {
        if (trim($keyWord)) {
            $this->messageSearchCriteria .= 'KEYWORD "' . $keyWord . '" ';
        }

        return $this;
    }

    public function matchText($keyWord) {
        if (trim($keyWord)) {
            $this->messageSearchCriteria .= 'TEXT "' . $keyWord . '" ';
        }

        return $this;
    }

    public function matchUnkeyword($keyWord) {
        if (trim($keyWord)) {
            $this->messageSearchCriteria .= 'UNKEYWORD "' . $keyWord . '" ';
        }

        return $this;
    }

    public function matchDeleted($flag = false) {
        if ($flag === true) {
            $this->messageSearchCriteria .= 'DELETED ';
        }

        return $this;
    }

    public function matchFlagged($flag = false) {
        if ($flag === true) {
            $this->messageSearchCriteria .= 'FLAGGED ';
        }

        return $this;
    }

    public function matchNew($flag = false) {
        if ($flag === true) {
            $this->messageSearchCriteria .= 'NEW ';
        }

        return $this;
    }

    public function matchOld($flag = false) {
        if ($flag === true) {
            $this->messageSearchCriteria .= 'OLD ';
        }

        return $this;
    }

    public function matchRecent($flag = false) {
        if ($flag === true) {
            $this->messageSearchCriteria .= 'RECENT ';
        }

        return $this;
    }

    public function matchSeen($flag = false) {
        if ($flag === true) {
            $this->messageSearchCriteria .= 'SEEN ';
        }

        return $this;
    }

    public function matchUnanswered($flag = false) {
        if ($flag === true) {
            $this->messageSearchCriteria .= 'UNANSWERED ';
        }

        return $this;
    }

    public function matchUndeleted($flag = false) {
        if ($flag === true) {
            $this->messageSearchCriteria .= 'UNDELETED ';
        }

        return $this;
    }

    public function matchUnflagged($flag = false) {
        if ($flag === true) {
            $this->messageSearchCriteria .= 'UNFLAGGED ';
        }

        return $this;
    }

    public function matchUnseen($flag = false) {
        if ($flag === true) {
            $this->messageSearchCriteria .= 'UNSEEN ';
        }

        return $this;
    }

    public function matchAnswered($flag = false) {
        if ($flag === true) {
            $this->messageSearchCriteria .= 'ANSWERED ';
        }

        return $this;
    }

    public function getMessage($uid) {
        return array(
            'uid' => $uid,
            'header' => $this->getMessageHeaderInfo($uid),
            'body' => $this->getMessageBody($uid)
        );
    }

    public function searchMessage($sort = self::ORDER_BY_DATE, $reverse = false, $seUIDS = false) {
        
        $refl = new ReflectionClass(__CLASS__);
        
        if(!in_array($sort, $refl->getConstants())) {
            $sort = self::ORDER_BY_DATE;
        }
        
        if (!trim($this->messageSearchCriteria)) {
            $criteria = "ALL";
        } else {
            $criteria = $this->messageSearchCriteria;
        }

        if ($reverse === true) {
            $reverse = 1;
        } else {
            $reverse = 0;
        }
        
        echo $sort == self::ORDER_BY_SIZE;

        $var = imap_sort($this->conn, $sort, $reverse, ($seUIDS === true ? SE_UID : SE_FREE), $criteria, $this->serverEncoding);

        if (is_array($var) && !empty($var)) {
            foreach ($var as $key => $val) {
                $info = $this->getMessageHeaderInfo($val);
                $var[$key] = array(
                    'uid' => $val,
                    'date' => $info->date,
                    'subject' => $info->subject,
                    'to' => array(
                        'email' => $info->to[0]->mailbox . "@" . $info->to[0]->host,
                        'personal' => property_exists($info->to[0], "personal") ? $info->to[0]->personal : null
                    ),
                    'from' => array(
                        'email' => $info->from[0]->mailbox . "@" . $info->from[0]->host,
                        'personal' => property_exists($info->from[0], "personal") ? $info->from[0]->personal : null
                    ),
                    'reply_to' => array(
                        'email' => $info->reply_to[0]->mailbox . "@" . $info->reply_to[0]->host,
                        'personal' => property_exists($info->reply_to[0], "personal") ? $info->reply_to[0]->personal : null
                    ),
                    'sender' => array(
                        'email' => $info->sender[0]->mailbox . "@" . $info->sender[0]->host,
                        'personal' => property_exists($info->sender[0], "personal") ? $info->sender[0]->personal : null
                    ),
                    'recent' => $info->Recent,
                    'unseen' => $info->Unseen,
                    'flagged' => $info->Flagged,
                    'answered' => $info->Answered,
                    'deleted' => $info->Deleted,
                    'draft' => $info->Draft,
                    'size' => $info->Size,
                    'udate' => $info->udate
                );
            }
        }

        return $var;
    }

    public function __destruct() {
        if ($this->conn) {
            imap_close($this->conn);
        }
    }

}
