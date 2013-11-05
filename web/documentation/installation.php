title: "Installation Guide", doc: "installation", @import "template.php"

"""
<h2>Step 1. Decide where you want to use SimplifiedPHP</h2>

<blockquote>
  <p><a href="https://www.digitalocean.com/?refcode=a45a81ffc828" style="
    height: 144px;
    display: inline-block;
    width: 227px;
    background: url(../static/digital-ocean.jpg);"></a></p>
  <p><a href="https://www.digitalocean.com/?refcode=a45a81ffc828">Digital Ocean</a>* has SSD-backed servers starting at just $5/month.<br/>
  <sub><i>* Disclaimer: signing up using this link helps pay for the hosting of this website.</i></sub></p>
  <p>You can run SimplifiedPHP on any Linux, Windows, or Mac OS computer that can run PHP.</p>
</blockquote>

<h2>Step 2. <a href="http://www.php.net/manual/en/install.php">Install PHP</a> (and Apache, if desired)</h2>

<p>Ensure that you are able to run any PHP code before proceeding.</p>

<h2>Step 3. <a href="https://help.github.com/articles/set-up-git">Install Git</a>

<h2>Step 4. Clone SimplifiedPHP</h2>

<blockquote>
  <p>Example for Linux / Mac OS X Terminal:</p>
  <pre><code class="bash">cd /var/lib &amp;&amp; git clone git@github.com:NateFerrero/simplified-php.git</code></pre>
  <p>Don't want to use Git? You may also download
    <a href="https://github.com/NateFerrero/simplified-php/archive/master.zip">simplified-php-master.zip</a>,
    extract the folder, rename it to simplified-php, and place it at the desired location.</p>
</blockquote>

<h2>Step 5. Install the SPHP Command</h2>
<blockquote>
  <p>Example for Linux / Mac OS X Terminal:</p>
  <pre><code class="bash">cd /var/lib &amp;&amp; php ./simplified-php/install.php</code></pre>
  <p>Congratulations! You can now execute SimplifiedPHP from the command line:</p>
  <pre><code class="bash">sphp -c "'Hello World\\n'.print"</code></pre>
</blockquote>

<h2>Step 6. Use SimplifiedPHP for all PHP files with Apache</h2>
<blockquote>
  <p>Create an .htaccess file containing the following directives:</p>
  <pre><code class="bash">php_value auto_prepend_file /var/lib/simplified-php/simplified.php
php_value auto_append_file /var/lib/simplified-php/simplified.php</code></pre>
  <p>You can also just run <code>sphp --init</code> in the root directory of your project.
  This .htaccess file causes all *.php files under the directory containing the .htaccess file to
  be executed by SimplifiedPHP.</p>
  <p>To develop only part of your project in SimplifiedPHP, create all SimplifiedPHP files wrapped in ordinary PHP:</p>
  <pre><code class="php">&lt;?php require('/var/lib/simplified-php/simplified.php'); ?&gt;

/**
 * Your SimplifiedPHP code goes here
 */
"Hello World".print
 
&lt;?php done();</code></pre>
</blockquote>

""".print