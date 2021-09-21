# Tiuswebs - Constructor Core

Package to create new modules for tiuswebs

## Developer tools

![](https://raw.githubusercontent.com/LuanHimmlisch/tiuswebs-complete/main/assets/logo.png)

### Visual Studio Code Extension

If you're developing on [VS Code]() it's recommended to use the **[Tiuswebs Complete](https://github.com/LuanHimmlisch/tiuswebs-complete)** snippets extension for faster development. You can get the extension from the [official Visual Studio Code marketplace](https://marketplace.visualstudio.com/items?itemName=LuanHimmlisch.tiuswebs-complete).



## Type of classes
There are a variaty of classes that you can extend in a module:

- **Component:** For any kind of component
- **Result:** For modules that requires to get data from the database (More than 1 element), for example: Show the last sliders, Show the members of a team, etc.
- **Footer:** Only for components that are a footer
- **Header:** Only for components that are a header
- **Form:** Only for form components
- **MultimediaItem:** Only for galleries
- **ItemsWithExcerpt:** Results that Load cruds with title, image, description, excerpt
- **ItemsWithTitle:** Results that Load cruds with title and image
- **Pagination:** Only for components that are a pagination
- **Instagram:** For modules that requires to get data from the Instagram API.

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
	'background_image' => '',
	'background_classes' => '',
	'padding_top' => '10px',
	'padding_bottom' => '10px',
	'padding_tailwind' => 'py-24',
	'with_container' => false,
];
```

#### Use of paddings when doesnt have a background color
If you have `public $have_background_color = false;`, then the padding will not be injected automatically on the view. You would need to inject it yourself

```html
<div class="{{$component->getDefaults()['padding_tailwind']}}" style="{{$component->getPaddingStyle()}}">
	<!-- more HTML -->
</div>
```
#### Views customization
If you want to add something to the header or footer you need to make use of the push blade method as this example:

```html
@push('header')
	<script src="https://dominio.com/javascript.js" type="text/javascript"></script>
@endpush

@push('scripts-footer')
	<script type="text/javascript">
	    alert('Example');
  	</script>
@endpush
```

#### Hide divs (Validation)
If we want to hide a div in case a field its empty, we can use the `hide($field)` function.

The function accepts the name of a value? (In this case instagram) or can accept also a string with the value.

```html
<a href="{{$values->instagram}}" class="w-8 h-8 rounded-full social-class flex justify-center items-center duration-150 {{$component->hide('instagram')}}">
  <i class="icofont-instagram lg:text-xl"></i>
</a>
```

So in case if the instragram value its empty, it will hide the div automatically.

*Note:* On the types this function is added automatically when ussing the css class.

#### Check links (Validation)
In occasions we would want that if an element doesnt have a link (Or its a #), we dont want to see the link there, so for these cases a new method of validation has been added. The `checkLink($field)` will work for you.

The function accepts the name of a value? (In this case icon1_link) or can accept also a string with the value.

```html
<a href="{{$values->icon1_link}}" class="{{$component->checkLink('icon1_link')}}">
  {!! $values->icon1 !!}
  <h3 class="title-class">{{$values->icon1_title}}</h3>
</a>
```

So in case if the link its empty or a #, in the website it will not look as have a link.

#### Show divs (Customization)
If we want to hide a div based in a boolean value, we can use the `show($boolean)` function.

The function accepts a boolean value and depending of the value it shows or hide the div

```html
<a href="{{$values->instagram}}" class="w-8 h-8 rounded-full social-class flex justify-center items-center duration-150 {{$component->show($values->show_instagram)}}">
  <i class="icofont-instagram lg:text-xl"></i>
</a>
```

So in case if the show_instragram value its false, it will hide the div automatically.

If we want to hide and in case its true to send some classes by default you can do something like this:

```html
<div class="carousel-caption {{ $component->show($values->with_captions, 'd-none d-md-block') }}">
    <h5>{{$element->name ?? $element->title}}</h5>
</div>
```

### Component for a template
If you want a component to only be available on one template you can add a variable

```php
public $use_on_template = 'single-documentation books';
```

And this component will be listed only on pages with that templates

#### Single Template
And if you want to get the items on the page in case if we are using a single template you can get it using the `$component->item` variable

```php
<h3 class="content-title-class mb-5">
    {{$component->item->title}}
</h3>
<div class="content-text-class">
    {!! $component->item->description !!}
</div>
```

### Result
Result class works for getting data from the database

How to use on the view
```html
@foreach($component->elements as $item)
  <div class="col-span-4 lg:col-span-2">
    <div class="hover:opacity-70 duration-150" >
      <a href="{{$item->url}}">
        <img class="select-none object-cover w-full object-center h-48 mb-4" src="{{getThumb($item->image_url, 'l')}}" alt="Mask-Group">
      </a>
      <a href="{{$item->url}}">
        <h3 class="text-center font-bold text-2xl text-blue-500">{{$item->title}}</h3>
      </a>
    </div>
  </div>
@endforeach
```

#### Variables of the class

- **default_result:** which option will be selected by default
- **default_sort:** how will be sorted the data by default
- **default_limit:** Any integer value, the quantity of elements to show by default (Default is 10)
- **show_options:** If you dont want the user to be able to select a value for result
- **include_options:** If you want the user to be able to select for example jobs and banners, Ex: `['jobs', 'banners', 'categories.products']`
- **exclude_options:** If you want the user to dont be able to select documentation for example, Ex: `['documentations']`

##### Categories
If you want the component to work with categories and you want to specify in which type of category you can use include or exclude options.

Example: `['categories.products']` This basically means that it works only for categories of products

You can see all the types of categories on here:
- [Category Types](https://tiuswebs.test/api/category_types)

##### default_sort values
- latest (Default value)
- oldest
- random


### Multimedia Item
Class that works when getting items from Multimedia (General or in a folder). It injects the variable `$gallery` with the folder info, and `$gallery->items` with the items.

The variables to customize are the next ones:
```php
public $default_limit = 10; // Quantity of results to get
public $show_limit = true; // Enable the user to change the limit
public $default_sort = 'latest'; // How to sort the items (Latest, oldest, random)
```

Example of use on the view
```html
@foreach($component->gallery->items as $key => $element)
	<div class="item">
		<div class="p-3">
			<img src="{{getThumb($element->value, 'm')}}" class="max-w-full">
		</div>
	</div>
@endforeach
```


### Footer
This class should be used on all the components that are a footer, by default it will load automatically the menu variable, with one menu and with load the correct category name.

#### Normal use
If you just want the footer to have one menu you dont need to make any change on the component.

And to use it on the view you need to add the next code:

```html
@isset($component->menu)
	<nav class="block md:hidden">
	  <ul class="space-y-3 sm:space-y-0 flex flex-col sm:flex-row justify-between">
	    @foreach($component->menu->elements as $item)
		    <li>
		      <a class="menu-item-class" href="{{$item->link}}">{{$item->title}}</a>
		    </li>
	    @endforeach
	  </ul>
	</nav>
@endisset
```

#### Use columns
It can happen that you need more that one object, for example if you have a footer with 4 columns and you want the user to be able to select menus and maybe more cruds you canfigure it

In the next configuration we set that we want the user to select maximum 4 objects, and can be menus and offices.

```php
public $columns = 4;
public $cruds = ['menus', 'offices'];
```

And in the view you can get the objects with the nex code:

```html
@foreach($component->columns as $element)
	@if($element->type=='Menu')
		<div class="{{$component->getColumnClasses()}}">
			<h6 class="font-bold uppercase mb-3 title-color">{{$element->title}}</h6>
			<ul class="list-unstyled mb-6 mb-md-8 mb-lg-0">
				@foreach($element->elements as $item)
	                <li class="mb-2">
	                    <a href="{{$item->link}}" class="hover:underline links-class">{{$item->title}}</a>
	                </li>
	            @endforeach
			</ul>
		</div>
	@elseif($element->type=='Office')
		<div class="{{$component->getColumnClasses()}} address">
			<h6 class="font-bold uppercase title-color">{{$element->title}}</h6>
			<b class="title-color"><i class="fa fa-map-marker" style="margin-right: 7px;"></i> {{__('Address')}}</b>
			<div class="text-color">
				<p>{{ $element->address->{1} }}</p>
				<p>{{ $element->address->{2} }}</p>
				<p>{{$element->location}}</p>
			</div>
			<b class="title-color"><i class="fa fa-phone"></i> {{__('Phone')}}</b>
			@foreach(collect($element->phones_html)->whereNotNull() as $phone)
				<p class="text-color">{!! $phone !!}</p>
			@endforeach
		</div>
	@endif
@endforeach
```

The `getColumnClasses()` comes by default on the Footer component, so you can use it with any problem, and it basically adds the tailwind cluds to show the correct size of the menu, depending of the user configuration.

### Header

Works the same that footer but adds the header category

#### No menu needed
If you don't need a menu by default you can disable it by adding columns = 0 on the component

```php
public $columns = 0;
```

## Types
*Namespace:* `use Tiuswebs\ConstructorCore\Types\Button;`

The Types are group of inputs that add more that one input, this, to just focus on the element.

### How to copy variables
If we want to set one general Type just for styles and want the user customize just one variable for other inputs we can do the next code:
```php
Icon::make('Icon')->default([
	'height' => '70px',
	'color' => '#3B82F6',
])->ignore('icon'),
Icon::make('Icon 1')->copyFrom('icon')->ignore(['height', 'color', 'classes'])->default(['icon' => 'https://cdn2.iconfinder.com/data/icons/music-and-multimedia-5/1000/music_icons_Light_blue-17-512.png']),
Icon::make('Icon 2')->copyFrom('icon')->ignore(['height', 'color', 'classes'])->default(['icon' => 'icofont-music-alt']),
```

### List of Types
#### Badge
```php
Badge::make('Badge');
```

This type of field will add the next fields
- {$name}_text
- {$name}_text_color
- {$name}_background_color
- {$name}_size
- {$name}_font
- {$name}_weight
- {$name}_classes

*Returns:* None

#### Button
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
- {$name}_size
- {$name}_font
- {$name}_weight
- {$name}_classes

*Returns:* None

#### Content
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

#### Icon
To put an icon. For now you can put an image, an icon from FontAwesome 4.7, and icons from icofont
```php
Icon::make('Icon');
```

This type of field will add the next fields
- {$name}_icon
- {$name}_height
- {$name}_color
- {$name}_classes

*Returns:* The icon on html

#### Input
```php
Input::make('Input');
```

This type of field will add the next fields
- {$name}_text_color
- {$name}_background_color
- {$name}_border_color
- {$name}_size
- {$name}_classes

*Returns:* None

#### Link
```php
Link::make('Link');
```

This type of field will add the next fields
- {$name}_text
- {$name}_link
- {$name}_color
- {$name}_color_hover
- {$name}_size
- {$name}_font
- {$name}_weight
- {$name}_classes

*Returns:* None

#### Paragraph
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

#### Title
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

#### Panel
We can think of panels as our own custom Types.

```php
Panel::make('Card')->default([
	Text::make('Title')->default('Card title');
	Content::make('Content')->default('Card content');
]);
```

Using the `repeat()` we give the freedom to the user to choose how many inputs he wants.

```php
Text::make('Total Items')->default(10);

Panel::make('Item', [
	Text::make('Year')->default(2021),
	Text::make('Title')->default('Item title'),
	Text::make('Subtitle')->default('Information'),
])->repeat('total_items');
```

This will create a default of 10 Items.

## Input types
All the inputs use `Tiuswebs\ConstructorCore\Inputs` namespace

### Normal inputs
Inputs that returns the user values

#### AdvancedColor
A [selection](#select) list of [Tailwind CSS color classes](https://tailwindcss.com/docs/customizing-colors)

```php
AdvancedColor::make('Advanced Color');
```

*returns:* The selected Tailwind CSS class

#### BasicColor
A [selection](#select) list of basic [Tailwind CSS color classes](https://tailwindcss.com/docs/customizing-colors) without shades.

```php
BasicColor::make('Advanced Color');
```

*returns:* The selected Tailwind CSS class

#### BelongsTo
Accepts crud name and variable name to use it on the view (If empty it will be named office in this case)

```php
BelongsTo::make('Office', 'map');
```

*returns:* The selected object

```json
{
  "id": 1,
  "team_id": 1,
  "title": "Tuxtla Gutierrez",
  "address": {
    "1": "1st Avenue Example #75",
    "2": "Downtown",
    "3": "C.P. 40250"
  },
  "location": "Tuxtla Gutierrez, Chiapas",
  "phones": {
    "1": "961-123-4567",
    "2": null,
    "3": null
  },
  "emails": {
    "1": "example@company.com",
    "2": null,
    "3": null
  },
  "social": {
    "facebook": null,
    "twitter": null,
    "instagram": null
  },
  "whatsapp": "+52 961 123 4567",
  "hours": "Monday - Friday from 8 am to 4 pm",
  "lat": "50.1234567",
  "long": "-40.1234567",
  "description": null,
  "image_url": null,
  "variable_2": null,
  "variable_1": null,
  "is_active": 1,
  "created_at": "2020-06-29T18:33:54.000000Z",
  "updated_at": "2021-02-16T22:04:12.000000Z",
  "deleted_at": null,
  "phones_html": {
    "1": "<a href='phone:9611234567'>961-123-4567</a>"
  },
  "emails_html": {
    "1": "<a href='mailto:example@company.com'>example@company.com</a>"
  },
  "icons": "<a href='phone:9611234567' class='fa fa-phone' title='961-123-4567' target='_blank'></a><a href='http://wa.me/+52 961 123 4567' class='fa fa-whatsapp' target='_blank'></a><a href='https://www.google.com/maps/search/?api=1&query=50.1234567,-40.1234567' class='fa fa-map-pin' target='_blank'></a>",
  "whatsapp_html": "<a href='http://wa.me/529611234567' target='_blank'>+52 961 123 4567</a>"
}

```

Example using the office object in a view:

```html
@if ($component->map->emails->{1})
<div class="flex mb-7">
	<a class="block hover:text-orange-500 transition-colors" href="mailto:{{ $component->map->emails->{1} }}">
		<i class="fa fa-envelope-o text-2xl mr-5" aria-hidden="true"></i>
		<p class="inline-block">{{ $component->map->emails->{1} }}</p>
	</a>
</div>
@endif
```

#### Boolean
```php
Boolean::make('Title')->default(false);
```

*returns:* The value added by the client on boolean

#### Code
```php
Code::make('Title')->default(false);
```

*returns:* The value added by the client

#### Color
This field is for selecting a color, in the front end it shows a color picker for selecting hexadecimal value.
```php
Color::make('Color')->default('#fff');
```

*returns:* The value added by the client

#### Date
```php
Date::make('Date');
```

*returns:* The value added by the client

#### DateTime
```php
Date::make('Date Time');
```

*returns:* The value added by the client

#### Hidden
```php
Hidden::make('Title')->default(false);
```

*returns:* The value added on the logic

#### Icon
```php
Icon::make('Title');
```
*returns:* The value added by the client

#### Number
```php
Number::make('Quantity')->default(2);
```

*returns:* The value added by the client

#### Select
```php
Select::make('Option')->default('left')->options([
	'left' => __('Left'),
	'right' => __('Right')
]);
```

*returns:* The value added by the client

#### Text
```php
Text::make('Title')->default('title');
```

*returns:* The value added by the client

#### Textarea
```php
Textarea::make('Description')->default('lorep ipsum');
```

*returns:* The value added by the client

#### Trix
A WYIWYG text area
```php
Trix::make('Description')->default('<p>lorep ipsum</p>');
```

*returns:* The value added by the client

#### BorderType
```php
BorderType::make('Border Type')->default('border-dashed');
```

*Value expected:*

- border-solid
- border-dashed
- border-dotted
- border-double
- border-none

*returns:* The value added by the client

### CSS inputs
Inputs that add a css class and don't return a value

These fields add a css class with the name of the input with a css property (Depending of the input).
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

#### BackgroundColor
```php
BackgroundColor::make('Background Color')->default('#f1f1f1');
```
*Css Property added:* background-color

*Value expected:* hexadecimal color

And if you need to add an opacity, you can use the next code

```php
BackgroundColor::make('Background Color')->default('#f1f1f1')->setOpacity(1);
```
*Value expected:* A number int or decimal

#### BorderColor
```php
BorderColor::make('Border Color')->default('#f1f1f1');
```
*Css Property added:* border-color

*Value expected:* hexadecimal color

And if you need to add an opacity, you can use the next code

```php
BorderColor::make('Border Color')->default('#f1f1f1')->setOpacity(0.5);
```
*Value expected:* A number int or decimal

#### FontFamily
```php
FontFamily::make('Font')->default('Lato');
```
*Css Property added:* font-family

*Value expected:* A font name added on the Font Family input class.

#### FontWeight
Similar that background color, except that it will add a font-weight
```php
FontWeight::make('Font Weight')->default('500');
```
*Css Property added:* font-weight

*Value expected:* A number between 0 and 100

#### TextColor
```php
TextColor::make('Text Color')->default('#f1f1f1');
```
*Css Property added:* text-color

*Value expected:* hexadecimal color

#### BackgroundImage
```php
BackgroundImage::make('Background Image')->default('insert_your_image_url');
```
*Css Property added:* background-image

*Value expected:* The Image Url

### Transform inputs
Inputs that gets a value from the user and return a processed and transformed value

#### Logo
This input will add the option to the front end user to change the team logo url just for one component, by default its empty. So if is empty it returns the team logo on the settings configuration, if there is a value on the input it will take that one instead of the general logo.

```php
Logo::make('Logo');
```

*returns:* The Logo Url

#### Items
Its a text area input when you can put a list of items and returns an array with all the values.
```php
Items::make('Feature List');
```
*returns:* An array with values

#### Money
The number formatted with decimals and commas (Example 4455778 returns 4,455,778.00)
```php
Money::make('Price');
```
*returns:* A formmated price

#### SpotifyEmbedUrl
This input transform a general spotify url to embed url. (Example https://open.spotify.com/track/1shqoTtZO8CLE8Xe2W76tP?si=21a13429f8b1405b converts to https://open.spotify.com/embed/track/1shqoTtZO8CLE8Xe2W76tP)

```php
SpotifyEmbedUrl::make('Spotify Song Url');
```
*returns:* An url

### Internal Inputs

#### TailwindClass
This class shouldn't be used in a component. But its documentated just for understanding purposes and its used on the Types.

This input injects the value set by the user to the html to the selected class.

```php
TailwindClass::make('Classes');
```

## Good to know

- [Modules categories](https://tiuswebs.com/api/categories)
- [Templates](https://tiuswebs.com/api/templates)
- [Cruds](https://tiuswebs.com/api/cruds)

### Image Sizes
- S - 90 x 90 (fit)
- b - 160 x 160 (fit)
- t - 160 x 160
- m - 320 x 320
- l - 640 x 640
- h - 1024 x 1024