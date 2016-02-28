GoogleEmailSearcher is a PHP class to access google mailboxes by POP3/IMAP/NNTP using PHP's native IMAP extension and searchs messages by your own criteria.

### Features

* Connect to mailbox by POP3/IMAP/NNTP, using [imap_open()](http://php.net/imap_open)
* Get mailboxes
* Search emails by any text
* Search emails by Subject
* Search emails by Body
* Search emails by From and To
* Search emails by BCC, CC
* Search emails by exact date, date before or date since
* Search emails by Keyword
* Search emails with Deleted flag
* Search emails with Undeleted flag
* Search emails with Flagged flag
* Search emails with Unflagged flag
* Search emails with New flag
* Search emails with Old flag
* Search emails with Recent flag
* Search emails with Seen flag
* Search emails with Unseen flag
* Search emails with Answered flag
* Search emails with Unanswered flag
 
### Requirements

* IMAP extension must be present and port 993 must be allowed in firewall


### Usage example

```php
// creating object with arguments username and password. You can also leave empty these arguments, later set user name and password with setUserName & setPassword methods
$gim = new GoogleEmailSearcher("username@gmail.com", "*******");

try {
    // Preparing search criterias
    $gim->matchText("welcome") // send search keyword
        ->matchSince("01/01/2016") // send date to start search after
        ->matchFrom("user@example.com") // send from address to filter
        ->matchNew(true) // search only in New flagged messages
        ->connect(); // connecting to google server


    // get messages with header information and
    $mails = $mailbox->searchMessage(
        GoogleEmailSearcher::ORDER_BY_SIZE, // order by size 
        true // ascending order (default descending)
    );

    foreach($mails as $key => $row) {
        var_dump($row); // message header info like from, to, reply to addresses, subject, date and etc.

        var_dump($this->getMessage($row['uid']); // returns message full header and content
    }
} catch(Exception $e) {
    echo $e->getMessage();
}

```
