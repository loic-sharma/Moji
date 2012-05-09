## Moji - A simple template parser

Moji lets you register helpers to parse in your template.

### Examples

**Parsing Content**

Parsing content is as easy as pie. Simply pass the content into Moji's parse method and you're done!

	$content = 'This is some content with some {{tags}}. Tags can have parameters! {{like this="tag"}}';

	Moji::parse($content);

**Basic Helper**

Creating a helper is very easy:

	Moji::helper('helloworld', function()
	{
		return 'Hello World!';
	});

This would replace:

	{{helloworld}}

**Helper Parameters**

Helpers can have parameters:

	Moji::helper('show', function($params)
	{
		return $params['message'];
	});

This would parse:

	{{show message="This is the parameter"}}

**Miscellaneous**

The name of the helper is very flexible:

	Moji::helper('cms:module:method', function($params)
	{
		// Do something here
	});

This would parse the following tag:

	{{cms:module:method param1="value1"}}