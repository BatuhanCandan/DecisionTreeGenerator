# Decision Tree Generator

Decision Tree Generator is a PHP library that generates and vizualites a decision tree on browser from csv file. 

You can reach the sample application with this IP address: http://188.166.19.196/decisiontree

CSS codes of decision tree are taken from [TheCodePlayer] (http://thecodeplayer.com/walkthrough/css3-family-tree)

## Installation

Utilize the src file wherever you want in your project, with the required source-code `require '/path/to/file/DecisionTreeGenerator.php'` 

## Basic Usage

Use `new DecisionTreeGenerator('/path/to/file/example.csv', $levelOfTree)` to create an instance of tree generator and use `create()` to initialize a decision tree.

```php
<?php
//require Decision Tree Generator
require '/src/DecisionTreeGenerator.php';

$path = 'samples/baloons.csv';
$treeLevel = 3;

//create an instance of tree generator
$decisionTreeGenerator = new DecisionTreeGenerator($path, $treeLevel);

//initialize a decision tree
$tree = $decisionTreeGenerator->create();

echo $tree;
```

## Important Notes

* When using the Decision Tree Generator for your csv file, your class feature must be defined at last part of the line.

![example csv](http://s10.postimg.org/h332yxyt5/Untitled.png)

* Due to the fact the width of visualized tree is larger than the web page's width - it apparently doesn't look nice. I will try to fix this issue when I have time. Maybe you would like to fix it and fork this repository :) If you have any queries about the library, you can send me an email.

## License

This package is licensed under the MIT license.