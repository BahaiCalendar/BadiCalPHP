# BadiCalPHP
Library to convert between Gregorian and [Badi / Bahai](https://en.wikipedia.org/wiki/Bah%C3%A1%27%C3%AD_calendar) calendars.

Previously at [BadiCal](https://github.com/dmcblue/BadiCal) but that repo is being split into individual languages in the [BahaiCalendar group](https://github.com/BahaiCalendar).

There are currently versions for:
+ PHP (this)
+ [Javascript](https://github.com/BahaiCalendar/BadiCalJS)

As the Badi date starts at sunset, every conversion has two possibilities.  Converting from Gregorian to Badi, you can get either the Badi date before sunset or after sunset for that Gregorian day; the *former* is the default.  Converting from Badi to Gregorian, you can get either the Gregorian date before midnight or after midnight; the default is the *latter*.

For example, 2015-03-21 (Gregorian) converts to the *end of* 172-01-01 (Badi: Bahá 1, 172 ie. Naw Ruz) as the default, and optionally to the *start of* 172-01-02 (Badi).
Consequently, 172-01-01 (Badi) converts to the *start of* 2015-03-21 (Gregorian) as the defauls, and optionally as the *end of* 2015-03-20 (Gregorian).

The default is always the *daytime* conversion.

The library uses precalculated NawRuz dates from http://wilmetteinstitute.org/wp-content/uploads/2014/11/Bahai-Dates-172-to-221-B-E-_UK-December-2014.pdf .  Please report any errors (see BadiCal.NAWRUZ).

## Loading

**TBA** once I push to Packagist.

If not using Composer, you can autoload all classes by requiring the *load.php* file at the root of this repo.

## Usage

The usage is currently extremely simple.  There are two static conversion functions which return \BahaiCalendar\BadiCal\Date objects.### Gregorian to Badi
\BahaiCalendar\BadiCal\Convert::GregorianToBadi
+ @param year  - the Gregorian year
+ @param month - the Gregorian month
+ @param date  - the Gregorian date
+ @param startOf - [OPTIONAL, default=FALSE] whether the returned date is the Badi date ending on (TRUE, evening) or starting on (FALSE, daytime) this Gregorian date.

```php
$badiDate = 
	\BahaiCalendar\BadiCal\Convert::GregorianToBadi(
		2015, //Gregorian year 
		3,    //Gregorian month
		21,   //Gregorian date,
		true  //TRUE = start of, FALSE/Omited = end of  
	);
echo $badiDate->monthName().' '.$badiDate->day().', '.$badiDate->year()."\n";
echo 'Date type: '.$badiDate->type."\n";

$badiDate = 
	\BahaiCalendar\BadiCal\Convert::GregorianToBadi(
		2015, //Gregorian year 
		3,    //Gregorian month
		21,   //Gregorian date,
		FALSE  //Note the difference
	);
echo $badiDate->year().'-'.$badiDate->month(true /* add zero */).'-'.$badiDate->day(true /* add zero */)."\n";
```

Which outputs:

```
Baha 2, 172
Date type: BADI
172-01-01
```

### Badi to Gregorian
\BahaiCalendar\BadiCal\Convert::BadiToGregorian
+ @param year  - the Badi year
+ @param month - the Badi month
+ @param date  - the Badi date
+ @param startsOn - [OPTIONAL, default=FALSE] whether the returned date is the Gregorian date that starts on (TRUE, evening) or ends on (FALSE, daytime) this Badi date.

```php
$gregDate = 
	\BahaiCalendar\BadiCal\Convert::BadiToGregorian(
		172, //Badi year 
		1,    //Badi month
		1,   //Badi date,
		true  //TRUE = start of, FALSE/Omited = end of  
	);
echo $gregDate->monthName().' '.$gregDate->day().', '.$gregDate->year()."\n";
echo 'Date type: '.$gregDate->type."\n";

$gregDate = 
	\BahaiCalendar\BadiCal\Convert::BadiToGregorian(
		172, //Badi year 
		1,    //Badi month
		1,   //Badi date,
		FALSE  //Note the difference
	);
echo $gregDate->year().'-'.$gregDate->month(true /* add zero */).'-'.$gregDate->day(true /* add zero */)."\n";
```

Which outputs:

```
March 20, 2015
Date type: GREGORIAN
2015-03-21
```

## Date object
The *\BahaiCalendar\BadiCal\Date* class has the following:

### Methods
**year($formatted)**
@param bool formatted - will pad the output with a zero
@return the year

**year($formatted)**
@param bool formatted - will pad the output with a zero
@return the year

**month($formatted)**
@param bool formatted - will pad the output with a zero
@return the month

**monthName()**
@return the name of the month according to the appropriate calendar

**day($formatted)**
@param bool formatted - will pad the output with a zero
@return the day

### Properties
**type** Either *\BahaiCalendar\BadiCal\Date::GREGORIAN* ('GREGORIAN') or *\BahaiCalendar\BadiCal\Date::BADI* ('BADI')


## Static Values / Methods

**\BahaiCalendar\BadiCal\Date::GREGORIAN**: 'GREGORIAN', used for the Date->type

**\BahaiCalendar\BadiCal\Date::BADI**: 'BADI', used for the Date->type

**\BahaiCalendar\BadiCal\Date::BADI_MONTHS**: Array with names for Badi Calendar months (zero based).  Includes Ayyam-i-Há.

**\BahaiCalendar\BadiCal\Convert::getAyyamiha($badi_year)**: 
@param badi_year - The year in the Badi calendar
@return \BahaiCalendar\BadiCal\Date


## Testing

You must have the [BadiTest](https://github.com/BahaiCalendar/BadiTest) repository to test the library.  Simple use *test.php* to run a test group from BadiTest by specifying the path to the test group file, like:

```
php test.php /path/to/BadiTest/Basic.json
```
Which currently results in:
```
Test Group: Basic
-----------------
        Naw Ruz........................success
        First day of Ridvan............success
        Ninth day of Ridvan............success
        Twelfth day of Ridvan..........success
        Declaration of the Báb........success
        Ascension of Bahá'u'lláh.....success
        Martyrdom of the Báb..........success
        Day of the Covenant............success
        Ascension of 'Abdu'l-Bahá.....success
        Ala 1st........................success
        Failure Check..................success
11 tests, 0 failures, 11 successes
```