<?php

  /*
    strftime.php - PHP function that replaces the strftime function, deprecated in PHP 8.1.0

    Copyright (C) 2025

    This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
    You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.
  */

  function strftime_polyfill($format,$timestamp=null,$locale='en') {
    $localeText=array(
      'en'=>array(
        'weekdaysShort'=>array('mon','tue','wed','thu','fri','sat','sun'),
        'weekdaysFull'=>array('monday','tuesday','wednesday','thursday','friday','saturday','sunday'),
        'monthsShort'=>array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'),
        'monthsFull'=>array('january','february','march','april','may','june','july','august','september','october','november','december'),
        'localTime'=>'%I:%M:%S %p',
        'localDate'=>'%m/%d/%Y',
        'localDateTime'=>'%a %d %b %Y %I:%M:%S %p %Z',
      ),
      'ro'=>array(
        'weekdaysShort'=>array('lu','ma','mi','jo','vi','sb','du'),
        'weekdaysFull'=>array('luni','marți','miercuri','joi','vineri','sâmbătă','duminică'),
        'monthsShort'=>array('ian','feb','mar','apr','mai','iun','iul','aug','sep','oct','noi','dec'),
        'monthsFull'=>array('ianuarie','februarie','martie','aprilie','mai','iunie','iulie','august','septembrie','octombrie','noiembrie','decembrie'),
        'localTime'=>'%H:%M:%S',
        'localDate'=>'%d.%m.%Y',
        'localDateTime'=>'%a %d %b %Y %H:%M:%S %z',
      ),
    );

    if ($format===null || !$format) {
      return false;
    }

    if ($timestamp===null) {
      $timestamp=time();
    }

    $r=preg_replace_callback('/%([a-zA-Z%])/',function($m) use ($timestamp,$locale,$localeText) {
      switch($m[0]) {
        // day

        case '%a': // Mon - Sun
          return ucfirst($localeText[$locale]['weekdaysShort'][date('N',$timestamp)-1]);
        case '%A': // Monday - Sunday
          return ucfirst($localeText[$locale]['weekdaysFull'][date('N',$timestamp)-1]);
        case '%d': // 01 - 31
          return date('d',$timestamp);
        case '%e': //  1 - 31
          return str_pad(date('j',$timestamp),2,' ',STR_PAD_LEFT);
        case '%j': // 001 - 366
          return str_pad(date('z',$timestamp)+1,3,'0',STR_PAD_LEFT);
        case '%u': // 1 - 7
          return date('N',$timestamp);
        case '%w': // 0 - 6
          return date('N',$timestamp)<7?date('N',$timestamp):0;

        // week

        case '%U': // Week number of the given year, starting with the first Sunday as the first week
          $weekday=date('N',$timestamp)<7?date('N',$timestamp):0;
          return str_pad(floor((date('z',$timestamp)-$weekday)/7)+1,2,'0',STR_PAD_LEFT);
        case '%V': // ISO-8601:1988 week number of the given year, starting with the first week of the year with at least 4 weekdays, with Monday being the start of the week
          return date('W',$timestamp);
        case '%W': // A numeric representation of the week of the year, starting with the first Monday as the first week
          return str_pad(floor((date('z',$timestamp)-(date('N',$timestamp)-1))/7)+1,2,'0',STR_PAD_LEFT);

        // month

        case '%b': // Jan - Dec
        case '%h':
          return ucfirst($localeText[$locale]['monthsShort'][date('n',$timestamp)-1]);
        case '%B': // January - December
          return ucfirst($localeText[$locale]['monthsFull'][date('n',$timestamp)-1]);
        case '%m': // 01 - 12
          return date('m',$timestamp);

        // year

        case '%C': // Two digit representation of the century (year divided by 100, truncated to an integer)
          return floor((int)date('Y',$timestamp)/100);
        case '%g': // Two digit representation of the year going by ISO-8601:1988 standards
          return substr(date('o',$timestamp),-2);
        case '%G': // The full four-digit version of %g
          return date('o',$timestamp);
        case '%y': // Two digit representation of the year
          return date('y',$timestamp);
        case '%Y': // Four digit representation for the year
          return date('Y',$timestamp);

        // time

        case '%H': // Hour 00 - 23
          return date('H',$timestamp);
        case '%k': // Hour 0 - 23
          return date('G',$timestamp);
        case '%I': // Hour 01 - 12
          return date('h',$timestamp);
        case '%l': // Hour 1 - 12
          return date('g',$timestamp);
        case '%M': // Minutes 00 - 59
          return date('i',$timestamp);
        case '%p': // AM / PM
          return date('A',$timestamp);
        case '%P': // am / pm
          return date('a',$timestamp);
        case '%r':
          return date('h:i:s A',$timestamp);
        case '%R':
          return date('H:i',$timestamp);
        case '%S':
          return date('s',$timestamp);
        case '%T':
          return date('H:i:s',$timestamp);
        case '%X': // Preferred time representation based on locale, without the date
          return strftime_polyfill($localeText[$locale]['localTime'],$timestamp,$locale);
        case '%z': // The time zone offset
          return date('O',$timestamp);
        case '%Z': // The time zone abbreviation
          return date('T',$timestamp);

        // time and date stamps

        case '%c': // Preferred date and time stamp based on locale
          return strftime_polyfill($localeText[$locale]['localDateTime'],$timestamp,$locale);
        case '%x': // Preferred date representation based on locale, without the time
          return strftime_polyfill($localeText[$locale]['localDate'],$timestamp,$locale);
        case '%s': // Unix Epoch Time timestamp
          return $timestamp;
        case '%D':
          return date('m/d/y',$timestamp);
        case '%F':
          return date('Y-m-d',$timestamp);

        // misc

        case '%n':
          return "\n";
        case '%t':
          return "\t";
        case '%%':
          return '%';
      }
    },$format);
    return $r;
  }

?>
