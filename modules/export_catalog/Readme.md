# Catalog in CSV format

## Overview

This module allows you to export your catalog in CSV format. It supports multiple exports and planning by cron.

This module works as follows: You define one or more export models and as many scheduled exports as you want.

## Export models

An export model defines the datas to export and how the generated file will be structured.

The aim of an export model is to generate an CSV file. A CSV file consists of any number of records (here the products), one by line; each record consists of fields (here named columns), separated by some other character or string, most commonly a literal comma or semicolon.

### Model name

You can create as many export models as you want therefore choose an appropriate name is a good idea for easy retrieval.

### File structure

#### Columns

Select the datas you want to exports. Use the (+) button to add a column below the current one and the (-) button to delete the current column.

##### Value

Each column has a value, they are computed at the time of export.

A special value "Fixed value" is independant of the product and can be anything you want.

##### Title

This column appears only if you activate the option Add header line. Indicate the names of the columns that will appear at the top of the generated file.

##### Decoration

This column appears only if you selected the option Columns decoration. Enter here the text to be inserted before and after the calculated values.

#### Filename

Name of the generated file. It can be a fixed value like "catalog.csv" or a computed one like "catalog_%Y-%m-%d.csv".

The following characters are recognized and will be replaced :

|format|Description|Example returned values|
|:-----|:----------|:----------------------|
|**Day**|---|---|
|%a|An abbreviated textual representation of the day|Sun through Sat|
|%A|A full textual representation of the day|Sunday through Saturday|
|%d|Two-digit day of the month (with leading zeros)|01 to 31|
|%e|Day of the month, with a space preceding single digits. Not implemented as described on Windows. See below for more information.|1 to 31|
|%j|Day of the year, 3 digits with leading zeros|001 to 366|
|%u|ISO-8601 numeric representation of the day of the week|1 (for Monday) though 7 (for Sunday)|
|%w|Numeric representation of the day of the week|0 (for Sunday) through 6 (for Saturday)|
|**Week**|---|---|
|%U|Week number of the given year, starting with the first Sunday as the first week|13 (for the 13th full week of the year)|
|%V|ISO-8601:1988 week number of the given year, starting with the first week of the year with at least 4 weekdays, with Monday being the start of the week|01 through 53 (where 53 accounts for an overlapping week)|
|%W|A numeric representation of the week of the year, starting with the first Monday as the first week|46 (for the 46th week of the year beginning with a Monday)|
|**Month**|---|---|
|%b|Abbreviated month name, based on the locale|Jan through Dec|
|%B|Full month name, based on the locale|January through December|
|%h|Abbreviated month name, based on the locale (an alias of %b)|Jan through Dec|
|%m|Two digit representation of the month|01 (for January) through 12 (for December)|
|**Year**|---|---|
|%C|Two digit representation of the century (year divided by 100, truncated to an integer)|19 for the 20th Century|
|%g|Two digit representation of the year going by ISO-8601:1988 standards (see %V)|Example: 09 for the week of January 6, 2009|
|%G|The full four-digit version of %g|Example: 2008 for the week of January 3, 2009|
|%y|Two digit representation of the year|Example: 09 for 2009, 79 for 1979|
|%Y|Four digit representation for the year|Example: 2038|
|**Time**|---|---|
|%H|Two digit representation of the hour in 24-hour format|00 through 23|
|%k|Two digit representation of the hour in 24-hour format, with a space preceding single digits|0 through 23|
|%I|Two digit representation of the hour in 12-hour format|01 through 12|
|%l (lower-case 'L')|Hour in 12-hour format, with a space preceding single digits|1 through 12|
|%M|Two digit representation of the minute|00 through 59|
|%p|UPPER-CASE 'AM' or 'PM' based on the given time|Example: AM for 00:31, PM for 22:23|
|%P|lower-case 'am' or 'pm' based on the given time|Example: am for 00:31, pm for 22:23|
|%r|Same as "%I:%M:%S %p"|Example: 09:34:17 PM for 21:34:17|
|%R|Same as "%H:%M"|Example: 00:35 for 12:35 AM, 16:44 for 4:44 PM|
|%S|Two digit representation of the second|00 through 59|
|%T|Same as "%H:%M:%S"|Example: 21:34:17 for 09:34:17 PM|
|%X|Preferred time representation based on locale, without the date|Example: 03:59:16 or 15:59:16|
|%z|The time zone offset. Not implemented as described on Windows. See below for more information.|Example: -0500 for US Eastern Time|
|%Z|The time zone abbreviation. Not implemented as described on Windows. See below for more information.|Example: EST for Eastern Time|
|**Time and Date Stamps**|---|---|
|%c|Preferred date and time stamp based on locale|Example: Tue Feb 5 00:45:10 2009 for February 5, 2009 at 12:45:10 AM|
|%D|Same as "%m/%d/%y"|Example: 02/05/09 for February 5, 2009|
|%F|Same as "%Y-%m-%d" (commonly used in database datestamps)|Example: 2009-02-05 for February 5, 2009|
|%s|Unix Epoch Time timestamp (same as the time() function)|Example: 305815200 for September 10, 1979 08:40:00 AM|
|%x|Preferred date representation based on locale, without the time|Example: 02/05/09 for February 5, 2009|
|**Miscellaneous**|---|---|
|%n|A newline character ("\n")|---|
|%t|A Tab character ("\t")|---|
|%%|A literal percentage character ("%")|---|

More informations on php.net : [http://www.php.net/strftime](http://www.php.net/strftime)

#### CSV separator

Character that separate columns. Most of the time , or ; but it can be any character like | or §.

If you want to use the file with Microsoft Excel, you should use ; as separator. More information about spreadsheet programs and CSV.

#### Character encoding

This define how to write the datas on the file. Most of the time, UTF-8 is good (but not for Microsoft Excel, see below).

More information : [http://en.wikipedia.org/wiki/Character\_encoding](http://en.wikipedia.org/wiki/Character_encoding)

Note about Microsoft Excel : [http://stackoverflow.com/questions/508558/what-charset-does-microsoft-excel-use-when-saving-files](http://stackoverflow.com/questions/508558/what-charset-does-microsoft-excel-use-when-saving-files)

#### Add header line

Add a first line in the CSV file with columns titles defined above

#### Decimal separator

Will be used in the prices. You can choose to have 12,34 or 12.34 (or 12@43 if you want).

#### Number of decimal points

Will be used in the prices. You can choose to have 12.34 instead of 12.34321, in that case number of decimal points is 2.

#### Columns decoration

You can insert text before and after the values ​​calculated for each column. For example you can add to the end of all products' descriptions "©mysite.com™".

### Products to export

Here you can filter the products you want to export.

#### Export inactive products

Choose if the products not activated in the export context will appear in the generated file.

#### Export products out of stock

Choose if the products out of stock at the export time will appear in the generated file.

#### Export attributes

If activated, a line will be created for each attributes of the products and the prices and quantities will be accurate for each attributes.

#### Categories

Choose the categories to export. If no category is checked, all categories will be exported.

This is the products default categories.

### Context

In Prestashop, you don't have one list of products and one price by product. Prices can be customised by group, shop, countries. Same thing for categories.

Here you can choose the context of the export.

#### Exported image size

If you choose to export the images URL, this define the size of the exported images.

#### Language / Currency / Group / Country / Shop

Choose the context of the export. It can modify the products list, the prices, shipping costs, names...

Some options will not appears depending of your shop configuration. For example if you have only one language, the language option will not appears.

## Scheduled export

The aim of a scheduled export is to generate a CSV file using an export model and to do something with it. So you will have to indicate the model to use, when and what to do with the created file.

Note : If no export model is created, you will not be able to create a scheduled export.

### Export name

You can create as many scheduled export as you want therefore choose an appropriate name is a good idea for easy retrieval.

### Model

Choose the export model to use.

Note : If an export model is deleted, linked scheduled exports will be deleted to.

### Export days / hour

Choose when to run the export. If you want to run an export two time a day, you will have to create two scheduled export.

### Send file to

Will send the generated file by email to the selected employee.

### Save in folder

Will save the generated file in the selected folder. This folder must be writable.

## Parameters

### Working directory

This module need to create files like temporary CSV files or export datas, so it needs a writable folder to that.

Most of the time you will not have to change this parameter.

This folder must be writable.

## Frequently Asked Questions

### What is a CSV File?

A CSV file consists of any number of records (here the products), one by line; each record consists of fields (here named columns), separated by some other character or string, most commonly a literal comma or semicolon.

CSV is a common, relatively simple file format that is widely supported by consumer, business, and scientific applications. Among its most common uses is moving tabular data between programs that natively operate on incompatible (often proprietary and/or undocumented) formats. This works because so many programs support some variation of CSV at least as an alternative import/export format.

### How to read a CSV file?

(from [http://www.computerhope.com/issues/ch001357.htm](http://www.computerhope.com/issues/ch001357.htm))

A CSV file can be opened in any program, however, for most users a CSV file is best viewed through a spreadsheet program such as Microsoft Excel, Open Office Calc or Google Docs.

Tip: If you do not have a spreadsheet program installed on your computer consider using an online Spreadsheet such as Google Docs or a free Spreadsheet program.

#### Microsoft Excel

If Microsoft Excel has been installed on the computer, by default CSV files should open automatically in Excel when the file icon is double-clicked. If you are getting an Open With prompt when opening the CSV file, choose Microsoft Excel from the available programs to open the file with.

Alternatively you can open Microsoft Excel and click File, Open, and select the .CSV file. If the file is not listed make sure to change the file type to be opened to Text files (*.prn, *.txt, *.csv).

More informations : [http://office.microsoft.com/en-us/excel-help/import-or-export-text-txt-or-csv-files-HP010099725.aspx](http://office.microsoft.com/en-us/excel-help/import-or-export-text-txt-or-csv-files-HP010099725.aspx)

#### Open/Free Office Calc

If Open Office has been installed on the computer, by default CSV files should open automatically in Calc when the file icon is double-clicked. If you are getting an Open With prompt when opening the CSV file, choose Open office Calc from the available programs to open the file with.

Alternatively you can open Open Office Calc and click File, Open, and select the .CSV file.

More informations : [http://www.linuxtopia.org/online_books/office_guides/openoffice_3_calc_user_guide/openoffice_calc_Starting_opening_saving_Opening_CSV_files.html](http://www.linuxtopia.org/online_books/office_guides/openoffice_3_calc_user_guide/openoffice_calc_Starting_opening_saving_Opening_CSV_files.html)

#### Google Docs

Open Google Docs, Click the Upload icon, Files, and browse to the directory containing the .CSV file. If an Upload settings window appears check the Convert documents, presentations, spreadsheets, and drawings to the corresponding Google Docs format checkbox and click Start upload.

More informations : [http://www.geek.com/apps/geek-101-how-to-open-a-csv-document-with-google-docs-1551489/](http://www.geek.com/apps/geek-101-how-to-open-a-csv-document-with-google-docs-1551489/)

### The price comparator X need some arbritary columns. How to deal with it ?

Most of the time, price comparators need a category ID that is different that yours, like '15423' for Pet food or 'AZ23' for Computer accessories.

You can deal with it using the Prestashop products features. Create a feature 'Comparator X category' ([more informations](http://doc.prestashop.com/display/PS15/A+Look+Inside+the+Catalog#ALookInsidetheCatalog-Features)) and complete your products details with this feature ([more informations](http://doc.prestashop.com/display/PS15/Adding+Products+and+Product+Categories#AddingProductsandProductCategories-ConfiguringProductFeatures)). Then in your export model, include the feature 'Comparator X category'.

If you don't want to display that feature on your shop, you can read this : [http://www.prestashop.com/forums/topic/174934-solved-hide-features-or-data-sheet-in-product-description/](http://www.prestashop.com/forums/topic/174934-solved-hide-features-or-data-sheet-in-product-description/)
