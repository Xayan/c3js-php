# c3js-php

**This is a work in progress.**

A wrapper for C3.js library which allows you to create interactive charts using PHP and PHP only! This library also introduces additional functions (like automatically calculated moving averages) and allows for styling chart elements.

## Examples

### Simple chart

Reference: https://c3js.org/samples/simple_multiple.html

```php
$renderer = new ChartRenderer(TwigBridge::getEnvironment());

$chart = new LineChart();
$chart->addColumn(new Column('data', 'Data', [100, 200, -50, 150]));

echo $renderer->renderChart($chart);
```

### Timeseries chart using factory

Reference: https://c3js.org/samples/timeseries.html

```php
$factory = new TimeseriesChartFactory(new DateArrayFiller());
$renderer = new ChartRenderer(TwigBridge::getEnvironment());

$rawData = [
    '2019-01-01' => 100,
    '2019-01-04' => 120,
    '2019-01-05' => 130,
    '2019-01-08' => null,
    // etc
];

$chart = $factory->createTimeseriesChart(
    $rawData,             // data in date => value format
    ZoomTypeEnum::DRAG(), // zoom type (optional, default: none)
    true,                 // connect null values to maintain axis continuity (optional, default: false)
    '%d/%m/%y'            // date format to be displayed (optional, default: Y-m-d)
);

echo $chartRenderer->render($chart);
```

As you can see, data in a timeseries chart can have both missing values and nulls. The difference is that nulls will cause the axis to be broken in two, while missing values not, and this is a default behaviour of the C3.js library. To prevent this, you can use the third option in this factory method to force nulls to be connected anyway.