<?php

class DecisionTreeGenerator
{
    public $cssLocation = '/css/tree.css';

    public $filePath;
    public $levelOfTree;

    public $featureNames;
    public $featureCount;
    public $featureCountWithClass;
    public $attributesForFeature;
    public $placeOfClass;
    public $data;


    function __construct($path, $level)
    {
        $cssFile = dirname(__FILE__) . $this->cssLocation;
        if (file_exists($cssFile)) {
            echo '<style>';
            include $cssFile;
            echo '</style>';
            $this->filePath = $path;
            $this->levelOfTree = $level;
        } else echo "Sorry, css file couldn't found! Be sure that you put it on right place or define new path for it from DecisionTree.php file";
    }


    public function create()
    {
        if ($this->filePath == NULL) {
            return;
        }

        $handle = fopen($this->filePath, "r");

        // fetch first line for taking feature names
        $this->featureNames = fgetcsv($handle, 1000, ",");

        $this->featureCountWithClass = count($this->featureNames);
        $this->featureCount = count($this->featureNames) - 1;
        $this->placeOfClass = $this->featureCountWithClass - 1;

        $totalSampleCount = 0;

        // $feat is shortcut of 'number of feature'
        for ($feat = 0; $feat < $this->featureCountWithClass; $feat++)
            $this->attributesForFeature [$feat] = array();

        while (($this->data [$totalSampleCount] = fgetcsv($handle, 1000, ",")) !== false) {
            for ($feat = 0; $feat < $this->featureCountWithClass; $feat++) {
                if (!in_array($this->data [$totalSampleCount] [$feat], $this->attributesForFeature [$feat]))
                    $this->attributesForFeature [$feat] [] = $this->data [$totalSampleCount] [$feat];
            }
            $totalSampleCount++;
        }

        fclose($handle);

        $samples = array();
        for ($i = 0; $i < $totalSampleCount; $i++)
            $samples [] = $i;

        $currentLevel = 0;
        $tree = $this::travel($samples, $currentLevel);
        return $tree;
    }


    public function travel($arrayOfSamples, $level)
    {

        $sampleCount = count($arrayOfSamples);
        $classOfFirstSample = $this->data [$arrayOfSamples [0]] [$this->placeOfClass];

        // $COFSC is shortcut of 'counter of first sample's class'
        $COFSC = 0;
        foreach ($arrayOfSamples as $sampleNum) {
            if ($classOfFirstSample == $this->data [$sampleNum] [$this->placeOfClass])
                $COFSC += 1;
        }
        if ($COFSC == count($arrayOfSamples))
            // return button that's written name of class
            return $this::drawClass($this->data [$arrayOfSamples [0]] [$this->placeOfClass]);

        if ($this->levelOfTree == $level) {
            foreach ($this->attributesForFeature [$this->placeOfClass] as $class)
                $counter [$class] = 0;
            foreach ($arrayOfSamples as $sample) {
                foreach ($this->attributesForFeature [$this->placeOfClass] as $samplelass) {
                    if ($class == $this->data [$sample] [$this->placeOfClass])
                        $counter [$class]++;
                }
            }
            $countOfMax = 0;
            foreach ($this->attributesForFeature [$this->placeOfClass] as $class) {
                if ($counter [$class] > $countOfMax) {
                    $countOfMax = $counter [$class];
                    $classOfMax = $class;
                }
            }
            // return button that's written name of class that has most samples
            return $this::drawCutClass($classOfMax, $countOfMax, $sampleCount);
        }

        $level++;

        for ($feat = 0; $feat < $this->featureCountWithClass; $feat++) {
            foreach ($this->attributesForFeature[$feat] as $att) {
                foreach ($this->attributesForFeature [$this->placeOfClass] as $class)
                    // $COCFA is shortcut of 'counts of classes for attribute'
                    $COCFA [$feat] [$att] [$class] = 0;
            }
        }

        for ($feat = 0; $feat <= $this->featureCount; $feat++) {
            foreach ($arrayOfSamples as $sample) {
                foreach ($this->attributesForFeature [$feat] as $att) {
                    if ($this->data [$sample] [$feat] == $att)
                        $COCFA [$feat] [$att] [$this->data [$sample] [$this->placeOfClass]]++;
                }
            }
        }


        for ($feat = 0; $feat < $this->featureCount; $feat++) {
            $gain [$feat] = 0;
            foreach ($this->attributesForFeature [$feat] as $att) {
                $SCOA = 0;
                // $count is shortcut of 'sample count of attribute's specific class'
                foreach ($COCFA [$feat] [$att] as $count)
                    // $sampleCountOfAttribute
                    $SCOA += $count;

                if ($SCOA != 0) {
                    $entropy = 0;
                    foreach ($this->attributesForFeature [$this->placeOfClass] as $classAtt) {
                        if ($COCFA [$feat] [$att] [$classAtt] != 0)
                            $entropy += $COCFA [$feat] [$att] [$classAtt] / $SCOA * log($COCFA [$feat] [$att] [$classAtt] / $SCOA, 2);
                    }
                    $gain [$feat] += -$SCOA / $sampleCount * ($entropy);
                }
            }
        }

        $chosen = $gain [0];
        $chosenIndex = 0;
        for ($i = 0; $i < $this->featureCount; $i++) {
            if ($gain [$i] < $chosen) {
                $chosen = $gain [$i];
                $chosenIndex = $i;
            }
        }

        // $AOCF is shortcut of 'attributes of chosen feature'
        foreach ($this->attributesForFeature [$chosenIndex] as $AOCF) {
            $newBranch [$AOCF] = array();
            foreach ($arrayOfSamples as $sample) {
                if ($this->data [$sample] [$chosenIndex] == $AOCF)
                    $newBranch [$AOCF] [] = $sample;
            }

            $draw [] = join('', ['<li><a href="#">', $AOCF, '</a>', $this::travel($newBranch [$AOCF], $level), '</li>']);
        }

        $rootNodeAndBeginningOfTree = join('', ['<ul><li><a href="#"><b>', $this->featureNames [$chosenIndex], '</b></a><ul>']);
        $allTreeExceptRootNode = join('', $draw);
        $endingOfTree = join('', ['</ul></li></ul>']);

        // finally, prepare last version of tree :)
        return join('', [$rootNodeAndBeginningOfTree, $allTreeExceptRootNode, $endingOfTree]);
    }

    public function drawClass($class)
    {
        return join('', ['<ul><li><a href="#"><b>', $class, '</b></a></li></ul>']);
    }

    public function drawCutClass($classOfWinner, $countOfWinner, $sampleCount)
    {
        return join('', ['<ul><li><a href="#"><b>', $classOfWinner, '<br>', $countOfWinner, ' of ', $sampleCount, '</b></a></li></ul>']);
    }
}
