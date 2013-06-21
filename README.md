Unite - A PHP Unit Testsing framework
======================================

**Unite** - легковесный фреймворк для Unit тестирования (и не только), написанный без "фанатизма".

### Configuration

### Modules

Enable module global
```js
{
    "modules": {
        "Module\\ClassName": {
            "option1": val1,
            "option2": val2
        }
    }
}
```

Enable module for case

```php
class SomeYourTestCase {
    use SomeModule;
    // ...
}
```

### Asserts

### Annotations

`@dataProvider`

```php
/**
 * @dataProvider localMethod
 * @dataProvider Some\ClassName::remoteMethod
 * @dataProvider path/to/some/file.csv
 **/
 public function testData($val1, $val2) {

 }
```

`@depends`

`@group`

`@test`

`@memcheck`

### Traits

### DevZone

    Test Suit (Unite\Test\TestSuite) mean folders, reading settings and order test files
      Test File (Unite\Test\TestFile) mean files
        Test Case (Unite\Test\TestCase) mean classes
          Test (Unite\Test) mean method