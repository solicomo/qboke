For Apache
==========

<pre>

# BEGIN QBoke

RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]

# END QBoke

</pre>

For Nginx
=========

<pre>

# BEGIN QBoke

if (!-d $request_filename){
	set $fd nd;
}
if (!-f $request_filename){
	set $fd nf$fd;
}
if ($fd = "nfnd"){
	rewrite (.*) /index.php last;
}

# END QBoke

</pre>