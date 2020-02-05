# c3js-php

**This is a work in progress.**

A wrapper for C3.js library which allows you to create interactive charts using PHP and PHP only! This library also introduces additional functions (like automatically calculated moving averages) and allows styling chart elements.

## Dependencies

This library uses [myclabs/php-enum](https://github.com/myclabs/php-enum) for better enum handling and [Twig](https://twig.symfony.com/) for rendering. Both are included as Composer dependencies and will be installed automatically.

You can provide your own Twig environment (if using inside Symfony, for example) to take advantage of caching, etc.:

```php
TwigBridge::setExternalEnvironment($myOwnTwigEnvironment);
```

Another thing is that you need to have [C3.js](https://c3js.org/) included in your website headers. Ideally, you'd use Bower or any other JS package manager but you can always do that manually as well.

## Chart types

So far, the only chart types supported are:
- LineChart
- SplineChart
- TimeseriesChart

## Examples

### Simple chart

Reference: https://c3js.org/samples/simple_multiple.html

```php
$renderer = new ChartRenderer(TwigBridge::getEnvironment());

$chart = new LineChart();
$chart->addColumn(new Column('data', 'Data', [100, 200, -50, 150]));

echo $renderer->renderChart($chart);
```

<p align="center"><img src="https://i.imgur.com/YYXUIPz.png" alt="Line Chart"></p>

### Timeseries chart using factory

Reference: https://c3js.org/samples/timeseries.html

```php
$factory = new TimeseriesChartFactory(new DateArrayFiller());
$renderer = new ChartRenderer(TwigBridge::getEnvironment());

$rawData = [
    '2019-01-01' => 100,
    '2019-01-03' => 120,
    '2019-01-05' => 130,
    '2019-01-08' => null,
    '2019-01-12' => 90,
    // etc
];

$chart = $factory->createTimeseriesChart(
    $rawData,             // data in date => value format
    ZoomTypeEnum::DRAG(), // zoom type (optional, default: none)
    true,                 // connect null values to maintain axis continuity (optional, default: false)
    '%d/%m/%y'            // date format to be displayed (optional, default: Y-m-d)
);

echo $renderer->render($chart);
```

<p align="center"><img src="https://i.imgur.com/CeuwjE3.png" alt="Timeseries Chart"></p>

As you can see, data in a timeseries chart can have both missing values and nulls. The difference is that nulls will cause the axis to be broken in two, while missing values not, and this is a default behaviour of the C3.js library. To prevent this, you can use the third option in this factory method to force nulls to be connected anyway.