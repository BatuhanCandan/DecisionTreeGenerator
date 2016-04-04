<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Decision Tree Generator</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/site.css">

</head>
<body>
<div class="wrapper">
    <header> DECISION TREE GENERATOR</header>
    <div class="content">
        <div class="main">
            <form action="index.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="radio" name="checkbox" value="file">
                    <label>Csv file: </label>
                    <input type="file" name="file" id="file">
                </div>
                <div class="form-group">
                    <input type="radio" name="checkbox" value="sample">
                    <label>Sample files: </label>
                    <select name="sample" class="form-control">
                        <option name="baloon" id="baloon">baloon</option>
                        <option name="lenses" id="lenses">lenses</option>
                        <option name="sims" id="sims">sims</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Level of tree: </label>
                    <select name="level" class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option selected>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Generate</button>
            </form>
            <br>
        </div>
        <div class="tree" id="displayTree">
            <?php
            if (isset($_POST['checkbox'])) {
                if ($_POST['checkbox'] == 'file') {
                    if (isset ($_FILES ["file"] ["tmp_name"])) {
                        move_uploaded_file($_FILES ["file"] ["tmp_name"], "upload.csv");
                        $path = 'samples/upload.csv';

                    }
                } else {
                    if ($_POST['checkbox'] == 'sample') {
                        switch ($_POST ['sample']) {
                            case 'baloon':
                                $path = 'samples/baloon.csv';
                                break;
                            case 'lenses':
                                $path = 'samples/lenses.csv';
                                break;
                            case 'sims':
                                $path = 'samples/sims.csv';
                                break;
                        }
                    }
                }
                require '../src/DecisionTreeGenerator.php';
                $treeLevel = $_POST ['level'];
                $decisionTreeGenerator = new DecisionTreeGenerator($path, $treeLevel);
                $tree = $decisionTreeGenerator->create();
                echo $tree;
            }
            ?>
        </div>
    </div>

</div>
</body>
</html>
