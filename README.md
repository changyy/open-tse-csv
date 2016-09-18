# Study #

Download:

```
$ curl -s 'http://www.twse.com.tw/ch/trading/fund/TWT43U/TWT43U.php' --form download=csv --form sorting=by_issue --form qdate=105/09/01
```

Encoding:

```
$ php -r "echo mb_convert_encoding(stream_get_contents(STDIN), 'UTF-8', 'Big5,UTF-8,AUTO');"
$ iconv -f Big5-2003 -t UTF-8
```

Job:

```
$ curl -s 'http://www.twse.com.tw/ch/trading/fund/TWT43U/TWT43U.php' --form download=csv --form sorting=by_issue --form qdate=105/09/01 | php -r "echo mb_convert_encoding(stream_get_contents(STDIN), 'UTF-8', 'Big5,UTF-8,AUTO');"
$ curl -s 'http://www.twse.com.tw/ch/trading/fund/TWT43U/TWT43U.php' --form download=csv --form sorting=by_issue --form qdate=105/09/01 | iconv -f Big5-2003 -t UTF-8
```

# Task #

Get:

```
$ php php/index.php Task/Corporate_info/daily
$ php php/index.php Task/Corporate_info/daily/105-09-01
$ php php/index.php Task/Corporate_info/daily/2016-09-01
```

Delete file content less than 1000 bytes:

```
$ find php/storage/Corporate_Info -type f -size -1000c -delete
```

# crontab #

run at Mon. ~ Sat. and remove by content length checker

```
# m	h	dom	mon	dow	command
3	15-19	*	*	1-6	php /path/php/index.php Task/Corporate_info/daily
20	20	*	*	1-6	find /path/php/storage/Corporate_Info -type f -size -1000c -delete
```

# Analysis #

Grep target info:

```
$ grep '2317  ' php/storage/Corporate_Info/* | grep daily-foreign
...
php/storage/Corporate_Info/daily-foreign.2016-09-01:"*","2317  ","鴻海            ","8876954","29267260","-20390306",
php/storage/Corporate_Info/daily-foreign.2016-09-02:" ","2317  ","鴻海            ","14852140","31549978","-16697838",
php/storage/Corporate_Info/daily-foreign.2016-09-05:" ","2317  ","鴻海            ","12088125","9724500","2363625",
php/storage/Corporate_Info/daily-foreign.2016-09-06:" ","2317  ","鴻海            ","20111204","11318360","8792844",
php/storage/Corporate_Info/daily-foreign.2016-09-07:"*","2317  ","鴻海            ","17416353","26647597","-9231244",
php/storage/Corporate_Info/daily-foreign.2016-09-08:"*","2317  ","鴻海            ","12505500","35932526","-23427026",
php/storage/Corporate_Info/daily-foreign.2016-09-09:" ","2317  ","鴻海            ","3555340","22102966","-18547626",
php/storage/Corporate_Info/daily-foreign.2016-09-10:" ","2317  ","鴻海            ","1328000","4516000","-3188000",
php/storage/Corporate_Info/daily-foreign.2016-09-12:" ","2317  ","鴻海            ","2871891","22955246","-20083355",
php/storage/Corporate_Info/daily-foreign.2016-09-13:"*","2317  ","鴻海            ","12646006","24626140","-11980134",
php/storage/Corporate_Info/daily-foreign.2016-09-14:" ","2317  ","鴻海            ","19751100","32348328","-12597228",
...
```

Build new format:

```
$ grep '2317  ' php/storage/Corporate_Info/* | grep daily-foreign | awk -F'[.:,]' '{print "\""$2"\""","$6","$7","$8}'
...
"2016-09-01","8876954","29267260","-20390306"
"2016-09-02","14852140","31549978","-16697838"
"2016-09-05","12088125","9724500","2363625"
"2016-09-06","20111204","11318360","8792844"
"2016-09-07","17416353","26647597","-9231244"
"2016-09-08","12505500","35932526","-23427026"
"2016-09-09","3555340","22102966","-18547626"
"2016-09-10","1328000","4516000","-3188000"
"2016-09-12","2871891","22955246","-20083355"
"2016-09-13","12646006","24626140","-11980134"
"2016-09-14","19751100","32348328","-12597228"
...
```

Google sheets with charts:

![Image of google sheet csv display]
(note/google_sheet_csv_display.png)
