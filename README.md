Currency Converter for Alfred 2
============

A simple Alfred 2 Currency Converter workflow's.

![Alfred 2 Currency Converter](http://i50.tinypic.com/125p63a.jpg)


Installation
----------------

- Download "Currency Converter.alfredworkflow" extension by clicking the "raw" link.
- Double click the *.alfredworkflow file to install.


Instructions
----------------

currency `<query>`

Currency Converter accepts simple and complex queries.
*Important: all output results are fetched from Google Finance and might not be in real-time!*

### Basic ratio xchange
 * `currency €` -- Current EUR/USD exchange
 * `currency € £` -- Current EUR/GBP exchange

### Basic convertion
 * `currency 12 €` -- Convert 12 EUR to USD
 * `currency 12€ £` -- Convert 12 EUR to GBP
 * `currency 12 € £` -- Convert 12 EUR to GBP

### Symbols and International Codes support
 * `currency 12 EUR £` -- Convert 12 EUR to GBP

###  Natural language support
  * `currency 12 EUR to £` -- Convert 12 EUR to GBP
  * `currency from 12 € to GBP` -- Convert 12 EUR to GBP
  * `currency to GBP from 3€` -- Convert 3 EUR to GBP
  * `currency to GBP 3€` -- Convert 3 EUR to GBP

...and many other combinations!


Defaults
----------------

You change change the default currency editing the "currency Script Filter" object from Alfred Preferences.
Here the default script invocation `php -f currencyConverter.php -- "{query}" "€" "$"`
Where:
 * € -- default from currency symbol/internaltional code
 * $ -- default to currency symbol/internaltional code