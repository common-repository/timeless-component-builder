=== Timeless Component Builder ===
Contributors: coder618
Donate link: https://coder618.github.io
Tags: Component System, Component Builder, Timeless component builder, tcb
Requires at least: 4.6
Tested up to: 5.3.2
Stable tag: 1.1.0
Requires PHP: 7.0
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
This plugin will help developer to build component in minimum amount of time.
This plugin based on some wordpress core function like : add_filter , meta box , Custom post type.
thats why you can consider this plugin is a timeless plugin, because i dont think WordPress going to replace 
this function in the feature release.

== Installation ==
1. Upload the plugin folder to the `/wp-content/plugins` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.

This plugin do not have any settings page, so after install you just have to active the plugin, thats it.


== Frequently Asked Questions ==

= Is this plugin have any settings page =
No, This plugin currently do not have any settings page. you just have to activated the plugin to use.

= Is this plugin compatible with other builder =
Yes, You can, use this plugin with other builder like : gutenberg, elementor, Composer etc. Because this component render via shortcode


== Screenshots ==
No available right now.

== Changelog ==

= 1.1.0 =
* File structure change with filter name.

= 1.0.0 =
* First release.

== Upgrade Notice ==

= 1.0.0 =
First relase.

== How to use == 
== How to Create Component: ==
There are Only 2 step you have to follow to create a component, which is
1. Create Component by registering Component Fields.
2. Create Component Rendering Template (a .php file, you can use HTML markup there too)

Detail:
Step 1: 
To Create a component Field first you have to define a function with any name you want. 
Then you need to add those function in a filter hook, name ---- tcb__fileds -----

example:
function banner_fields($arr){	
	$arr['banner'] = [	        
        [
            'type' => 'text',
            'field' => 'title',
            'label' => __('Banner Title', 'tcb'),
			'columns' => '12',
		],		
        [
            'type' => 'textarea',
            'field' => 'detail',
            'label' => __('Banner detail', 'tcb'),
			'columns' => '12',
	    ],		
    ];
    return $arr;
}
add_filter( 'tcb__fields', 'banner_fields' );

Step 2: 
Create a template file (.php) at the location " activated-theme/tcb/component-{$component-category}.php"
In the template file you just have to call a function to receive the user input data.
eg: $component_data = tcb_data();
After that all the user provided data will store in the $component_data variable as array.

Now you can use the data as per your template need.

Isn't that simple ?


For Example full Documentation :
Please Visit: [Plugin Landing Page](https://coder618.github.io/tcb)

== How to use a component  ==
1. From Dashboard, go to Component->Add New
2. Give a name and Select the Component Type/Category and publish/update the component post.
3. You will find Component Related field with the component will be available below the title, user can provide the necessary data in the field and update the component post.
4. Copy the shortcode and use it where you want to render/view the component.