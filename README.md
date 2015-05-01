# yii2-staticactiverecord

A faster but slightly less flexible version of Yii2's ActiveRecord.

[![Build Status](https://travis-ci.org/laszlovl/yii2-staticactiverecord.svg?branch=master)](https://travis-ci.org/laszlovl/yii2-staticactiverecord)

DESCRIPTION
-------

Yii2 is a great framework, and its flexibility is a big part of that. As usual though, flexibility comes at the cost of performance, and
sometimes the performance impact might not be worth the tradeoff.

This code focusses specifically on Yii2's ActiveRecord. Because of ActiveRecord's flexibility, a lot of calculations have to be done at runtime:
look up the corresponding database schema, derive the list of attributes from that, determine the list of safe attribute for each scenario based
on the model's `rules()`, etcetera.

In Yii2's architecture, most of these calculations are not only done once (per request) for every one of your ActiveRecord *classes*, but for
every one of your ActiveRecord *instances*.

Take this code as example:

```php
foreach (Cat::find()->limit(100)->all() as $cat) {
    echo $cat->owner;
}
```

The call to `->owner` will be forwarded to `__get()`, which will call `hasAttribute()`, which will call `attributes()`, which will call `getTableSchema()`,
which will use the DI container to retrieve a reference to the `db` component, and so on. All of this just to determine whether your Cat class has a
native property `$owner` or that it should be resolved as ActiveRecord property, or perhaps as a relation. This is done not just once, but 100 times:
again for every one of your Cat instances.

Is all this necessary? This of course depends on the way your application is designed. For example, it's very possible to attach behaviors to objects
at runtime, causing one of your cats to have a `owner` relation, while another one doesn't. Or to override `attributes()` to completely hide the `owner`
property based on the value of one of the other properties of the instance.

But in a lot of applications this isn't the case: while different instances of a class would of course have different *values*, their *metadata* would
be **static**: either all of your Cats have a property `owner`, or none of them do. If one of your cat instances is found to have the `MiowBehavior`,
all other cats would have it as well.

If this is indeed the case for your application, we can cache this metadata on the class level: once it's calculated for one instance of a class, for
all other instances it can be looked up instantly. This extension's `ActiveRecord` does just that, by using PHP's static function variables to cache
the result of a function between all instances of the containing class.

This can enormously benefit your application's performance, especially for requests that handle a lot of instances of the same class, like a GridView
or ListView with a large pagination size, or an API call on a resource collection.

BENCHMARKS
----------

The extension comes with a benchmark tool to measure the performance improvements for a few very simple scenarios. In all cases, 1000 instances of
the same class are created.

```
~/yii2-staticactiverecord/benchmark$ ./yii benchmark

Benchmarking benchmarkGetProperty...
Regular ActiveRecord: 0.0046327114105225
Static ActiveRecord:  0.0035665512084961
Improvement:          24%

Benchmarking benchmarkGetRelation...
Regular ActiveRecord: 0.029149389266968
Static ActiveRecord:  0.024874639511108
Improvement:          15%

Benchmarking benchmarkSetProperty...
Regular ActiveRecord: 0.0057635307312012
Static ActiveRecord:  0.0044625282287598
Improvement:          23%

Benchmarking benchmarkValidate...
Regular ActiveRecord: 0.022860932350159
Static ActiveRecord:  0.017516303062439
Improvement:          24%
```

The sum of the performance improvements in a single request of course highly depends on your specific application, but I've been able to reduce
the response time for one of my application's heavy requests by more than 50%.

TESTS
-----

The extension re-uses Yii's existing ActiveRecordTest suite, overriding the default ActiveRecord implementation with the one in this extension.
This shows that none of the testcases included in Yii are broken by this extension's behavior.

CAVEATS
-------

As was said, this extension is not for everyone. Some signs that it might not be suitable for a specific class:

* The class overrides any of the functions `getTableSchema()`, `primaryKey()`, `attributes()`, `hasAttribute()`, `scenarios()`
* There is a reference to `$this` in one of these functions in the class: `rules()`, `behaviors()`, `tableName()`
* Your application attaches behaviors to the class at runtime
* Your application calls `getTableSchema($refresh=true)` on the class' table

INSTALLATION
------------

Via composer: `composer require laszlovl/yii2-staticactiverecord`

After that, simply extend your ActiveRecord classes from `lvl\staticactiverecord\db\ActiveRecord` instead of `yii\db\ActiveRecord`.
