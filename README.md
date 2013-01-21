Currency Converter for Alfred 2
============

A simple Alfred 2 Currency Converter workflow's.

![Alfred 2 Currency Converter](http://i50.tinypic.com/125p63a.jpg)


Installation
----------------

- Download "[Currency Converter.alfredworkflow](https://github.com/BigLuck/alfred2-currencyconverter/raw/master/Currency%20Converter.alfredworkflow)" extension.
- Double click the downloaded "Currency Converter.alfredworkflow" file to install.
*Alfred 2 is required*


Instructions
----------------

- currency `<query>`

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

- currency-set-from `<query>`

Set a new default currency input

- currency-set-to `<query>`

Set a new default currency out

- currency-help

Keep updated and read the latest news


Set defaults
----------------

You can change the default currency typing `currency-set-from` or `currency-set-to`


Supported currency
----------------

Full list of currency supported:
- USD (US Dollar)
- EUR (Euro)
- JPY (Japanese Yen)
- GBP (British Pound Sterling)
- CHF (Swiss Franc)
- AUD (Australian Dollar)
- CAD (Canadian Dollar)
- SEK (Swedish Krona)
- HKD (Hong Kong Dollar)
- NOK (Norwegian Krone)
- BTC (Bitcoin) *using btcrate.com*
- AED (United Arab Emirates Dirham)
- ANG (Netherlands Antillean Guilder)
- ARS (Argentine Peso)
- BDT (Bangladeshi Taka)
- BGN (Bulgarian Lev)
- BHD (Bahraini Dinar)
- BND (Brunei Dollar)
- BOB (Bolivian Boliviano)
- BRL (Brazilian Real)
- BWP (Botswanan Pula)
- CLP (Chilean Peso)
- CNY (Chinese Yuan)
- COP (Colombian Peso)
- CRC (Costa Rican Colón)
- CZK (Czech Republic Koruna)
- DKK (Danish Krone)
- DOP (Dominican Peso)
- DZD (Algerian Dinar)
- EEK (Estonian Kroon)
- EGP (Egyptian Pound)
- FJD (Fijian Dollar)
- HNL (Honduran Lempira)
- HRK (Croatian Kuna)
- HUF (Hungarian Forint)
- IDR (Indonesian Rupiah)
- ILS (Israeli New Sheqel)
- INR (Indian Rupee)
- JMD (Jamaican Dollar)
- JOD (Jordanian Dinar)
- KES (Kenyan Shilling)
- KRW (South Korean Won)
- KWD (Kuwaiti Dinar)
- KYD (Cayman Islands Dollar)
- KZT (Kazakhstani Tenge)
- LBP (Lebanese Pound)
- LKR (Sri Lankan Rupee)
- LTL (Lithuanian Litas)
- LVL (Latvian Lats)
- MAD (Moroccan Dirham)
- MDL (Moldovan Leu)
- MKD (Macedonian Denar)
- MUR (Mauritian Rupee)
- MVR (Maldivian Rufiyaa)
- MXN (Mexican Peso)
- MYR (Malaysian Ringgit)
- NAD (Namibian Dollar)
- NGN (Nigerian Naira)
- NIO (Nicaraguan Córdoba)
- NPR (Nepalese Rupee)
- NZD (New Zealand Dollar)
- OMR (Omani Rial)
- PEN (Peruvian Nuevo Sol)
- PGK (Papua New Guinean Kina)
- PHP (Philippine Peso)
- PKR (Pakistani Rupee)
- PLN (Polish Zloty)
- PYG (Paraguayan Guarani)
- QAR (Qatari Rial)
- RON (Romanian Leu)
- RSD (Serbian Dinar)
- RUB (Russian Ruble)
- SAR (Saudi Riyal)
- SCR (Seychellois Rupee)
- SGD (Singapore Dollar)
- SKK (Slovak Koruna)
- SLL (Sierra Leonean Leone)
- SVC (Salvadoran Colón)
- THB (Thai Baht)
- TND (Tunisian Dinar)
- TRY (Turkish Lira)
- TTD (Trinidad and Tobago Dollar)
- TWD (New Taiwan Dollar)
- TZS (Tanzanian Shilling)
- UAH (Ukrainian Hryvnia)
- UGX (Ugandan Shilling)
- UYU (Uruguayan Peso)
- UZS (Uzbekistan Som)
- VEF (Venezuelan Bolívar)
- VND (Vietnamese Dong)
- XOF (CFA Franc BCEAO)
- YER (Yemeni Rial)
- ZAR (South African Rand)
- ZMK (Zambian Kwacha'