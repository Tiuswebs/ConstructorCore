# Tiuswebs - Constructor Core

Package to create new modules for tiuswebs

## Type of classes
There are a variaty of classes that you can extend in a module:

- **Component:** For any kind of component
- **Result:** For modules that requires to get data from the database (More than 1 element), for example: Show the last sliders, Show the members of a team, etc.
- **Blog:** Same that result but only for cruds that have an image, text and a title (products, jobs, promotions, etc), this elements by default adds the module to the category "blog"
- **Footer:** Only for components that are a footer
- **Form:** Only for form components
- **MultimediaItem:** Only for galleries
- **ItemsWithExcerpt:** Results that Load cruds with title, image, description, excerpt
- **ItemsWithTitle:** Results that Load cruds with title and image

### Core information
By default a component will add the background color and padding options, if your module doesnt need it you can disabled it with the next code
```php
public $have_background_color = false;
public $have_paddings = false;
```

And if you need to have a container you can add it with the next code (by default is disabled)
```php
public $have_container = true;
```

If you want to change the default values for this 3 fields
```php
public $default_values = [
	'background_color' => '#f1f1f1',
	'padding_top' => '10px',
	'padding_bottom' => '10px',
	'padding_tailwind' => 'py-24',
	'with_container' => false,
];
```

#### Views customization
If you want to add something to the header or footer you need to make use of the push blade method as this example:

```
@push('header')
	<script src="https://maps.google.com/maps/api/js?sensor=false&key=AIzaSyAsi6YqwsrGrGR4Y67qTNkBY9NdoVbB82s" type="text/javascript"></script>
@endpush

@push('scripts-footer')
	<script type="text/javascript">
	    alert('Example');
  	</script>
@endpush
```

### Result
Result class works for getting data from the database

#### Variables of the class

- **default_result:** which option will be selected by default
- **default_sort:** how will be sorted the data by default
- **default_limit:** Any integer value, the quantity of elements to show by default (Default is 10)
- **show_options:** If you dont want the user to be able to select a value for result
- **include_options:** If you want the user to be able to select for example jobs and banners, Ex: `['jobs', 'banners']`
- **exclude_options:** If you want the user to dont be able to select documentation for example, Ex: `['documentations']`

##### default_result values
- banners
- jobs
- multimedias
- offices
- partners
- products
- promotions
- testimonials
- documentations
- blog_entries
- portfolios

##### default_sort values
- latest (Default value)
- oldest
- random

## Good to know

### Modules categories
- header
- image
- slider
- content
- map
- gallery
- video
- footer
- hero
- feature
- form
- testimonial
- cta
- extra
- comming
- pricing

### Cruds Available
- Banner
- Documentation
- Job
- Multimedia
- Office
- Partner
- Product
- Promotion
- Testimonial
- Blog Entry
- Portfolio

## Types
*Namespace:* `use Tiuswebs\ConstructorCore\Types\Button;`

The Types are group of inputs that add more that one input, this, to just focus on the element.

### Badge
```php
Badge::make('Badge');
```

This type of field will add the next fields
- {$name}_text
- {$name}_text_color
- {$name}_background_color
- {$name}_font
- {$name}_weight
- {$name}_classes

*Returns:* None

### Button
```php
Button::make('Main Button');
Button::make('Secondary Button');
```

This type of field will add the next fields
- {$name}_text
- {$name}_link
- {$name}_text_color
- {$name}_text_color_hover
- {$name}_background_color
- {$name}_background_color_hover
- {$name}_font
- {$name}_weight
- {$name}_classes

*Returns:* None

### Content
```php
Content::make('Content');
```

This type of field will add the next fields
- {$name}_text
- {$name}_color
- {$name}_size
- {$name}_font
- {$name}_weight
- {$name}_classes

*Returns:* None

### Icon
```php
Icon::make('Icon');
```

This type of field will add the next fields
- {$name}_icon
- {$name}_height
- {$name}_color
- {$name}_classes

*Returns:* The icon on html

### Link
```php
Link::make('Link');
```

This type of field will add the next fields
- {$name}_text
- {$name}_link
- {$name}_color
- {$name}_color_hover
- {$name}_font
- {$name}_weight
- {$name}_classes

*Returns:* None

### Paragraph
```php
Paragraph::make('Paragraph');
```

This type of field will add the next fields
- {$name}_text
- {$name}_color
- {$name}_size
- {$name}_font
- {$name}_weight
- {$name}_classes


*Returns:* None

### Title
```php
Title::make('Title');
```

This type of field will add the next fields
- {$name}_text
- {$name}_color
- {$name}_size
- {$name}_font
- {$name}_weight
- {$name}_classes


*Returns:* None

## Input types
```php
use Tiuswebs\ConstructorCore\Inputs\Text;
```

### AdvancedColor
```php
AdvancedColor::make('Text Color')->default('gray-50');
```

### BackgroundColor
This field is the same that color, excepting that it will add a class with the name of the input with the background-color setted on colors attribute.
```php
BackgroundColor::make('Background Color')->default('#f1f1f1');
```

This will add at the end the next css if there is not presence of the word `hover` on the name

```css
.background-color, .background-color a {
	background-color: #f1f1f1;
}
```

So if the text color name is `background_color_hover` it will show the next css

```css
.background-color:hover, .background-color a:hover {
	background-color: #f1f1f1;
}
```

### BasicColor
```php
BasicColor::make('Title Color')->default('gray');
```

*returns:* The value added by the client

### BelongsTo
Accepts crud name and variable name to use it on the view (If empty it will be named office in this case)

```php
BelongsTo::make('Office', 'map');
```

*returns:* The selected object

### Boolean
```php
Boolean::make('Title')->default(false);
```

*returns:* The value added by the client on boolean

### Code
```php
Code::make('Title')->default(false);
```

*returns:* The value added by the client

### Color
This field is for selecting a color, in the front end it shows a color picker for selecting hexadecimal value.
```php
Color::make('Color')->default('#fff');
```

*returns:* The value added by the client

### Hidden
```php
Hidden::make('Title')->default(false);
```

*returns:* The value added on the logic

### Icon
```php
Icon::make('Title');
```
*returns:* The value added by the client

### Items
To get a list of items
```php
Items::make('Feature List');
```
*returns:* The value added by the client

### Number
```php
Number::make('Quantity')->default(2);
```

*returns:* The value added by the client

### Select
```php
Select::make('Option')->default('left')->options([
	'left' => --('Left'),
	'right' => __('Right')
]);
```

*returns:* The value added by the client

### Text
```php
Text::make('Title')->default('title');
```

*returns:* The value added by the client

### TextColor
This field is the same that color, excepting that it will add a class with the name of the input with the color setted on colors attribute.
```php
TextColor::make('Text Color')->default('#f1f1f1');
```

This will add at the end the next css if there is not presence of the word `hover` on the name

```css
.text-color, .text-color a {
	color: #f1f1f1;
}
```

So if the text color name is `text_color_hover` it will show the next css

```css
.text-color:hover, .text-color a:hover {
	color: #f1f1f1;
}
```

*returns:* The value added by the client

### Textarea
```php
Textarea::make('Description')->default('lorep ipsum');
```

*returns:* The value added by the client

### Trix
A WYIWYG text area
```php
Trix::make('Description')->default('<p>lorep ipsum</p>');
```

*returns:* The value added by the client