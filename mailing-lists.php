<?php
// $Id$
$_SERVER['BASE_PAGE'] = 'mailing-lists.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/include/prepend.inc';
include_once $_SERVER['DOCUMENT_ROOT'] . '/include/email-validation.inc';
commonHeader("Mailing Lists");

// Some mailing list is selected for [un]subscription
if (isset($_POST['maillist'])) {
    
    // No error found yet
    $error = "";
    
    // Check email address
    if (empty($_POST['email']) || $_POST['email'] == 'user@example.com' ||
        $_POST['email'] == 'fake@from.net' || !is_emailable_address($_POST['email'])) {
        $error = "You forgot to specify an email address to be added to the list, or specified an invalid address." .
                 "<br />Please go back and try again.";
    }
    
    // Seems to be a valid email address
    else {
 
        // Decide on request mode, email address part and IP address
        $request = strtolower($_POST['action']);
        if ($request != "subscribe" && $request != "unsubscribe") {
            $request = "subscribe";
        }
        $sub = str_replace("@", "=", $_POST['email']);
        $remote_addr = i2c_realip();

        // Try to send mail
        $mail_sent = mail(
            "$maillist-$request-$sub@lists.php.net",
            "Website Subscription", 
            "This was a request generated from the form at http://php.net/mailing-lists.php by $remote_addr.",
            "From: {$_GET['email']}\r\n"
        );
        
        // Provide error if unable to send mail
        if (!$mail_sent) {
            $error = "We were unable to subsribe you due to some technical problems.<br/ >" .
                     "Please try again later.";
        }
    }
    
    // Give error information or success report
    if (!empty($error)) {
        echo "<p class=\"formerror\">$error</p>";
    } else {
?>
<p>
 A request has been entered into the mailing list processing queue. You
 should receive an email at <?php echo $_GET['email']; ?> shortly describing
 how to complete your request.
</p>
<?php
    }
    commonFooter();
    exit;
}

// No form submitted
?>
<h1>Mailing Lists</h1>

<p>
 There are many PHP-related mailing lists available on our server.
 Most of them are archived, and all of them are available as newsgroups
 on our <a href="news://news.php.net">news server</a>. The archives
 are searchable. There is an experimental web interface for the news
 server at <a href="http://news.php.net/">http://news.php.net/</a>.
</p>

<p>
 <b>Note:</b> When posting to mailing lists or newsgroups, please keep 
 the following in mind:
</p>

<ul>
 <li>
  Use a valid email address. Every new poster's email address
  is checked for validity through confirmation.
 </li>
 <li>
  Send plain ASCII messages, no HTML-formatted emails please.
 </li>
 <li>
  Turn on wordwrapping so your entire message doesn't show up on
  a single line.
 </li>
 <li>
  No attachments please, just post a URL if you want someone to
  look at something.
 </li>
 <li>
  Don't GPG/PGP sign your messages. If you want people to be able
  to send you encrypted email, stick your key-locator in your signature
 </li>
 <li>
  Don't hijack other peoples' threads. To post on a new topic, start
  a new message, don't reply and just change the subject.
 </li>
 <li>
  Check the archives before posting a question, chances are it has
  already been asked and answered a few times.
 </li>
 <li>
  When asking a question, don't just tell us, &quot;it doesn't work&quot;.
  Tell us what you are trying to accomplish, a <strong>short</strong> code
  snippet showing how you tried to solve it, what you expected to get and
  what you got instead.
 </li>
</ul>
<?php

  // array of lists (list, name, short desc., moderated, archive, digest, newsgroup)
  $general_mailing_lists = array(

    'General mailing lists for PHP users',
    array (
      'php-announce', 'Announcements',
      'Announcements of new PHP releases are sent to this very low-volume list',
      TRUE, FALSE, FALSE, "php.announce"
    ),
    array (
      'php-general', 'General user list',
      'This is a really high volume general list for PHP users',
      FALSE, TRUE, TRUE, "php.general"
    ),
    array (
      'php-windows', 'Windows PHP users list',
      'Using PHP on Microsoft Windows',
      FALSE, TRUE, TRUE, "php.windows"
    ),

    'Subject specific lists for PHP users',
    array (
      'php-install', 'Installation issues and problems',
      'How to install PHP with partiucular configurations, and servers',
      FALSE, TRUE, TRUE, "php.install"
    ),
    array (
      'php-db', 'Databases and PHP',
      'This list is for the discussion of PHP database topics',
      FALSE, TRUE, TRUE, "php.db"
    ),
    array (
      'php-i18n', 'Internationalization',
      'Internationalization (i18n) and localization (l10n) issues and features',
      FALSE, TRUE, TRUE, "php.i18n"
    ),
    array (
      'php-evangelism', 'PHP evangelism mailing list',
      'A list for people interested in promoting PHP and learning good reasons to support PHP in the enterprise',
      TRUE, TRUE, TRUE, "php.evangelism"
    ),	
    array (
      'soap', 'PHP SOAP list',
      'List for the SOAP developers',
      FALSE, FALSE, FALSE, 'php.soap'
    ),

    'PEAR mailing lists',
    array (
      'pear-general', 'PEAR general list',
      'A list for users of PEAR, an open-source common code library for PHP',
      FALSE, TRUE, TRUE, "php.pear.general"
    ),
    array (
      'pear-dev', 'PEAR developers list',
      'A list for developers of PEAR, an open-source common code library for PHP',
      FALSE, TRUE, TRUE, "php.pear.dev"
    ),
    array (
      'pear-doc', 'PEAR documentation list',
      'A list involving the documentation of PEAR, an open-source common code library for PHP',
      FALSE, FALSE, TRUE, "php.pear.doc"
    ),
    array (
      'pear-cvs', 'PEAR CVS list',
      'All the commits of the cvs PEAR code repository are posted to this list automatically',
      FALSE, TRUE, TRUE, "php.pear.cvs"
    ),

    'PHP-GTK mailing lists',
    array (
      'php-gtk-general', 'PHP-GTK general list',
      'A list for users of PHP-GTK, An extension for PHP making it possible to write client-side GUI applications' ,
      FALSE, TRUE, TRUE, "php.gtk.general"
    ),
    array (
      'php-gtk-doc', 'PHP-GTK documentation list',
      'List for the authors of the PHP-GTK documentation with CVS commits',
      FALSE, TRUE, FALSE, "php.gtk.doc"
    ),
    array (
      'php-gtk-dev', 'PHP-GTK developers list',
      'A list for the developers of PHP-GTK, An extension for PHP making it possible to write client-side GUI applications' ,
      FALSE, TRUE, TRUE, "php.gtk.dev"
    ),
    array (
      'php-gtk-cvs', 'PHP-GTK CVS list',
      'All the commits to the cvs PHP-GTK code repository are posted to this list automatically' ,
      FALSE, TRUE, TRUE, "php.gtk.cvs"
    ),

    'Smarty template engine project mailing lists',
    array (
      'smarty-general', 'General user list',
      'This is a medium volume general list for Smarty users',
      FALSE, TRUE, TRUE, "php.smarty.general"
    ),
    array (
      'smarty-dev', 'Smarty developers list',
      'A list for Smarty developers' ,
      FALSE, TRUE, TRUE, "php.smarty.dev"
    ),
    array (
      'smarty-cvs', 'Smarty CVS commit list',
      'All the commits of the cvs Smarty code repository are posted to this list automatically',
      TRUE, TRUE, FALSE, "php.smarty.cvs"
    ),
  );

  // array of lists (list, name, short desc., moderated, archive, digest, newsgroup)
  $internals_mailing_lists = array(

    'PHP and Zend Engine internals lists',
    array (
      'internals', 'Internals list',
      'A medium volume list for those who want to help out with the development of PHP',
      FALSE, 'php-dev', TRUE, "internals"
    ),
    array (
      'php-cvs', 'CVS commit list',
      'All the commits of the cvs PHP code repository are posted to this list automatically',
      TRUE, TRUE, FALSE, "php.cvs"
    ),
    array (
      'php-cvs-daily', 'Daily CVS commit summary',
      'Daily changelog and NEWS file updates',
      TRUE, FALSE, FALSE, ""
    ),
    array (
      'zend-engine-cvs', 'Zend Engine CVS commit list',
      'All the commits of the Zend Engine CVS modules are posted to this list automatically',
      FALSE, FALSE, TRUE, "php.zend-engine.cvs"
    ),
    array (
      'php-qa', 'Quality Assurance list',
      'List for the members of the PHP-QA Team',
      FALSE, TRUE, FALSE, "php.qa"
    ),

    'PHP documentation mailing lists',
    array (
      'phpdoc', 'PHP documentation list',
      'List for the authors of the PHP documentation with CVS commits',
      FALSE, TRUE, FALSE, "php.doc"
    ),
    array (
      'php-doc-chm', 'PHP CHM documentation list',
      'List for developers of the Windows Help-format PHP documentation',
      FALSE, FALSE, TRUE, "php.doc.chm"
    ),
  );

// Print out a table for a given list array
function output_lists_table($mailing_lists)
{
    echo '<table cellpadding="5" border="0" class="standard">', "\n";
    while ( list(, $listinfo) = each($mailing_lists)) {
        if (!is_array($listinfo)) {
            echo "<tr><th>{$listinfo}</th><th>Moderated</th><th>Archive</th>" .
                 "<th>Newsgroup</th><th>Normal</th><th>Digest</th></tr>\n";
        } else {
            echo '<tr align="center">';
            echo '<td align="left"><strong>' . $listinfo[1] . '</strong><br /><small>'. $listinfo[2] . '</small></td>';
            echo '<td>' . ($listinfo[3] ? 'yes' : 'no') . '</td>';

            // Let the list name defined with a string, if the
            // list is archived under a different name then php.net
            // uses for it (for backward compatibilty for example)
            if ($listinfo[4] !== FALSE) {
                $larchive = ($listinfo[4] === TRUE ? $listinfo[0] : $listinfo[4]);
            } else { $larchive = FALSE; }
            echo '<td>' . ($larchive ? make_link("http://marc.theaimsgroup.com/?l=" . $larchive, 'yes') : 'n/a') . '</td>';
            echo '<td>' . ($listinfo[6] ? make_link("news://news.php.net/" . $listinfo[6], 'yes') : 'n/a') . '</td>';
            echo '<td><input name="maillist" type="radio" value="' . $listinfo[0] . '" /></td>';
            echo '<td>' . ($listinfo[5] ? '<input name="maillist" type="radio" value="' . $listinfo[0] . '-digest" />' : 'n/a' ) . '</td>';
            echo "</tr>\n";
        }
    }
    echo "</table>\n";
}

?>

<form method="post" action="http://www.php.net/mailing-lists.php">

<h2><a name="general">General Mailing Lists</a></h2>

<?php output_lists_table($general_mailing_lists); ?>

<h2><a name="internals">Internals Mailing Lists</a></h2>

<?php output_lists_table($internals_mailing_lists); ?>

<p align="center">
 <strong>Email:</strong>
 <input type="text" name="email" size="40" value="user@example.com" />
 <input type="submit" name="action" value="Subscribe" />
 <input type="submit" name="action" value="Unsubscribe" />
</p>

</form>

<p>
 You will be sent a confirmation mail at the address you wish to
 be subscribed or unsubscribed, and only added to the list after
 following the directions in that mail.
</p>

<p>
 There are a variety of commands you can use to modify your subscription.
 Either send a message to php-whatever-help@lists.php.net (as in,
 php-general-help@lists.php.net) or you can view the commands for
 ezmlm <a href="http://www.ezmlm.org/ezman-0.32/ezman1.html">here.</a>
</p>

<p>
 Archives for some of these mailing lists are also available at
 <a href="http://www.phpbuilder.com/mail/">http://www.phpbuilder.com/mail/</a>.
</p>

<p>
 <strong>Warning:</strong> The PHP users mailing list gets close to 2500
 messages per month currently. Do the math. That translates to about 80
 messages per day. If your mailbox can't handle this sort of traffic you
 might want to consider subscribing to the digest list instead (two messages
 per day), using the news server, or reading the mailing list using the
 archives. 
</p>

<p>
 You can search the main mailing lists right from this website from <a
 href="/search.php">the search page</a> or by using the search input box
 selecting the appropriate option on the top-right of every page.
</p>

<hr />

<h2><a name="local">Local Mailing Lists</a></h2>

<p>
 There are also some mailing lists and newsgroups for PHP users
 that have been set up by others in the community to host local
 (particularly non-English) discussions.
</p>

<ul>
 <li><a href="news:dk.edb.internet.webdesign.serverside.php">Danish Newsgroup</a></li>
 <li><a href="http://www.ilovephp.com/mailinglist/">French Mailing List</a></li>
 <li><a href="news:fr.comp.infosystemes.www.auteurs.php">French Newsgroup</a></li>
 <li><a href="http://www.phpbar.de/">German Mailing List</a></li>
 <li><a href="news:de.comp.lang.php.misc">German Newsgroup</a> (also other topics under de.comp.lang.php)</li>
 <li><a href="http://weblabor.hu/wl-phplista/etikett">Hungarian Mailing List</a></li>
 <li><a href="http://groups.yahoo.com/group/php_greek">Greek Mailing List</a></li>
 <li><a href="http://www.ziobudda.net/mailman/listinfo/php-it/">Italian Mailing List</a></li>
 <li><a href="http://cortesi.com/php/">Italian Mailing List (coordinating the translation of the PHP manual)</a></li>
 <li><a href="http://groups.yahoo.com/group/php-pt">Portuguese Mailing List</a></li>
 <li><a href="mailto:lista-subscribe@phpes.com">Spanish Mailing List</a></li>
 <li><a href="http://phptr.baslangic.net/">Turkish Mailing List</a></li>
 <li><a href="http://ns1.php.gr.jp/ml.html">The Japanese PHP User Group's Mailing lists</a></li>
</ul>

<?php commonFooter(); ?>
