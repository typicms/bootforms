# BootForms

[![Latest Version on Packagist](https://img.shields.io/packagist/v/typicms/bootforms.svg)](https://packagist.org/packages/typicms/bootforms)
[![tests](https://github.com/typicms/bootforms/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/typicms/bootforms/actions/workflows/tests.yml)
[![Rector](https://github.com/typicms/bootforms/actions/workflows/rector.yml/badge.svg?branch=main)](https://github.com/typicms/bootforms/actions/workflows/rector.yml)
[![Pint](https://github.com/typicms/bootforms/actions/workflows/pint.yml/badge.svg?branch=main)](https://github.com/typicms/bootforms/actions/workflows/pint.yml)

BootForms was originally created by [Adam Wathan](https://github.com/adamwathan). It is build on top of the more general [Form](https://github.com/adamwathan/form) package by adding another layer of abstraction to rapidly generate markup for standard Bootstrap 5 forms. Probably not perfect for your super custom branded ready-for-release apps, but a _huge_ time saver when you are still in the prototyping stage!

- [Installation](#installing-with-composer)
- [Using BootForms](#using-bootforms)
  - [Basic Usage](#basic-usage)
  - [Customizing Elements](#customizing-elements)
  - [Reduced Boilerplate](#reduced-boilerplate)
  - [Input Groups](#input-groups)
  - [Automatic Validation State](#automatic-validation-state)
  - [Horizontal Forms](#horizontal-forms)
  - [Additional Tips](#additional-tips)
- [Related Resources](#related-resources)

## Installing with Composer

You can install this package via Composer by running this command in your terminal in the root of your project:

```bash
composer require typicms/bootforms
```

### Laravel

If you are using Laravel 4 or 5, you can get started very quickly by registering the included service provider.

Modify the `providers` array in `config/app.php` to include the `BootFormsServiceProvider`:

```php
'providers' => [
    //...
    'TypiCMS\BootForms\BootFormsServiceProvider'
  ],
```

Add the `BootForm` facade to the `aliases` array in `config/app.php`:

```php
'aliases' => [
    //...
    'BootForm' => 'TypiCMS\BootForms\Facades\BootForm'
  ],
```

You can now start using BootForms by calling methods directly on the `BootForm` facade:

```php
BootForm::text('Email', 'email');
```

### Outside of Laravel

Usage outside of Laravel is a little trickier since there’s a bit of a dependency stack you need to build up, but it’s not too tricky.

```php
$formBuilder = new TypiCMS\Form\FormBuilder;

$formBuilder->setOldInputProvider($myOldInputProvider);
$formBuilder->setErrorStore($myErrorStore);
$formBuilder->setToken($myCsrfToken);

$basicBootFormsBuilder = new TypiCMS\BootForms\BasicFormBuilder($formBuilder);
$horizontalBootFormsBuilder = new TypiCMS\BootForms\HorizontalFormBuilder($formBuilder);

$bootForm = new TypiCMS\BootForms\BootForm($basicBootFormsBuilder, $horizontalBootFormsBuilder);
```

> Note: You must provide your own implementations of `TypiCMS\Form\OldInputInterface` and `TypiCMS\Form\ErrorStoreInterface` when not using the implementations meant for Laravel.

## Using BootForms

### Basic Usage

BootForms lets you create a label and form control and wrap it all in a form group in one call.

```php
//  <form method="post">
//    <div class="mb-3">
//      <label for="field_name" class="form-label">Field Label</label>
//      <input type="text" class="form-control" id="field_name" name="field_name">
//    </div>
//  </form>
{!! BootForm::open() !!}
{!! BootForm::text('Field Label', 'field_name') !!}
{!! BootForm::close() !!}
```

> Note: Don’t forget to `open()` forms before trying to create fields! BootForms needs to know if you opened a vertical or horizontal form before it can render a field, so you’ll get an error if you forget.

### Customizing Elements

If you need to customize your form elements in any way (such as adding a default value or placeholder to a text element), simply chain the calls you need to make and they will fall through to the underlying form element.

Attributes can be added either via the `attribute` method, or by simply using the attribute name as the method name.

```php
// <div class="mb-3">
//    <label for="first_name" class="form-label">First Name</label>
//    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="John Doe">
// </div>
BootForm::text('First Name', 'first_name')->placeholder('John Doe');

// <div class="mb-3">
//   <label for="color" class="form-label">Color</label>
//   <select class="form-select" id="color" name="color">
//     <option value="red">Red</option>
//     <option value="green" selected>Green</option>
//   </select>
// </div>
BootForm::select('Color', 'color')->options(['red' => 'Red', 'green' => 'Green'])->select('green');

// <form method="GET" action="/users">
BootForm::open()->get()->action('/users');

// <div class="mb-3">
//    <label for="first_name" class="form-label">First Name</label>
//    <input type="text" class="form-control" id="first_name" name="first_name" value="John Doe">
// </div>
BootForm::text('First Name', 'first_name')->defaultValue('John Doe');
```

For more information about what’s possible, check out the documentation for [the Form package.](https://github.com/TypiCMS/form)

### Reduced Boilerplate

Typical Bootstrap form boilerplate might look something like this:

```html
<form>
  <div class="mb-3">
    <label for="first_name" class="form-label">First Name</label>
    <input type="text" class="form-control" name="first_name" id="first_name" />
  </div>
  <div class="mb-3">
    <label for="last_name" class="form-label">Last Name</label>
    <input type="text" class="form-control" name="last_name" id="last_name" />
  </div>
  <div class="mb-3">
    <label for="date_of_birth" class="form-label">Date of Birth</label>
    <input type="date" class="form-control" name="date_of_birth" id="date_of_birth" />
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="email" class="form-control" name="email" id="email" />
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" name="password" id="password" />
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
```

BootForms makes a few decisions for you and allows you to pare it down a bit more:

```php
{!! BootForm::open() !!}
  {!! BootForm::text('First Name', 'first_name') !!}
  {!! BootForm::text('Last Name', 'last_name') !!}
  {!! BootForm::date('Date of Birth', 'date_of_birth') !!}
  {!! BootForm::email('Email', 'email') !!}
  {!! BootForm::password('Password', 'password') !!}
  {!! BootForm::submit('Submit') !!}
{!! BootForm::close() !!}
```

### Input groups

Bootforms allows you to create input groups.

```php
//  <div class="mb-3">
//      <label for="amount" class="form-label">Amount</label>
//      <div class="input-group">
//          <span class="input-group-text">$</span>
//          <input type="number" name="amount" id="amount" class="form-control">
//      </div>
//  </div>
{!! BootForm::inputGroup('Amount', 'amount')->type('number')->beforeAddon('<span class="input-group-text">$</span>') !!}
```

With a button appended.

```php
//  <div class="mb-3">
//      <label for="email">Email</label>
//      <div class="input-group">
//          <input type="text" name="email" id="email" class="form-control">
//          <button type="submit" class="btn btn-primary">OK</button>
//      </div>
//  </div>
{!! BootForm::inputGroup('Email', 'email')->afterAddon(BootForm::submit('OK')) !!}
```

### Automatic Validation State

Another nice thing about BootForms is that it will automatically add error states and error messages to your controls if it sees an error for that control in the error store.

Essentially, this takes code that would normally look like this:

```php
<div class="mb-3">
  <label for="first_name" class="form-label">First Name</label>
  <input type="text" class="form-control {!! $errors->has('first_name') ? 'is-invalid' : '' !!}" id="first_name">
  {!! $errors->first('first_name', '<div class="invalid-feedback">:message</div>') !!}
</div>
```

And reduces it to this:

```php
{!! BootForm::text('First Name', 'first_name') !!}
```

…with the `is-invalid` class being added automatically if there is an error in the session.

### Horizontal Forms

To use a horizontal form instead of the standard basic form, simply swap the `BootForm::open()` call with a call to `openHorizontal($columnSizes)` instead:

```php

// Width in columns of the left and right side
// for each breakpoint you’d like to specify.
$columnSizes = [
  'sm' => [4, 8],
  'lg' => [2, 10]
];

{!! BootForm::openHorizontal($columnSizes) !!}
  {!! BootForm::text('First Name', 'first_name') !!}
  {!! BootForm::text('Last Name', 'last_name') !!}
  {!! BootForm::text('Date of Birth', 'date_of_birth') !!}
  {!! BootForm::email('Email', 'email') !!}
  {!! BootForm::password('Password', 'password') !!}
  {!! BootForm::submit('Submit') !!}
{!! BootForm::close() !!}
```

### Additional Tips

#### Hiding Labels

You can hide labels by chaining the `hideLabel()` helper off of any element definition.

```php
BootForm::text('First Name', 'first_name')->hideLabel()
```

The label will still be generated in the markup, but hidden using Bootstrap’s `.visually-hidden` class, so you don’t reduce the accessibility of your form.

#### Form Text

You can add a text block underneath a form element using the `formText()` helper.

```php
BootForm::text('Password', 'password')->formText('A strong password should be long and hard to guess.')
```

#### Model Binding

BootForms makes it easy to bind an object to a form to provide default values. Read more about it [here](https://github.com/adamwathan/form#model-binding).

```php
BootForm::open()->action(route('users.update', $user))->put()
BootForm::bind($user)
BootForm::close()
```
