Q2AM Star Ratings
=================

Version 1.0.1
------------

This plugin will add a stars rating system to the question page and allow rating questions and answers.

Watch Installation Video
------------------------
Watch video for comelete installation and setup guide.

- [Watch on TV - Q2A Market][watch-tv]
- [Watch on Youtube][watch-youtube]

Installation Guide
------------------

1. Extrat zip file.
2. Place "q2am-star-ratings" directory to qa-plugin directory.
3. Done

*Note:* rename plugin root directory to `q2am-star-ratings` if required. Usually this happens if you downloading plugin zip file from github.

Usage Guide
-----------

once you complete installation than you can start to use the plugin with following steps.

1. Login with admin id.
2. Go to Admin > Plugins page.
3. Scroll down to "Q2AM Star Ratings" in plugin list.
4. Click on "options" link to jump to the options section,

Here you will find all available options to customize Q2AM Star Ratings plugin.

### Options

##### Enagle Star Ratings 
This will enable Q2AM Star Ratings plugin.

##### Allow Only Logged In User
TThis will only allows to rate if user is logged in. However user only can allow one time rate either logged in or a guest.

##### Star Size
There are total 3 sizes are available Small, Medium and Big.

##### Position
Define the position to display star ratings. Currently you can possition to the below voting box or below question/answer content.

##### Modify Tick Position
This will move select best answer tick little down as star ratings will researved some space.

##### Custom Tick Position
If above option on than you can define the value. Defautl 120.

##### Display Rate Count
If enable it will display rating count below the stars.

##### Total Stars
Set number of stars for ratings. Default 5

##### Total Stars Rate
Set totla value for star ratings set above. If total stars 5 and total stars rate 5 than each stars will have 1 rate. Default 5

##### Step Rate By Star 
If enable it will rates star block. If disable it will allows to rate float value ( in betwen star block )

##### Decimal Length
If above option enable, set how many digits you would like to display for decimal.

##### Show Rate Info
If enable it will display rate info during the rating.

##### Info Position
If above option enable define position of info box.

##### Save Changes
Will save all changes.

##### Reset to Defaults
Will discard all changes and reset to default settings.   

How to add stars rating to custom place
=======================================
This is quite tricky and little complicated and may not useful for normal user but developers as not everything is done within the class for this version. However I may try to wrap everything in class from next version to make if more simple.

First make sure your function should getting post id. For example ```$post``` variable in ```voting($post)``` containing ```postid``` information. Than you can assign post id to the variable.

```php
$post_id = $post['raw']['postid'];
```

To display star ratings you need to include class file by adding below code     

```php
require_once QA_PLUGIN_DIR.'q2am-star-ratings/q2am_star_ratings_class.php';
```

Than instantiate the ```q2am_star_ratings``` class

```php
$star = new q2am_star_ratings;
```

Now you can call star ratings ```get_items($post_id)``` method to retrieve star ratings data from database. 

```php
$item = $star->get_items($post_id);
```

Than simply copy and paste below code to the place (function) you want to add star ratings.

```php
$allipaddress = explode(',', $item['ipaddress']);
$currentip = qa_remote_ip_address();

if((in_array($currentip, $allipaddress)) || (qa_opt('q2am_star_ratings_loggedin') && !qa_is_logged_in())){
    $class = 'jDisabled';
} else {
    $class = '';
}

if($this->template=='question') {

	$this->output('<DIV CLASS="q2am-star-ratings-container-vote">');

	qa_html_theme_base::voting($post); // here you need to recall your fucntion

	$this->output('<DIV CLASS="q2am-star-ratings-element '.$class.'" data-average="'.$item['rating'].'" data-id="'.$post_id.'"></DIV>');

	if(qa_opt('q2am_enable_rate_count'))
	$this->output('<DIV CLASS="q2am-star-ratings-counter">'.$star->q2am_ratings_count($post_id).'</DIV>');

	$this->output('</DIV><!--q2am-star-ratings-container-vote-->');

}
```
Now star ratings should display on the custom place. You can modify html and class as per your need.

Change Logs
===========
#### Version 1.0.1
 - Added support to Question2Answer 1.7
 - Dropped support below Question2Answer 1.7
 - Fixed major bugs
 - Prepare database and code for future updates



About Question2Answer
=====================
[Question2Answer][q2a_link] is a open source question and answer system built on PHP. Built with great flexibilities to customize according to the requirements. [Find out Question2Answer community][q2a_community]

About Q2A Market
================
[Q2A Market ][author]is a leading developer for Question2Answer open source system. It is providing high quality theme, plugins and customization service. Find out more for [Q2A Market][author]  

Store - Q2A Market
==================
Find high quality premium and free themes and plugins from Q2A Market and other authors on [Q2A Market Store][store]

TV - Q2A Market
==================
Watch free video tutorials, making of our custom projects, themes and plugins demo and many more on [TV - Q2A Market][tv]

YouTube - Q2A Market
==================
Keep up to date with our new videos by [subscribing][youtube] on our [YouTube Channel][youtube].

Disclaimer
----------
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
See the [GNU General Public License][GNU] for more details.

[q2a_link]:http://www.question2answer.org
[q2a_community]:http://www.question2answer.org/qa/
[author]: http://www.q2amarket.com
[GNU]:http://www.gnu.org/licenses/gpl.html
[store]:http://store.q2amarket.com
[tv]:http://tv.q2amarket.com
[youtube]:http://www.youtube.com/user/q2amarket
[watch-tv]:http://tv.q2amarket.com/plugins/question2answer-star-ratings-plugin-by-q2a-market-installation-and-setup-guide/
[watch-youtube]:http://www.youtube.com/watch?v=BkqTSbldRiM
